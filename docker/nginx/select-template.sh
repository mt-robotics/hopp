#!/bin/sh
set -eu

DOMAIN_NAME="${DOMAIN_NAME:?DOMAIN_NAME is required}"
WWW_DOMAIN="${WWW_DOMAIN:-}"
CERT_ROOT="${CERT_ROOT:-/etc/letsencrypt/live}"
CERT_DIR="$CERT_ROOT/$DOMAIN_NAME"
TARGET_TEMPLATE="${TARGET_TEMPLATE:-/etc/nginx/templates/default.conf.template}"

mkdir -p "$(dirname "$TARGET_TEMPLATE")"

write_bootstrap_template() {
  SERVER_NAMES="$DOMAIN_NAME"
  if [ -n "$WWW_DOMAIN" ]; then
    SERVER_NAMES="$SERVER_NAMES $WWW_DOMAIN"
  fi

  cat >"$TARGET_TEMPLATE" <<EOF
server {
    listen 80;
    server_name $SERVER_NAMES;
    client_max_body_size 12m;

    location ^~ /.well-known/acme-challenge/ {
        root /var/www/certbot;
    }

    location / {
        proxy_http_version 1.1;
        proxy_connect_timeout 300s;
        proxy_send_timeout 300s;
        proxy_read_timeout 300s;
        send_timeout 300s;
        proxy_pass http://hopp_wordpress:80;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto http;
    }
}
EOF

  echo "HOPP nginx: using HTTP bootstrap template for $DOMAIN_NAME"
}

write_ssl_template() {
  HTTP_SERVER_NAMES="$DOMAIN_NAME"
  HTTPS_ALIAS_BLOCK=""

  if [ -n "$WWW_DOMAIN" ]; then
    HTTP_SERVER_NAMES="$HTTP_SERVER_NAMES $WWW_DOMAIN"
    HTTPS_ALIAS_BLOCK=$(cat <<EOF
server {
    listen 443 ssl http2;
    server_name $WWW_DOMAIN;

    ssl_certificate /etc/letsencrypt/live/$DOMAIN_NAME/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$DOMAIN_NAME/privkey.pem;

    return 301 https://$DOMAIN_NAME\$request_uri;
}

EOF
)
  fi

  cat >"$TARGET_TEMPLATE" <<EOF
server {
    listen 80;
    server_name $HTTP_SERVER_NAMES;

    location ^~ /.well-known/acme-challenge/ {
        root /var/www/certbot;
    }

    location / {
EOF

  if [ -n "$WWW_DOMAIN" ]; then
    cat >>"$TARGET_TEMPLATE" <<EOF
        if (\$host = $WWW_DOMAIN) {
            return 301 https://$DOMAIN_NAME\$request_uri;
        }
        return 301 https://\$host\$request_uri;
EOF
  else
    cat >>"$TARGET_TEMPLATE" <<'EOF'
        return 301 https://$host$request_uri;
EOF
  fi

  cat >>"$TARGET_TEMPLATE" <<EOF
    }
}

$HTTPS_ALIAS_BLOCK
server {
    listen 443 ssl http2;
    server_name $DOMAIN_NAME;

    ssl_certificate /etc/letsencrypt/live/$DOMAIN_NAME/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$DOMAIN_NAME/privkey.pem;
    client_max_body_size 12m;

    location / {
        proxy_http_version 1.1;
        proxy_connect_timeout 300s;
        proxy_send_timeout 300s;
        proxy_read_timeout 300s;
        send_timeout 300s;
        proxy_pass http://hopp_wordpress:80;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto https;
    }
}
EOF

  echo "HOPP nginx: using HTTPS template for $DOMAIN_NAME"
}

if [ -f "$CERT_DIR/fullchain.pem" ] && [ -f "$CERT_DIR/privkey.pem" ]; then
  write_ssl_template
else
  write_bootstrap_template
fi
