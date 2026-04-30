# Current Site Audit

Source: `archive/crawl-ea796113.md` from `markdown.new/crawl`

Scope: public crawl of `https://humansofphnompenh.com`, 27 pages captured. The crawl is sufficient for V1 UI/UX demo planning. It is not sufficient for production validation of plugins, checkout, payment, form delivery, or admin settings.

---

## Table of Contents

1. [Global Structure](#global-structure)
2. [Page Inventory](#page-inventory)
3. [Content Types](#content-types)
4. [Reusable Components Required](#reusable-components-required)
5. [Forms](#forms)
6. [E-commerce](#e-commerce)
7. [Media and External Links](#media-and-external-links)
8. [Known Current-Site Issues](#known-current-site-issues)
9. [V1/V2 Classification](#v1v2-classification)

---

## Global Structure

### Header Navigation

Primary nav discovered on all crawled pages:

| Label | URL | Type | Notes |
|---|---|---|---|
| Home | `/` | Homepage | Main landing page |
| About Us | `/about-us/` | Static page | Mission, vision, objectives |
| Products | `/products/` | E-commerce listing | Product/store entry |
| Stories | `/stories/` | Story listing | Links to posts and series |
| Artist | `/artist/` | Form/content page | Artwork contribution and contest gateway |
| Career | `/career/` | Form/content page | Volunteer/internship application |
| Contact Us | `/contact-us/` | Contact/form page | Contact info and message form |
| Pitch Your Pal: Phnom Penh | `/pitch-your-pal-phnom-penh/` | Event/form page | Event registration and pitch submission |
| 0 items | `/products/` | Cart/store indicator | Text suggests WooCommerce-style cart/menu item |
| Cart icon/link | `/cart/` | Cart page | Empty cart page crawled |

### Footer

Footer repeats across pages:

- Footer nav: Home, About Us, Stories, Get in Touch
- Social links: Facebook, Instagram, LinkedIn, Telegram
- Copyright: `Copyright @2024 - Humans of Phnom Penh`
- Credit: `Designed by Macro Solutions`
- Legal text: Privacy Policy, Terms and Conditions

The crawl shows repeated footer headings/links; this may be an extraction artifact or current-site duplication.

---

## Page Inventory

| Page | URL | Type | Main content captured |
|---|---|---|---|
| Home | `/` | Homepage | Platform intro, Phnom Penh story/culture copy, CTAs to About Us, Stories, Contact Us |
| About Us | `/about-us/` | Static content | About, Mission, Vision, Objectives, social share |
| Products | `/products/` | E-commerce listing | Merchandise intro, product links/prices |
| Stories | `/stories/` | Archive/listing | Stories intro, latest story cards/excerpts, Series teaser |
| Artist | `/artist/` | Form/content page | Artist contribution copy, artwork upload form, contest guidelines link |
| Career | `/career/` | Form/content page | Volunteer/internship copy, roles, qualifications, CV/cover-letter upload form |
| Contact Us | `/contact-us/` | Contact/form page | Phone, email, address/map link, message form, social links |
| Pitch Your Pal | `/pitch-your-pal-phnom-penh/` | Event/form page | Event pitch, guidelines, attendee form, friend-pitch form |
| Cart | `/cart/` | E-commerce cart | Empty cart state, return-to-shop link |
| Product category | `/product-category/uncategorized/` | Product archive | Sorting controls, product list, sidebar search/recent posts |
| Series | `/series/` | Video/playlist page | Series intro and five YouTube playlist links |
| Contest Guidelines | `/contest-guidelines/` | Legal/guidelines page | Color Outside the Lines guidelines and terms |
| Individual stories | `/post-12/`, `/story-11/`, `/story-10/`, `/story-9/`, `/story-8/` | Story/post | Long-form article content, source attribution |
| Product pages | `/product/...` | Product detail | Product title, image, price, description, metadata, add to cart, related products |

---

## Content Types

| Content type | Examples | V1 demo approach |
|---|---|---|
| Homepage sections | Platform intro, story mission, CTAs | Build as editorial landing page using `index.html` style |
| Static content pages | About, Contest Guidelines | Design reusable content-page layout |
| Story listing | Stories page, category/archive pages | Design story grid/list with cards and excerpts |
| Story detail | `story-*`, `post-12` | Design article template with strong typography |
| Series/video links | `/series/` | Design playlist/card grid with external YouTube CTAs |
| Product listing | `/products/`, product category | Visual product grid demo now; production later |
| Product detail | paintings/books/registration fee | Visual product detail demo now; WooCommerce validation later |
| Forms | Artist, Career, Contact, Pitch Your Pal | Design form UI now; real submission later |
| Cart/checkout | Cart captured, checkout not captured | Do not treat as production-safe until plugin/admin access |

---

## Reusable Components Required

### Global

- Primary header navigation
- Cart indicator/link
- Mobile nav
- Footer navigation
- Social link group
- Page hero/header block
- Section label/eyebrow
- Primary and secondary buttons

### Editorial/story

- Story card with title, excerpt, optional image
- Story archive/list grid
- Long-form article template
- Source attribution block
- Social share block
- Series/video playlist card

### Static/content

- Mission/vision/objective sections
- Prose content layout
- Feature/benefit grid
- Guideline/terms content block

### Forms

- Text input
- Email input
- Phone input
- Textarea
- Select
- Checkbox
- File upload field
- Submit button
- Required-field indicators
- Demo-only disabled/safe state where production handling is unknown

### E-commerce

- Product card
- Product grid/list
- Product detail layout
- Price display
- Product metadata table/list
- Quantity control
- Add-to-cart button
- Related products
- Empty cart state
- Return-to-shop CTA

---

## Forms

| Page | Form | Fields captured | V1 status | V2 blocker |
|---|---|---|---|---|
| Artist | Artwork contribution | Text area/character count, upload work, terms checkbox, contribute button | Design visual demo | Unknown form plugin, upload handling, destination |
| Career | Application | Text area/character count, upload CV, upload cover letter, terms checkbox, submit button | Design visual demo | Unknown form plugin, file upload handling, destination |
| Contact Us | Message form | Name, phone, email, subject, message, terms checkbox, submit | Design visual demo | Unknown form plugin and delivery behavior |
| Pitch Your Pal | Register to Attend | Full name, email, phone/Telegram/WhatsApp, age range, gender, relationship status, code-of-conduct checkboxes, Register Me ($5) | Design visual demo | Registration/payment workflow unknown |
| Pitch Your Pal | Pitch a Friend | Your name/email, friend name, why date them, friend full name, age, gender, occupation, relationship, consent checkboxes, submit | Design visual demo | Submission workflow unknown |

---

## E-commerce

Public crawl strongly suggests WooCommerce or WooCommerce-like behavior, but this is not verified without admin access.

### Product Listing

Products page lists:

- Evening on Riverside — 450$
- Love in Saigon Book — 10$
- Registration Fee — 5$
- Temple of Dreams — 720$
- ព្រោះស្នេហា (Because of Love) — 450$

Product category page includes:

- Breadcrumbs
- Result count
- Sorting controls
- Product list
- Sidebar search
- Recent posts

### Product Detail Fields

Captured product detail structure:

- Product title
- Breadcrumbs
- Product image for most products
- Price
- Description
- Artist / medium / dimensions for artwork items
- Quantity control
- Add to cart button
- Category
- Related products

### Product Media Captured

- `Phnom-Penh-night.jpg` for Evening on Riverside
- `Love-in-Saigon.jpg` for Love in Saigon Book
- `A-JOURNEY-THROUGH-CAMBODIAN-scaled.jpg` for Temple of Dreams
- `Pov-Meas.jpg` for ព្រោះស្នេហា (Because of Love)

### V2 Blockers

- Active e-commerce plugin unknown
- Product data model unknown
- Cart behavior not validated
- Checkout page not captured
- Payment/shipping/account behavior unknown
- WooCommerce page assignments unknown
- Add-to-cart notices and errors unknown

---

## Media and External Links

### Product Images

The crawl captured product image URLs, enough for demo placeholders or remote references.

### YouTube Series Links

Series page links to five YouTube playlists:

- Dakshin Restaurant
- Uy Ratha
- Ley Oudom
- E Chen
- Duck Roasted House

### Contact Links

- Phone links are present
- Email: `info@humansofphnompenh.com`
- Address: Morgan Tower, Street Sopheak Mongkol Rd, Koh Pich, Phnom Penh 120101
- Map link to Google Maps

### Social Links

Global footer:

- Facebook
- Instagram
- LinkedIn
- Telegram

Some page-level share/follow sections include X/Twitter and placeholder `#` links.

---

## Known Current-Site Issues

These are observed from the crawl and should be cleaned up in the redesign:

- Header/footer content appears duplicated in crawl output.
- Footer social labels appear as repeated `Follow` text, likely icon/text extraction issue or broken icon handling.
- Raw media/file labels appear on homepage: `HUMANS-OF-PHNOM-PENH-3-1`, `Asset 2`.
- Current site mixes storytelling platform, e-commerce, artist submissions, careers, and event registration without a clearly separated information architecture.
- Some copy has spelling/formatting issues, e.g. `Phnompenh`, duplicated labels, compressed select values, and malformed text around consent/terms.
- Products/cart are present, but checkout/payment behavior is not visible in the crawl.

---

## V1/V2 Classification

| Page/feature | URL | Classification | Reason |
|---|---|---|---|
| Home | `/` | Safe for UI demo now | Homepage design direction exists in `index.html`; content captured |
| About Us | `/about-us/` | Safe for UI demo now | Static content captured |
| Stories listing | `/stories/` | Safe for UI demo now | Story list/excerpts captured |
| Story detail | `/story-*`, `/post-12/` | Safe for UI demo now | Long-form post structure captured |
| Series | `/series/` | Safe for UI demo now | External playlist links captured |
| Contest Guidelines | `/contest-guidelines/` | Safe for UI demo now | Static legal/guideline content captured |
| Contact page visual | `/contact-us/` | Design now, functionality later | Fields/content captured, submission unknown |
| Artist page visual | `/artist/` | Design now, functionality later | Upload form captured, plugin/upload handling unknown |
| Career page visual | `/career/` | Design now, functionality later | Upload form captured, plugin/upload handling unknown |
| Pitch Your Pal visual | `/pitch-your-pal-phnom-penh/` | Design now, functionality later | Forms and event content captured, registration/payment workflow unknown |
| Products listing visual | `/products/` | Design now, production later | Product content captured, plugin behavior unknown |
| Product detail visual | `/product/...` | Design now, production later | Product fields captured, WooCommerce behavior unknown |
| Cart visual | `/cart/` | Needs admin/plugin access before production | Empty state captured only; real cart/session behavior unknown |
| Checkout/payment | unknown | Needs admin/plugin access before production | Not captured |
| Form submissions | multiple | Needs admin/plugin access before production | Plugin/destination/validation unknown |

---

## Conclusion

The crawl is enough to design a complete V1 UI/UX demo because it captures page inventory, content types, required components, product examples, story examples, and form field structure.

The crawl is not enough to certify production behavior. V2 must wait for WP admin access so the real content export, plugins, WooCommerce settings, form plugins, checkout, and admin page assignments can be inspected and mirrored locally.
