# Project Status

**Last Updated:** 2026-05-18 (production workflow standardized end-to-end in repo-owned runbooks and helper scripts; primary-domain cutover execution split into its own next task)

---

## Current State

- The imported live site is stable locally and in production: local WordPress mirrors the live content/settings snapshot, and the sponsor-funded VM now serves the imported stack at `https://hopp.delvedeepasia.org`.
- The deployment artifact is reproducible enough to boot the site safely: nginx handles HTTP-first certificate bootstrap, WordPress startup seeds core files correctly, and the ABA PayWay runtime patch is encoded in repo-mounted startup files instead of manual container edits.
- The sponsor-funded production VM setup is complete: the server is provisioned, serving the imported site, and normalized under the `/opt/hopp` group-owned operating model.
- The canonical production branch strategy is now defined: `feature/*` for task work, `development` for integration, and `main` as the only production branch and rollback reference.
- The canonical server deploy path is now defined: production runs from the `/opt/hopp` checkout on the VM, deploys from `origin/main` through `./scripts/deploy-production.sh`, and uses `./scripts/rollback-production.sh <sha>` for emergency rollback to a known-good `main` commit.
- Production mail is now standardized in code and docs: the repo owns the SMTP bridge, verification checklist, and cutover dependency, while the final whitelisted-domain order/mail verification remains part of the next live cutover task.
- The production workflow is now standardized in repo-owned docs and helper scripts: deploy, rollback, smoke checks, backup/restore, access/change rules, mail-routing policy, and final-domain cutover sequence all have a canonical home in the repo.
- The main unresolved production work is no longer "how should we operate this stack?" but "when do we execute the final primary-domain cutover and live whitelisted-domain verification on the server?"
- Several UI/content items remain blocked on external input: final brand color direction, Privacy Policy/Terms ownership and content, a replacement Home hero asset, and any additional approved copy/media.
- Historical implementation details remain archived in `current_state/milestone.md`; this file should now stay focused on active tasks, blockers, and next operational work.

---

## ✅ Completed Milestones

| Milestone                                              | Completed  | Tests                                                    |
| ------------------------------------------------------ | ---------- | -------------------------------------------------------- |
| Standard Project Documentation Scaffold                | 2026-04-30 | Manual file verification                                 |
| Design System (DESIGN.md)                              | 2026-04-30 | —                                                        |
| Local WordPress Docker Environment                     | 2026-04-30 | Docker Compose config, manual WP/theme render            |
| Current Site Audit + Demo Design Plan                  | 2026-04-30 | Manual document review                                   |
| V1 Local WordPress UI/UX Demo Theme                    | 2026-04-30 | PHP lint, Docker Compose config, local route checks      |
| GCP Infrastructure Setup                               | 2026-05-06 | SSH, Docker, IP ping                                     |
| Live Export, Local Import, and ABA Checkout Recovery   | 2026-05-08 | Manual admin inspection, XML import, checkout smoke test |
| Frontend Standards — HIGH Priority Fixes               | 2026-05-08 | Manual visual review                                     |
| Contact Form 7 Forms Migration                         | 2026-05-09 | PHP lint, Compose config, CF7 validator, route checks    |
| Home/About UI Refinements                              | 2026-05-09 | PHP lint, Chrome screenshot review                       |
| Products Page UI Refinement                            | 2026-05-09 | PHP lint, rendered HTML check, Chrome screenshot review  |
| Stories/Series UI Refinement                           | 2026-05-09 | PHP lint, JS syntax check, rendered HTML, screenshots    |
| Hero Background Image Mapping                          | 2026-05-11 | PHP lint, HTTP 200 image checks, rendered HTML checks    |
| May 11 Team Feedback — Non-blocked UI Fixes            | 2026-05-11 | PHP lint, HTTP checks, Playwright screenshots            |
| Navigation, Context CTA, and Return-to-Top Refinements | 2026-05-11 | PHP lint, JS syntax, HTTP checks, Playwright screenshots |
| Sponsor-Funded GCP Deployment Plan                     | 2026-05-15 | Shell syntax checks, manual doc review                   |
| Set up GCP-hosted public preview                       | 2026-05-18 | Manual public-preview verification                       |
| Set up sponsor-funded GCP production server            | 2026-05-18 | Manual production verification                           |
| Stabilize ABA PayWay gateway for deployment            | 2026-05-18 | Local checkout verification, runtime patch verification  |
| Standardize Production WordPress Workflow              | 2026-05-18 | Shell syntax checks, manual doc review                   |

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
- ✅ Plan sponsored GCP deployment for the live WordPress site
- ✅ Set up sponsor-funded GCP production server
- ✅ Set up GCP-hosted public preview
- ✅ Standardize Production WordPress Workflow
- ✅ Inspect live admin settings, active theme, menus, plugins, and e-commerce setup
- ✅ Export live site content
- ✅ Import live content into local WordPress
- ✅ Install/mirror required plugins locally, especially e-commerce/contact form plugins
- ✅ Stabilize ABA PayWay gateway for deployment
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
- ✅ Replace Forminator forms with Contact Form 7 (CF7) on Artist, Career, Contact Us, Pitch Your Pal pages
- ✅ Restore HOME teaser images dynamically for Products and Artists
- ✅ Redesign About Us using live-site content and screenshot-driven QA
- ✅ Refine Products page registration thumbnail and product-card summaries
- ✅ Refine Stories/Series IA, playlist cards, and card summary consistency
- ✅ Apply imported hero background images to key pages
- ✅ Move Pitch Your Pal under Products
- ✅ Add YouTube thumbnails to the Series page
- 🔲 Reconcile theme color with final brand direction
- ✅ Audit and fix missing images across pages
- 🔲 Link footer Privacy Policy and Terms pages
- ✅ Replace footer social icons with source-aligned icons
- 🔲 Replace the Home hero background after designer update
- 🔲 Gather and add remaining approved content
- 🔄 Operationalize WP-Admin Ownership For Theme-Controlled Content And Settings
- 🔄 Execute Primary-Domain Cutover And Final Live Verification
- 🔲 Run First Production Backup/Restore Drill
- 🔲 Run browser visual QA with screenshots after Playwright or another browser test tool is installed
- 🔲 Performance audit (Core Web Vitals — LCP, CLS, FID)
- ✅ Add favicon / WordPress Site Icon
- 🔲 Audit media asset weight and optimization
- 🔲 Evaluate Telegram order-status alert integration
- 🔲 Add GitHub Actions deployment wrapper for the production VM path
- ✅ Redesign footer layout and spacing
- ✅ Fix Stories dropdown layering above page banners
- ✅ Apply reusable contextual CTA component across pages
- ✅ Implement token-level theme color source of truth in code
- ✅ Add floating Go to Top control

---

## Active Task Details

Completed V1 implementation details are archived in `current_state/milestone.md`. This file now keeps only active tasks, blockers, and next work.

### 🔲 Reconcile theme color with final brand direction

The team flagged the current orange/terracotta direction as potentially inconsistent with the brand. This needs designer input before changing global tokens because the palette affects the whole theme.

- Blocked until the designer provides the final theme color or a clear brand-color direction
- Theme color now has a code-level single source of truth in `wp-content/themes/hopp/style.css` through `--hopp-brand-*` variables
- Update `DESIGN.md` first if the color system changes, then update the matching `--hopp-brand-*` variables instead of editing every page template
- Apply the approved color tokens consistently across any remaining one-off surfaces if the designer changes the brand direction

### 🔲 Link footer Privacy Policy and Terms pages

The footer currently needs Privacy Policy and Terms links, but the exact policy content/page ownership needs project manager confirmation before inventing legal text.

- Blocked until the project manager confirms which policy pages should exist and what content source should be used
- Create or link the approved WordPress pages
- Verify footer links resolve correctly and do not point to placeholders

### 🔲 Replace the Home hero background after designer update

The current Home hero background task has moved past planning and is now mostly implemented. The remaining work is to replace the current temporary/local video with the final approved designer export and then run final visual QA.

- The original designer-source requirement is still the same for the final asset: about 20 seconds, no burned subtitles, and edited to loop cleanly
- Delivery decision is now fixed: self-host the optimized hero video in the theme/repo path; do not use a paid video CDN for this feature
- Browser-policy contract is fixed: autoplay muted on first visit; the implemented ops-facing audio modes are `Start muted` and `No sound (always muted)`
- The currently observed documentary-style recording overlay/HUD is acceptable if it remains part of the approved export
- The `Pages > Home` screen now uses a unified `Hero Media` panel for image/video selection, poster image selection, and video audio mode; no raw media URLs are exposed to ops
- Homepage presentation rule is now fixed:
  image hero => show the normal homepage hero copy/buttons
  video hero => hide the homepage hero copy/buttons completely so the video stands alone
- The `Homepage Content` editor now warns ops when `Hero media type = Video`, clarifying that the homepage hero copy/buttons are inactive until the hero switches back to `Image`
- Video hero sizing is now separate from image hero sizing:
  the homepage video hero fills the remaining first-screen viewport below the sticky header instead of using the shallower image-hero frame
- Final remaining work:
  replace the current temporary/local video with the approved designer export
  verify desktop/tablet/mobile framing, poster fallback, audio-mode behavior, and overall first-screen presentation

### 🔲 Gather and add remaining approved content

The team noted that more content may need to be added. This needs project manager confirmation so the theme does not invent copy or publish unapproved material.

- Blocked until the project manager identifies which pages need more content and provides approved copy/media
- Add only approved copy/media to the relevant WordPress content or theme-controlled sections
- Re-run visual checks on any page whose content length changes materially

### 🔄 Execute Primary-Domain Cutover And Final Live Verification

The workflow is now standardized, but the final public move back to the brand domain is still an operational execution task on the real server. This is the step that should remove the current ABA whitelist blocker and close the last live WooCommerce-mail verification gap.

- Final primary hostname decision is now explicit:
  `https://humansofphnompenh.com`
- Canonical hostname behavior is now explicit:
  `www.humansofphnompenh.com` should redirect to `https://humansofphnompenh.com` through nginx on the sponsor-funded VM
- Use `docs/production_operations_index.md` as the first operator guide, then follow the cutover commands/checks from the production workflow files it points to
- Current execution state on 2026-05-19:
  the VM checkout has been refreshed to current `origin/main`, a fresh backup exists at `/opt/hopp/backups/20260519T081412Z`, and the transition host smoke test still passes
- Current external blockers:
  `humansofphnompenh.com` still points to the old Hostinger IP instead of `34.21.157.41`, and the ABA merchant-side whitelist still needs the final brand domain
- Director-side information still required before the final env switch:
  GoDaddy update for `humansofphnompenh.com` -> `34.21.157.41`, confirmation that no conflicting apex `AAAA` record should remain unless IPv6 is intentionally configured, and the real Hostinger SMTP mailbox details (`from` address, host, port, security mode, username, password, sender name)
- ABA verification rule is now explicit:
  there is no separate pre-confirmation path; once DNS points at the VM and the VM is switched to `humansofphnompenh.com`, run the live ABA checkout test directly and treat the result as the whitelist proof
- Do not mark this done until:
  `humansofphnompenh.com` is live on the sponsor-funded VM, HTTPS is valid, CF7 mail arrives, and the ABA/WooCommerce order-email path is verified on the whitelisted domain

### 🔲 Run First Production Backup/Restore Drill

Backup and restore automation now exists in repo-owned scripts, but one controlled live drill is still needed so the team learns the real recovery timing before a real incident.

- Use `./scripts/backup-production.sh` to create a fresh bundle on the VM
- Run `./scripts/restore-production.sh --yes <backup_dir>` in a controlled window or rehearsal environment
- Follow with `./scripts/smoke-test-production.sh` and the operator checks summarized in `docs/production_operations_index.md`
- Log timing, surprises, and any script/doc gaps in `archive/daily_log.md` and `docs/error_log.md` if needed

### 🔲 Run browser visual QA with screenshots

No automated browser testing has been run. A visual pass across all pages on both desktop and mobile viewports is needed to catch layout regressions before the site goes live.

- Install Playwright or equivalent browser tool in the project
- Run screenshot captures for all primary routes: `/`, `/about-us/`, `/products/`, `/stories/`, `/artist/`, `/career/`, `/contact-us/`, `/cart/`, and a single product and story page
- Review screenshots for layout breakages, spacing issues, and mobile overflow

### 🔲 Performance audit (Core Web Vitals)

No performance measurement has been done on the imported-content staging site. LCP, CLS, and FID should be measured against Google's thresholds before the GCP deployment.

- Run Lighthouse or PageSpeed Insights against the local staging site (or the GCP preview once deployed)
- Address any LCP element not loading eagerly, CLS from layout shifts, or blocking scripts

### 🔲 Audit media asset weight and optimization

The imported site now uses many images and external thumbnails. A performance pass must verify whether asset count, dimensions, and file sizes are slowing down the site.

- Inventory large images used above the fold and in repeated cards
- Check whether WordPress-generated sizes are used instead of full-size originals
- Add lazy loading, explicit dimensions, compression, or WebP/AVIF conversion where appropriate
- Re-run Lighthouse or equivalent after optimization

### 🔲 Evaluate Telegram order-status alert integration

Telegram order alerts are a potential operations enhancement, not a current production blocker. Treat this as a scoped future feature only after core production mail delivery and order-notification behavior are verified.

- Confirm the business need and exact recipients for Telegram alerts
- Decide which order events matter:
  placed only, or also accepted/rejected/processing/completed/cancelled
- Evaluate implementation options:
  WooCommerce plugin, automation bridge, or custom bot/webhook integration
- Document feasibility, operational risk, and ownership before any implementation

### 🔲 Add GitHub Actions deployment wrapper for the production VM path

The canonical production deploy path is now stable and manual: update `main`, SSH to the VM, and run `./scripts/deploy-production.sh`. A future CI/CD task can automate that same path without redefining the runtime contract.

- Add a GitHub Actions workflow that deploys only reviewed `main` revisions
- Store the VM SSH key, host, and any required connection metadata in GitHub Secrets
- Have the workflow SSH into the VM, `cd /opt/hopp`, and run `./scripts/deploy-production.sh`
- Decide whether deploys should trigger automatically on `main` pushes or require a manual workflow dispatch
- Add a minimal post-deploy smoke check so CI/CD confirms the wrapper succeeded

### 🔄 Operationalize WP-Admin Ownership For Theme-Controlled Content And Settings

Started on 2026-05-19. The ownership boundary is now largely operational for the main editorial surfaces that previously required theme edits, but the task is not fully complete yet.

- Audit all major theme-controlled surfaces and classify each as Git-managed, WP-admin-managed, or host-managed
- Delivered in this pass:
  homepage hero and section copy live on the static front-page edit screen through the `HOPP Front Page Content` meta box; main editorial page bodies come from normal WordPress page content; page hero intros come from page excerpts; page hero images prefer featured images/page media; footer intro follows the WordPress site tagline; and footer navigation/social/legal surfaces are now managed through WordPress menu locations
- Follow-up operationalization completed on 2026-05-20:
  the `Pages > Home` screen now uses a unified `Hero Media` control for image/video hero selection, poster image handling, and video audio mode; no raw media URLs are exposed to ops; homepage video mode now clearly warns that hero copy/buttons are hidden while video is active
- Extended the handover architecture on 2026-05-20:
  key pages are now explicitly classified by page type rather than left in a mixed WordPress state. Hero-section pages use the shared structured editor pattern with `Hero Media` plus dedicated hero/body meta boxes; WooCommerce system pages (`Cart`, `Checkout`, `My account`) now render as locked system pages in admin with no irrelevant hero or featured-image controls; document-style pages (`CONTEST GUIDELINES`, `Termsandconditions artist`, `Refund and Returns Policy`) now use a calmer theme-native document layout on the frontend and a single rich `Document Content` editor in admin
- Remaining page-type cleanup after this session:
  `Message Us` has been moved to Trash because it is legacy form-staging content with no current runtime dependency; the final triage still pending is the last questionable/placeholder group (`Press`, `Product`, and the Khmer placeholder page `មិនមានខ្លឹមសារនៅទីនេះទេ។`)
- Important remaining ownership/platform work:
  page-type behavior is now implemented for the major existing pages, but the full operator-controlled page-creation workflow is not finished yet. A future pass still needs to define the approved page-type/template set explicitly for this website and decide how WP Admin should constrain or guide `Add Page` so operations create new pages only from the supported page types (`Homepage`, `Hero-section page`, `System page`, `Document page`, and any final approved additional type)
- Keep true runtime/infrastructure concerns in code: deploy scripts, nginx/TLS, SMTP bridge, ABA patching, and container/runtime configuration
- Document the final operator workflow clearly so the handover explains what ops can change in WP Admin and what still requires code or host access
