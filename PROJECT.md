# Humans of Phnom Penh Website - Project Navigation

**What it is:** A WordPress website redesign and custom theme build for Humans of Phnom Penh, a Phnom Penh-based cultural storytelling and content production platform.

---

## Current Focus

**Next:** Present the V1 local WordPress UI/UX demo to the team for review.

Full task spec: `current_state/project_status.md` -> V1 Local WordPress Theme Developed and Verified.

---

## Tech Stack

| Layer | Technology |
|---|---|
| CMS | WordPress |
| Theme | Custom WordPress theme, planned directory `wp-content/themes/hopp/` |
| Styling | HTML, CSS, JavaScript; Tailwind CDN or local Tailwind build to be decided during theme implementation |
| Design source | `DESIGN.md` using Google Stitch DESIGN.md format |
| Database | MySQL via Docker Compose for local WordPress |
| E-commerce | Unknown WordPress plugin; likely WooCommerce, but not verified |
| Local infra | Docker Compose, WordPress container, MySQL container, named volumes |
| Production access | Pending WP admin credentials; no server access |

---

## Project Structure

```text
.
├── CLAUDE.md                  # Project-specific agent instructions
├── DESIGN.md                  # Design system source of truth
├── DOCKER_SETUP.md            # Local WordPress Docker guide
├── PROJECT.md                 # Internal project navigation hub
├── README.md                  # Public-facing project overview and setup
├── .env.example               # Safe env var reference
├── archive/
│   └── daily_log.md           # Session log
├── current_state/
│   ├── milestone.md           # Completed task archive
│   └── project_status.md      # Active status and roadmap
├── docs/
│   ├── error_log.md           # Debugging investigations
│   ├── current_site_audit.md  # Public site content/component audit
│   ├── demo_design_plan.md    # V1 demo page/component design plan
│   └── live_wordpress_deployment.md # Live WP admin deployment runbook
├── docker-compose.yml         # Local WordPress + MySQL environment
├── index.html                 # Current static prototype/reference
└── resources/
    └── context.md             # Legacy website and business context
├── wp-content/
│   └── themes/
│       └── hopp/              # Bind-mounted local theme directory
```

Planned once theme work starts:

```text
wp-content/
└── themes/
    └── hopp/
        ├── style.css
        ├── functions.php
        ├── assets/js/navigation.js
        ├── index.php
        ├── header.php
        ├── footer.php
        └── templates...
```

---

## Key Decisions

**Keep WordPress:** The existing live site is WordPress and server access is unavailable. A custom WordPress theme is the standard safe path because it preserves the CMS/content model and avoids a full-stack migration.

**Local-first workflow:** Theme work must happen in Docker first. The live site should only receive the theme after local verification across pages, responsive sizes, product/cart behavior, forms, and browser console checks.

**Design source of truth:** `DESIGN.md` defines the palette, typography, components, and navigation decision. The theme should implement these tokens directly instead of inventing a separate visual system.

**Navigation preserved:** The original 7-item nav remains: Home, About Us, Products, Stories, Artist, Career, Contact Us, plus cart.

**Plugin uncertainty:** Products/cart behavior suggests an e-commerce plugin, probably WooCommerce, but this is not confirmed. Avoid plugin-specific assumptions until admin access or local import confirms the real setup.

---

## Key Files

| File | Purpose |
|---|---|
| `current_state/project_status.md` | Current status, active V1 checklist, blockers |
| `current_state/milestone.md` | Completed milestone details |
| `DESIGN.md` | Visual tokens, layout rules, component inventory |
| `resources/context.md` | Legacy site description and constraints |
| `docs/current_site_audit.md` | Public site page inventory, components, forms, e-commerce risk classification |
| `docs/demo_design_plan.md` | V1 demo design direction, page plans, component plan, implementation order |
| `DOCKER_SETUP.md` | Docker setup, env vars, local workflow |
| `docs/live_wordpress_deployment.md` | Live WordPress admin-only deployment runbook |
| `.env.example` | Safe environment variable reference |
| `.env.local` | Local Docker runtime environment file, ignored by Git |
| `docs/error_log.md` | Root-cause debugging notes |

---

## Current Blockers

| Blocker | Impact |
|---|---|
| No WP admin credentials | Cannot export live content, inspect active theme, confirm plugins, or upload final theme |
| No server access | Theme rollback is limited to what WP admin allows |
| Current theme name unknown | Production rollback procedure cannot be finalized |
| E-commerce plugin unknown | Products/cart template requirements cannot be confirmed yet |

---

## Where to Find Things

| What you need | File |
|---|---|
| Active task list and next step | `current_state/project_status.md` |
| Completed work detail | `current_state/milestone.md` |
| Visual implementation rules | `DESIGN.md` |
| Current public site structure | `docs/current_site_audit.md` |
| V1 demo design plan | `docs/demo_design_plan.md` |
| Old site notes and constraints | `resources/context.md` |
| Docker setup and env vars | `DOCKER_SETUP.md` |
| Live admin-only deployment steps | `docs/live_wordpress_deployment.md` |
| Session history | `archive/daily_log.md` |

---

## Live Site

| Environment | URL |
|---|---|
| Production website | `https://www.humansofphnompenh.com` |
| Local WordPress | `http://localhost:8080` after `docker compose --env-file .env.local up -d` |
