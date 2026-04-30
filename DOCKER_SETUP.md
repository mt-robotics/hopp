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

The Docker environment is scaffolded with `docker-compose.yml`, WordPress, MySQL, persistent named volumes, and a bind-mounted local theme directory.

Constraints:

- No server access
- No WP admin credentials yet
- Live active theme name unknown
- Installed e-commerce plugin unknown

---

## Prerequisites

- Docker Desktop
- Docker Compose V2
- WP admin credentials, later, for content export/import and final theme upload

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
```

Do not commit local runtime env files with real credentials.
`.env.local` is ignored by Git and is the file used for local Docker commands.

---

## Setup Workflow

```bash
cp .env.example .env.local
docker compose --env-file .env.local up -d
docker compose --env-file .env.local ps
```

Open:

```text
http://localhost:8080
```

Expected first-run flow:

1. Complete local WordPress installation.
2. Log in to local WP admin.
3. Confirm the mounted `hopp` theme appears under Appearance -> Themes.
4. Activate the theme only after required files exist.

---

## Theme Development Workflow

Theme directory:

```text
wp-content/themes/hopp/
└── .gitkeep
```

The full theme files are not built yet. WordPress requires at least `style.css` with a theme header and `index.php` before the theme appears under Appearance -> Themes.

Implementation rules:

- Use `DESIGN.md` as the visual source of truth.
- Preserve original nav labels and cart behavior.
- Do not assume WooCommerce until confirmed.
- Keep content editable through WordPress where practical.
- Test each page locally before deployment.

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
docker compose --env-file .env.local ps
docker compose --env-file .env.local logs wordpress
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

Then check the volume mount in `docker-compose.yml`.
