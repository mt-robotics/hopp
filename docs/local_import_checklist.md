# Local Import Checklist

Checklist for importing the live Humans of Phnom Penh WordPress export into the local WordPress stack.

Source inputs:

- `archive/humansofphnompenh.WordPress.2026-05-08.xml`
- `docs/live_site_settings.md`
- `docs/live_wordpress_deployment.md`

---

## Table of Contents

1. [Goal](#goal)
2. [Pre-Import Checks](#pre-import-checks)
3. [Import Steps](#import-steps)
4. [Post-Import Verification](#post-import-verification)
5. [Definition of Done](#definition-of-done)

---

## Goal

Bring the live site content and settings into local WordPress so theme work, plugin checks, and GCP sizing are based on the real workload instead of assumptions.

This checklist is for the local clone only. Do not use it to change the live site.

---

## Pre-Import Checks

1. Start the local WordPress stack.
2. Confirm the local site is reachable in the browser.
3. Confirm the import file exists at:
   - `archive/humansofphnompenh.WordPress.2026-05-08.xml`
4. Confirm the local WordPress admin user can access `Tools -> Import`.
5. Confirm the local database is empty enough that the import will not collide with unwanted duplicate content.
6. Confirm the active local theme and plugins are known before import.

---

## Import Steps

1. Go to `Tools -> Import`.
2. Install the WordPress importer if WordPress prompts for it.
3. Upload the XML export file.
4. Import all available authors.
5. Import attachments/media if the importer offers that option.
6. Wait for the import to finish fully before making any changes.
7. Record any warnings, skipped items, or missing media references.

The export contains these content types and should be checked for them after import:

- `attachment`
- `page`
- `post`
- `product`
- `nav_menu_item`
- `forminator_forms`
- `et_template`
- `et_body_layout`
- `et_footer_layout`
- `et_pb_layout`
- `custom_css`
- `wp_font_face`
- `wp_font_family`
- `wp_global_styles`
- `wp_navigation`

---

## Post-Import Verification

Verify the following in local WordPress:

1. Pages imported correctly.
2. Posts imported correctly.
3. Products imported correctly.
4. Attachments/media are present or their missing references are documented.
5. The `Home` menu exists and matches the live ordering.
6. `Primary Menu` assignment is restored.
7. `Settings -> Reading` matches the live site:
   - Homepage: `Home`
   - Posts page: unset
8. WooCommerce page assignments match the live site:
   - Cart: `Cart`
   - Checkout: `Checkout`
   - My account: `My account`
   - Terms and conditions: blank
9. Divi-related content is visible and not broken:
   - templates
   - layouts
   - global styles
   - custom CSS
10. Forminator forms are present and accessible.
11. Product pages, cart, and checkout render without fatal errors.

If a plugin is missing locally, record it before trying to fix the rendering problem.

---

## Definition of Done

The import step is complete when:

- the XML import finishes without blocking errors,
- the imported content counts are documented,
- the live menu and reading assignments are mirrored locally,
- products and forms are visible in the local admin,
- and the remaining gaps are reduced to missing plugins or media only.

When this checklist is done, move on to local theme adaptation and production-critical flow testing.
