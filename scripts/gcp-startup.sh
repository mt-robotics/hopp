#!/bin/sh
set -eu

export DEBIAN_FRONTEND=noninteractive

apt-get update
apt-get install -y ca-certificates curl git gnupg

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

systemctl enable docker
systemctl restart docker

install -m 0755 -d /opt/hopp

cat >/etc/motd <<'EOF'
Humans of Phnom Penh GCP host

Bootstrap completed:
- Docker Engine installed
- Docker Compose plugin installed
- Git installed

Next:
1. Copy or clone the repo into /opt/hopp
2. Populate .env.gcp with real secrets
3. Run the Compose stack with docker compose -f docker-compose.yml -f docker-compose.gcp.yml --env-file .env.gcp up -d
EOF
