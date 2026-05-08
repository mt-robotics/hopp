## 2026-04-30

- Session 1: project kickoff — read context.md, understood scope (WP UI/UX redesign, no server access, local Docker approach)
- Researched Google Stitch DESIGN.md format — fetched canonical spec from GitHub (format is "alpha", evolves)
- Decisions confirmed: Option B nav (original 7-item nav preserved), inline colors named by role (sand, terracotta, paper)
- Created `DESIGN.md` — 9 color tokens, 8 typography tokens, 13 components, Do's and Don'ts
- Diagnosed context injection issue: `.claude/` files not loading because no root `CLAUDE.md` and files not in `.claude/rules/`. User resolved by symlinking to `.claude/rules/`
- Created `/design-md` agent skill at `agent_core/skills/design-md/SKILL.md` — 6-step workflow, always fetches live spec
- Created `current_state/project_status.md` and `current_state/milestone.md` — V1 plan documented, Design System milestone logged
- Next: local WP Docker environment (can start without admin credentials; content import blocked on creds)

- Session 2: initialized standard project docs from `~/projects/smart_chatbot/` reference — created `CLAUDE.md`, `PROJECT.md`, `README.md`, `DOCKER_SETUP.md`, `.env.example`, `docs/error_log.md`
- Scaffolded local WordPress Docker setup — `docker-compose.yml`, `.env.local`, external `proxy-network`, private `hopp-network`, WordPress/MySQL services, theme bind mount
- Verified local WordPress pipeline manually with user: installed WP locally, activated HOPP theme, confirmed frontend render
- Built minimal WordPress theme foundation under `wp-content/themes/hopp/`: `style.css`, `functions.php`, `index.php`, `header.php`, `footer.php`, `front-page.php`, `page.php`, `single.php`, `archive.php`, `404.php`, `search.php`, `home.php`
- Added initial base CSS from `DESIGN.md`: tokens, typography, header/footer, hero, page/post/archive/card/404 styles
- Clarified strategy: V1 is a local UI/UX demo for team review; V2 production integration waits for WP admin access, live export/import, plugin inspection, and e-commerce/form validation
- Moved live WP admin deployment procedure into `docs/live_wordpress_deployment.md` instead of `DOCKER_SETUP.md`
- User obtained public crawl from `markdown.new/crawl`; created `docs/current_site_audit.md` and `docs/demo_design_plan.md`
- Current resume point: implement V1 demo from `docs/demo_design_plan.md`, starting with global header/footer/navigation and reusable components
- Session 3: invoked vibe-code but user clarified frontend work does not need development notes, knowledge assessments, or dataflow diagrams; removed the started dev note and continued with practical frontend implementation
- Initialized local Git repository on `development` because the project had no `.git`; committed the baseline before feature work
- Built V1 local WordPress UI/UX demo theme: responsive editorial homepage, page-specific demo layouts, product cards, story cards, demo forms, empty cart state, sticky header, mobile navigation, and footer
- Added local-only demo seed behavior through Docker `WORDPRESS_CONFIG_EXTRA` and theme guard `HOPP_ENABLE_DEMO_SEED`, creating placeholder pages, stories, and the original nav plus cart only in local WordPress
- Verified `docker compose --env-file .env.local config`, PHP syntax, local HTTP 200 routes, and WordPress logs; Playwright not available for browser screenshots

## 2026-05-04

- Diagnosed local 503 on `humansofphnompenh.local` as nginx-proxy receiving an empty `VIRTUAL_HOST` because `docker compose up -d` was run without `--env-file .env.local`
- Added a repo Makefile to make `.env.local` the default startup path and reduce repeat setup mistakes: `make init`, `make up`, `make down`, `make restart`, `make rebuild`, `make ps`, `make logs`, `make logs-db`
- Updated `README.md` and `DOCKER_SETUP.md` to steer local setup through the new Makefile and call out the explicit env file requirement
- Switched the next hosting step to Oracle Always Free so the team can review the demo without the developer's laptop staying online
- Documented the Oracle-hosted preview workflow in `docs/oracle_preview_deployment.md` so the deployment plan is easy to follow later
- Moved the Oracle preview runbook into `deliverables/software_ai_engineering/infrastructure/oracle_preview_deployment.md` and left a symlink at `archive/oracle_preview_deployment.md`
- Split the Docker setup into shared, local, and Oracle override compose files so local and cloud preview settings can differ cleanly
- Confirmed LAN sharing for the local WordPress demo by setting `WORDPRESS_LOCAL_URL` to the laptop's Wi-Fi URL and recreating the stack
- Prepared `archive/20260504_team_confirmation.md` as a concise team-facing review note for comparing the local demo against three reference sites
- Updated `current_state/project_status.md` so the immediate next task is team feedback collection, with Oracle preview kept as the longer-lived hosting option

## 2026-05-08

- Reoriented the deployment plan from the old free-tier preview path to a sponsor-funded GCP deployment sized from the real WordPress workload
- Confirmed WordPress admin access on the live site, but plugin installation is restricted for this account, so All-in-One WP Migration is not available as a self-serve full-clone path
- Verified that the live site uses WooCommerce plus several standard third-party plugins, including ABA PayWay Payment Gateway, Checkout Field Editor, Code Snippets, Forminator, Smart Slider 3, WP Menu Cart, Divi Torque Lite, Popups for Divi, PostX, Simple Custom CSS and JS, Supreme Modules Lite, and Lead Form Builder
- Captured WooCommerce ABA PayWay settings from the admin screen so the payment configuration can be recreated on the new server
- Updated `PROJECT.md` and `current_state/project_status.md` to reflect the export-first, rebuild-on-our-own-server plan and preserve the earlier preview work as history
- Created `docs/live_site_settings.md` and `docs/local_import_checklist.md` to separate the live snapshot from the local import workflow
- Linked both docs from `PROJECT.md` and `current_state/project_status.md` so the import handoff is easy to find
- Reset the local WordPress database, installed WooCommerce and Forminator locally, imported the live XML cleanly, and mirrored the live menu/Reading/WooCommerce assignments
- Confirmed the imported counts match the live snapshot for pages, posts, products, attachments, nav menu items, and Forminator forms; Divi-only layout post types were skipped locally
- Fixed the local staging runtime so pretty permalinks are enforced automatically and WooCommerce store visibility is turned off in local mode
- Cleaned the imported story and product rendering paths so stories no longer show raw Divi shortcode junk and product cards now link to real product URLs
- Confirmed the live-exported WooCommerce product content still contains Divi builder structure in `post_content`, so the remaining product-detail text fidelity question needs a live-site check before adding any placeholder copy
- Added a custom `single-product.php` template plus an XML-derived product profile map so imported product pages now show the real recovered descriptions where the export provided them
- Confirmed the live checkout payment rows are injected by the ABA PayWay plugin's JavaScript, not by WooCommerce defaults
- Patched the ABA PayWay gateway runtime so it normalizes `payment_options` and no longer crashes checkout on PHP 8.3
- Synced the local ABA gateway settings so `ABA KHQR`, `Credit/Debit Card`, `WeChat`, and `Alipay` appear again on checkout
- Verified the PayWay domain whitelist still blocks local payment completion on `localhost`; production must keep the registered `humansofphnompenh.com` URLs
- Logged the live export/import snapshot, staging behavior, and deployment notes in `current_state/project_status.md` and `current_state/milestone.md`
- Folded the old V2 roadmap into V1 in `current_state/project_status.md`, removed the redundant V2 section, and pruned the obsolete Post-V1 item that had already been completed
- Tightened the V1 goal wording so it matches the current imported-content staging review instead of the pre-access demo-only framing
- Full frontend standards HIGH-priority fix pass on `feat/frontend-standards-high-priority`: added `--hopp-black` token, replaced both `#000000` hardcodes with the token, raised 4 font sizes to 0.875rem minimum, fixed product card hover/focus state (opacity + focus-visible outline), changed story card headings h3→h2 to fix heading skip, refactored entire style.css from desktop-first (max-width) to mobile-first (min-width) with 641px and 961px breakpoints, restructured demo form with explicit labels/error spans/required fields, added inline JS validation (blur + change + submit with error messaging + color/icon), changed form button type="button"→"submit", removed "Demo theme for local review." from footer copyright
- Added "Designed by Macro Solutions" credit to footer bar (clickable, links to macrosolutions.asia, rel=noopener noreferrer)
- Fixed WooCommerce product price display: DB had KHR decimal/thousand separators from live import; updated woocommerce_currency=USD, price_decimal_sep=`.`, price_thousand_sep=`,`, currency_pos=left directly in the DB; flushed WC transients and restarted WordPress container; PHP filters in functions.php kept as override safety net
