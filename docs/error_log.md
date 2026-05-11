# Error Log

Debugging investigations for this project. Use this file for errors that required root-cause analysis, multiple attempts, or a non-obvious fix.

---

## Table of Contents

- [WordPress / Docker](#wordpress--docker)
- [Theme Development](#theme-development)
- [Deployment](#deployment)

---

## WordPress / Docker

### LAN WordPress URL redirects to localhost after env change

**Date:** 2026-05-11
**Context:** Local WordPress Docker staging access from another device on the same Wi-Fi network
**Tags:** `wordpress` `docker-compose` `environment` `lan-access`

**Error:** `http://192.168.11.155:8080` was reachable from the host machine but failed from another device because WordPress returned `301 Moved Permanently` to `http://localhost:8080/`.

**Tried & Failed:**
- Checked Docker port binding — `hopp_wordpress` was already listening on `0.0.0.0:8080`, so the port was exposed correctly.
- Checked the host Wi-Fi IP — the Mac still had `192.168.11.155`, so the LAN URL was not stale.
- Ran `make up` — Docker Compose kept the existing WordPress container running, so its old injected environment values stayed unchanged.

**Solution:** Recreate the WordPress container so Docker Compose reinjects the current `.env.local` value: `docker compose --env-file .env.local -f docker-compose.yml -f docker-compose.local.yml up -d --force-recreate wordpress`. In this repo, `make rebuild` is the Makefile shortcut for `up -d --force-recreate`.

**Learned:** Container environment variables are fixed when the container is created. Changing `.env.local` does not update an already-running container; after changing `WORDPRESS_LOCAL_URL`, use `make rebuild` or another `--force-recreate` command so `WP_HOME` and `WP_SITEURL` are regenerated.

---

## Theme Development

No entries yet.

---

## Deployment

No entries yet.
