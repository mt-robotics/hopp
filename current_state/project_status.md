# Project Status

**Last Updated:** 2026-05-09 (UI tasks complete)

---

## Current State

- Google Cloud sponsor access is now available, so infrastructure sizing must be done conservatively and with the full workload in view before spending credits
- WordPress admin access for `humansofphnompenh.com` is available, so the live site has now been exported and imported locally
- Live admin theme, plugin, WooCommerce, menu, and Reading settings are captured in `docs/live_site_settings.md`
- The local import checklist is now documented in `docs/local_import_checklist.md`
- The live XML import into local WordPress is complete: pages, posts, products, attachments, menu items, and forms match the live snapshot; HOPP is active locally with live menu, Reading, and WooCommerce assignments mirrored
- Divi-only `et_*` layout post types were skipped during import; the custom HOPP theme does not depend on them for the new UI
- Local staging runtime now auto-enforces pretty permalinks and disables WooCommerce "coming soon" mode so `/products/`, `/stories/`, and product/story permalinks resolve correctly
- Story pages now render without raw Divi shortcode junk, and product cards are clickable and point to real product URLs
- WooCommerce single-product pages now use a custom theme template plus an XML-derived product profile map so recovered descriptions render in a clean code-driven layout
- Two imported products still have no recoverable body copy in the export data, so those pages intentionally show image/title/price/add-to-cart/category without invented placeholder text
- ABA PayWay checkout is restored in local staging: the gateway settings mirror the live payment-method selection, the gateway plugin was patched to normalize `payment_options`, and checkout now renders the four live payment rows; local order submission still hits PayWay's domain whitelist on `localhost`, so the production host must remain the whitelisted `humansofphnompenh.com`
- The ABA PayWay runtime fix is active in local staging, but the deployment artifact still needs to carry that patch forward before any rebuild or cutover
- The sponsor-funded GCP path is now the only active deployment route; the next step is to inspect the imported stack, preserve the ABA PayWay fix, estimate resource needs, and design the smallest reliable GCP deployment
- The live admin account can inspect plugins and WooCommerce settings, but it cannot install new plugins, so All-in-One WP Migration is not available from this account
- GCP server access established — VM (e2-micro) running in us-west1 (personal-account legacy preview VM; no longer used for the sponsor-funded deployment)
- Static External IP (35.252.238.69) reserved and mapped to `hopp.delvedeepasia.org`
- SSH access configured from local laptop via SSH keys
- Docker and Docker Compose installed and verified on GCP server
- Nginx templates prepared for automatic variable substitution
- Current live theme confirmed: `Divi`
- `DESIGN.md` written — all color tokens reconciled, nav Option B (original 7-item nav preserved)
- Standard project documentation scaffold created (`CLAUDE.md`, `PROJECT.md`, `README.md`, `DOCKER_SETUP.md`, `.env.example`, `docs/error_log.md`)
- Local WordPress Docker scaffold created (`docker-compose.yml`, `docker-compose.local.yml`, `.env.local`, theme bind-mount directory); Compose config validates
- Local WordPress is running and the HOPP theme pipeline is verified
- Live admin-only deployment runbook documented in `docs/live_wordpress_deployment.md`
- Public site crawl distilled into `docs/current_site_audit.md`
- V1 demo design plan documented in `docs/demo_design_plan.md`
- Current strategy: inspect the imported live stack, inventory any remaining runtime gaps, then size the smallest reliable sponsor-funded GCP deployment before redeploying anything
- V1 local WordPress UI/UX demo theme implemented and verified locally and over LAN
- Team review note prepared at `archive/20260504_team_confirmation.md`
- All 11 V1 UI tasks completed on feat/v1-ui-tasks: Artist, Career, Contact Us, Pitch Your Pal pages built; AJAX add-to-cart + toast; WooCommerce button color fixes; coupon hidden; hover effects consistent; story card images fixed; Browse by Series on Stories page; X icon in footer; all forms migrated to Forminator
- Cart icon with count badge added to the header (mobile: brand→cart→hamburger; desktop: brand→nav→cart); count increments live after AJAX add-to-cart
- "Pitch Your Pal" menu rename filter fixed (was checking wrong property); item now displays in terracotta color to distinguish it from standard nav items
- Forminator forms cannot be reliably styled — its ~500KB CSS (`forminator-ui.min.css`) uses `[data-design=default]` attribute selectors that beat any class-only override; dropdowns use Select2 (not native select), making them doubly hard to target
- Decision: replace all four Forminator forms (617 Artist, 628 Contact Us, 1256 Career, 1259 Pitch Your Pal) with Contact Form 7 (CF7) — minimal CSS, no skin system, full HTML control
- **Next:** Replace Forminator forms with CF7 on all four pages, then plan sponsored GCP deployment 🔲

---

## ✅ Completed Milestones

| Milestone                                            | Completed  | Tests                                                    |
| ---------------------------------------------------- | ---------- | -------------------------------------------------------- |
| Standard Project Documentation Scaffold              | 2026-04-30 | Manual file verification                                 |
| Design System (DESIGN.md)                            | 2026-04-30 | —                                                        |
| Local WordPress Docker Environment                   | 2026-04-30 | Docker Compose config, manual WP/theme render            |
| Current Site Audit + Demo Design Plan                | 2026-04-30 | Manual document review                                   |
| V1 Local WordPress UI/UX Demo Theme                  | 2026-04-30 | PHP lint, Docker Compose config, local route checks      |
| GCP Infrastructure Setup                             | 2026-05-06 | SSH, Docker, IP ping                                     |
| Live Export, Local Import, and ABA Checkout Recovery | 2026-05-08 | Manual admin inspection, XML import, checkout smoke test |
| Frontend Standards — HIGH Priority Fixes             | 2026-05-08 | Manual visual review                                     |

→ Full details: `current_state/milestone.md`

---

## V1 — Local WordPress UI/UX Demo for Team Review

**Goal:** A polished local WordPress theme demo and imported-content staging review built from `DESIGN.md`, so the team can review and approve the UI/UX direction and real site behavior before deployment.

### Task Checklist

- ✅ Design system (DESIGN.md)
- ✅ Local WP Docker environment
- ✅ Current site audit
- ✅ Demo design plan
- ✅ Build local UI/UX demo theme
- ✅ Create placeholder pages/content for review
- ✅ Set up local primary navigation
- ✅ Test demo locally (desktop/mobile/basic routes)
- ✅ Fix HIGH-priority frontend standards violations
- 🔲 Plan sponsored GCP deployment for the live WordPress site
- 🔄 Set up GCP-hosted public preview
- ✅ Inspect live admin settings, active theme, menus, plugins, and e-commerce setup
- ✅ Export live site content
- ✅ Import live content into local WordPress
- ✅ Install/mirror required plugins locally, especially e-commerce/contact form plugins
- 🔄 Stabilize ABA PayWay gateway for deployment
- ✅ Adapt HOPP theme to real content and plugin behavior
- ✅ Test production-critical flows locally
- ✅ Build Artist page UI
- ✅ Build Career page UI
- ✅ Build Contact Us page + update footer (add X / @HoPP_Kh)
- ✅ Rename and redesign "Pitch Your Pal: Phnom Penh" page
- ✅ Fix ADD TO CART UX (AJAX add-to-cart + dismissing toast)
- ✅ Fix WooCommerce button colors (ADD TO CART, UPDATE CART, VIEW CART, Proceed to checkout)
- ✅ Hide cart coupon section
- ✅ Fix blank image placeholders (investigate + re-link featured images)
- ✅ Surface /series/ on Stories page
- ✅ Audit and apply hover effects consistently across all pages
- 🔲 Replace Forminator forms with Contact Form 7 (CF7) on Artist, Career, Contact Us, Pitch Your Pal pages

---

## Active Task Details

Completed V1 implementation details are archived in `current_state/milestone.md`. This file now keeps only active tasks, blockers, and next work.

### 🔲 Replace Forminator forms with Contact Form 7 (CF7)

Forminator's `[data-design=default]` CSS framework (~500KB, includes Select2) cannot be reliably overridden with class selectors. CF7 has minimal CSS, no skin system, and gives full HTML control via its tag-based template syntax.

- 🔲 Verify CF7 plugin is active locally; install if missing
- 🔲 Create four CF7 forms: Artist (file upload + checkbox), Career (file upload + selects + checkbox), Contact Us (textarea), Pitch Your Pal (textarea)
- 🔲 Replace `do_shortcode('[forminator_form id="N"]')` calls in `page.php` with `do_shortcode('[contact-form-7 id="N"]')` for each slug
- 🔲 Style CF7 fields in `style.css` using `.wpcf7` selectors — no `!important` battles needed
- 🔲 Verify email notifications deliver (CF7 uses `wp_mail()` same as Forminator)
- Note: Forminator submission entries in DB (2 test entries) are non-critical and can be left as-is

### 🔲 Plan sponsored GCP deployment for the live WordPress site

Use the live WordPress admin access plus exported content and plugin inventory to size the smallest reliable GCP deployment before creating a new production server.

- ✅ Export the live WordPress project content locally
- ✅ Identify the actual theme, plugins, media volume, and database shape
- ✅ Reconcile the skipped Divi-only layout post types or document them as intentionally unsupported locally
- 🔲 Measure the runtime footprint of the imported stack
- 🔲 Decide whether the deployment should stay on one VM or split services
- 🔲 Choose the smallest reliable GCP machine type, disk size, and backup shape
- 🔲 Define the redeployment steps for the final server
- 🔲 Only after the plan is clear, provision the new GCP server and migrate

### 🔄 Stabilize ABA PayWay gateway for deployment

Keep the payment gateway behavior from drifting when the server is rebuilt or redeployed.

- ✅ Capture the live ABA PayWay settings and selected payment methods
- ✅ Patch the gateway plugin locally so `payment_options` is normalized before checkout rendering
- ✅ Verify the four ABA payment rows render on the checkout page
- 🔲 Persist the plugin fix in the deployment artifact or runtime layer before any rebuild
- 🔲 Keep the production success/pushback URLs on `humansofphnompenh.com`

### 🔄 Set up GCP-hosted public preview

The free-tier preview path was completed first and then superseded by the sponsor-funded deployment plan. Kept as historical record because the same repo, domain, and Docker wiring were reused as part of the transition.

- ✅ Provision VM on GCP Always Free Tier (e2-micro, us-west1)
- ✅ Reserve Static IP and point domain
- ✅ Configure SSH access with keys
- ✅ Install Docker and Docker Compose on VM
- ✅ Clone repo on VM using Deploy Key
- ✅ Configure production `.env.gcp`
- ✅ Run `docker-compose -f docker-compose.yml -f docker-compose.gcp.yml --env-file .env.gcp up -d`
- ✅ Verify public access at `http://hopp.delvedeepasia.org`
- 🔄 Share URL with the team

Transition note: this path proved the preview stack, but the sponsored project now needs a server plan sized from the real WordPress workload, not the free-tier demo constraints.

## Post-V1 Backlog

- ✅ Set up Git versioning for the theme directory
- 🔲 Run browser visual QA with screenshots after Playwright or another browser test tool is installed
- ✅ Evaluate WooCommerce styling (Products page may need additional theme work)
- 🔲 Performance audit (Core Web Vitals — LCP, CLS, FID)
- ✅ Contact form integration (elevated to V1 — Migrate all forms to Forminator)
- ✅ Fix MEDIUM/LOW frontend standards violations (from 2026-05-08 audit)

## Post-V1 Task Details

### 🔲 Run browser visual QA with screenshots

No automated browser testing has been run. A visual pass across all pages on both desktop and mobile viewports is needed to catch layout regressions before the site goes live.

- Install Playwright or equivalent browser tool in the project
- Run screenshot captures for all primary routes: `/`, `/about-us/`, `/products/`, `/stories/`, `/artist/`, `/career/`, `/contact-us/`, `/cart/`, and a single product and story page
- Review screenshots for layout breakages, spacing issues, and mobile overflow

### 🔲 Performance audit (Core Web Vitals)

No performance measurement has been done on the imported-content staging site. LCP, CLS, and FID should be measured against Google's thresholds before the GCP deployment.

- Run Lighthouse or PageSpeed Insights against the local staging site (or the GCP preview once deployed)
- Address any LCP element not loading eagerly, CLS from layout shifts, or blocking scripts


