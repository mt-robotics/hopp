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
- Ran project_status.md alignment audit: added missing "Plan sponsored GCP deployment" checklist entry, changed ABA PayWay checklist symbol ✅→🔄 (still has open sub-tasks), aligned detail heading names to match checklist exactly, added 5 missing Post-V1 detail sections (Git versioning, browser QA, WooCommerce styling, performance audit, contact form integration)
- Decided to implement all remaining Post-V1 tasks in next session (MEDIUM/LOW violations + WooCommerce styling are pure code changes; browser QA and performance audit need Playwright/Lighthouse tooling setup)

## 2026-05-09

- Completed all Post-V1 code-change tasks: 8 MEDIUM/LOW frontend standards fixes, Git versioning audit, WooCommerce styling audit
- Frontend standards fixes: added `--hopp-prose: 38rem` token for body text width; fixed duplicate `h1/h2` on single-product by changing detail `<h2>` to `<p class="product-detail__name">`; nav touch targets now 44px via `min-height: 44px`; active nav now uses bold + underline (two visual cues); first-image LCP eager loading on archive.php and home.php; styled `.empty-state` divs added to front-page.php stories, archive-product.php, archive.php, home.php (all outside grid containers); `aria-expanded` item verified as non-violation
- WooCommerce styling: overrode notice colors (message/info/error) to HOPP palette; styled breadcrumb typography; overrode WooCommerce button colors to match HOPP beige/rust system; cart/checkout deep override deferred to post-V1
- Git versioning: confirmed clean — only `wp-content/themes/hopp/` tracked, no WP core or uploads leaking
- Reviewed crucial_notes.md §13 (UI Update) and §14 (Form Submission); discussed and resolved all open decisions with user
- Decisions made: Contact Us page does NOT repeat social links (footer is canonical); ADD TO CART fix = Option A (AJAX + dismissing toast, not page reload); /series/ surfaces on Stories page (not main nav — already at 7 items); all forms site-wide use Forminator; rename "Pitch Your Pal: Phnom Penh" → "Pitch Your Pal"; X (@HoPP_Kh) added to footer
- Transferred all items from crucial_notes.md §13-14 to project_status.md as 12 new V1 tasks with full detail sections; "Contact form integration" elevated from Post-V1 to V1 (as Forminator migration task)
- Next session: start implementing in order — quick CSS wins first (hide coupon, fix button colors), then page builds (Artist, Career, Contact Us), then AJAX add-to-cart, then investigation tasks (blank images, Forminator audit)

## 2026-05-09 (continued)

- Committed "Frontend Standards HIGH-priority fixes" from previous session (was uncommitted) to feat/frontend-standards-high-priority branch
- Created feat/v1-ui-tasks branch and implemented all 11 remaining V1 UI tasks in one session
- Artist page: hero + "Calling All Artists!" content section (all 4 bullet points + contest-guidelines link) + Forminator form 617 + "Why Contribute" two-column section
- Career page: hero + intro + 4 benefit cards + volunteer role grid (Writers/Photographers/Videographers/SM Managers with HOPP palette backgrounds) + 4 internship text cards + qualifications section + Forminator form 1256 (career form placeholder — admin must create proper career form in wp-admin)
- Contact Us page: hero + real contact details (phone +85581363753, email, address) + Google Maps embed + Forminator form 628
- Pitch Your Pal: HOPP-designed hero ("Pitch Your Pal" title override) + Forminator form 1259; menu label renamed via wp_nav_menu_objects filter (no wp-admin needed)
- Stories page: Browse by Series section added with h2 + body text + "Browse All Series" CTA linking to /series/
- Story cards: front-page.php and page.php now use actual featured images when available, falling back to gradient placeholder
- Footer: X (@HoPP_Kh) social icon added alongside existing Fb/Ig/In/Tg
- WooCommerce buttons: all .button.alt (ADD TO CART, UPDATE CART, Proceed to checkout) overridden to --hopp-beige with --hopp-rust hover; single-product ADD TO CART changed from --hopp-black to --hopp-beige
- Cart coupon section hidden via CSS
- AJAX add-to-cart: hopp-cart.js intercepts single-product form submit, fires wc-ajax=add_to_cart, shows dismissing toast; enqueued only on is_product() pages
- Hover effects: story-card and archive-card get opacity 0.88 on hover (matching product cards); volunteer-cards same
- Blank image investigation: 107 attachments all exist on disk; 9 posts have valid thumbnail_id pointing to real attachments; root cause was story-card template using hardcoded gradient instead of actual thumbnail — fixed in both front-page.php and page.php
- Forminator overrides: submit buttons styled to HOPP beige/rust, inputs use border-radius: 0 to match editorial aesthetic
- All PHP linted clean; no fatal errors detected in runtime bootstrap checks
- Committed as single feat commit (99671cc) on feat/v1-ui-tasks

## 2026-05-09 (post-compact session)

- Investigated 5 UI bugs reported via screenshots (ui_issues_*.png):
  1. Forminator form styling misaligned with DESIGN.md
  2. "Register Me ($5)" button on Career page → was form 1256's custom submit text; behavior was redirect to live checkout (humansofphnompenh.com/?add-to-cart=1268) — fixed in DB: button → "Submit Application", behavior → thank-you message
  3. Form submission investigation: AJAX handler confirmed registered; 2 submissions already in DB; email won't work locally (Docker has no SMTP, mailserver=mail.example.com default) — works on real server
  4. Cart not in header — added SVG cart icon + count badge to header.php; mobile: brand→cart→hamburger; desktop: brand→nav→cart; badge increments after AJAX add
  5. "Pitch Your Pal" menu rename not working — filter was checking item->post_name (nav_menu_item ID like "1300") instead of the linked page slug; fixed to use get_post(object_id)->post_name; added terracotta color + underline for visual distinction
- Discovered root causes of Forminator styling failure:
  - Forminator loads forminator-ui.min.css (~500KB) with [data-design=default] attribute selectors that beat class-only overrides
  - Dropdowns are Select2 (custom JS widget), not native <select> — previous CSS targeted wrong element
  - Artist/Contact Us forms had empty field_label values in DB — rendered placeholder-only with no <label> — fixed in DB for forms 617 and 628
- Confirmed: checkout page uses WooCommerce Gutenberg blocks (not Forminator) — that's why it looks clean
- Decision: replace all four Forminator forms with Contact Form 7 (CF7) — minimal CSS, no skin system, full HTML control. Forminator's ~500KB CSS framework makes reliable styling impossible without matching its [data-design=X] specificity throughout
- Committed: fix: cart icon, menu rename, Forminator CSS, WC notices, form 1256 (d079604)
- Current state: all file fixes committed; DB fixes (form labels, form 1256 behavior) live in Docker volume; Forminator replacement with CF7 is next task

## 2026-05-09 (continued)

- Replaced Forminator with Contact Form 7 on Artist, Career, Contact Us, and Pitch Your Pal pages
- Added title-based CF7 rendering helpers so templates do not depend on environment-specific CF7 IDs
- Created four local CF7 forms and validated their configurations; added Docker PHP upload limits (`upload_max_filesize=10M`, `post_max_size=12M`) so artwork/CV upload fields can support 10 MB files
- Replaced Forminator/Select2 CSS with `.wpcf7` theme styling and stripped legacy `[forminator_form ...]` shortcodes from imported content
- Clarified deployment context: current third-party live server plugin restrictions are not a blocker because the site will be deployed to a new user-managed GCP server; CF7 is a requirement for the new deployment, not the old server
- Fixed HOME missing teaser images: Products now pulls the newest product image with Products page banner fallback; Artists now pulls artist-related post imagery with Artist page banner fallback; imported upload URLs are normalized to the current site URL
- Rebuilt About Us using the live-site About/Mission/Vision/Objectives copy
- User rejected the first About redesign based on `archive/about_us_1.png` through `archive/about_us_4.png`; screenshot review identified concrete failures: oversized text island, disconnected decorative word panel, oversized boxed Mission/Vision cards, broken overlapping Objectives grid, and excessive empty space
- Asked `claude -p` for senior editorial web-design guidance and followed its recommendation: remove decorative word panel, remove boxed Mission/Vision cards, replace 6-column objective mosaic with stable numbered rows, reduce About typography scale, and keep a restrained editorial layout
- Tried `gemini`, but it failed due to unavailable configured model and broken local rule imports; no Gemini advice was used
- Adjusted normal page hero height separately from homepage hero so standard pages do not consume a full desktop viewport before content
- Verified PHP lint for `functions.php`, `front-page.php`, and `page.php`; rendered `/` and `/about-us/` locally; captured Chrome screenshots for About visual review at `archive/about_us_redesign_viewport_check.png` and `archive/about_us_redesign_final_check.png`

## 2026-05-09 (continued)

- Refined the Products page after user review
- Added a theme-owned SVG access-pass thumbnail for the non-physical `Registration Fee` product instead of leaving the card as a plain gradient fallback
- Added reusable product-card thumbnail logic so the registration fallback applies consistently across `/products/`, WooCommerce archive cards, and related-product cards
- Replaced word-count product excerpts with character-based trimming and reserved a consistent three-line summary area; products with no recoverable imported description get an aria-hidden empty layout placeholder rather than invented copy
- Verified PHP lint for `functions.php`, `page.php`, `archive-product.php`, and `single-product.php`; confirmed local `/products/` renders the SVG thumbnail and `product-card__summary` markup; captured `archive/products_page_check_final.png`
- Reused the same saved SVG on `/product/registration-fee/` so the product detail page no longer shows WooCommerce's default placeholder image; confirmed normal products still use the regular WooCommerce image gallery; captured `archive/registration_fee_product_detail_check.png`
- Reused the same saved SVG on `/cart/` via `woocommerce_cart_item_thumbnail`; confirmed the `Registration Fee` cart row loads `registration-fee-thumbnail.svg`; captured `archive/cart_registration_fee_thumbnail_check.png`
- Standardized card descriptions across Stories, Home story cards, archives, and search by replacing card-level `wp_trim_words()` / raw `the_excerpt()` with `hopp_get_post_card_summary()` and shared `.card-summary` CSS; captured `archive/stories_card_summary_check.png`
- Refined Stories/Series IA: asked `claude -p`, moved `Browse by Series` above the story grid, built `/series/` as a YouTube playlist-card landing page, and injected `Browse by Series` as a child under `Stories` without adding an 8th top-level nav item
- Resolved playlist titles/counts with `yt-dlp`: Dakshin Restaurant Stories (4), Uy Ratha Stories (5), Ley Oudom Stories (5), E Chen Stories (5), Duck Roasted House Stories (5)
- Verified PHP lint for `functions.php`, `page.php`, and `header.php`; verified JS syntax for `navigation.js`; confirmed `/stories/` and `/series/` rendered expected HTML; captured `archive/stories_series_cta_top_check.png` and `archive/series_youtube_cards_check.png`

## 2026-05-11

- Diagnosed LAN access failure at `http://192.168.11.155:8080`: Docker port binding and Wi-Fi IP were correct, but WordPress redirected LAN requests to `http://localhost:8080/` because the existing container still had old `WP_HOME`/`WP_SITEURL` environment values
- Confirmed `.env.local` already had `WORDPRESS_LOCAL_URL=http://192.168.11.155:8080`; `make up` kept the existing container, while force-recreating WordPress injected the current value and restored LAN access
- Logged the Docker/WordPress environment-variable issue in `docs/error_log.md`, added the global error-log index row, and recorded the learning that env-file changes require container recreation (`make rebuild`)
- Investigated blank page hero backgrounds: current custom page templates were rendering color-only heroes, not broken image URLs; imported Divi background image files existed and resolved over HTTP
- Applied mapped hero images for Home, About Us, Artist, Career, Stories, Products, and Pitch Your Pal; left Contact Us color-only as requested
- Verified mapped image URLs return HTTP 200, PHP syntax passes for changed theme files, and rendered page hero markup outputs the expected `--hopp-hero-image` URLs
- Logged all 9 actionable May 11 team feedback items from `archive/crucial_notes.md` into `current_state/project_status.md` as active V1 tasks with detail sections; marked the source crucial notes as transferred so initialization no longer treats them as unlogged actions
- Completed the non-blocked May 11 feedback: nested Pitch Your Pal under Products, added YouTube thumbnails to Series cards, replaced Career blank role media with imported images, rebuilt footer social icons/layout, verified rendered image URLs return 200, and captured Playwright screenshots. Left designer/PM-blocked items active: final brand color, Privacy/Terms content, replacement Home hero asset, and additional approved content.
- Follow-up fixes after user review: added a visible Pitch Your Pal section inside `/products/`, replaced Career role images with requested `2023/10/2.jpg` through `5.jpg`, changed Contact Us and homepage CTA away from terracotta/orange to centralized brand surface tokens, logged Go to Top/favicon/media optimization tasks, and replaced the 11.5 MB Artist hero original with the 1536px generated image.
- Second follow-up: fixed Stories submenu stacking/hover behavior with higher header/menu z-index and a hover bridge, then removed Pitch Your Pal from the primary menu entirely while keeping the Products-page bottom CTA section.
- Third follow-up: fixed the Stories dropdown for the actual top-of-page hover case with fixed submenu positioning, converted the Products bottom CTA treatment into reusable `hopp_render_context_cta()` sections across primary pages, added code-level `--hopp-brand-*` theme tokens, implemented the floating Return to Top control, and verified with PHP lint, JS syntax, HTTP checks, and Playwright screenshots.
- Fourth follow-up: fixed the Stories dropdown pointer path by keeping the submenu open briefly after pointer exit and canceling the close when the pointer enters the submenu; Playwright verified moving from `Stories` to `Browse by Series` and clicking through to `/series/`. Documented `--hopp-brand-*` token usage in `README.md`.

## 2026-05-15

- Created `docs/sponsored_gcp_deployment_plan.md` to capture the sponsor-funded production GCP recommendation and cutover sequence
- Measured the imported local WordPress stack with Docker running: MySQL data is about 7.95 MB, `wp-content/uploads` is about 83 MB, the WordPress named volume is about 260.7 MB, and the MySQL named volume is about 267 MB
- Sampled container usage on the imported stack: idle memory was about 74 MB for WordPress and 505 MB for MySQL; repeated route hits pushed WordPress to about 226.5 MB and MySQL to about 526.6 MB, which supports `e2-medium` as the smallest reliable single-VM starting point
- Confirmed the production gap is no longer sizing uncertainty but deployment details: the nginx template still lacks `client_max_body_size`, Contact Form 7 still needs real SMTP/live-host delivery verification, and the ABA PayWay plugin patch still needs a durable deployment path
- Located the exact ABA patch in local staging at `wp-content/plugins/aba-payway-woocommerce-payment-gateway/PayWayApiCheckout.php` with backup reference `PayWayApiCheckout.php.bak.1778238414`; the production-safe behavior is normalization of `payment_options` before `get_icon()` and `getPaymentOption()` use it
- Hardened the GCP deployment artifact itself: `docker-compose.gcp.yml` now defaults to `WORDPRESS_ENVIRONMENT_TYPE=production` and `HOPP_ENABLE_DEMO_SEED=false`, the nginx template now sets `client_max_body_size 12m`, and `.env.example` / `DOCKER_SETUP.md` / planning docs were synced to reflect the production-safe contract
- Persisted the ABA PayWay fix into the deployment artifact: `docker-compose.yml` now mounts `docker/wordpress/start-wordpress.sh` and `docker/wordpress/apply-aba-payway-patch.php`, so WordPress container startup reapplies the known-safe plugin patch automatically instead of relying on manual in-container edits
- Verified the new runtime patch path two ways: recreating the local WordPress container logs `HOPP ABA patch: plugin already patched`, and running the patcher against the clean `PayWayApiCheckout.php.bak.1778238414` backup produces the normalized `payment_options` behavior needed for checkout
- Added repo-owned GCP provisioning assets: `scripts/gcp-provision-vm.sh` creates the recommended VM/static-IP/firewall baseline, `scripts/gcp-startup.sh` bootstraps Docker/Git on first boot, and `make gcp-provision` now exposes the flow from the repo
- Repointed `.env.gcp` from the old preview domain to production-safe `https://humansofphnompenh.com` defaults and updated deployment docs so the sponsor-funded host path is executable instead of only descriptive
- Archived "Plan sponsored GCP deployment for the live WordPress site" into `current_state/milestone.md` and split the remaining operational work into the new active task "Set up sponsor-funded GCP production server"
- Installed the Google Cloud SDK locally, but the machine still has no authenticated `gcloud` account selected; actual VM creation is now blocked only on sponsor-project login/auth
- Switched the active GCP deployment host back to `hopp.delvedeepasia.org` by updating `.env.gcp` defaults and deployment docs; future cutover back to `humansofphnompenh.com` is now explicitly documented as a centralized `.env.gcp` edit
- Normalized the production server ownership model to `root:hopp` with setgid directories under `/opt/hopp`, added a neutral operator account for proof, and documented the long-term standard: one real Linux user per operator, each added to deploy group `hopp`
- Finished the sponsor-funded VM cutover path on `hopp-prod` (`34.21.157.41`): DNS now points `hopp.delvedeepasia.org` to the new VM, Docker Compose is running there, and Let's Encrypt issued a valid certificate for the domain
- Root-caused the first production nginx failure: the original repo config required `/etc/letsencrypt/live/...` files before nginx could even bind port 80, which deadlocked the ACME webroot flow. Fixed it in repo code with `docker/nginx/select-template.sh` plus separate bootstrap-HTTP and SSL nginx templates so first boot serves HTTP until the cert exists
- Root-caused the first production WordPress 403: the repo's custom `docker/wordpress/start-wordpress.sh` replaced the official image bootstrap with a bare `apache2-foreground`, so `/var/www/html` never got seeded with WordPress core files. Fixed it in repo code by copying `/usr/src/wordpress` into the volume when `index.php` is missing, then execing `docker-entrypoint.sh apache2-foreground`
- Imported the real local clone into the sponsor-funded VM by streaming the local MySQL dump plus `wp-content/plugins` and `wp-content/uploads` directly into the remote Docker volumes
- Root-caused the post-import WordPress database error: the host-only `.env.gcp` on `hopp-prod` had a mismatched `WORDPRESS_DB_PASSWORD` versus `MYSQL_PASSWORD`; after aligning them, WordPress could connect to MySQL normally
- Normalized server-side production settings after import: `home`, `siteurl`, and `blogname` now point to `https://hopp.delvedeepasia.org`, WooCommerce ABA PayWay success URLs now target `/products/`, and the ABA `pushback_url` now points to `https://hopp.delvedeepasia.org/abapayway/pushback`
- Verified the imported site is serving live content over HTTPS on the new VM: homepage `200`, Products `200`, and Cart `200`; remaining deployment work is now CF7 mail-path verification plus broader production smoke testing

## 2026-05-18

- Ran full `project_status.md` cleanup: fixed missing checklist/detail alignment, shortened `Current State` to true status bullets, corrected task hierarchy so `Standardize Production WordPress Workflow` children use `####`, and removed completed items from `Post-V1 Backlog`
- Archived completed status-only tasks out of active work: `Set up sponsor-funded GCP production server`, `Set up GCP-hosted public preview`, `Stabilize ABA PayWay gateway for deployment`
- Added corresponding historical milestone entries for those three tasks in `current_state/milestone.md` and updated `PROJECT.md` so current focus now points only to `Standardize Production WordPress Workflow`
- Resolved two actionable `archive/crucial_notes.md` sections: folded email/order-notification verification into `Add production mail delivery and form verification`, and moved Telegram order alerts into Post-V1 backlog as `Evaluate Telegram order-status alert integration`
- Reclassified remaining production work boundaries: mail verification, smoke tests, backups, rollback, deploy-path cleanup, and domain-cutover planning now live only under production workflow standardization rather than old bring-up/setup tasks
- Repeated integrity checks after each archive pass: `project_status.md` has no stray `### ✅` sections, no leftover `Share URL with the team` coordination task, and no unresolved `🔲` items left in crucial-notes sections §16–§17
- Defined the canonical production branch strategy and synced docs: `feature/*` for task work, `development` for reviewed integration, and `main` as the only production branch and rollback reference. Updated `README.md`, `PROJECT.md`, `current_state/project_status.md`, and marked `docs/live_wordpress_deployment.md` as a historical admin-only runbook rather than the default VM deploy path.
- Defined the canonical Git-to-VM deploy path for the live host: production now deploys from `main` onto `/opt/hopp` through `./scripts/deploy-production.sh`, with `./scripts/rollback-production.sh <known-good-main-sha>` as the emergency rollback path. Added the dedicated runbook in `docs/production_vm_deploy.md`, updated `scripts/gcp-startup.sh` to point at the standardized deploy entrypoint, and synced `README.md`, `PROJECT.md`, `DOCKER_SETUP.md`, `docs/live_wordpress_deployment.md`, and `current_state/project_status.md`.
- After the first real git-based production deploy on `hopp-prod`, closed two follow-up gaps: updated `scripts/gcp-startup.sh` and related docs so the VM bootstrap now includes `make` for deploy helpers, and removed the stale untracked `nginx/templates/default.conf.template` from the live `/opt/hopp` checkout so server git status aligns with the repo.
- Investigated production mail on `hopp-prod`: confirmed Contact Form 7 and WooCommerce are active, confirmed the four expected CF7 forms exist live, confirmed all current mail recipients/senders still point at `admin@example.com`, and confirmed the container has no valid `/usr/sbin/sendmail`, so production mail was never actually configured
- Verified the public mail footprint for `humansofphnompenh.com`: MX points to Hostinger and SPF includes Hostinger mail, so standardized the project on a repo-owned Hostinger SMTP path rather than a WP-admin SMTP plugin
- Added repo-owned production mail scaffolding: new MU plugin `docker/wordpress/mu-plugins/hopp-production-mail.php` now reads host env vars to configure PHPMailer SMTP plus CF7/WooCommerce admin recipients, `docker-compose.yml` and `docker/wordpress/start-wordpress.sh` now sync that MU plugin into the running container, and docs/env templates were updated with the exact live-verification runbook in `docs/production_mail_and_form_verification.md`
- Temporarily configured production SMTP to use the user-controlled Zoho mailbox `monireach.tang@monireach.com`, then verified the live container picked up the SMTP env vars after fixing a missing `HOPP_*` environment pass-through in `docker-compose.yml`
- Root-caused the live CF7 upload failure on the Artist form: `wp-content/uploads` inside the WordPress container was owned by `root:root`, so WordPress could not write uploaded files. Hotfixed the live directory to `www-data:www-data` and updated `docker/wordpress/start-wordpress.sh` so future boots normalize upload-directory ownership automatically
- Verified the hardest CF7 production path end to end: the Artist form now uploads successfully on `https://hopp.delvedeepasia.org/artist/` and SMTP mail is received in the temporary Zoho inbox
- Verified the remaining WooCommerce boundary with a live ABA attempt: checkout on `hopp.delvedeepasia.org` fails with `Requested Domain is not in whitelist`, so order-status email verification is blocked until `humansofphnompenh.com` is cut over to this server or the ABA merchant whitelist is updated
- Completed the `Separate code-managed state from WP-admin-managed state` subtask by adding `docs/production_state_ownership.md`, which defines the canonical boundary between Git-managed runtime code, host-managed secrets, WP-admin-managed business/content state, and the small set of sensitive admin/provider-side settings that must be changed deliberately
- Closed the repo-owned production-standardization phase: added canonical runbooks for backup/restore, smoke test + rollback, access/change management, and final primary-domain cutover; added helper scripts `backup-production.sh`, `restore-production.sh`, and `smoke-test-production.sh`; archived `Standardize Production WordPress Workflow` into `current_state/milestone.md`; and split the remaining real-world execution into two explicit next tasks: primary-domain cutover with final live verification, plus the first controlled backup/restore drill.
- Reduced the tracked production-doc surface: moved the deep-dive ops notes for backup/restore, smoke/rollback, access/change management, and domain cutover into archive/production_ops_2026-05-18/, kept the helper scripts tracked, and added docs/production_operations_index.md as the new first-stop operator entrypoint.

## 2026-05-19

- Investigated the designer-provided Home hero source video at `archive/hoppweb1_1.mp4`: the source file is about 94 MB, about 76 seconds, and not suitable for direct homepage delivery as-is
- Verified the browser-policy constraint from current platform docs: first-visit autoplay with sound cannot be guaranteed across modern browsers, so the canonical behavior will be muted autoplay first with an immediate sound toggle and remembered best-effort sound preference for later visits
- Agreed the Home hero delivery plan before implementation: self-host the optimized hero video instead of using a paid video CDN, accept the documentary-style recording overlay if it remains in the final export, and wait for the designer's final approved asset
- Final asset contract for the blocked Home hero task: roughly 20-second loop-friendly export, no burned subtitles, intended for a looping homepage hero rather than a separate long-form player treatment
- Started the `Execute Primary-Domain Cutover And Final Live Verification` task under vibe-code on branch `feature/primary-domain-cutover`
- Verified live cutover blockers with direct evidence: `humansofphnompenh.com` still resolves to Hostinger `156.67.222.232`, while the sponsor-funded VM remains `34.21.157.41`; the VM `.env.gcp` still targets `hopp.delvedeepasia.org`
- Refreshed the server checkout on `hopp-prod` from stale `origin/main` `8f9ab8a` to current reviewed `origin/main` `4075cbd` so the backup/restore/smoke helper scripts now exist on the VM
- Created the required pre-cutover production backup on the VM at `/opt/hopp/backups/20260519T081412Z`
- Verified the current transition host still passes the automated smoke suite on `https://hopp.delvedeepasia.org`
- Execution is now blocked only on external actions outside the repo/VM: DNS must point `humansofphnompenh.com` to `34.21.157.41`, and the ABA merchant-side whitelist must include `humansofphnompenh.com` before the actual domain/env/TLS cutover can proceed safely
- Locked the remaining cutover decisions: `www.humansofphnompenh.com` should redirect to apex via nginx on the VM, the director needs to provide the final Hostinger SMTP mailbox credentials/details alongside the GoDaddy DNS change, and ABA whitelist confirmation will be determined by the first live checkout test after cutover rather than a separate pre-check
