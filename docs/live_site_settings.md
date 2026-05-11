# Live Site Settings Reference

Captured from the live WordPress admin on `2026-05-08` and the exported XML file saved in `archive/`.

Source inputs:

- `archive/humansofphnompenh.WordPress.2026-05-08.xml`
- Live WordPress admin screenshots for Themes, Plugins, WooCommerce settings, Menus, and Reading

---

## Table of Contents

1. [Overview](#overview)
2. [Theme](#theme)
3. [Active Plugins](#active-plugins)
4. [WooCommerce Page Setup](#woocommerce-page-setup)
5. [Menu Settings](#menu-settings)
6. [Reading Settings](#reading-settings)
7. [Export Notes](#export-notes)

---

## Overview

This file records the live site state that was inspected one item at a time in WP Admin so the local clone and the final GCP deployment can mirror the current production configuration.

It is a reference snapshot, not a change log.

---

## Theme

| Setting | Value |
|---|---|
| Active theme | `Divi` |

The export file also contains Divi-related builder content types, which is consistent with the live theme stack.

---

## Active Plugins

The live site has 13 active plugins:

- `ABA PayWay Payment Gateway for WooCommerce`
- `Checkout Field Editor for WooCommerce`
- `Code Snippets`
- `Divi Torque Lite`
- `Forminator`
- `Lead Form Builder`
- `Popups for Divi`
- `PostX`
- `Simple Custom CSS and JS`
- `Smart Slider 3`
- `Supreme Modules Lite - Divi Theme, Extra Theme and Divi Builder`
- `WooCommerce`
- `WP Menu Cart`

Key implication:

- WooCommerce is confirmed active.
- Checkout and payment behavior depend on the existing plugin stack, not on the theme alone.

---

## WooCommerce Page Setup

| Setting | Value |
|---|---|
| Cart page | `Cart (ID: 878)` |
| Checkout page | `Checkout (ID: 879)` |
| My account page | `My account (ID: 880)` |
| Terms and conditions | unset / blank |

Standard checkout endpoints were present and unchanged:

- `order-pay`
- `order-received`
- `add-payment-method`
- `delete-payment-method`
- `set-default-payment-method`
- `orders`
- `view-order`
- `downloads`
- `edit-account`
- `edit-address`
- `payment-methods`
- `lost-password`
- `customer-logout`

---

## Menu Settings

| Setting | Value |
|---|---|
| Menu name | `Home` |
| Display location | `Primary Menu` |
| Secondary Menu | unchecked |
| Footer Menu | unchecked |

Menu items in order:

1. `Home`
2. `About Us`
3. `Products`
4. `Stories`
5. `Artist`
6. `Career`
7. `Contact Us`
8. `Pitch Your Pal: Phnom Penh`

The `Products` item points to the Shop page.

---

## Reading Settings

| Setting | Value |
|---|---|
| Homepage displays | `A static page` |
| Homepage | `Home` |
| Posts page | unset |
| Blog pages show at most | `10` posts |
| Syndication feeds show the most recent | `10` items |
| Feed content | `Full text` |
| Search engine visibility | unchecked |

---

## Export Notes

| Setting | Value |
|---|---|
| Export file | `archive/humansofphnompenh.WordPress.2026-05-08.xml` |
| Export type | `All content` |
| File size | `1.4 MB` |
| WordPress base URL | `https://humansofphnompenh.com` |
| WordPress version in export | `6.8.3` |

Content types present in the export:

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

This confirms the export captured products and the Divi builder content needed for local inspection.
