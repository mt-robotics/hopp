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

### Why `make rebuild` did not fix it (root cause — 2026-05-12)

**Date:** 2026-05-12
**Tags:** `makefile` `export` `docker-compose` `env-priority`

`make rebuild` uses `--force-recreate` (same as the manual fix) but the redirect persisted. Root cause: the Makefile had a blanket `export` paired with `include .env.gcp`. Make's `export` pushes every included variable into the shell environment before running any recipe. Docker Compose v2 treats shell environment variables with higher priority than `--env-file`, so `WORDPRESS_LOCAL_URL=http://localhost:8080` from `.env.gcp` silently overrode the value in `--env-file .env.local` (`http://192.168.11.155:8080`). The manual terminal command worked because `.env.gcp` was not loaded into the shell outside of `make`.

**Fix:** Commented out the blanket `export` in the Makefile (lines 34–62). `include` is kept because `gcp-cert` needs `$(DOMAIN_NAME)` and `$(LETSENCRYPT_EMAIL)` as Make variables. If a future shell script genuinely needs environment variables, export them individually (`export DOMAIN_NAME`) rather than exporting everything from the file.

---

## Theme Development

No entries yet.

---

## Deployment

No entries yet.
