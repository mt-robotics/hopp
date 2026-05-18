#!/bin/sh
set -eu

DOMAIN_NAME="${DOMAIN_NAME:?DOMAIN_NAME is required}"
CERT_DIR="/etc/letsencrypt/live/$DOMAIN_NAME"
TEMPLATE_DIR="/etc/nginx/hopp-templates"
TARGET_TEMPLATE="/etc/nginx/templates/default.conf.template"

mkdir -p "$(dirname "$TARGET_TEMPLATE")"

if [ -f "$CERT_DIR/fullchain.pem" ] && [ -f "$CERT_DIR/privkey.pem" ]; then
  cp "$TEMPLATE_DIR/default.ssl.conf.template" "$TARGET_TEMPLATE"
  echo "HOPP nginx: using HTTPS template for $DOMAIN_NAME"
else
  cp "$TEMPLATE_DIR/default.bootstrap.conf.template" "$TARGET_TEMPLATE"
  echo "HOPP nginx: using HTTP bootstrap template for $DOMAIN_NAME"
fi
