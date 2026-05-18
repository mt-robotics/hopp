# Humans of Phnom Penh Website

A WordPress redesign and custom theme project for Humans of Phnom Penh, a cultural storytelling and content production platform rooted in Phnom Penh, Cambodia.

The goal is to build and verify the site locally first, then promote reviewed changes through Git and the sponsor-funded GCP production stack once they are production-ready.

## Current Status

- Design system completed in `DESIGN.md`
- Local WordPress Docker environment scaffolded
- V1 local WordPress UI/UX demo theme implemented for team review
- GCP-hosted public preview stack scaffolded in the repo
- Live WordPress content export imported locally
- Live theme and plugin stack confirmed through WP admin
- Custom single-product template now renders recovered product copy from the XML export where available
- Form pages migrated from Forminator to Contact Form 7 in local staging
- ABA PayWay checkout patch is now preserved through a repo-owned WordPress startup hook so container rebuilds do not drop the working gateway fix
- Production mail now has a repo-owned SMTP/recipient bridge, but live mailbox credentials and inbox verification are still required before forms/order mail are truly production-ready
- Production site must not be changed until V2 validation against real content/plugins is complete

## Planned Features

- Custom WordPress theme: `hopp`
- V1 demo pages for:
  - Home
  - About Us
  - Products
  - Stories
  - Artist
  - Career
  - Contact Us
- Preserved cart/product behavior from the existing WordPress site
- Responsive implementation down to 320px viewport width
- Accessible navigation, forms, headings, focus states, and semantic HTML
- Local-first testing before any live upload

## Technology Stack

| Layer | Technology |
|---|---|
| CMS | WordPress |
| Database | MySQL |
| Local environment | Docker Compose |
| Theme | Custom WordPress theme |
| Forms | Contact Form 7 |
| Frontend | HTML, CSS, JavaScript |
| Design source | `DESIGN.md` |

## Theme Color Tokens

`DESIGN.md` is the design reference, but the code-level source of truth for reusable theme colors is `wp-content/themes/hopp/style.css`.

Use the semantic `--hopp-brand-*` variables for shared surfaces, CTAs, and button states instead of hardcoding page-specific colors. When the final brand direction changes, update the token values first, then check for any remaining one-off styles only if a page intentionally needs a special treatment.

Current shared brand tokens:

| Token | Intended use |
|---|---|
| `--hopp-brand-surface` | Primary dark brand surfaces, including shared CTA and utility controls |
| `--hopp-brand-accent` | Primary button/accent fill |
| `--hopp-brand-accent-hover` | Hover/focus fill for primary accents |
| `--hopp-brand-band` | Broad branded bands when a section needs the global theme surface |

## Local Setup

Start the local WordPress environment:

```bash
make init
make up
```

Expected local services:

- WordPress: `http://localhost:8080`
- MySQL: internal Docker service

The local Docker override defines `WP_HOME` and `WP_SITEURL` as `http://localhost:8080` and keeps demo seeding disabled so the clean-import workflow starts from the imported live content instead of placeholder data.

The shared WordPress container startup path now also runs a repo-owned ABA PayWay patch step from `docker/wordpress/` so a recreated container can reapply the known gateway fix automatically when the plugin is present.

Production mail is now also standardized around a repo-owned MU plugin plus host-managed `.env.gcp` values. For the current live domain, the intended provider path is Hostinger SMTP, and the detailed verification runbook lives in `docs/production_mail_and_form_verification.md`.

Use `make help` to see the available shortcuts for starting, stopping, rebuilding, and opening shells in the containers.

See `DOCKER_SETUP.md` for the setup plan and environment variable reference.

## Project Files

| File | Purpose |
|---|---|
| `Makefile` | Local setup and Docker shortcuts |
| `docker-compose.local.yml` | Local WordPress override |
| `docker-compose.gcp.yml` | GCP preview override |
| `docker/wordpress/` | WordPress startup scripts, including the ABA PayWay runtime patch path |
| `docker/wordpress/mu-plugins/` | Repo-owned production MU plugins, including SMTP/recipient routing |
| `scripts/gcp-provision-vm.sh` | GCP VM provisioner for the sponsor-funded production host |
| `scripts/gcp-startup.sh` | First-boot host bootstrap for Docker, Compose, Git, and `make` |
| `DESIGN.md` | Design system and implementation source of truth |
| `PROJECT.md` | Internal navigation, architecture, and current focus |
| `current_state/project_status.md` | Active roadmap and blockers |
| `current_state/milestone.md` | Completed milestone archive |
| `DOCKER_SETUP.md` | Local Docker setup guide |
| `.env.example` | Safe environment variable template |

## Branch And Release Flow

Production is anchored to `main`.

- `feature/*`: one focused task per branch
- `development`: integration branch for reviewed work before release
- `main`: production branch; every live deploy must map to a committed revision here

Expected release path:

```text
feature/* -> development -> main -> production deploy
```

Rules:

- Never treat a personal or ad hoc branch as deployable production state
- If the server needs a hotfix, write it back into Git immediately and merge it to `main`
- Production verification and rollback should always reference a `main` commit, not an untracked server edit

## Production Ownership Boundary

Production now follows an explicit three-layer ownership model:

- Git-managed code and infrastructure
- host-managed secrets in `/opt/hopp/.env.gcp`
- WP-admin-managed content and business settings

The detailed rule set lives in `docs/production_state_ownership.md`. This boundary is what future backup, rollback, access-control, and cutover work should assume.

## Canonical VM Deploy Path

The production VM deploy path is now:

```text
main commit -> ssh hopp-prod -> /opt/hopp -> ./scripts/deploy-production.sh
```

Operational rules:

- `/opt/hopp` is the canonical production checkout on the VM
- `.env.gcp` stays host-managed on the VM and is never overwritten by Git
- normal deploys use `./scripts/deploy-production.sh`
- emergency rollbacks use `./scripts/rollback-production.sh <known-good-main-sha>`
- the full runbook lives in `docs/production_vm_deploy.md`

## Deployment Safety

This project must be developed and tested locally first. Production deployment should happen only from reviewed `main` commits after the production workflow tasks are complete. Production deployment remains blocked until:

- Live content is exported and imported locally
- Local theme passes full page, mobile, product/cart, form, and console-error checks
- Production mail delivery is verified
- Backup and restore procedures are documented
