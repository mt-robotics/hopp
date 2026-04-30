# Gemini / NotebookLM Prompt: Humans of Phnom Penh Site Audit

Use this prompt to generate a structured audit of the public website before continuing the WordPress redesign demo.

```text
You are auditing the public website https://humansofphnompenh.com for a WordPress redesign project.

Goal:
Create a complete Markdown audit of every publicly accessible page, section, content type, navigation item, media item, form, button, link, product/e-commerce feature, and footer element.

Important:
Do not redesign anything. Do not summarize too aggressively. I need factual site structure and content so a developer/designer can rebuild a local WordPress UI/UX demo.

Audit requirements:

1. Crawl or inspect all publicly accessible pages from:
   - Header navigation
   - Footer navigation
   - Homepage links/buttons
   - Story links
   - Product links
   - Artist/contest links
   - Career/contact links
   - Legal links
   - Any visible internal links

2. For each page, document in this exact format:

## Page: [Page Title]
URL:
Page type:
- Homepage
- Static content page
- Story/post
- Product/e-commerce
- Form page
- Legal page
- Archive/listing page
- Other

Purpose:
Briefly state what this page is for.

Header:
- Logo/brand shown:
- Navigation items shown:
- Cart/search/account icons shown:
- Any active/current nav state:

Main content sections:
For each section, document:
### Section: [Section Name or inferred name]
- Heading:
- Subheading:
- Body text:
- Media:
  - Type: image / video / none
  - Description:
  - Alt text if available:
  - File name or URL if visible:
- Buttons/CTAs:
  - Label:
  - Destination URL:
  - Function:
- Forms:
  - Form name/purpose:
  - Fields:
  - Required fields:
  - Submit button label:
  - Confirmation/error behavior if visible:
- Links:
  - Label:
  - Destination URL:
- Notes:

Footer:
- Footer nav links:
- Social links:
- Contact info:
- Copyright text:
- Privacy/terms links:
- Newsletter/form if any:

Functional/dynamic behavior:
- E-commerce behavior:
- Cart behavior:
- Checkout behavior:
- Search behavior:
- Filters/sorting:
- Form submission:
- Embedded maps/videos:
- Any plugin-like behavior:
- Anything that probably depends on WordPress plugins:

Design/layout observations:
- Overall layout:
- Image placement:
- Card/grid/list patterns:
- Colors:
- Typography impression:
- Spacing:
- Mobile/responsive issues if visible:

Content extraction:
Copy the visible text content as accurately as possible. Preserve headings and section order.

Missing/uncertain:
List anything that could not be accessed, loaded, or verified.

3. After all pages, create these summary sections:

# Global Site Structure

## Header Navigation
List every header navigation item in order:
- Label:
- URL:
- Page type:
- Notes:

## Footer Structure
List all footer sections, links, social links, contact info, copyright, privacy/terms links.

## Reusable Components Found
List repeated components, for example:
- Hero section
- Story card
- Product card
- Contact form
- Artist submission form
- Contest guidelines block
- Footer social links
- Cart icon
For each component:
- Where it appears:
- Content fields:
- Media fields:
- Buttons/links:
- Functional behavior:

## Content Types
Classify all discovered content into:
- Pages
- Stories/posts
- Products
- Forms
- Legal pages
- Contest/event pages
- Other

## E-commerce Audit
If products/cart/checkout exist, document:
- Product listing URL:
- Product detail pages:
- Product fields shown:
- Add to cart behavior:
- Cart URL:
- Checkout URL:
- Payment/shipping fields visible:
- Account/login behavior:
- Plugin hints, if visible:

## Forms Audit
For every form:
- Page URL:
- Form purpose:
- Fields:
- Required fields:
- Submit button:
- Upload fields:
- Validation or confirmation messages:
- Likely plugin if detectable:

## Media Inventory
List all images/videos found:
- Page:
- Section:
- Media type:
- Description:
- URL or filename if available:
- Approximate role: hero, card image, logo, inline image, background, product image, etc.

## Internal Link Map
List all internal links discovered:
- Source page:
- Link label:
- Destination URL:
- Destination page type:

## Redesign Risk Classification
Classify each page as:

- Safe for UI demo now:
  Static content/story pages where placeholder content is enough.

- Design now, functionality later:
  Form pages, contact pages, artist submission pages.

- Needs admin/plugin access before production:
  Products, cart, checkout, account, payment, plugin-generated pages.

Use a table:
| Page | URL | Classification | Reason |

Output format:
- Markdown only.
- Use clear headings.
- Be detailed and factual.
- Do not invent missing content.
- If something cannot be accessed, mark it as "Unable to verify".
```
