# Completed Milestones

**Last Updated:** 2026-05-11

> Full details of every completed task. For active tasks and roadmap, see `current_state/project_status.md`.

---

## Quick Reference

| Milestone | Completed | Tests |
|---|---|---|
| Navigation, Context CTA, and Return-to-Top Refinements | 2026-05-11 | PHP lint, JS syntax, HTTP checks, Playwright screenshots |
| May 11 Team Feedback — Non-blocked UI Fixes | 2026-05-11 | PHP lint, HTTP checks, Playwright screenshots |
| V1 UI Tasks — All 11 Pages, WooCommerce, AJAX, Forms | 2026-05-09 | PHP lint, WP runtime bootstrap |
| Home/About UI Refinements | 2026-05-09 | PHP lint, Chrome screenshot review |
| Fix MEDIUM/LOW Frontend Standards Violations | 2026-05-09 | PHP lint, manual review |
| Evaluate WooCommerce Styling | 2026-05-09 | Manual visual review |
| Set up Git Versioning for Theme Directory | 2026-05-09 | `git ls-files` verification |
| Frontend Standards — HIGH Priority Fixes | 2026-05-08 | Manual visual review |
| Standard Project Documentation Scaffold | 2026-04-30 | Manual file verification |
| Design System (DESIGN.md) | 2026-04-30 | — |
| Local WordPress Docker Environment | 2026-04-30 | Docker Compose config, manual WP/theme render |
| Current Site Audit + Demo Design Plan | 2026-04-30 | Manual document review |
| V1 Local WordPress UI/UX Demo Theme | 2026-04-30 | PHP lint, Docker Compose config, local route checks |

---

## ✅ V1 Local WordPress UI/UX Demo Theme (2026-04-30)

**Implementation:** Built a local WordPress theme demo from `DESIGN.md` and `docs/demo_design_plan.md` for team UI/UX review before live admin access is available.

- ✅ Added local-only demo seed behavior guarded by `WP_ENVIRONMENT_TYPE=local` and `HOPP_ENABLE_DEMO_SEED`
- ✅ Seeded placeholder top-level pages for About Us, Products, Stories, Artist, Career, Contact Us, and Cart
- ✅ Seeded demo story posts and primary navigation preserving Home, About Us, Products, Stories, Artist, Career, Contact Us, plus Cart
- ✅ Built responsive sticky header, mobile menu toggle, editorial homepage, page heroes, story cards, product cards, demo forms, empty cart state, and footer
- ✅ Forced local WordPress URLs to `http://localhost:8080` through Docker `WORDPRESS_CONFIG_EXTRA`

**Verification:**

- ✅ `docker compose --env-file .env.local config`
- ✅ PHP syntax checks for theme templates
- ✅ HTTP 200 route checks for `/`, `/about-us/`, `/products/`, `/stories/`, `/artist/`, `/career/`, `/contact-us/`, `/cart/`
- ✅ WordPress logs checked for PHP fatal/warning/parse errors
- ⚠️ Playwright browser verification not run because Playwright is not installed in this project

**Final state:** Ready for local team review at `http://localhost:8080`. Production integration remains blocked until WP admin access, live content export/import, plugin inspection, and e-commerce/form validation.

---

## ✅ Local WordPress Docker Environment (2026-04-30)

**Infrastructure:** Scaffolded and verified a local WordPress development environment for safe theme work before touching the live site.

- ✅ Created `docker-compose.yml` with WordPress 6.8 PHP 8.3 Apache and MySQL 8.4 services
- ✅ Added persistent named volumes for WordPress and MySQL runtime data
- ✅ Added bind mount from `./wp-content/themes/hopp` to the WordPress theme directory
- ✅ Created `.env.example` and `.env.local` workflow for local runtime values
- ✅ Documented setup, env vars, and troubleshooting in `DOCKER_SETUP.md`
- ✅ Verified WordPress was accessible locally and the HOPP theme appeared in WP admin
- ✅ Confirmed HOPP theme could be activated and rendered on the frontend

**Verification:**

- ✅ `docker compose --env-file .env.local config`
- ✅ Manual local WordPress install/admin check
- ✅ Manual theme activation/render check

**Final state:** Local Docker is the safe V1/V2 development environment. Production remains blocked until WP admin credentials, live content export/import, plugin inspection, and rollback details are available.

---

## ✅ Current Site Audit + Demo Design Plan (2026-04-30)

**Planning:** Converted the public crawl and design system into actionable V1 demo planning documents.

- ✅ Saved long Gemini/NotebookLM audit prompt at `archive/site_audit_prompt.md`
- ✅ Reviewed `archive/crawl-ea796113.md` from `markdown.new/crawl` (27 pages captured; corrupted trailing crawl output removed by user)
- ✅ Created `docs/current_site_audit.md`
  - page inventory
  - header/footer structure
  - content types
  - reusable component requirements
  - form field inventory
  - e-commerce/product fields
  - V1/V2 risk classification
- ✅ Created `docs/demo_design_plan.md`
  - chosen style: warm editorial magazine
  - information architecture
  - page-by-page V1 plan
  - reusable components
  - form strategy
  - e-commerce demo boundaries
  - implementation order
- ✅ Updated `PROJECT.md` and `current_state/project_status.md` to use the audit/design plan as next-session context

**Key decision:** Continue building a local UI/UX demo before WP admin access. Production integration waits for admin access, live content export/import, plugin inspection, and e-commerce/form validation.

**Final state:** Ready to implement V1 demo from `docs/demo_design_plan.md`.

---

## ✅ Standard Project Documentation Scaffold (2026-04-30)

**Documentation:** Created the required standard project files using `~/projects/smart_chatbot/` as the structural reference, while adapting the content to this WordPress redesign/theme project.

- ✅ `CLAUDE.md` — project-specific coding instructions, initialization flow, local-first safety constraints, doc sync rules
- ✅ `PROJECT.md` — project navigation hub, tech stack, structure, blockers, key decisions
- ✅ `README.md` — public-facing overview, current status, planned theme scope, setup pointer
- ✅ `DOCKER_SETUP.md` — planned WordPress/MySQL Docker workflow, env vars, deployment safety checklist
- ✅ `.env.example` — safe local WordPress/MySQL environment variable template
- ✅ `docs/error_log.md` — debugging investigation log scaffold with table of contents

**Final state:** Manual file verification only. Git was not initialized by request.

---

## ✅ Design System (2026-04-30)

**Design:** Single source of truth in Google Stitch DESIGN.md format — YAML front matter (machine-readable tokens) + markdown sections (human-readable rationale). The format was fetched live from `github.com/google-labs-code/design.md` to ensure spec accuracy (format is labeled "alpha" and evolves).

- ✅ Color tokens: 9 total — 6 from `tailwind.config` (`brown`, `beige`, `rust`, `cream`, `footer-surface`, `footer-bar`) + 3 inline hex values promoted to role-based tokens (`sand` #a68a61, `terracotta` #c47254, `paper` #fcfbf9). Image fallback colors (#7ba07e, #1f607c) excluded from token system.
- ✅ Typography scale: 8 tokens (Playfair Display for headings, Montserrat for UI/body)
- ✅ Component inventory: 13 components defined with token references (no hardcoded hex in components)
- ✅ Nav decision: Option B — original 7-item nav preserved (Home, About Us, Products, Stories, Artist, Career, Contact Us + cart), not Stitch prototype's reduced nav
- ✅ `/design-md` agent skill created at `agent_core/skills/design-md/SKILL.md` — reusable across future frontend projects; always fetches live spec before writing

**Final state:** No tests applicable (design system document).

---

## ✅ Live Export, Local Import, and ABA Checkout Recovery (2026-05-08)

**Implementation:** Exported the live WordPress content, imported it into a clean local staging database, and repaired the ABA PayWay checkout flow so the staging site matches the live commerce structure closely enough for review.

- ✅ Exported the live site as a WordPress XML/WXR file and saved it in `archive/humansofphnompenh.WordPress.2026-05-08.xml`
- ✅ Recorded the live theme, active plugins, WooCommerce page assignments, menu structure, and Reading settings in project docs
- ✅ Reset the local WordPress database, imported the XML cleanly, and mirrored the live menu/Reading/WooCommerce assignments
- ✅ Confirmed Divi-only layout post types were skipped intentionally because the new custom HOPP theme does not depend on them
- ✅ Patched the ABA PayWay gateway runtime so `payment_options` is normalized before checkout rendering and checkout no longer fatals on PHP 8.3
- ✅ Synced the saved ABA gateway methods locally so the checkout page renders the four live payment rows again
- ✅ Verified that the local checkout still depends on PayWay's whitelisted production domain, so local `localhost` submission is not expected to complete as a real payment

**Verification:**

- ✅ Local content counts match the live snapshot for pages, posts, products, attachments, nav menu items, and forms
- ✅ `php -l` passes on the patched ABA gateway file
- ✅ Local checkout HTML now includes the ABA gateway payload and the four payment rows
- ✅ The live payment-method selection is mirrored in the staging database
- ⚠️ PayWay domain whitelist still blocks local payment completion on `localhost`; production must use the registered `humansofphnompenh.com` host

**Final state:** Local staging is ready for UI and content review. The remaining work is production deployment planning, persistence of the ABA gateway patch in the deployment artifact, and later polish items such as footer links and favicon.

---

## ✅ Frontend Standards — HIGH Priority Fixes (2026-05-08)

All items were identified during a full frontend standards audit against `frontend-standards.md` and `ux-standards.md` on 2026-05-08. Two extra fixes were added in the same session.

- ✅ CSS token: added `--hopp-black: #000000` to `:root`; replaced hardcoded `#000000` at `style.css` (`.page-hero--product` and `.single_add_to_cart_button`)
- ✅ Font sizes raised to 0.875rem minimum: `.card-kicker`, `.site-nav__list a`, `.button-primary/.button-outline/.demo-form button`, `.product-card__footer`
- ✅ Product card hover/focus: replaced `color: inherit` (no feedback) with `opacity: 0.88` on hover and `outline: 2px solid var(--hopp-beige)` on `focus-visible`
- ✅ Heading hierarchy: story card titles changed from `<h3>` to `<h2>` on the Stories page; updated CSS selector `.story-card h3` → `.story-card h2`
- ✅ Mobile-first CSS refactor: all base styles now target mobile; replaced both `max-width` media queries with `@media (min-width: 641px)` (tablet) and `@media (min-width: 961px)` (desktop)
- ✅ Demo form: changed button `type="button"` → `type="submit"`; restructured form with explicit `label[for]`/`input[id]` pairs, `required`, and `<span class="demo-form__error">` placeholders; added inline JS validation (blur/change/submit) with per-field error messages using color + ✕ icon
- ✅ Footer: removed hardcoded "Demo theme for local review." from copyright bar
- ✅ Footer: added "Designed by Macro Solutions" credit with link to macrosolutions.asia (extra request)
- ✅ WooCommerce price format: updated DB settings to `currency=USD`, `decimal_sep=.`, `thousand_sep=,`, `currency_pos=left`; flushed WC transients; PHP filters added to `functions.php` as override safety net (extra request)

**Final state:** No tests apply — visual/accessibility fixes. Manual review required in browser.

---

## ✅ Set up Git Versioning for Theme Directory (2026-05-09)

- ✅ `.gitignore` verified: excludes `wp-content/uploads/`, `wp-content/upgrade/`, `wp-content/cache/`
- ✅ No WordPress core files tracked: confirmed via `git ls-files wp-content/` returning only `themes/hopp/` paths
- ✅ Theme stays in the repo root — no sub-repo needed

**Final state:** No tests apply — configuration verification only.

---

## ✅ Evaluate WooCommerce Styling (2026-05-09)

WooCommerce default styles are active (theme does not declare `add_theme_support('woocommerce')`). Targeted overrides were added to `style.css` to align WooCommerce-generated elements with HOPP design tokens.

- ✅ WooCommerce notices (`.woocommerce-message`, `.woocommerce-error`, `.woocommerce-info`) — overridden: HOPP cream background, sand/rust top-border by notice type, brown text; replaces WooCommerce green/blue/red
- ✅ WooCommerce breadcrumb (`.woocommerce-breadcrumb`) — overridden: muted gray text, beige link color, rust hover, uppercase tracking to match HOPP nav style
- ✅ WooCommerce buttons (`.woocommerce button.button`, `.button.alt`, input buttons, `#respond input#submit`) — overridden to HOPP beige/brown with rust hover; `min-height: 44px` for touch target compliance
- Single-product detail layout is fully custom — no WooCommerce default layout styles used
- Cart/checkout shortcode pages: deep table/form override deferred to post-V1 styling pass

**Final state:** No tests apply — visual audit. Manual review in browser required.

---

## ✅ Fix MEDIUM/LOW Frontend Standards Violations (2026-05-09)

All 8 active violations fixed. One audit item (aria-expanded) verified as a non-issue.

- ✅ Body text line length: added `--hopp-prose: 38rem` CSS token; applied to `.prose-section > *` and `.single-content__article` — body text now ≤ 80 chars/line; `--hopp-content` kept at 56rem for section headers
- ✅ Duplicate product heading: `single-product.php` detail `<h2>` changed to `<p class="product-detail__name">`; added `.product-detail__name` CSS with explicit `font-family`, `color`, and sizing — one `<h1>` per product page
- ✅ Nav touch target: `.site-nav__list a` now has `min-height: 44px; align-items: center` — meets 44px minimum
- ✅ `aria-expanded` — verified non-violation: `navigation.js` correctly toggles `aria-expanded` on every click; initial `false` value is semantically correct
- ✅ LCP images: `archive.php` and `home.php` first-iteration thumbnail passes `fetchpriority="high" loading="eager"` via `$wp_query->current_post === 0` check; subsequent images use default lazy
- ✅ `front-page.php` stories empty state: `WP_Query` restructured — `.card-grid` only rendered when posts exist; `.empty-state` div shown outside grid when empty
- ✅ `archive-product.php` empty state: product list restructured — empty array renders `.empty-state` in its own `<section>` (not inside the CSS grid)
- ✅ Active nav second visual cue: `.current-menu-item > a { font-weight: 700 }` added — bold + underline = two distinct cues; rust color rejected (3:1 contrast only, fails WCAG AA on dark header)
- ✅ `archive.php` and `home.php` empty states: bare `<p>` replaced with styled `.empty-state` div with contextual copy; `.empty-state` CSS updated to add `padding: 4rem 1.25rem`

**Final state:** PHP lint passes on all 5 modified templates. No automated tests — manual browser review required.

## ✅ V1 UI Tasks — All 11 Pages, WooCommerce, AJAX, Forms (2026-05-09)

- ✅ Artist page: hero (terracotta) + "Calling All Artists!" content section with contest-guidelines hyperlink + Forminator form 617 + "Why Contribute" two-column feature-grid
- ✅ Career page: hero (sand) + intro paragraph + 4 benefit cards + volunteer role grid (Writers/Photographers/Videographers/SM Managers with HOPP palette backgrounds as image placeholders) + 4 internship text cards + qualifications section + Forminator form 1256 (placeholder — admin must create proper career form in wp-admin)
- ✅ Contact Us page: hero (terracotta) + contact details (phone/email/address) + Google Maps embed + Forminator form 628 (message-us-2)
- ✅ Footer X icon: `𝕏` icon linking to https://x.com/HoPP_Kh added alongside Fb/Ig/In/Tg
- ✅ Pitch Your Pal: HOPP-designed hero showing "Pitch Your Pal" title (overrides "Pitch Your Pal: Phnom Penh" WP title) + Forminator form 1259 + menu label renamed via `wp_nav_menu_objects` filter in functions.php
- ✅ Story cards: front-page.php and page.php now show actual featured images (get_the_post_thumbnail_url) with gradient fallback; blank image investigation confirmed all 107 attachment files exist on disk with 0 broken _thumbnail_id links
- ✅ Browse by Series: section added to Stories page with h2 + descriptive body text + "Browse All Series" CTA button linking to /series/
- ✅ WooCommerce button colors: all .button.alt (ADD TO CART archive, UPDATE CART, Proceed to checkout) overridden to --hopp-beige with --hopp-rust hover via !important selectors; single-product ADD TO CART changed from --hopp-black to --hopp-beige
- ✅ Cart coupon hidden: `.woocommerce-cart .coupon` display:none !important
- ✅ AJAX add-to-cart: hopp-cart.js intercepts single-product form submit, fires `?wc-ajax=add_to_cart`, shows HOPP toast (4s auto-dismiss, role=alert), prevents page reload; enqueued only on is_product() pages via hopp_enqueue_cart_assets()
- ✅ Hover effects: story-card links and archive-card links get `opacity: 0.88` on hover (consistent with product cards); volunteer-card same
- ✅ Forminator overrides: submit buttons styled to --hopp-beige/--hopp-rust, border-radius: 0 on inputs, Montserrat font enforced
- Commit: `99671cc` on `feat/v1-ui-tasks`
- Note: Career Forminator form requires creation in wp-admin; currently uses form 1256 as visual placeholder

## ✅ Contact Form 7 Forms Migration (2026-05-09)

Forminator was replaced on the four custom theme pages because its default skin and Select2 dropdown layer were not reliable to override cleanly.

- ✅ Installed and activated Contact Form 7 locally
- ✅ Created four local CF7 forms:
  - HOPP Artist Submission: name, email, artwork title, story textarea, upload field, consent checkbox
  - HOPP Career Application: name, email, application type select, preferred role select, motivation textarea, upload field, consent checkbox
  - HOPP Contact Message: name, email, subject, message textarea
  - HOPP Pitch Your Pal Nomination: name, email, nominee name, nomination story textarea
- ✅ Added `hopp_render_contact_form()` helper in `functions.php` to resolve CF7 forms by title instead of hardcoding environment-specific shortcode IDs
- ✅ Replaced all four Forminator template render points in `page.php` with title-based CF7 rendering
- ✅ Replaced Forminator/Select2 CSS overrides with `.wpcf7` styles for labels, fields, selects, file inputs, checkboxes, submit buttons, focus states, inline errors, and response banners
- ✅ Extended imported-content cleanup to strip legacy `[forminator_form ...]` shortcodes before they can render
- ✅ Added `docker/php/uploads.ini` and mounted it into the WordPress container so PHP supports 10 MB artwork/CV uploads

**Validation:**

- ✅ `php -l wp-content/themes/hopp/functions.php`
- ✅ `php -l wp-content/themes/hopp/page.php`
- ✅ Docker Compose config validates
- ✅ WordPress container reports `upload_max_filesize=10M` and `post_max_size=12M`
- ✅ CF7 configuration validator reports all four HOPP forms as valid
- ✅ Route checks for `/artist/`, `/career/`, `/contact-us/`, and `/pitch-your-pal-phnom-penh/` render CF7 markup with zero Forminator markup and no missing-form fallback

**Remaining deployment checks:** Real email delivery was not verified locally because the Docker stack has no SMTP/mail delivery service. CF7 mail templates are configured and valid; final delivery must be verified through the production SMTP/live-host path. The new user-managed GCP WordPress host must install and activate Contact Form 7 as part of deployment; the current third-party live server's plugin restrictions do not block this migration path.

## ✅ Home/About UI Refinements (2026-05-09)

Completed page-level UI fixes after screenshot review.

### HOME teaser images

- ✅ Products teaser now uses the newest published WooCommerce product image, skipping products without images
- ✅ Products teaser falls back to the Products page banner/imported image if no product image exists
- ✅ Artists teaser now looks for artist-related category/tag posts first, then falls back to the Artist page banner/imported Divi background image
- ✅ Imported `/wp-content/uploads/...` image URLs are normalized to the current WordPress site URL so old `localhost:8080` references do not leak across environments
- ✅ `.editorial-image img` now fills the teaser card using the existing media-card object-fit pattern

### About Us redesign

- ✅ Replaced the thin placeholder About page with the richer live-site content: platform description, Mission, Vision, and five Objectives
- ✅ Initial About redesign was rejected after screenshots showed weak layout: giant text island, disconnected 2x2 word panel, oversized boxed Mission/Vision cards, broken/overlapping Objectives grid, and excessive empty space
- ✅ Asked `claude -p` for external senior editorial web-design guidance; followed its recommendation to remove decorative panels, remove boxed cards, and replace the fragile mosaic with numbered editorial rows
- ⚠️ Tried `gemini`, but it failed due to unavailable configured model and broken local rule imports, so no Gemini design advice was used
- ✅ Final About structure: existing page hero, calm single-column intro, Mission/Vision as restrained text columns with left accent rules, Objectives as stable numbered rows, CTA band
- ✅ Normal page hero height was separated from the homepage hero so About and other standard pages no longer consume a full desktop viewport before content

**Validation:**

- ✅ `php -l wp-content/themes/hopp/functions.php`
- ✅ `php -l wp-content/themes/hopp/front-page.php`
- ✅ `php -l wp-content/themes/hopp/page.php`
- ✅ Local `/` render check confirmed Products and Artists teaser images output real `<img>` tags
- ✅ Local `/about-us/` render check confirmed the removed broken classes are absent
- ✅ Chrome screenshots captured for visual review:
  - `archive/about_us_redesign_viewport_check.png`
  - `archive/about_us_redesign_final_check.png`

## ✅ Products Page UI Refinement (2026-05-09)

Completed targeted Products page fixes after user review.

- ✅ Added a theme-owned SVG fallback thumbnail for the non-physical `Registration Fee` product: `wp-content/themes/hopp/assets/images/registration-fee-thumbnail.svg`
- ✅ Added `hopp_get_product_card_thumbnail_url()` so product cards use featured images first, then the registration access-pass artwork only for the `registration-fee` product
- ✅ Reused the same saved SVG on the `Registration Fee` product detail page instead of WooCommerce's default `placeholder.webp`; normal product pages still use the standard WooCommerce gallery when a real product image exists
- ✅ Reused the same saved SVG on the Cart page through `woocommerce_cart_item_thumbnail`, so the `Registration Fee` cart row no longer shows WooCommerce's default placeholder
- ✅ Replaced word-count product summary trimming with character-based trimming through `hopp_trim_text_by_chars()` and `hopp_get_product_summary()`
- ✅ Applied the same thumbnail/summary behavior to `/products/`, the WooCommerce product archive template, and related-product cards on single-product pages
- ✅ Added a reserved three-line product summary area, including an aria-hidden empty placeholder when imported product data has no recoverable description, so card footers align without inventing copy

**Validation:**

- ✅ `php -l wp-content/themes/hopp/functions.php`
- ✅ `php -l wp-content/themes/hopp/page.php`
- ✅ `php -l wp-content/themes/hopp/archive-product.php`
- ✅ `php -l wp-content/themes/hopp/single-product.php`
- ✅ Local `/products/` HTML confirms the registration card loads `registration-fee-thumbnail.svg`
- ✅ Local `/product/registration-fee/` HTML confirms the detail page loads `registration-fee-thumbnail.svg` and no longer outputs WooCommerce `placeholder.webp`
- ✅ Local `/cart/` HTML confirms the `Registration Fee` cart item loads `registration-fee-thumbnail.svg` and no longer outputs WooCommerce `placeholder.webp`
- ✅ Chrome screenshot captured at `archive/products_page_check_final.png`
- ✅ Chrome screenshot captured at `archive/registration_fee_product_detail_check.png`
- ✅ Chrome screenshot captured at `archive/cart_registration_fee_thumbnail_check.png`

## ✅ Stories/Series UI Refinement (2026-05-09)

Completed Stories information architecture and card consistency fixes after user review.

- ✅ Asked `claude -p` for IA/design advice; recommendation was to keep Series out of the top-level nav, expose it as a Stories child, and move the in-page CTA above the story grid
- ✅ Moved the `Browse by Series` CTA on `/stories/` from the bottom of the page to above the story card grid
- ✅ Built `/series/` as a dedicated YouTube playlist-card landing page
- ✅ Added 5 YouTube playlist cards using resolved playlist titles and video counts:
  - Dakshin Restaurant Stories — 4 videos
  - Uy Ratha Stories — 5 videos
  - Ley Oudom Stories — 5 videos
  - E Chen Stories — 5 videos
  - Duck Roasted House Stories — 5 videos
- ✅ Playlist cards open the supplied YouTube playlist URLs in a new tab with `target="_blank"` and `rel="noopener noreferrer"`
- ✅ Added a theme-injected `Browse by Series` submenu child under `Stories`, preserving the 7-item top-level navigation
- ✅ Added mobile submenu behavior: tapping `Stories` opens the submenu first on mobile; desktop still allows direct `Stories` navigation with hover/focus submenu access
- ✅ Added `hopp_get_post_card_summary()` and applied character-based summaries consistently to Stories, Home story cards, archive cards, and search result cards
- ✅ Replaced remaining card-level `wp_trim_words()` / raw `the_excerpt()` paths with shared `card-summary` rendering

**Validation:**

- ✅ `php -l wp-content/themes/hopp/functions.php`
- ✅ `php -l wp-content/themes/hopp/page.php`
- ✅ `php -l wp-content/themes/hopp/header.php`
- ✅ `node --check wp-content/themes/hopp/assets/js/navigation.js`
- ✅ Local `/stories/` HTML confirms the Series CTA renders before the story grid
- ✅ Local `/series/` HTML confirms all 5 playlist cards, real playlist titles, external URLs, and nav submenu output
- ✅ Chrome screenshots captured:
  - `archive/stories_card_summary_check.png`
  - `archive/stories_series_cta_top_check.png`
  - `archive/series_youtube_cards_check.png`

## ✅ Hero Background Image Mapping (2026-05-11)

Applied imported upload images to the custom HOPP hero system after confirming the issue was template behavior, not broken image files.

- ✅ Diagnosed page heroes as color-only by code: `hopp_render_page_hero()` did not output an image or background-image value
- ✅ Confirmed affected pages had no active featured image assignment in WordPress (`_thumbnail_id` empty or `0`)
- ✅ Verified the requested imported image files exist inside the Docker WordPress upload volume and resolve over HTTP
- ✅ Corrected the Pitch Your Pal image path to the actual local file: `happy-new-year-2025-fireworks-festive-fun-joyous-midnight-countdown-new-beginnings-scaled-1.jpg`
- ✅ Added a theme-owned hero image mapping for:
  - Home → `2023/09/combodians.jpg`
  - About Us → `2023/10/phnom-penh-cover-image.jpg`
  - Artist → `2025/03/Untitled-2-01.png`
  - Career → `2023/10/Untitled-design-15.jpg`
  - Stories → `2023/09/combodians.jpg`
  - Products → `2023/10/Artist.jpg`
  - Pitch Your Pal → `2026/01/happy-new-year-2025-fireworks-festive-fun-joyous-midnight-countdown-new-beginnings-scaled-1.jpg`
- ✅ Left Contact Us as color-only by request
- ✅ Added overlay styling for image-backed home/page heroes while preserving existing variant colors as fallbacks

**Validation:**

- ✅ `php -l /var/www/html/wp-content/themes/hopp/functions.php`
- ✅ `php -l /var/www/html/wp-content/themes/hopp/front-page.php`
- ✅ All mapped upload URLs returned HTTP 200
- ✅ Rendered page HTML confirmed expected `--hopp-hero-image` URLs on Home, About Us, Artist, Career, Stories, Products, and Pitch Your Pal
- ✅ Rendered Contact Us hero confirmed no image mapping and no `page-hero--has-image` class

## ✅ May 11 Team Feedback — Non-blocked UI Fixes (2026-05-11)

Completed the May 11 feedback items that did not require waiting for designer or project-manager input.

- ✅ Move Pitch Your Pal under Products: the primary menu no longer shows `Pitch Your Pal`; the Products page now includes a visible Pitch Your Pal section while preserving the existing page URL and title.
- ✅ Add YouTube thumbnails to the Series page: each playlist card now uses a first-video YouTube thumbnail with a play icon overlay.
- ✅ Audit and fix missing images across pages: Career volunteer role cards now use the requested imported media (`2023/10/2.jpg`, `3.jpg`, `4.jpg`, `5.jpg`) instead of blank color blocks, and rendered image URLs across primary pages returned HTTP 200.
- ✅ Replace footer social icons with source-aligned icons: footer social links now use inline SVG icons for Facebook, Instagram, LinkedIn, Telegram, and X while preserving existing destinations.
- ✅ Redesign footer layout and spacing: footer was reorganized into intro, navigation, social, and legal areas with tighter desktop/mobile spacing.
- ✅ Correct Contact/CTA banner color: Contact Us and the homepage bottom CTA no longer use the terracotta/orange banner treatment; theme surfaces now use centralized CSS variables.
- ✅ Reduce oversized rendered asset: Artist hero mapping now uses the generated `Untitled-2-01-1536x1536.png` image instead of the 11.5 MB original.
- ✅ Remaining blocked feedback stayed active in `project_status.md`: final brand color, Privacy/Terms page content, Home hero replacement asset, and additional approved content.

**Validation:**

- ✅ `php -l wp-content/themes/hopp/functions.php`
- ✅ `php -l wp-content/themes/hopp/page.php`
- ✅ `php -l wp-content/themes/hopp/footer.php`
- ✅ HTTP 200 route checks for `/`, `/about-us/`, `/products/`, `/stories/`, `/series/`, `/artist/`, `/career/`, `/contact-us/`, `/pitch-your-pal-phnom-penh/`, and `/cart/`
- ✅ Rendered nav HTML confirms `Pitch Your Pal` under `Products`
- ✅ Rendered `/series/` HTML confirms all 5 YouTube thumbnail URLs
- ✅ Rendered page image URL audit returned HTTP 200 for discovered upload/theme/YouTube image URLs
- ✅ Playwright screenshots captured:
  - `archive/series_thumbnails_check.png`
  - `archive/career_images_check.png`
  - `archive/footer_mobile_check.png`

## ✅ Navigation, Context CTA, and Return-to-Top Refinements (2026-05-11)

Completed the follow-up UI fixes after the user verified the first May 11 pass.

- ✅ Fixed Stories dropdown layering and pointer usability: the desktop submenu now uses fixed positioning under the hovered nav link, sits above page heroes/banners, and remains open long enough for the pointer to move into the submenu.
- ✅ Removed Pitch Your Pal from the primary menu entirely while keeping the Products-page Pitch Your Pal CTA.
- ✅ Added reusable `hopp_render_context_cta()` output for the Products-style bottom CTA pattern.
- ✅ Applied the contextual CTA component across Home, About Us, Products, Stories, Artist, Career, and Contact Us.
- ✅ Added code-level theme source-of-truth variables (`--hopp-brand-*`) in `style.css`, so normal brand-surface/button/CTA color changes can be made through tokens instead of page-by-page template edits.
- ✅ Documented the theme token workflow in `README.md` for future developers.
- ✅ Added a floating Return to Top control with an accessible label, scroll-triggered visibility, and top anchor target.

**Validation:**

- ✅ PHP lint for `functions.php`, `front-page.php`, `page.php`, `header.php`, and `footer.php`
- ✅ `node --check wp-content/themes/hopp/assets/js/navigation.js`
- ✅ HTTP 200 route checks for `/`, `/about-us/`, `/products/`, `/stories/`, `/series/`, `/artist/`, `/career/`, and `/contact-us/`
- ✅ Rendered nav HTML confirms Pitch Your Pal is absent from the primary menu and `Browse by Series` remains nested under Stories
- ✅ Playwright hover/click check confirms the pointer can move from `Stories` into `Browse by Series` and click through to `/series/`
- ✅ Playwright scroll check confirms the Return to Top control appears after scrolling
- ✅ Screenshots captured:
  - `archive/stories_dropdown_top_hover_check.png`
  - `archive/return_to_top_visible_check.png`
