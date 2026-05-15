# Humans of Phnom Penh Website

A WordPress redesign and custom theme project for Humans of Phnom Penh, a cultural storytelling and content production platform rooted in Phnom Penh, Cambodia.

The goal is to build a new WordPress theme from the `DESIGN.md` design system, verify it locally, then deploy it through WordPress admin only after the theme is production-ready.

## Current Status

- Design system completed in `DESIGN.md`
- Local WordPress Docker environment scaffolded
- V1 local WordPress UI/UX demo theme implemented for team review
- GCP-hosted public preview stack scaffolded in the repo
- Live WordPress content export imported locally
- Live theme and plugin stack confirmed through WP admin
- Custom single-product template now renders recovered product copy from the XML export where available
- Form pages migrated from Forminator to Contact Form 7 in local staging
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

Use `make help` to see the available shortcuts for starting, stopping, rebuilding, and opening shells in the containers.

See `DOCKER_SETUP.md` for the setup plan and environment variable reference.

## Project Files

| File | Purpose |
|---|---|
| `Makefile` | Local setup and Docker shortcuts |
| `docker-compose.local.yml` | Local WordPress override |
| `docker-compose.gcp.yml` | GCP preview override |
| `DESIGN.md` | Design system and implementation source of truth |
| `PROJECT.md` | Internal navigation, architecture, and current focus |
| `current_state/project_status.md` | Active roadmap and blockers |
| `current_state/milestone.md` | Completed milestone archive |
| `DOCKER_SETUP.md` | Local Docker setup guide |
| `.env.example` | Safe environment variable template |

## Deployment Safety

This project must be developed and tested locally first because there is currently no server access. A broken theme upload could make rollback difficult. Production deployment is blocked until:

- WP admin credentials are available
- Live content is exported and imported locally
- Current live theme name is documented
- Local theme passes full page, mobile, product/cart, form, and console-error checks
- Rollback procedure is documented
