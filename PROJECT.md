# Humans of Phnom Penh Website - Project Navigation

**What it is:** A WordPress website redesign and custom theme build for Humans of Phnom Penh, a Phnom Penh-based cultural storytelling and content production platform.

---

## Current Focus

**Next:** Finish validating the imported staging site, including the custom single-product layout and any remaining content gaps, then size the sponsor-funded GCP deployment from the real workload.

Full task spec: `current_state/project_status.md` -> sponsored GCP deployment plan.

---

## Tech Stack

| Layer | Technology |
|---|---|
| CMS | WordPress |
| Theme | Custom WordPress theme, planned directory `wp-content/themes/hopp/` |
| Styling | HTML, CSS, JavaScript; Tailwind CDN or local Tailwind build to be decided during theme implementation |
| Design source | `DESIGN.md` using Google Stitch DESIGN.md format |
| Database | MySQL via Docker Compose for local WordPress |
| E-commerce | WooCommerce |
| Local infra | Docker Compose, WordPress container, MySQL container, named volumes |
| Cloud hosting | Google Cloud Platform (GCP) - sponsor-funded Compute Engine target, size to be determined from the cloned workload |
| Production access | WP admin access available; live server access unavailable but not required for the import-first plan |

---

## Project Structure

```text
.
├── CLAUDE.md                  # Project-specific agent instructions
├── DESIGN.md                  # Design system source of truth
├── DOCKER_SETUP.md            # Local WordPress Docker guide
├── PROJECT.md                 # Internal project navigation hub
├── README.md                  # Public-facing project overview and setup
├── Makefile                   # Local Docker shortcuts for setup and runtime tasks
├── docker-compose.local.yml   # Local WordPress override
├── docker-compose.gcp.yml     # GCP preview override
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
├── docker-compose.yml         # Shared WordPress + MySQL base
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

**Keep WordPress:** The existing live site is WordPress. The safe path is to export the live site, inspect and adapt the clone locally, and keep the CMS/content model intact instead of migrating to a different stack.

**Local-first workflow:** Theme work must happen in Docker first. The live site should only receive the theme after local verification across pages, responsive sizes, product/cart behavior, forms, and browser console checks.

**Import-first workflow:** The live site has already been exported and imported locally. Use the imported stack to inspect the real theme/plugins/media footprint and runtime needs before any final server is created.

**Design source of truth:** `DESIGN.md` defines the palette, typography, components, and navigation decision. The theme should implement these tokens directly instead of inventing a separate visual system.

**Navigation preserved:** The original 7-item nav remains: Home, About Us, Products, Stories, Artist, Career, Contact Us, plus cart.

**Plugin stack confirmed:** WooCommerce is active on the live site and is mirrored locally for the imported workload. Divi-only builder layouts were not imported because the live Divi stack is not present locally.

---

## Key Files

| File | Purpose |
|---|---|
| `current_state/project_status.md` | Current status, active V1 checklist, blockers |
| `current_state/milestone.md` | Completed milestone details |
| `docs/live_site_settings.md` | Live admin snapshot: theme, plugins, WooCommerce, menus, reading settings |
| `docs/local_import_checklist.md` | Step-by-step local import checklist for the live XML export |
| `DESIGN.md` | Visual tokens, layout rules, component inventory |
| `resources/context.md` | Legacy site description and constraints |
| docs/current_site_audit.md | Public site page inventory, components, forms, e-commerce risk classification |
| docs/demo_design_plan.md | V1 demo design direction, page plans, component plan, implementation order |
| DOCKER_SETUP.md | Docker setup, env vars, local workflow |

| `docs/live_wordpress_deployment.md` | Live WordPress admin-only deployment runbook |
| `.env.example` | Safe environment variable reference |
| `.env.local` | Local Docker runtime environment file, ignored by Git |
| `docs/error_log.md` | Root-cause debugging notes |

---

## Current Blockers

| Blocker | Impact |
|---|---|
| Divi builder layouts not imported locally | The live stack uses Divi-specific post types that are not available in the current local theme stack |
| Sponsor-funded GCP size not yet chosen | Final server layout cannot be locked until the clone is measured |

---

## Where to Find Things

| What you need | File |
|---|---|
| Local Docker shortcuts | `Makefile` |
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
| Local WordPress | `http://localhost:8080` after `make up` |
| Historical GCP preview | `http://hopp.delvedeepasia.org` (legacy preview path) |
