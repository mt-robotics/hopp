# Live WordPress Deployment Runbook

Operational guide for updating the live Humans of Phnom Penh WordPress site through WP Admin only.

This document reflects the earlier admin-only deployment path from before the sponsor-funded GCP VM became the primary production host. The canonical production branch strategy is now `feature/* -> development -> main`, with `main` as the only production branch. The canonical VM deploy workflow now lives in `docs/production_vm_deploy.md`. Use this runbook only when a theme-only admin upload is explicitly required; do not treat it as the default production deploy workflow for the current VM-backed stack.

---

## Table of Contents

1. [Theme and Content Model](#theme-and-content-model)
2. [Phase 1: Inspect Before Changing Anything](#phase-1-inspect-before-changing-anything)
3. [Phase 2: Export Live Content](#phase-2-export-live-content)
4. [Phase 3: Import Into Local WordPress](#phase-3-import-into-local-wordpress)
5. [Phase 4: Build and Test Locally](#phase-4-build-and-test-locally)
6. [Phase 5: Package the Theme](#phase-5-package-the-theme)
7. [Phase 6: Upload Through Live WordPress Admin](#phase-6-upload-through-live-wordpress-admin)
8. [Phase 7: Activate and Reassign Settings](#phase-7-activate-and-reassign-settings)
9. [Phase 8: Live Smoke Test](#phase-8-live-smoke-test)
10. [Phase 9: Rollback](#phase-9-rollback)

---

## Theme and Content Model

```text
WordPress database = pages, posts, products, menus, media, settings
Theme PHP files = reusable rendering templates
CSS = visual design
JavaScript = interactions
Plugins = functionality such as e-commerce, forms, SEO, analytics
```

Importing real WordPress content does not replace the theme files. The custom theme files remain the rendering system; imported content is the data plugged into that system.

For e-commerce, the theme should not rebuild payment/cart logic. That functionality belongs to the active WordPress plugin, likely WooCommerce. The theme must avoid breaking plugin-rendered product, cart, checkout, account, notice, and form flows.

---

## Phase 1: Inspect Before Changing Anything

Do these before uploading or activating the new theme:

1. Log in to the live WordPress admin.
2. Go to Appearance -> Themes.
3. Write down the current active theme name.
4. Confirm whether WordPress offers Live Preview for uploaded themes.
5. Go to Plugins -> Installed Plugins.
6. Write down all active plugins.
7. Identify the e-commerce plugin. It is likely WooCommerce, but this must be verified in admin.
8. If WooCommerce is active, inspect:
   - WooCommerce -> Settings -> Advanced
   - Cart page assignment
   - Checkout page assignment
   - My account page assignment
   - Terms and conditions page assignment, if any
9. Go to Appearance -> Menus.
10. Write down the current menu names, menu locations, and menu items.
11. Go to Settings -> Reading.
12. Write down the homepage and posts page settings.

Do not activate the new theme during this phase.

---

## Phase 2: Export Live Content

Use the live admin to export content for local testing:

1. Go to Tools -> Export.
2. Choose All content.
3. Download the export XML file.
4. If WooCommerce exists, check whether Products are included in the standard export or whether WooCommerce has a dedicated export tool.
5. If a backup plugin exists, create and download a full backup before theme deployment.

Expected imported content:

```text
pages
posts
categories
menus
media references
possibly products, depending on export/plugin support
```

The export gives local WordPress realistic content for testing. It does not replace the custom theme.

---

## Phase 3: Import Into Local WordPress

On local WordPress:

1. Go to Tools -> Import.
2. Install the WordPress importer if prompted.
3. Import the XML file from the live site.
4. Import or re-upload media if needed.
5. Recreate menu assignments if the importer does not restore them.
6. Match Settings -> Reading to the live site.
7. Install the same e-commerce plugin locally if the live site uses one.
8. Verify pages, posts, products, menus, cart, checkout, and forms render locally.

The theme must be tested against this imported content before any live activation.

---

## Phase 4: Build and Test Locally

Local verification must cover:

- Homepage
- About Us
- Products
- Stories
- Artist
- Career
- Contact Us
- Single story/post page
- Category/archive page
- Search results
- 404 page
- Product listing
- Single product page
- Add to cart
- Cart page
- Checkout page up to a safe stopping point
- Contact form
- Header navigation
- Footer links
- Mobile viewport down to 320px
- Browser console errors

For e-commerce, verify that the theme does not break the plugin-rendered product, cart, checkout, account, or notice flows.

---

## Phase 5: Package the Theme

Package only the theme folder:

```text
wp-content/themes/hopp/
```

The zip should contain files like:

```text
hopp/
├── style.css
├── functions.php
├── header.php
├── footer.php
├── front-page.php
├── page.php
├── single.php
├── archive.php
├── home.php
├── search.php
└── 404.php
```

Do not include Docker files, local database volumes, `.env.local`, or local WordPress uploads in the theme zip.

---

## Phase 6: Upload Through Live WordPress Admin

On the live site:

1. Go to Appearance -> Themes.
2. Add New -> Upload Theme.
3. Upload `hopp.zip`.
4. Install the theme.
5. Do not activate blindly.
6. Use Live Preview if available.
7. In preview, check homepage, pages, posts, products, cart, checkout, and contact.

If Live Preview is unavailable or incomplete, activate only when the local test result is strong and the old theme name has been recorded for rollback.

---

## Phase 7: Activate and Reassign Settings

After activation:

1. Go to Appearance -> Menus.
2. Assign the correct menu to the HOPP Primary Menu location.
3. Go to Settings -> Reading.
4. Confirm homepage and posts page assignments.
5. If WooCommerce is active, go to WooCommerce -> Settings -> Advanced.
6. Confirm Cart, Checkout, My Account, and Terms page assignments.
7. Clear any cache plugin cache if a cache plugin exists.

---

## Phase 8: Live Smoke Test

Immediately test:

- Homepage
- About Us
- Products
- Stories
- Artist
- Career
- Contact Us
- Product page
- Add to cart
- Cart page
- Checkout page up to a safe stopping point
- Contact form submission, if safe
- Mobile navigation
- Browser console

If a critical issue appears, rollback immediately.

---

## Phase 9: Rollback

Rollback path through admin:

```text
Appearance -> Themes -> activate previous theme
```

This is why the previous active theme name must be recorded before activation.

If plugin settings changed during deployment, restore the recorded menu, reading, WooCommerce, and page assignment settings.
