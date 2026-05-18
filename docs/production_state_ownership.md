# Production State Ownership

Canonical boundary between Git-managed production state and WP-admin-managed production state for the live sponsor-funded VM.

---

## Table of Contents

1. [Purpose](#purpose)
2. [Code-Managed State](#code-managed-state)
3. [WP-Admin-Managed State](#wp-admin-managed-state)
4. [Sensitive Admin-Managed Settings](#sensitive-admin-managed-settings)
5. [Host-Managed Secrets And Runtime State](#host-managed-secrets-and-runtime-state)
6. [Change Rules](#change-rules)

---

## Purpose

The production site now has three different state layers:

- Git-managed application/infrastructure code
- host-managed secrets and runtime environment
- WordPress-admin-managed content and business settings

If those layers are not separated clearly, deploys become risky because Git changes can accidentally overwrite production-only state, or WP Admin changes can quietly drift into areas that should stay reproducible from code.

This file defines the canonical ownership boundary for `https://hopp.delvedeepasia.org` and for the eventual `humansofphnompenh.com` cutover.

---

## Code-Managed State

These items belong to Git and must be changed through feature branches, `development`, `main`, and the canonical VM deploy path:

- theme code:
  PHP templates, CSS, JavaScript, theme helpers, image references, shortcode cleanup logic
- deploy/runtime scripts:
  `scripts/deploy-production.sh`, `scripts/rollback-production.sh`, `scripts/gcp-startup.sh`, bootstrap shell logic
- container/infrastructure config:
  `docker-compose.yml`, `docker-compose.gcp.yml`, `Makefile`, runtime mounts, PHP upload limits
- nginx and TLS bootstrap templates:
  `nginx/templates/*`, `docker/nginx/select-template.sh`
- repo-owned WordPress runtime hooks:
  ABA patch logic, MU plugins, upload-permission normalization, SMTP bridge code
- operational documentation:
  deploy runbooks, ownership rules, backup/restore instructions, smoke-test procedures

Code-managed rule:

- if changing it requires a deploy to take effect, assume it is code-managed unless this document explicitly says otherwise

---

## WP-Admin-Managed State

These items belong to normal WordPress or WooCommerce operations and should be changed in WP Admin, not in Git:

- posts, stories, pages, products, categories, tags
- menus and menu ordering
- media library content
- editorial copy, featured images, SEO/editorial metadata if later added
- CF7 submissions and normal form-entry handling
- WooCommerce catalog content:
  prices, product descriptions, stock, product images, attributes, order records
- normal site-reading/editorial settings that do not change runtime architecture
- plugin content/settings whose purpose is business operation rather than runtime wiring, as long as they are not listed below as sensitive

WP-admin-managed rule:

- if it is day-to-day content or business data that should survive deploys unchanged, it belongs to WP Admin or the database layer, not Git

---

## Sensitive Admin-Managed Settings

These settings still live in WordPress or third-party admin surfaces, but they are sensitive enough that they must be changed deliberately and documented:

- permalink structure
- reading settings and front-page assignments
- WooCommerce page assignments
- WooCommerce payment-gateway settings
- ABA merchant-side domain whitelist and callback assumptions
- SMTP-related plugin settings if ever reintroduced
- user roles/capabilities for real operators
- any plugin setting that changes checkout, notifications, redirects, or customer/account flow

Sensitive-admin rule:

- do not casually change these in WP Admin during content work
- if one changes, record it in `archive/daily_log.md`
- if the setting should become reproducible, move it into repo-managed runtime code or host-managed env config where practical

---

## Host-Managed Secrets And Runtime State

These items are not committed to Git and do not belong in normal WP Admin content workflows:

- `/opt/hopp/.env.gcp`
- SMTP credentials
- database passwords
- WordPress bootstrap admin password/email values on the host copy
- TLS contact email
- any real API key, merchant secret, or mailbox password

Host-managed rule:

- secrets live only on the VM host copy and trusted operator machines when needed
- never commit real values to Git
- repo code may define the variable names and expected shape, but not the real secret values

---

## Change Rules

1. Never let a deploy overwrite WP-admin-managed content silently.
Because the production WordPress volume and database contain business data, deploys should recreate runtime code paths, not replace posts, products, uploads, or normal admin-managed settings.

2. Never patch repo-owned code only in WP Admin or only on the VM.
If a change affects theme/runtime behavior, write it into Git and redeploy.

3. Never treat host-only secrets as WP Admin configuration.
SMTP credentials, DB passwords, and similar values belong in `/opt/hopp/.env.gcp`, not inside ad hoc production plugin config when a repo-owned runtime path exists.

4. If a plugin is retained only for old data but not current runtime, disable auto-updates and remove it only after a deactivate-then-test check.
This is the correct standard for plugins like Forminator that may still have historical references in the database.

5. If a setting is business-critical and outside Git, identify the control surface explicitly.
Example:
- ABA checkout success/pushback URLs are stored in WooCommerce plugin settings
- ABA domain whitelist is controlled on the merchant/provider side, not by WordPress

6. When a future task needs backup, restore, access control, or cutover rules, use this ownership split as the source of truth.
That prevents backup plans from missing DB/uploads state, and prevents cutover plans from assuming Git alone defines production reality.
