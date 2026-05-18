# Humans of Phnom Penh Website - Project Navigation

**What it is:** A WordPress website redesign and custom theme build for Humans of Phnom Penh, a Phnom Penh-based cultural storytelling and content production platform.

---

## Current Focus

**Next:** Standardize the live sponsor-funded production stack so `https://hopp.delvedeepasia.org` is not just working, but operating through a clean long-term workflow: verify mail/smoke tests, document the split between code-managed state and WP-admin-managed state, and finish the remaining production operations rules.

Full task spec: `current_state/project_status.md` -> `Standardize Production WordPress Workflow`.

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
| Forms | Contact Form 7 |
| Local infra | Docker Compose, WordPress container, MySQL container, named volumes |
| Cloud hosting | Google Cloud Platform (GCP) - sponsor-funded Compute Engine VM, currently one `e2-medium` with `50 GB pd-balanced` |
| Production access | WP admin access available; sponsor-funded VM access available through `ssh hopp-prod` and group-managed `/opt/hopp` ownership |

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
├── docker/nginx/              # Nginx bootstrap selector for HTTP-first / HTTPS-after-cert startup
├── docker/php/uploads.ini     # PHP upload limits for CF7 artwork/CV forms
├── docker/wordpress/          # WordPress startup scripts, including ABA PayWay runtime patching
├── nginx/templates/           # Bootstrap HTTP and SSL nginx templates for production
├── scripts/                   # GCP VM provisioning and host bootstrap scripts
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

**Local-first workflow:** Theme work must happen in Docker first. Production should receive only reviewed, reproducible changes after local verification across pages, responsive sizes, product/cart behavior, forms, and browser console checks.

**Import-first workflow:** The live site has already been exported and imported locally. Use the imported stack to inspect the real theme/plugins/media footprint and runtime needs before any final server is created.

**Live-first standardization phase:** The sponsor-funded VM is now serving the imported site at `https://hopp.delvedeepasia.org`. The current phase is no longer initial bring-up; it is production standardization: deploy workflow, backups, mail delivery, smoke tests, and explicit ownership boundaries between Git-managed code and WP-admin-managed content/settings.

**Design source of truth:** `DESIGN.md` defines the palette, typography, components, and navigation decision. The theme should implement these tokens directly instead of inventing a separate visual system.

**Navigation preserved:** The original 7-item nav remains: Home, About Us, Products, Stories, Artist, Career, Contact Us, plus cart.

**Plugin stack confirmed:** WooCommerce is active on the live site and is mirrored locally for the imported workload. Contact Form 7 now handles the four public form pages in local staging. Divi-only builder layouts were not imported because the live Divi stack is not present locally.

**ABA patch is deployment-owned:** The ABA PayWay plugin fix is no longer only a one-off local container edit. `docker/wordpress/start-wordpress.sh` runs on WordPress container boot and calls `docker/wordpress/apply-aba-payway-patch.php`, which reapplies the known-safe plugin patch when the ABA plugin file exists and still matches the expected unpatched vendor version.

**Production ownership is group-based:** The server app path should live at `/opt/hopp` with ownership `root:hopp`. Every real operator gets an individual Linux user and membership in group `hopp`; the deployment must not depend on one personal account owning the repository tree.

**Git-managed code vs WP-admin-managed operations:** Developers should own theme code, Docker/nginx/bootstrap logic, deployment scripts, and infrastructure documentation through Git. Ops/content teams should own posts, pages, products, menus, media, routine editorial settings, and approved business settings through WP Admin. Sensitive operational settings such as payment callbacks, SMTP, reading settings, and WooCommerce page assignments must still be documented and changed carefully.

**Manual VM path adopted:** The sponsor-funded VM was created manually in GCP Console for maximum safety. Repo-owned bootstrap and deployment files still apply after VM creation; only the VM creation step itself was done outside the repo automation.

**Canonical branch strategy:** Production is anchored to `main`. Feature work happens on `feature/*`, reviewed integration happens on `development`, and only committed revisions on `main` are eligible for production deployment or rollback references.

**Canonical VM deploy path:** Production deploys now happen by SSHing to the VM, using the `/opt/hopp` checkout, fast-forwarding that checkout to `origin/main`, and recreating the stack from the repo-owned GCP Compose path. The standard entrypoint is `./scripts/deploy-production.sh`; emergency rollback uses `./scripts/rollback-production.sh <known-good-main-sha>`. `.env.gcp` remains host-managed on the VM and is not Git-managed.

---

## Key Files

| File | Purpose |
|---|---|
| `current_state/project_status.md` | Current status, active V1 checklist, blockers |
| `current_state/milestone.md` | Completed milestone details |
| `docs/live_site_settings.md` | Live admin snapshot: theme, plugins, WooCommerce, menus, reading settings |
| `docs/local_import_checklist.md` | Step-by-step local import checklist for the live XML export |
| `docs/sponsored_gcp_deployment_plan.md` | Current recommendation for the sponsor-funded production GCP host |
| `docs/production_vm_deploy.md` | Canonical Git-to-VM deploy and rollback runbook for the live host |
| `scripts/gcp-provision-vm.sh` | Creates the recommended Compute Engine VM and networking primitives |
| `scripts/gcp-startup.sh` | First-boot package install script for the GCP host, including `make` for deploy helpers |
| `scripts/deploy-production.sh` | Production deploy helper: update `/opt/hopp` to `origin/main` and recreate the stack |
| `scripts/rollback-production.sh` | Emergency rollback helper for a known-good `main` commit |
| `docker/nginx/select-template.sh` | Chooses HTTP bootstrap or HTTPS nginx config based on cert presence |
| `DESIGN.md` | Visual tokens, layout rules, component inventory |
| `resources/context.md` | Legacy site description and constraints |
| docs/current_site_audit.md | Public site page inventory, components, forms, e-commerce risk classification |
| docs/demo_design_plan.md | V1 demo design direction, page plans, component plan, implementation order |
| DOCKER_SETUP.md | Docker setup, env vars, local workflow |

| `docs/live_wordpress_deployment.md` | Live WordPress admin-only deployment runbook |
| `.env.example` | Safe environment variable reference |
| `.env.local` | Local Docker runtime environment file, ignored by Git |
| `.env.gcp` | Host-only production env file on the VM; source-controlled copy remains placeholders only |
| `docs/error_log.md` | Root-cause debugging notes |

---

## Current Blockers

| Blocker | Impact |
|---|---|
| CF7 delivery is not yet verified on a real mail path | Public forms are not production-complete until SMTP or the final mail relay is proven |
| Backup and restore path is not yet automated | A production incident would still rely too much on manual recovery unless DB/uploads backup and restore are documented and tested |
| Final primary domain strategy is not yet settled | `hopp.delvedeepasia.org` works now, but any eventual move back to `humansofphnompenh.com` needs an explicit cutover plan |

---

## Where to Find Things

| What you need | File |
|---|---|
| Local Docker shortcuts | `Makefile` |
| Active task list and next step | `current_state/project_status.md` |
| Completed work detail | `current_state/milestone.md` |
| Current GCP sizing/deployment recommendation | `docs/sponsored_gcp_deployment_plan.md` |
| Canonical production VM deploy path | `docs/production_vm_deploy.md` |
| Production-standardization task list | `current_state/project_status.md` |
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
| Current live sponsor-funded host | `https://hopp.delvedeepasia.org` |
| Legacy public production domain | `https://www.humansofphnompenh.com` |
| Local WordPress | `http://localhost:8080` after `make up` |
| Historical GCP preview note | `hopp.delvedeepasia.org` started as a preview path and is now the active live sponsor-funded host |
