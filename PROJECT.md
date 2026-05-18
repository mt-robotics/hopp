# Humans of Phnom Penh Website - Project Navigation

**What it is:** A WordPress website redesign and custom theme build for Humans of Phnom Penh, a Phnom Penh-based cultural storytelling and content production platform.

---

## Current Focus

**Next:** Execute the final primary-domain cutover to `https://humansofphnompenh.com` and finish the first whitelisted-domain production verification round for mail and WooCommerce.

Full task spec: `current_state/project_status.md` -> `Execute Primary-Domain Cutover And Final Live Verification`.

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

**Repo-owned production workflow standardized:** The sponsor-funded VM is now serving the imported site at `https://hopp.delvedeepasia.org`, and the repo now contains the canonical deploy, rollback, smoke-test, backup/restore, access-control, and cutover runbooks/scripts needed to operate it consistently.

**Design source of truth:** `DESIGN.md` defines the palette, typography, components, and navigation decision. The theme should implement these tokens directly instead of inventing a separate visual system.

**Navigation preserved:** The original 7-item nav remains: Home, About Us, Products, Stories, Artist, Career, Contact Us, plus cart.

**Plugin stack confirmed:** WooCommerce is active on the live site and is mirrored locally for the imported workload. Contact Form 7 now handles the four public form pages in local staging. Divi-only builder layouts were not imported because the live Divi stack is not present locally.

**ABA patch is deployment-owned:** The ABA PayWay plugin fix is no longer only a one-off local container edit. `docker/wordpress/start-wordpress.sh` runs on WordPress container boot and calls `docker/wordpress/apply-aba-payway-patch.php`, which reapplies the known-safe plugin patch when the ABA plugin file exists and still matches the expected unpatched vendor version.

**Production ownership is group-based:** The server app path should live at `/opt/hopp` with ownership `root:hopp`. Every real operator gets an individual Linux user and membership in group `hopp`; the deployment must not depend on one personal account owning the repository tree.

**Git-managed code vs WP-admin-managed operations:** The ownership boundary is now explicit in `docs/production_state_ownership.md`. Developers own theme/runtime/infrastructure code through Git; ops/content teams own normal content and business data in WP Admin; host secrets stay in `/opt/hopp/.env.gcp`; and a small set of sensitive admin-side settings must still be changed deliberately and logged.

**Production mail path is now repo-owned:** The live VM no longer needs a WP-admin SMTP plugin as the source of truth. SMTP transport, sender identity, and admin-recipient overrides are now defined through `docker/wordpress/mu-plugins/hopp-production-mail.php` plus host-managed `.env.gcp` values. The remaining live execution step is the final whitelisted-domain verification during primary-domain cutover.

**Manual VM path adopted:** The sponsor-funded VM was created manually in GCP Console for maximum safety. Repo-owned bootstrap and deployment files still apply after VM creation; only the VM creation step itself was done outside the repo automation.

**Canonical branch strategy:** Production is anchored to `main`. Feature work happens on `feature/*`, reviewed integration happens on `development`, and only committed revisions on `main` are eligible for production deployment or rollback references.

**Canonical VM deploy path:** Production deploys now happen by SSHing to the VM, using the `/opt/hopp` checkout, fast-forwarding that checkout to `origin/main`, and recreating the stack from the repo-owned GCP Compose path. The standard entrypoint is `./scripts/deploy-production.sh`; emergency rollback uses `./scripts/rollback-production.sh <known-good-main-sha>`. `.env.gcp` remains host-managed on the VM and is not Git-managed.

**Final primary-domain decision:** `https://humansofphnompenh.com` is the intended long-term public production host. `https://hopp.delvedeepasia.org` is now the transition host until the final cutover and verification task is executed.

---

## Key Files

| File | Purpose |
|---|---|
| `current_state/project_status.md` | Current status, active V1 checklist, blockers |
| `current_state/milestone.md` | Completed milestone details |
| `docs/live_site_settings.md` | Live admin snapshot: theme, plugins, WooCommerce, menus, reading settings |
| `docs/local_import_checklist.md` | Step-by-step local import checklist for the live XML export |
| `docs/sponsored_gcp_deployment_plan.md` | Current recommendation for the sponsor-funded production GCP host |
| `docs/production_operations_index.md` | First-stop operator guide for production commands and custom-vs-normal WP boundaries |
| `docs/production_vm_deploy.md` | Canonical Git-to-VM deploy and rollback runbook for the live host |
| `docs/production_state_ownership.md` | Canonical boundary for Git-managed code, WP-admin-managed state, and host-managed secrets |
| `scripts/gcp-provision-vm.sh` | Creates the recommended Compute Engine VM and networking primitives |
| `scripts/gcp-startup.sh` | First-boot package install script for the GCP host, including `make` for deploy helpers |
| `scripts/deploy-production.sh` | Production deploy helper: update `/opt/hopp` to `origin/main` and recreate the stack |
| `scripts/rollback-production.sh` | Emergency rollback helper for a known-good `main` commit |
| `scripts/backup-production.sh` | Creates a production backup bundle with DB dump, uploads archive, and manifest |
| `scripts/restore-production.sh` | Restores production DB and uploads from a backup bundle |
| `scripts/smoke-test-production.sh` | Runs the automated production GET smoke suite |
| `docs/production_mail_and_form_verification.md` | Canonical production SMTP path and live verification checklist |
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
| Live mailbox credentials and inbox verification are still pending | The SMTP bridge is in repo code now, but public forms and order mail are not production-complete until Hostinger mailbox delivery is proven |
| Final primary-domain cutover is not yet executed | The workflow is now defined, but the live move back to `humansofphnompenh.com` and final ABA/WooCommerce verification still need a controlled production window |
| First live backup/restore drill has not been run yet | Backup automation now exists, but one controlled rehearsal is still needed before treating recovery timing as proven |

---

## Where to Find Things

| What you need | File |
|---|---|
| Local Docker shortcuts | `Makefile` |
| Active task list and next step | `current_state/project_status.md` |
| Completed work detail | `current_state/milestone.md` |
| Current GCP sizing/deployment recommendation | `docs/sponsored_gcp_deployment_plan.md` |
| First production operations doc to read | `docs/production_operations_index.md` |
| Canonical production VM deploy path | `docs/production_vm_deploy.md` |
| Active production execution tasks | `current_state/project_status.md` |
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
