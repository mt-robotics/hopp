# Demo Design Plan

Plan for the V1 local WordPress UI/UX demo.

Inputs:

- `DESIGN.md` — visual system and homepage direction
- `docs/current_site_audit.md` — public site page/content/component audit
- `index.html` — existing homepage prototype/reference
- `.claude/rules/frontend-standards.md` and `.claude/rules/ux-standards.md` — implementation standards

---

## Table of Contents

1. [Goal](#goal)
2. [Design Direction](#design-direction)
3. [Information Architecture](#information-architecture)
4. [Page Plans](#page-plans)
5. [Reusable Components](#reusable-components)
6. [Form Strategy](#form-strategy)
7. [E-commerce Demo Strategy](#e-commerce-demo-strategy)
8. [Responsive and Accessibility Rules](#responsive-and-accessibility-rules)
9. [Demo Boundaries](#demo-boundaries)
10. [Implementation Order](#implementation-order)

---

## Goal

Build a polished local WordPress theme demo that can be shown to the team before live WP admin access is granted.

This is a **UI/UX approval demo**, not production integration. It should communicate the future site's visual direction, content organization, component behavior, and storytelling tone while making plugin-dependent features visually understandable but non-final.

---

## Design Direction

### Chosen Style

Use a warm editorial magazine style.

The site should feel like:

- cultural publication
- storytelling archive
- community platform
- small creative studio
- curated art/shop experience

It should not feel like:

- generic corporate landing page
- SaaS dashboard
- decorative portfolio with little content
- product-first e-commerce store
- playful event microsite as the dominant identity

### Visual Rules

From `DESIGN.md`:

- Human before product: stories, faces, and cultural context lead.
- Restraint over decoration: typography, whitespace, and earthy color carry the design.
- Photography-forward layouts: images should be treated as primary content, not decoration.
- Sharp corners: no rounded cards/buttons/inputs except circular social buttons if needed.
- Minimal shadows: depth comes from image scale, overlays, contrast, and section rhythm.
- Two fonts only: Playfair Display for headings; Montserrat for UI/body.
- 8pt spacing grid.
- Earthy palette: brown, beige, rust, cream, sand, terracotta, paper.

### UX Positioning

The homepage and top-level pages should guide users through three main jobs:

1. Understand what Humans of Phnom Penh is.
2. Explore stories, artists, series, products, and events.
3. Contact, contribute, apply, register, or shop.

The V1 demo should prioritize clarity over cleverness. Navigation must remain predictable because the site has several content types.

---

## Information Architecture

Primary nav for the demo:

| Label | URL | Demo priority | Notes |
|---|---|---:|---|
| Home | `/` | High | Editorial landing page |
| About Us | `/about-us/` | High | Mission, vision, objectives |
| Products | `/products/` | Medium | Visual store demo; production later |
| Stories | `/stories/` | High | Story archive and content engine |
| Artist | `/artist/` | High | Contribution page with demo upload form |
| Career | `/career/` | Medium | Volunteer/internship page with demo form |
| Contact Us | `/contact-us/` | High | Contact info and demo message form |
| Pitch Your Pal | `/pitch-your-pal-phnom-penh/` | Medium | Event page; include after core pages |
| Cart | `/cart/` | Low for V1 | Visual empty cart only until plugin validation |

Footer:

- Home
- About Us
- Stories
- Get in Touch
- Social links: Facebook, Instagram, LinkedIn, Telegram
- Copyright and credit
- Privacy Policy / Terms and Conditions text or placeholders

---

## Page Plans

### Home

Purpose: make the brand understandable immediately and lead users into stories, products, artist contribution, and contact.

Sections:

- Full-bleed/editorial hero from existing homepage direction
- Intro statement about platform mission
- Featured stories grid
- Products/art teaser
- Artist contribution teaser
- Series/video teaser
- Contact/CTA band

Design notes:

- Use `display` Playfair headline.
- Use dark brown hero with image overlay once suitable image assets exist.
- Keep next section visible below hero on desktop and mobile.
- Avoid marketing-heavy hero copy; brand/name should be the first signal.

### About Us

Purpose: explain identity, mission, vision, and objectives.

Sections:

- Page hero: About Us
- Intro prose block
- Mission / Vision split section
- Objectives as editorial numbered statements or compact text blocks
- Storytelling/community CTA

Design notes:

- This page is content-heavy but should not become a wall of text.
- Use alternating `paper` and `cream` sections.
- Objectives should be scannable.

### Stories

Purpose: browse latest stories and enter Series.

Sections:

- Page hero: Stories
- Short intro
- Latest stories grid/cards
- Show more stories CTA
- Series teaser

Design notes:

- Story cards need title, excerpt, optional image, and link.
- Card title uses tracked Playfair `h3` style.
- Mobile grid collapses to one column.

### Single Story

Purpose: read one long-form story.

Sections:

- Article header with title and optional source/date metadata
- Optional featured image
- Long-form body
- Source attribution
- Share/return to stories CTA

Design notes:

- Body line length must stay 60-80 characters.
- One `<h1>` only; article subheadings use proper hierarchy.
- The article should feel like a magazine story, not a blog dump.

### Series

Purpose: present video/story series and link to YouTube playlists.

Sections:

- Page hero: Series
- Intro statement
- Playlist card grid for Dakshin Restaurant, Uy Ratha, Ley Oudom, E Chen, Duck Roasted House
- Share CTA

Design notes:

- Treat each playlist as a content card, not a plain link list.
- CTA label should be clearer than "Click Here" in the demo, e.g. "Watch Playlist".

### Products

Purpose: show that products/art/books exist and give the team a visual store direction.

Sections:

- Page hero: Products
- Merchandise/art intro prose
- Product grid
- Optional note/CTA about supporting local stories

Product card fields:

- image or placeholder
- title
- price
- category/type if known
- short description if available
- CTA: View Product

Design notes:

- Products should be framed as cultural objects and story artifacts, not generic shop items.
- V1 buttons can link visually; production add-to-cart behavior waits for plugin access.

### Product Detail

Purpose: demonstrate a premium product/art detail page direction.

Sections:

- Breadcrumbs, if hierarchy is useful
- Product image
- Product title
- Price
- Description
- Metadata: artist, medium, dimensions
- Quantity/add-to-cart visual control
- Related products

Design notes:

- Product detail should resemble a gallery/catalog page.
- Product image should be large and inspectable.
- Add-to-cart controls are visual demo only until WooCommerce is mirrored locally.

### Artist

Purpose: invite artists to contribute work and understand contest/contribution expectations.

Sections:

- Page hero: Calling All Artists
- Contribution options
- Link to contest guidelines
- Showcase artwork form UI
- Why contribute
- How to contribute
- Contact/contribute CTA

Design notes:

- Form must be clearly marked as demo/non-final if it cannot submit.
- File upload UI should be polished but not claim production behavior.

### Career

Purpose: recruit volunteers and interns.

Sections:

- Page hero: Careers
- Intro copy
- Benefits grid
- Volunteer opportunities
- Internship opportunities
- Qualifications
- Application form UI

Design notes:

- Benefits should be icon/text cards or a compact grid.
- Roles should be grouped clearly.
- Upload fields are visual only until form plugin is known.

### Contact Us

Purpose: provide direct contact info and a message form.

Sections:

- Page hero: Contact Us
- Contact info block: phone, email, address/map
- Message form UI
- Follow/social section

Design notes:

- Contact info should be easy to scan.
- Form labels must be above fields.
- The CTA band can use `terracotta`.

### Pitch Your Pal

Purpose: present an event and collect registration/pitch submissions.

Sections:

- Event hero: Pitch Your Pal
- Short pitch/value proposition
- Two CTAs: Register to Attend, Pitch a Friend
- What happens
- Why people love it
- How the night runs
- Good vibes only
- Pitch guidelines
- Register form UI
- Pitch a friend form UI

Design notes:

- This page can have slightly more event energy, but must still sit inside the HOPP editorial brand.
- Avoid making the whole site feel like this event.
- Forms are demo-only until registration/payment flow is known.

### Contest Guidelines

Purpose: present detailed contest rules and terms.

Sections:

- Page hero: Contest Guidelines
- Theme/objectives
- Key dates
- Eligibility/registration
- Artwork requirements
- Submission process
- Judging criteria
- Exhibition/awards
- Terms and conditions

Design notes:

- Use a readable legal/guideline layout with sticky/anchored section navigation only if simple.
- Long text should be grouped into sections with strong headings.

### Cart

Purpose: show empty cart state only for V1.

Sections:

- Page hero: Cart
- Empty cart state
- Return to shop CTA

Design notes:

- Do not build custom cart logic.
- Production cart/checkout waits for WooCommerce/plugin validation.

---

## Reusable Components

### Navigation

Requirements:

- Sticky header on scroll.
- Desktop nav visible.
- Mobile nav fixed/overlay behavior.
- Active state uses at least two visual cues.
- Cart link remains visible.
- Max 7-8 main nav items; if Pitch Your Pal overcrowds the nav, move it to a CTA/footer or secondary treatment.

### Page Hero

Fields:

- eyebrow/section label
- h1
- intro copy
- optional background/image
- optional CTA group

Usage:

- all top-level pages
- event page variant
- product/category variant if useful

### Editorial Section

Fields:

- label
- heading
- body text
- optional CTA

Usage:

- homepage intro
- about mission/vision
- product intro
- artist/career sections

### Cards

Card types:

- story card
- product card
- series card
- benefit/role card

Rules:

- No nested cards.
- Sharp corners.
- Stable image aspect ratios.
- Text must not overflow.
- Hover can scale image inside fixed card area; card dimensions must not shift.

### Forms

Rules from standards:

- Top labels only.
- No placeholder-only labels.
- Required fields clearly marked.
- Field errors next to fields.
- Minimum touch target 44px.
- V1 demo forms should not pretend to submit unless wired.

V1 demo behavior:

- Use disabled or clearly demo-safe submit behavior if needed.
- If buttons are active, they should show an inline demo notice rather than failing silently.

### Buttons

Types:

- primary: beige background, white text
- secondary/outline: transparent with underline or border treatment
- text CTA: editorial link with rust hover

Rules:

- Sharp corners.
- Letter spacing per `DESIGN.md`.
- Distinct hover, active, focus states.

---

## Form Strategy

V1 forms are visual demos.

Do:

- render the right fields
- make the layout polished
- show how upload/checkbox/select controls should look
- keep labels and required indicators clear

Do not:

- claim submissions work
- build custom backend handling
- simulate payment or upload behavior as if production-ready

V2 blockers:

- active form plugin
- file upload destination
- spam protection
- email/notification recipients
- validation rules
- payment/registration coupling for Pitch Your Pal

---

## E-commerce Demo Strategy

V1 goal: show a refined product/art-shopping visual direction.

V1 can include:

- product grid
- product cards
- product detail page design
- price display
- product metadata
- visual quantity/add-to-cart area
- empty cart state

V1 must not claim:

- cart works
- checkout works
- payment works
- inventory/order handling works

V2 requires:

- WP admin access
- plugin confirmation
- local WooCommerce/plugin mirror
- product import
- cart/add-to-cart testing
- checkout testing up to safe stopping point

---

## Responsive and Accessibility Rules

Apply during implementation:

- Mobile-first CSS.
- Minimum viewport: 320px.
- Body text minimum 16px on mobile.
- One `<h1>` per page.
- Heading levels must not skip.
- Use `<a>` for navigation and `<button>` for actions.
- All focusable elements need visible focus rings.
- Touch targets must be at least 44px.
- Images need explicit dimensions where possible.
- Static content renders immediately; no global skeletons for static pages.
- Navigation remains sticky/fixed according to viewport.
- Tables or dense legal content must scroll or reflow on mobile.

---

## Demo Boundaries

### Safe To Build Now

- full visual homepage
- page hero component
- header/footer/nav
- about page
- story archive
- story detail
- series page
- products visual listing
- product detail visual template
- artist/career/contact visual form pages
- Pitch Your Pal visual event page
- contest guidelines page
- empty cart visual state

### Must Wait For Admin/Plugin Access

- real WooCommerce/cart/checkout integration
- product import/mapping
- checkout/payment behavior
- contact form delivery
- upload form handling
- Pitch Your Pal registration/payment behavior
- live menu/page assignments
- production cache/SEO/plugin side effects

---

## Implementation Order

1. Update local WP content/menu for demo pages.
2. Finalize global header/footer/navigation styling.
3. Build reusable section/card/form/button CSS.
4. Build homepage sections from `index.html` style.
5. Build About page layout.
6. Build Stories archive and Single Story styling.
7. Build Series page.
8. Build Products visual listing and Product Detail demo.
9. Build Artist, Career, Contact form pages.
10. Build Pitch Your Pal event page if time allows.
11. Build Contest Guidelines readable layout.
12. Run manual responsive checks at desktop, tablet, and 320px mobile.
13. Record remaining V2 blockers before team presentation.

---

## Open Decisions

- Whether Pitch Your Pal belongs in primary nav or should become a featured CTA/secondary nav item.
- Whether Products should be visually framed as "Shop" or "Products" in the demo; current live nav says Products.
- Whether to use remote public product images in the demo or local placeholders until media import.
- Whether demo forms should have disabled submits or active submits with demo-only notices.
- Whether homepage hero uses a real image from current public media or a generated/placeholder image until official assets are provided.
