#!/bin/sh
set -eu

if command -v gcloud >/dev/null 2>&1; then
  GCLOUD_BIN="$(command -v gcloud)"
elif [ -x /opt/homebrew/share/google-cloud-sdk/bin/gcloud ]; then
  GCLOUD_BIN="/opt/homebrew/share/google-cloud-sdk/bin/gcloud"
else
  echo "gcloud CLI not found. Install Google Cloud SDK first." >&2
  exit 1
fi

require_var() {
  name="$1"
  eval "value=\${$name:-}"
  if [ -z "$value" ]; then
    echo "Missing required environment variable: $name" >&2
    exit 1
  fi
}

require_var GCP_PROJECT_ID

ACTIVE_ACCOUNT="$("$GCLOUD_BIN" auth list --filter=status:ACTIVE --format='value(account)' 2>/dev/null || true)"
if [ -z "$ACTIVE_ACCOUNT" ]; then
  echo "No active gcloud account. Run: $GCLOUD_BIN auth login --no-launch-browser" >&2
  exit 1
fi

require_var GCP_CONFIRM_PROVISION
if [ "$GCP_CONFIRM_PROVISION" != "yes" ]; then
  echo "Refusing to provision. Set GCP_CONFIRM_PROVISION=yes after reviewing the target project and names." >&2
  exit 1
fi

SCRIPT_DIR="$(CDPATH='' cd -- "$(dirname -- "$0")" && pwd)"

GCP_REGION="${GCP_REGION:-asia-southeast1}"
GCP_ZONE="${GCP_ZONE:-asia-southeast1-b}"
GCP_VM_NAME="${GCP_VM_NAME:-hopp-prod}"
GCP_MACHINE_TYPE="${GCP_MACHINE_TYPE:-e2-medium}"
GCP_DISK_SIZE_GB="${GCP_DISK_SIZE_GB:-50}"
GCP_DISK_TYPE="${GCP_DISK_TYPE:-pd-balanced}"
GCP_IMAGE_FAMILY="${GCP_IMAGE_FAMILY:-debian-12}"
GCP_IMAGE_PROJECT="${GCP_IMAGE_PROJECT:-debian-cloud}"
GCP_NETWORK="${GCP_NETWORK:-default}"
GCP_SUBNET="${GCP_SUBNET:-}"
GCP_ADDRESS_NAME="${GCP_ADDRESS_NAME:-${GCP_VM_NAME}-ip}"
GCP_NETWORK_TAG="${GCP_NETWORK_TAG:-${GCP_VM_NAME}-web}"
GCP_FIREWALL_HTTP_RULE="${GCP_FIREWALL_HTTP_RULE:-${GCP_VM_NAME}-allow-http}"
GCP_FIREWALL_HTTPS_RULE="${GCP_FIREWALL_HTTPS_RULE:-${GCP_VM_NAME}-allow-https}"

if "$GCLOUD_BIN" compute instances list \
  --project "$GCP_PROJECT_ID" \
  --filter="tags.items=${GCP_NETWORK_TAG}" \
  --format='value(name)' | grep -q '.'; then
  echo "Refusing to continue: network tag ${GCP_NETWORK_TAG} is already used by an existing instance in project ${GCP_PROJECT_ID}." >&2
  echo "Set a different GCP_NETWORK_TAG or GCP_VM_NAME first." >&2
  exit 1
fi

if ! "$GCLOUD_BIN" compute addresses describe "$GCP_ADDRESS_NAME" --project "$GCP_PROJECT_ID" --region "$GCP_REGION" >/dev/null 2>&1; then
  "$GCLOUD_BIN" compute addresses create "$GCP_ADDRESS_NAME" --project "$GCP_PROJECT_ID" --region "$GCP_REGION"
fi

IP_ADDRESS="$("$GCLOUD_BIN" compute addresses describe "$GCP_ADDRESS_NAME" --project "$GCP_PROJECT_ID" --region "$GCP_REGION" --format='value(address)')"

if ! "$GCLOUD_BIN" compute firewall-rules describe "$GCP_FIREWALL_HTTP_RULE" --project "$GCP_PROJECT_ID" >/dev/null 2>&1; then
  "$GCLOUD_BIN" compute firewall-rules create "$GCP_FIREWALL_HTTP_RULE" \
    --project "$GCP_PROJECT_ID" \
    --network "$GCP_NETWORK" \
    --allow tcp:80 \
    --target-tags "$GCP_NETWORK_TAG" \
    --description "Allow HTTP to Humans of Phnom Penh web host"
fi

if ! "$GCLOUD_BIN" compute firewall-rules describe "$GCP_FIREWALL_HTTPS_RULE" --project "$GCP_PROJECT_ID" >/dev/null 2>&1; then
  "$GCLOUD_BIN" compute firewall-rules create "$GCP_FIREWALL_HTTPS_RULE" \
    --project "$GCP_PROJECT_ID" \
    --network "$GCP_NETWORK" \
    --allow tcp:443 \
    --target-tags "$GCP_NETWORK_TAG" \
    --description "Allow HTTPS to Humans of Phnom Penh web host"
fi

if "$GCLOUD_BIN" compute instances describe "$GCP_VM_NAME" --project "$GCP_PROJECT_ID" --zone "$GCP_ZONE" >/dev/null 2>&1; then
  echo "VM already exists: $GCP_VM_NAME ($GCP_ZONE)"
else
  set -- compute instances create "$GCP_VM_NAME" \
    --project "$GCP_PROJECT_ID" \
    --zone "$GCP_ZONE" \
    --machine-type "$GCP_MACHINE_TYPE" \
    --boot-disk-size "${GCP_DISK_SIZE_GB}GB" \
    --boot-disk-type "$GCP_DISK_TYPE" \
    --image-family "$GCP_IMAGE_FAMILY" \
    --image-project "$GCP_IMAGE_PROJECT" \
    --network "$GCP_NETWORK" \
    --address "$IP_ADDRESS" \
    --tags "$GCP_NETWORK_TAG" \
    --metadata-from-file "startup-script=$SCRIPT_DIR/gcp-startup.sh"

  if [ -n "$GCP_SUBNET" ]; then
    set -- "$@" --subnet "$GCP_SUBNET"
  fi

  "$GCLOUD_BIN" "$@"
fi

cat <<EOF
Provisioning complete.

Project:        $GCP_PROJECT_ID
Region:         $GCP_REGION
Zone:           $GCP_ZONE
VM name:        $GCP_VM_NAME
Machine type:   $GCP_MACHINE_TYPE
Disk:           ${GCP_DISK_SIZE_GB}GB $GCP_DISK_TYPE
Static IP:      $IP_ADDRESS
Network tag:    $GCP_NETWORK_TAG

Next:
1. Point the chosen DNS host from .env.gcp (${GCP_NETWORK_TAG%*-web} if unchanged) to $IP_ADDRESS, or update .env.gcp first if the domain changed
2. SSH in: $GCLOUD_BIN compute ssh $GCP_VM_NAME --project $GCP_PROJECT_ID --zone $GCP_ZONE
3. Wait for the startup script to finish installing Docker
4. Copy this repo to /opt/hopp and populate .env.gcp with real secrets
5. Start the stack and request TLS with make gcp-cert
EOF
