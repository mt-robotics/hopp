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
