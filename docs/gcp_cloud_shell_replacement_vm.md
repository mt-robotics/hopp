# GCP Cloud Shell Runbook — Replacement HOPP VM In Dedicated VPC

Use this runbook from **GCP Cloud Shell** to create the replacement HOPP VM inside:

- VPC: `hopp-vpc`
- Subnet: `hopp-subnet`
- Region: `asia-southeast1`
- Zone: `asia-southeast1-c`

This follows the repo's current production shape and keeps the server equivalent to the current HOPP VM in all important ways except network placement:

- machine type: `e2-medium`
- boot disk: `50 GB pd-balanced`
- OS image: `Debian 12`
- startup bootstrap: `scripts/gcp-startup.sh` logic, including the `root:hopp` app-path ownership model

This runbook assumes:

- the dedicated VPC and subnet already exist
- the subnet is dual-stack and has `ipv6AccessType=EXTERNAL`
- the firewall rules from `docs/hopp_dedicated_vpc_setup.md` already exist

Official docs verified on 2026-05-21:

- Create VM in specific subnet:
  https://docs.cloud.google.com/compute/docs/instances/create-vm-specific-subnet
- Create dual-stack / IPv6 VM:
  https://docs.cloud.google.com/compute/docs/instances/create-ipv6-instance
- Configure IPv6 addresses:
  https://docs.cloud.google.com/compute/docs/ip-addresses/configure-ipv6-address
- Reserve static external IPs:
  https://docs.cloud.google.com/vpc/docs/reserve-static-external-ip-address
- Debian public images:
  https://docs.cloud.google.com/compute/docs/instances/create-vm-from-public-image
- Exact CLI flag reference used for the static IPv6 nuance in this runbook:
  https://cloud.google.com/sdk/gcloud/reference/compute/instances/create

---

## 1. Set Variables

```bash
# Confirm the active project first.
gcloud config get-value project

# Confirm both the project ID and the human-readable project name.
gcloud projects describe "$(gcloud config get-value project)" \
  --format="yaml(projectId,name,projectNumber)"

# Set the exact target values for this replacement VM.
PROJECT_ID="$(gcloud config get-value project)"
REGION="asia-southeast1"
ZONE="asia-southeast1-c"
NETWORK="hopp-vpc"
SUBNET="hopp-subnet"

# Use a replacement name, not the existing live VM name, while both servers coexist.
VM_NAME="hopp-prod-v2"

# Keep parity with the current recommended production shape in this repo.
MACHINE_TYPE="e2-medium"
BOOT_DISK_SIZE="50GB"
BOOT_DISK_TYPE="pd-balanced"
IMAGE_FAMILY="debian-12"
IMAGE_PROJECT="debian-cloud"

# Keep a web tag only if you want instance-targeted rules later.
# The current dedicated-VPC firewall rules already target the whole network.
NETWORK_TAG="hopp-prod-web"
```

Hard rule:

- do not continue unless `gcloud config get-value project` shows the exact intended production project

Optional explicit safety step:

```bash
# Replace this with the real target project ID if you want a hard guard.
EXPECTED_PROJECT_ID="replace-me"

test "$PROJECT_ID" = "$EXPECTED_PROJECT_ID"
```

If the `test` command fails, stop and switch Cloud Shell to the correct project first.

Likely IAM permissions required:

- `roles/compute.instanceAdmin.v1`
- `roles/compute.networkAdmin`

---

## 2. Verify The Subnet Really Matches The Dual-Stack Plan

```bash
# This must show a dual-stack subnet with external IPv6 enabled.
gcloud compute networks subnets describe "$SUBNET" \
  --project="$PROJECT_ID" \
  --region="$REGION" \
  --format="yaml(name,network,region,ipCidrRange,stackType,ipv6AccessType,externalIpv6Prefix)"
```

Expected facts:

- `stackType: IPV4_IPV6`
- `ipv6AccessType: EXTERNAL`
- region is `asia-southeast1`

If those facts do not match, stop. Do not create the VM yet.

---

## 3. Reserve Static External IP Addresses

Use static addresses for the replacement VM so DNS cutover and later rollback logic stay controlled.

```bash
# Reserve a static external IPv4 address in the same region.
gcloud compute addresses create "${VM_NAME}-ipv4" \
  --project="$PROJECT_ID" \
  --region="$REGION"

# Reserve a static external IPv6 /96 from the dual-stack subnet.
# For a regional external IPv6 reservation for a VM, Google requires:
# - --subnet
# - --ip-version=IPV6
# - --endpoint-type=VM
gcloud compute addresses create "${VM_NAME}-ipv6" \
  --project="$PROJECT_ID" \
  --region="$REGION" \
  --subnet="$SUBNET" \
  --ip-version=IPV6 \
  --endpoint-type=VM

# Read back the actual reserved addresses.
IPV4_ADDRESS="$(gcloud compute addresses describe "${VM_NAME}-ipv4" \
  --project="$PROJECT_ID" \
  --region="$REGION" \
  --format='value(address)')"

# Google may return the IPv6 reservation as a CIDR range such as ".../96".
# The instance-create flag expects the first IPv6 address in that range.
IPV6_CIDR="$(gcloud compute addresses describe "${VM_NAME}-ipv6" \
  --project="$PROJECT_ID" \
  --region="$REGION" \
  --format='value(address)')"

IPV6_ADDRESS="${IPV6_CIDR%/*}"

printf 'IPv4: %s\nIPv6 CIDR: %s\nIPv6 first address: %s\n' \
  "$IPV4_ADDRESS" "$IPV6_CIDR" "$IPV6_ADDRESS"
```

Notes:

- For IPv4, `gcloud compute instances create --address=...` expects the actual IP value, not the address resource name.
- For static external IPv6, the `gcloud compute instances create` reference says `--external-ipv6-address` must be the first IP address in the reserved `/96` range.
- External IPv6 uses Premium Tier.

Source for that IPv6 rule:

- `gcloud compute instances create`
  https://cloud.google.com/sdk/gcloud/reference/compute/instances/create
- `Reserve a static external IP address`
  https://docs.cloud.google.com/vpc/docs/reserve-static-external-ip-address

If you delete and recreate the IPv6 reservation during troubleshooting:

- re-run the `gcloud compute addresses describe ...` command for `IPV6_CIDR`
- then re-run `IPV6_ADDRESS="${IPV6_CIDR%/*}"`
- do not trust the old `IPV6_ADDRESS` shell value after a recreate

---

## 4. Paste The Startup Script Into Cloud Shell

The current repo bootstrap uses `scripts/gcp-startup.sh`. Because Cloud Shell runs against GCP directly and not from this local repo checkout, paste the startup script content into a temporary file first.

```bash
cat > /tmp/hopp-gcp-startup.sh <<'EOF'
#!/bin/sh
set -eu

export DEBIAN_FRONTEND=noninteractive

apt-get update
apt-get install -y ca-certificates curl git make jq

install -m 0755 -d /etc/apt/keyrings
if [ ! -f /etc/apt/keyrings/docker.asc ]; then
  curl -fsSL https://download.docker.com/linux/debian/gpg -o /etc/apt/keyrings/docker.asc
  chmod a+r /etc/apt/keyrings/docker.asc
fi

. /etc/os-release
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/debian ${VERSION_CODENAME} stable" \
  > /etc/apt/sources.list.d/docker.list

apt-get update
apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

if ! getent group hopp >/dev/null; then
  groupadd hopp
fi

install -d -m 2775 -o root -g hopp /opt/hopp

systemctl enable docker
systemctl start docker
EOF
```

This pasted block should stay aligned with `scripts/gcp-startup.sh`. If you change one later, update the other in the same task.

---

## 5. Create The Replacement VM In The Dedicated VPC/Subnet

First confirm the replacement VM name is still unused:

```bash
gcloud compute instances describe "$VM_NAME" \
  --project="$PROJECT_ID" \
  --zone="$ZONE"
```

Expected result before creation:

- a `not found` error for `"$VM_NAME"` in `"$ZONE"`

If the instance already exists, stop and choose a different `VM_NAME`.

```bash
# Create a dual-stack VM in the dedicated VPC/subnet.
# This keeps the current production-equivalent shape while placing it on the new network.
gcloud compute instances create "$VM_NAME" \
  --project="$PROJECT_ID" \
  --zone="$ZONE" \
  --machine-type="$MACHINE_TYPE" \
  --boot-disk-size="$BOOT_DISK_SIZE" \
  --boot-disk-type="$BOOT_DISK_TYPE" \
  --image-family="$IMAGE_FAMILY" \
  --image-project="$IMAGE_PROJECT" \
  --network="$NETWORK" \
  --subnet="$SUBNET" \
  --stack-type=IPV4_IPV6 \
  --address="$IPV4_ADDRESS" \
  --external-ipv6-address="$IPV6_ADDRESS" \
  --external-ipv6-prefix-length=96 \
  --ipv6-network-tier=PREMIUM \
  --tags="$NETWORK_TAG" \
  --metadata-from-file=startup-script=/tmp/hopp-gcp-startup.sh
```

Why these flags matter:

- `--subnet="$SUBNET"` puts the VM in the dedicated HOPP subnet
- `--stack-type=IPV4_IPV6` makes the NIC dual-stack
- `--address="$IPV4_ADDRESS"` attaches the reserved static public IPv4
- `--external-ipv6-address="$IPV6_ADDRESS"` attaches the reserved static public IPv6 range
- `--external-ipv6-prefix-length=96` matches Google's documented static external IPv6 assignment shape for VM NICs

---

## 6. Verify The VM Network Details

```bash
# Inspect the resulting NIC, public IPv4, and public IPv6 details.
gcloud compute instances describe "$VM_NAME" \
  --project="$PROJECT_ID" \
  --zone="$ZONE" \
  --format="yaml(name,zone,machineType,tags.items,networkInterfaces)"
```

Check that:

- the NIC is on `hopp-vpc`
- the subnet is `hopp-subnet`
- the interface stack is dual-stack
- the reserved IPv4 is attached
- the external IPv6 `/96` is attached

---

## 7. SSH Into The VM And Wait For Bootstrap To Finish

```bash
# First login.
gcloud compute ssh "$VM_NAME" \
  --project="$PROJECT_ID" \
  --zone="$ZONE"
```

Once inside:

```bash
# Confirm Docker is installed and the shared deploy directory exists.
docker --version
docker compose version
ls -ld /opt/hopp
getent group hopp
```

If Docker is not ready yet, wait a minute and re-check. Startup scripts can still be finishing in the background.

If bootstrap looks stuck or incomplete, inspect the startup-script logs directly:

```bash
sudo journalctl -u google-startup-scripts.service -f
sudo journalctl -u google-startup-scripts.service -n 200 --no-pager
sudo tail -n 200 /var/log/syslog
```

If your operator user is not yet in group `hopp`, add it now:

```bash
sudo usermod -aG hopp monireach
getent group hopp
id
```

Important:

- `getent group hopp` should show `monireach` after the `usermod`
- `id` in the current SSH session may still show the old group list
- the safest fix is to disconnect and SSH into the VM again before continuing
- after reconnecting, run `id` again and confirm `hopp` appears in the groups
- this is required so `/opt/hopp` follows the intended `root:hopp` group-based operating model

---

## 8. Immediate Next Steps After VM Creation

After the VM exists, continue with the repo's canonical production workflow:

1. clone the repo into `/opt/hopp`
2. create `/opt/hopp/.env.gcp`
3. deploy from `main`
4. verify HTTPS, mail, WooCommerce, and ABA on the replacement host before DNS cutover

High-level commands on the VM:

```bash
# Example only: use your real repo remote.
sudo git clone <REPO_URL> /opt/hopp
cd /opt/hopp

# Populate the host-only env file before first deploy.
cp .env.example .env.gcp
# Then edit .env.gcp with the real production values.

# Deploy using the repo-owned production path.
./scripts/deploy-production.sh
```

Use these repo docs next:

- `docs/production_operations_index.md`
- `docs/production_vm_deploy.md`
- `docs/production_mail_and_form_verification.md`

---

## 9. Important Non-Guess Notes

- Do **not** try to move the existing `hopp-prod` VM into the new VPC in place. The repo's current architecture decision is replacement-VM cutover, not in-place mutation.
- Do **not** reuse the exact current VM name while the old VM still exists.
- Do **not** point `humansofphnompenh.com` at this new VM until the stack is deployed and verified.
- The dedicated VPC firewall rules already exist at the network level, so the instance tag is optional for current ingress behavior.
- If you decide to use ephemeral IPv6 instead of static IPv6, the CLI can create a dual-stack VM with `--stack-type=IPV4_IPV6` and no explicit IPv6 address flag, but this runbook intentionally uses static addressing because final cutover is safer with fixed IPs.
