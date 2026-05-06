# Humans of Phnom Penh Website

A WordPress redesign and custom theme project for Humans of Phnom Penh, a cultural storytelling and content production platform rooted in Phnom Penh, Cambodia.

The goal is to build a new WordPress theme from the `DESIGN.md` design system, verify it locally, then deploy it through WordPress admin only after the theme is production-ready.

## Current Status

- Design system completed in `DESIGN.md`
- Local WordPress Docker environment scaffolded
- V1 local WordPress UI/UX demo theme implemented for team review
- GCP-hosted public preview stack scaffolded in the repo
- WP admin credentials pending
- Live theme name and installed plugins not yet confirmed
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
| Frontend | HTML, CSS, JavaScript |
| Design source | `DESIGN.md` |

## Local Setup

Start the local WordPress environment:

```bash
make init
make up
```

Expected local services:

- WordPress: `http://localhost:8080`
- MySQL: internal Docker service

The local Docker override defines `WP_HOME` and `WP_SITEURL` as `http://localhost:8080` and enables local-only demo seeding. Demo content must not be treated as production content.

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
