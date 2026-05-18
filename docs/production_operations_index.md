# Production Operations Index

This site looks like a simple WordPress site on the frontend, but operationally it is not a default WordPress setup.

The production stack includes:

- Git-managed deploys on a sponsor-funded VM
- Docker Compose for the runtime
- a repo-owned ABA PayWay patch path on WordPress startup
- a repo-owned SMTP/MU-plugin mail path
- helper scripts for deploy, rollback, backup, restore, and smoke checks

Use this file first. Open the deeper docs only if this index is not enough.

---

## What Is Normal vs Custom

Normal WordPress admin work:

- posts, pages, products, menus, media
- normal WooCommerce catalog/content work
- normal Contact Form 7 content/form management

Custom/project-specific operations:

- production deploys
- production rollback
- backup and restore
- final domain cutover
- SMTP/runtime wiring
- ABA checkout runtime patch behavior

If the change needs a container recreate or server access, assume it is part of the custom operations layer, not normal WP-admin work.

---

## Operator Commands

Run these on the VM after `ssh hopp-prod` and `cd /opt/hopp`.

Deploy latest reviewed production code:

```bash
./scripts/deploy-production.sh
```

Rollback to a known-good production commit:

```bash
git log --oneline origin/main -n 10
./scripts/rollback-production.sh <known-good-main-sha>
```

Create a production backup bundle:

```bash
./scripts/backup-production.sh
```

Restore DB and uploads from a backup bundle:

```bash
./scripts/restore-production.sh --yes /opt/hopp/backups/<timestamp>
```

Run the automated production GET smoke check:

```bash
./scripts/smoke-test-production.sh
```

Run the smoke check against a specific host during cutover:

```bash
./scripts/smoke-test-production.sh https://humansofphnompenh.com
```

---

## Core Rules

1. Deploy only from reviewed `main`.
2. Do not treat WP Admin as the source of truth for runtime code or mail transport.
3. Take a backup before DNS, payment, plugin, or mailbox-risk changes.
4. After every deploy, rollback, or restore, run the smoke check.
5. If a server-side hotfix is ever made under incident pressure, write it back into Git immediately.

---

## Primary Docs To Open

- deploy and rollback:
  `docs/production_vm_deploy.md`
- production mail behavior and the remaining WooCommerce verification gate:
  `docs/production_mail_and_form_verification.md`
- active next tasks:
  `current_state/project_status.md`

---

## Current Production Reality

- Current live transition host:
  `https://hopp.delvedeepasia.org`
- Intended long-term public host:
  `https://humansofphnompenh.com`
- Current major live execution task:
  primary-domain cutover plus final whitelisted-domain ABA/WooCommerce verification

That means the repo workflow is standardized, but the final business-facing cutover is still an operations task, not something already completed.
