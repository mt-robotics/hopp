# Project Status

**Last Updated:** 2026-05-18 (canonical production branch strategy and Git-to-VM deploy path defined; deferred GitHub Actions CI/CD follow-up added to backlog; email alert notes absorbed into production workflow; Telegram alert moved to backlog)

---

## Current State

- The imported live site is stable locally and in production: local WordPress mirrors the live content/settings snapshot, and the sponsor-funded VM now serves the imported stack at `https://hopp.delvedeepasia.org`.
- The deployment artifact is reproducible enough to boot the site safely: nginx handles HTTP-first certificate bootstrap, WordPress startup seeds core files correctly, and the ABA PayWay runtime patch is encoded in repo-mounted startup files instead of manual container edits.
- The sponsor-funded production VM setup is complete: the server is provisioned, serving the imported site, and normalized under the `/opt/hopp` group-owned operating model.
- The canonical production branch strategy is now defined: `feature/*` for task work, `development` for integration, and `main` as the only production branch and rollback reference.
- The canonical server deploy path is now defined: production runs from the `/opt/hopp` checkout on the VM, deploys from `origin/main` through `./scripts/deploy-production.sh`, and uses `./scripts/rollback-production.sh <sha>` for emergency rollback to a known-good `main` commit.
- The main unresolved project area is production workflow standardization: document what is Git-managed vs WP-admin-managed, verify production mail delivery, add backup/restore and rollback procedures, and finalize production access rules.
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
- 🔄 Standardize Production WordPress Workflow
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

The current Home hero background is mapped from imported media, but the team expects a new design/background image. This task is blocked on receiving the final designer asset or direction.

- Blocked until the approved Home background image or specific redesign instruction is available
- Replace the mapped Home hero image without disturbing other page hero mappings
- Verify desktop, tablet, mobile crop behavior after replacement

### 🔲 Gather and add remaining approved content

The team noted that more content may need to be added. This needs project manager confirmation so the theme does not invent copy or publish unapproved material.

- Blocked until the project manager identifies which pages need more content and provides approved copy/media
- Add only approved copy/media to the relevant WordPress content or theme-controlled sections
- Re-run visual checks on any page whose content length changes materially

### 🔄 Standardize Production WordPress Workflow

The site is reachable and serving real content, but the production operating model is not standard enough yet. The repo, server, and WP Admin workflow need to be tightened so developers own code through Git and deployment automation, while ops/content teams manage content and approved settings directly in WordPress admin.

- ✅ Define the canonical branch strategy for production
- ✅ Define the canonical server deploy path from Git to VM
- 🔲 Separate code-managed state from WP-admin-managed state
- 🔲 Add backup and restore automation
- 🔲 Add production mail delivery and form verification
- 🔲 Add production smoke-test checklist and rollback procedure
- 🔲 Add production access, update, and change-management rules
- 🔲 Decide the final primary domain cutover path (`hopp.delvedeepasia.org` vs `humansofphnompenh.com`)

#### ✅ Define the canonical branch strategy for production

This sub-task is complete, but its parent production-workflow task is still in progress. The project now has a defined release branch model so future deploy and rollback procedures can reference a stable Git source of truth.

- Canonical branch policy is now `feature/* -> development -> main`
- `main` is the only production branch and the only valid rollback reference for production code
- Branch policy is documented in `README.md`, `PROJECT.md`, and `docs/live_wordpress_deployment.md`
- `project_status.md` current-state notes also reflect that the branch strategy is defined, while the remaining deploy-path and operations sub-tasks stay active

#### ✅ Define the canonical server deploy path from Git to VM

This sub-task is complete. The production VM now has an explicit repo-owned deploy and rollback path instead of relying on memory or ad hoc shell commands.

- Canonical deploy source is `main`, deployed onto the `/opt/hopp` checkout on the VM
- Normal production deploy command is `./scripts/deploy-production.sh` after SSHing to `hopp-prod`
- The deploy helper fetches `origin`, checks out `main`, fast-forwards to `origin/main`, then runs `make gcp-rebuild` and `make gcp-ps`
- `.env.gcp` remains host-managed on the VM and is never overwritten by Git
- Emergency rollback command is `./scripts/rollback-production.sh <known-good-main-sha>`
- The full operational runbook is documented in `docs/production_vm_deploy.md`

#### 🔲 Separate code-managed state from WP-admin-managed state

To keep the project maintainable, developers should own theme code, deploy scripts, and infrastructure code, while ops teams should own posts, pages, products, menus, media, form submissions, and routine site settings in WP Admin.

- Document which settings are code-owned:
  theme PHP/CSS/JS, Docker Compose, nginx templates, upload limits, runtime patch hooks
- Document which settings are WP-admin-owned:
  posts, pages, products, menus, widgets if any, media, WooCommerce catalog content, CF7 form entries/notifications, general editorial settings
- Identify the small set of sensitive WP settings that must be controlled carefully even in admin:
  permalinks, WooCommerce page assignments, payment gateway settings, SMTP plugin settings, reading settings
- Add a change rule so repo code does not silently overwrite ops-managed admin content

#### 🔲 Add backup and restore automation

The live site is now important enough that a working restore path matters more than a one-time snapshot. Production should have repeatable backups for database and uploads, plus a tested restore drill.

- Add automated MySQL dump backups on the VM or to cloud storage
- Add automated `wp-content/uploads` backups
- Decide whether plugin and theme files are backed up separately or reconstructed from Git + plugin source
- Add a documented restore procedure:
  DB restore, uploads restore, container recreate, smoke check
- Take and document a manual pre-change snapshot before future risky changes

#### 🔲 Add production mail delivery and form verification

Production mail behavior is still not verified end to end. Until this is done, public forms and WooCommerce order notifications cannot be treated as production-ready.

- Choose the real mail path:
  SMTP plugin, transactional email provider, or host relay
- Configure sender identity, SPF/DKIM/DMARC if needed for the chosen provider
- Verify all public CF7 forms end to end:
  Artist, Career, Contact Us, Pitch Your Pal
- Verify WooCommerce order email behavior end to end:
  confirm whether a customer/admin email is sent immediately after order placement and which order-status transitions also trigger email notifications
- Decide whether the default WooCommerce status-email behavior is acceptable or needs business-specific changes
- Record the expected inboxes and alert contacts for failed delivery

#### 🔲 Add production smoke-test checklist and rollback procedure

The site needs a stable post-deploy verification routine so future deploys do not rely on memory.

- Define the minimum smoke suite after every production deploy:
  homepage, key pages, single story, products, cart, checkout payment rows, CF7 forms, admin login, media rendering
- Record browser/device coverage expectations for those checks
- Define the rollback trigger:
  what failures justify immediate rollback
- Define the rollback method:
  previous Git revision, prior DB backup if needed, container recreate, smoke recheck

#### 🔲 Add production access, update, and change-management rules

A standard production project needs clear operational boundaries. Without that, future changes will become ambiguous between dev work, ops work, and emergency server edits.

- Define who can SSH to the VM and under what Linux users/groups
- Define who can change DNS, TLS email, SMTP credentials, and payment settings
- Define plugin/theme update policy:
  direct WP-admin updates vs repo-reviewed updates vs emergency-only updates
- Define when server-side hotfixes are allowed and how they must be written back into Git immediately

#### 🔲 Decide the final primary domain cutover path

`hopp.delvedeepasia.org` works, but the final public production hostname may still need to move back to `humansofphnompenh.com`. That cutover should be treated as an explicit production task, not an implied future step.

- Decide whether `hopp.delvedeepasia.org` remains the long-term production host or only a staging/transition host
- If moving back to `humansofphnompenh.com`, define the exact cutover sequence:
  DNS, `.env.gcp`, WordPress URLs, certificate issuance, ABA callbacks, smoke test
- Verify all hardcoded references, redirects, and payment callbacks before that cutover
- Keep the current working domain stable until the final cutover plan is approved

## Post-V1 Backlog

- 🔲 Run browser visual QA with screenshots after Playwright or another browser test tool is installed
- 🔲 Performance audit (Core Web Vitals — LCP, CLS, FID)
- 🔲 Add favicon / WordPress Site Icon
- 🔲 Audit media asset weight and optimization
- 🔲 Evaluate Telegram order-status alert integration
- 🔲 Add GitHub Actions deployment wrapper for the production VM path

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

### 🔲 Add favicon / WordPress Site Icon

The site currently has no confirmed favicon/site icon tracked in the project plan. This needs either a brand-approved icon asset or a clean fallback from the existing logo mark.

- Add a favicon/site icon through the theme or WordPress Site Icon configuration
- Include standard browser icon sizes and verify the tab icon renders locally
- Document the chosen source asset so deployment can reproduce it

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
