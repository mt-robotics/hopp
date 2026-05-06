# Project Status

**Last Updated:** 2026-05-06

---

## Current State

- GCP server access established — VM (e2-micro) running in us-west1
- Static External IP (35.252.238.69) reserved and mapped to `hopp.delvedeepasia.org`
- SSH access configured from local laptop via SSH keys
- Docker and Docker Compose installed and verified on GCP server
- Nginx templates prepared for automatic variable substitution
- No WP admin credentials yet (pending from client)
- No current theme name known
- `DESIGN.md` written — all color tokens reconciled, nav Option B (original 7-item nav preserved)
- Standard project documentation scaffold created (`CLAUDE.md`, `PROJECT.md`, `README.md`, `DOCKER_SETUP.md`, `.env.example`, `docs/error_log.md`)
- Local WordPress Docker scaffold created (`docker-compose.yml`, `docker-compose.local.yml`, `.env.local`, theme bind-mount directory); Compose config validates
- Local WordPress is running and the HOPP theme pipeline is verified
- Live admin-only deployment runbook documented in `docs/live_wordpress_deployment.md`
- Public site crawl distilled into `docs/current_site_audit.md`
- V1 demo design plan documented in `docs/demo_design_plan.md`
- Current strategy: share the V1 local WordPress UI/UX demo with the team for design feedback, then move the approved build to the GCP-hosted public preview for longer-lived external access
- V1 local WordPress UI/UX demo theme implemented and verified locally and over LAN
- Team review note prepared at `archive/20260504_team_confirmation.md`
- **Next:** Deploy the WordPress site to the GCP-hosted public preview. 🔲

---

## ✅ Completed Milestones

| Milestone | Completed | Tests |
|---|---|---|
| Standard Project Documentation Scaffold | 2026-04-30 | Manual file verification |
| Design System (DESIGN.md) | 2026-04-30 | — |
| Local WordPress Docker Environment | 2026-04-30 | Docker Compose config, manual WP/theme render |
| Current Site Audit + Demo Design Plan | 2026-04-30 | Manual document review |
| V1 Local WordPress UI/UX Demo Theme | 2026-04-30 | PHP lint, Docker Compose config, local route checks |
| GCP Infrastructure Setup | 2026-05-06 | SSH, Docker, IP ping |

→ Full details: `current_state/milestone.md`

---

## V1 — Local WordPress UI/UX Demo for Team Review

**Goal:** A polished local WordPress theme demo built from `DESIGN.md`, using placeholder/local content, so the team can review and approve the UI/UX direction before live WP admin access is granted.

### Task Checklist

- ✅ Design system (DESIGN.md)
- ✅ Local WP Docker environment
- ✅ Current site audit
- ✅ Demo design plan
- ✅ Build local UI/UX demo theme
- ✅ Create placeholder pages/content for review
- ✅ Set up local primary navigation
- ✅ Test demo locally (desktop/mobile/basic routes)
- 🔲 Present demo to team for feedback
- 🔲 Incorporate team feedback
- 🔄 Set up GCP-hosted public preview

---

## V2 — Production Integration After Admin Access

**Goal:** Import the real live site content/plugins into local WordPress, adapt the HOPP theme to the actual site structure and e-commerce flow, test thoroughly, then upload through WP admin.

### Task Checklist

- 🔲 Inspect live admin settings, active theme, menus, plugins, and e-commerce setup
- 🔲 Export live site content
- 🔲 Import live content into local WordPress
- 🔲 Install/mirror required plugins locally, especially e-commerce/contact form plugins
- 🔲 Adapt HOPP theme to real content and plugin behavior
- 🔲 Test production-critical flows locally
- 🔲 Package and upload theme through live WP admin
- 🔲 Smoke-test live site and rollback if needed

---

## Active Task Details

Completed V1 implementation details are archived in `current_state/milestone.md`. This file now keeps only active tasks, blockers, and next work.

### 🔄 Set up GCP-hosted public preview

Move the working local WordPress demo onto a free GCP VM so the team can access it from any browser without keeping a laptop online.

- ✅ Provision VM on GCP Always Free Tier (e2-micro, us-west1)
- ✅ Reserve Static IP and point domain
- ✅ Configure SSH access with keys
- ✅ Install Docker and Docker Compose on VM
- 🔲 Clone repo on VM using Deploy Key
- 🔲 Configure production `.env.gcp`
- 🔲 Run `docker-compose -f docker-compose.yml -f docker-compose.gcp.yml --env-file .env.gcp up -d`
- 🔲 Verify public access at `http://hopp.delvedeepasia.org`
- 🔲 Share URL with the team

### 🔲 V2 Only — Import Live Site Content

Populate the local WP instance with content that mirrors the live site so theme development is realistic.

- Export content from `humansofphnompenh.com` WP admin (requires admin credentials — blocked until access granted)
- Import via WP XML export (`Tools > Export > All content`)
- Re-upload media assets if needed
- Verify pages, posts, menus, and widgets render correctly

**Blocked by:** WP admin credentials from client.

---

### 🔲 V2 Only — Adapt Custom WP Theme to Live Content

Adapt the V1 HOPP demo theme to real imported content and plugin behavior after WP admin access is granted.

- Theme directory: `wp-content/themes/hopp/` (`hopp` = Humans of Phnom Penh)
- Required WP theme files: `style.css`, `index.php`, `functions.php`, `header.php`, `footer.php`
- Page templates for: Home, About Us, Products, Stories, Artist, Career, Contact Us
- Tailwind CSS via CDN (matching `index.html` setup) or compiled locally
- No WordPress page builder — pure custom theme
- Must preserve all original content (posts, images, menus, cart) — no data loss

**Critical:** Replacing a theme on a live site without server access means no easy rollback. The V1 demo can be shown to the team, but production activation must wait for V2 validation against real content/plugins.

---

### 🔲 V2 Only — Test Production-Critical Flows Locally

Verify the theme is production-safe before any push.

- All 7 nav pages render correctly
- Mobile responsive (320px minimum viewport)
- Products page + shopping cart functional (WooCommerce or existing plugin)
- No broken images, missing fonts, or console errors
- Accessibility: focus indicators visible, semantic HTML, heading hierarchy correct
- Cross-browser: Chrome, Safari, Firefox

---

### 🔲 V2 Only — Push Theme to Live Site

Once local testing is 100% complete, push the theme to the live WordPress site.

- Upload via WP admin > Appearance > Themes > Add New > Upload (requires admin credentials)
- Activate theme
- Verify live site matches local
- Follow the admin-only deployment runbook in `docs/live_wordpress_deployment.md`
- Rollback procedure: re-activate previous theme from Appearance > Themes

**Blocked by:** WP admin credentials from client AND completion of all prior tasks.

---

## Post-V1 Backlog

- 🔲 Set up Git versioning for the theme directory
- 🔲 Run browser visual QA with screenshots after Playwright or another browser test tool is installed
- 🔲 Evaluate WooCommerce styling (Products page may need additional theme work)
- 🔲 Performance audit (Core Web Vitals — LCP, CLS, FID)
- 🔲 Contact form integration (verify it submits correctly after theme change)

## Post-V1 Task Details
