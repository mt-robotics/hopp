# Docker Setup Guide

Guide for the local WordPress development environment for Humans of Phnom Penh.

---

## Table of Contents

1. [Current Status](#current-status)
2. [Prerequisites](#prerequisites)
3. [Planned Services](#planned-services)
4. [Environment Variables](#environment-variables)
5. [Planned Setup Workflow](#planned-setup-workflow)
6. [Theme Development Workflow](#theme-development-workflow)
7. [Production Deployment Checklist](#production-deployment-checklist)
8. [Troubleshooting](#troubleshooting)

---

## Current Status

The Docker environment is scaffolded with a shared `docker-compose.yml`, plus environment-specific override files:

- `docker-compose.local.yml`
- `docker-compose.gcp.yml`

WordPress, MySQL, persistent named volumes, a bind-mounted local theme directory, and a PHP upload-limit override are part of the shared stack. The local runtime values live in `docker-compose.local.yml`.

The local environment also injects WordPress config for:

- `WP_HOME=http://localhost:8080`
- `WP_SITEURL=http://localhost:8080`
- `WP_ENVIRONMENT_TYPE=local`
- `HOPP_ENABLE_DEMO_SEED=false`

The HOPP theme no longer seeds demo pages/navigation in the clean-import workflow.

PHP upload limits are configured in `docker/php/uploads.ini` and mounted into the WordPress container:

- `upload_max_filesize=10M`
- `post_max_size=12M`

These limits support the Contact Form 7 artwork/CV upload forms and must be preserved in preview or production containers.

The WordPress container now also mounts a repo-owned ABA PayWay startup patch path:

- `docker/wordpress/start-wordpress.sh`
- `docker/wordpress/apply-aba-payway-patch.php`

On every WordPress container boot, that startup path checks whether `wp-content/plugins/aba-payway-woocommerce-payment-gateway/PayWayApiCheckout.php` exists and still has the unpatched vendor code. If so, it rewrites the plugin file in place so the ABA gateway keeps the PHP 8.3-safe `payment_options` normalization behavior after rebuilds. If the plugin is missing, it skips cleanly. If the plugin file has drifted to an unexpected version, startup fails loudly instead of silently serving a broken checkout.

Constraints:

- No server access
- WP admin access is available for live inspection
- Live active theme is confirmed as Divi
- Installed e-commerce plugin is confirmed as WooCommerce

If you are preparing the sponsor-funded GCP host, first provision the VM with `make gcp-provision`, then use `docker-compose.gcp.yml` together with `make gcp-up`. This file stays focused on local Docker workflow.

For HTTPS on the GCP host, the nginx template exposes `/.well-known/acme-challenge/` and the GCP compose file includes a `certbot` service. Populate `DOMAIN_NAME`, `LETSENCRYPT_EMAIL`, and `WORDPRESS_PUBLIC_URL` in `.env.gcp`, then use the `certbot/certbot` container with the shared webroot volume to request the certificate before switching traffic to `https://...`.

The deployment domain is intentionally centralized in `.env.gcp`. If you temporarily deploy to `hopp.delvedeepasia.org` and later switch back to `humansofphnompenh.com`, update these values in `.env.gcp` on the host copy:

- `WORDPRESS_VIRTUAL_HOST`
- `WORDPRESS_LOCAL_URL`
- `DOMAIN_NAME`
- `WORDPRESS_PUBLIC_URL`

The GCP override is now production-safe by default:

- `WORDPRESS_ENVIRONMENT_TYPE=production`
- `HOPP_ENABLE_DEMO_SEED=false`
- nginx `client_max_body_size 12m`

Keep those values in `.env.gcp` unless you are intentionally creating a temporary non-production environment.

The Makefile includes `make gcp-cert` for that certificate request step.

The VM provisioner is repo-owned:

- `scripts/gcp-provision-vm.sh` reserves or reuses a static IP, ensures HTTP/HTTPS firewall rules exist, and creates the recommended `e2-medium` / `50 GB pd-balanced` Compute Engine VM
- `scripts/gcp-startup.sh` runs as the instance startup script and installs Docker Engine, Docker Compose plugin, and Git on first boot

Production host ownership model:

- Keep application files under `/opt/hopp`
- Own the app directory as `root:hopp`, not as a personal user
- Add each real operator as an individual Linux user and add that user to group `hopp`
- Use group write permissions for deploy work; do not depend on a single personal account such as `monireach`
- Keep system-managed paths such as `/etc`, Docker service config, and `/etc/letsencrypt` root-managed

This is the handoff-safe standard for this project. Temporary helper users are acceptable during setup, but long-term access should be through real named operator accounts in group `hopp`.

---

## Prerequisites

- Docker Desktop
- Docker Compose V2
- WP admin credentials for live inspection and final theme upload

---

## Services

| Service | Purpose | Port |
|---|---|---|
| `wordpress` | Local WordPress site for theme development | `8080:80` |
| `db` | MySQL database for WordPress | internal only |

Theme mount:

```text
./wp-content/themes/hopp:/var/www/html/wp-content/themes/hopp
```

This mount allows theme file edits to appear in WordPress without rebuilding the container.

Runtime patch mounts:

```text
./docker/wordpress/start-wordpress.sh:/usr/local/bin/hopp-wordpress-start.sh
./docker/wordpress/apply-aba-payway-patch.php:/usr/local/share/hopp/apply-aba-payway-patch.php
```

These files are part of the deployment artifact. Recreating the WordPress container reruns the startup patch logic automatically.

---

## Environment Variables

Use `.env.example` as the safe template and `.env.local` as the local runtime file:

```bash
cp .env.example .env.local
```

Expected variables:

```bash
WORDPRESS_PORT=8080
WORDPRESS_DB_HOST=db:3306
WORDPRESS_DB_NAME=hopp_wordpress
WORDPRESS_DB_USER=hopp_user
WORDPRESS_DB_PASSWORD=change_me
MYSQL_DATABASE=hopp_wordpress
MYSQL_USER=hopp_user
MYSQL_PASSWORD=change_me
MYSQL_ROOT_PASSWORD=change_me_root
WORDPRESS_TABLE_PREFIX=wp_
WORDPRESS_DEBUG=1
WORDPRESS_VIRTUAL_HOST=humansofphnompenh.local
```

Do not commit local runtime env files with real credentials.
`.env.local` is ignored by Git and is the file used for local Docker commands.

---

## Setup Workflow

Preferred workflow:

```bash
make init
make up
make ps
```

Direct Docker Compose workflow:

```bash
cp .env.example .env.local
docker compose --env-file .env.local -f docker-compose.yml -f docker-compose.local.yml up -d
docker compose --env-file .env.local -f docker-compose.yml -f docker-compose.local.yml ps
```

Open:

```text
http://localhost:8080
```

If you use `humansofphnompenh.local`, make sure the container was started with `make up` or the full local compose command above. Running plain `docker compose up -d` falls back to the default `.env` lookup and can leave `WORDPRESS_VIRTUAL_HOST` empty.

Expected first-run flow:

1. Complete local WordPress installation.
2. Log in to local WP admin.
3. Confirm the mounted `hopp` theme appears under Appearance -> Themes.
4. Activate the `HOPP` theme.
5. Import the live XML export.
6. Open `http://localhost:8080` and confirm the imported pages/navigation render.

The HOPP theme no longer seeds V1 demo pages, story posts, or primary navigation in the clean-import path.

---

## Theme Development Workflow

Theme directory:

```text
wp-content/themes/hopp/
├── assets/js/navigation.js
├── style.css
├── functions.php
├── front-page.php
├── page.php
├── single.php
├── archive.php
├── home.php
├── search.php
├── 404.php
├── header.php
└── footer.php
```

The V1 demo theme files are built. WordPress requires at least `style.css` with a theme header and `index.php` before the theme appears under Appearance -> Themes.

Implementation rules:

- Use `DESIGN.md` as the visual source of truth.
- Preserve original nav labels and cart behavior.
- Do not assume WooCommerce until confirmed.
- Keep content editable through WordPress where practical.
- Test each page locally before deployment.
- For V1 team review, verify these local routes at minimum:
  - `/`
  - `/about-us/`
  - `/products/`
  - `/stories/`
  - `/artist/`
  - `/career/`
  - `/contact-us/`
  - `/cart/`

Live WordPress admin-only deployment is documented separately in `docs/live_wordpress_deployment.md`.

---

## Production Deployment Checklist

Production deployment is blocked until all items are complete:

- WP admin credentials received
- Live content exported from WP admin
- Local WordPress populated with live content
- Active live theme name documented
- Installed plugins documented, especially products/cart plugin
- Custom theme tested locally
- Rollback procedure documented

Planned upload path:

1. Zip the finished `hopp` theme directory.
2. In WP admin, go to Appearance -> Themes -> Add New -> Upload Theme.
3. Upload the zip.
4. Preview before activation if available.
5. Activate only after final confirmation.
6. Verify the live site immediately.

Rollback procedure cannot be finalized until the current live theme name is known.

---

## Troubleshooting

### `localhost:8080` does not load

Check containers:

```bash
docker compose --env-file .env.local -f docker-compose.yml -f docker-compose.local.yml ps
docker compose --env-file .env.local -f docker-compose.yml -f docker-compose.local.yml logs wordpress
```

### Database connection error

Verify `.env.local` values match between WordPress and MySQL:

- `WORDPRESS_DB_NAME` must match `MYSQL_DATABASE`
- `WORDPRESS_DB_USER` must match `MYSQL_USER`
- `WORDPRESS_DB_PASSWORD` must match `MYSQL_PASSWORD`
- `WORDPRESS_DB_HOST` should usually be `db:3306`

### Theme does not appear in WordPress admin

Verify the theme directory contains:

- `style.css` with a WordPress theme header
- `index.php`

Then check the volume mount in `docker-compose.yml` and the local override in `docker-compose.local.yml`.
