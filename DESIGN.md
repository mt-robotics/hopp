---
version: alpha
name: Humans of Phnom Penh
description: >
  Design system for the Humans of Phnom Penh website redesign.
  A content-as-a-service studio rooted in Cambodian culture —
  warm, editorial, human-centric. Visual language draws from
  earthy terracottas, sandy beiges, and deep browns.

colors:
  # Primary brand palette
  brown: "#33231a"
  beige: "#9c8469"
  rust: "#c16e4d"
  cream: "#f4f1ea"

  # Section backgrounds (role-based)
  sand: "#a68a61"        # warm intro / tagline section
  terracotta: "#c47254"  # contact / CTA section
  paper: "#fcfbf9"       # soft alternating section background

  # Footer
  footer-surface: "#a35d41"
  footer-bar: "#8e4c32"

  # Neutrals
  white: "#ffffff"
  gray-text: "#374151"
  gray-border: "#e5e7eb"
  gray-muted: "#6b7280"

typography:
  display:
    fontFamily: '"Playfair Display", serif'
    fontSize: clamp(3rem, 8vw, 6rem)
    fontWeight: 600
    lineHeight: "1.1"
  h1:
    fontFamily: '"Playfair Display", serif'
    fontSize: 2.5rem
    fontWeight: 700
    lineHeight: "1.2"
  h2:
    fontFamily: '"Playfair Display", serif'
    fontSize: 2rem
    fontWeight: 600
    lineHeight: "1.3"
  h3:
    fontFamily: '"Playfair Display", serif'
    fontSize: 1.125rem
    fontWeight: 400
    lineHeight: "1.4"
    letterSpacing: "0.1em"
  section-label:
    fontFamily: '"Playfair Display", serif'
    fontSize: 1.5rem
    fontWeight: 500
    letterSpacing: "0.25em"
  body:
    fontFamily: '"Montserrat", sans-serif'
    fontSize: 1rem
    fontWeight: 400
    lineHeight: "1.625"
  body-sm:
    fontFamily: '"Montserrat", sans-serif'
    fontSize: 0.875rem
    fontWeight: 500
    lineHeight: "1.625"
  caption:
    fontFamily: '"Montserrat", sans-serif'
    fontSize: 0.625rem
    fontWeight: 400
    letterSpacing: "0.05em"
  nav:
    fontFamily: '"Montserrat", sans-serif'
    fontSize: 0.875rem
    fontWeight: 500
    letterSpacing: "0.1em"
  button:
    fontFamily: '"Montserrat", sans-serif'
    fontSize: 0.875rem
    fontWeight: 500
    letterSpacing: "0.2em"

rounded:
  none: "0px"
  full: "9999px"

spacing:
  xs: 8px
  sm: 16px
  md: 24px
  lg: 32px
  xl: 48px
  2xl: 64px
  3xl: 96px

components:
  nav-primary:
    backgroundColor: "transparent"
    textColor: "{colors.white}"
  nav-link:
    textColor: "{colors.white}"
    typography: "{typography.nav}"
  button-primary:
    backgroundColor: "{colors.beige}"
    textColor: "{colors.white}"
    typography: "{typography.button}"
    padding: "12px 40px"
  button-outline:
    backgroundColor: "transparent"
    textColor: "{colors.white}"
    typography: "{typography.button}"
    padding: "16px 0"
  section-title:
    textColor: "{colors.beige}"
    typography: "{typography.section-label}"
  hero:
    backgroundColor: "{colors.brown}"
    textColor: "{colors.white}"
  intro-band:
    backgroundColor: "{colors.sand}"
    textColor: "{colors.white}"
  portfolio-card:
    backgroundColor: "{colors.white}"
    textColor: "{colors.brown}"
  story-card:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.brown}"
  contact-section:
    backgroundColor: "{colors.terracotta}"
    textColor: "{colors.white}"
  footer:
    backgroundColor: "{colors.footer-surface}"
    textColor: "{colors.white}"
  footer-bar:
    backgroundColor: "{colors.footer-bar}"
    textColor: "{colors.white}"
  tag-badge:
    backgroundColor: "{colors.white}"
    textColor: "{colors.gray-muted}"
    typography: "{typography.caption}"
  form-input:
    backgroundColor: "{colors.white}"
    textColor: "{colors.gray-text}"
---

## Overview

Humans of Phnom Penh is a cultural storytelling and content production studio based in Phnom Penh, Cambodia. The visual identity must feel editorial and warm — closer to a high-end magazine than a tech product.

**Design principles:**
- Human before product. Faces and stories lead every section.
- Restraint over decoration. White space, serif type, and earthy color do the work.
- Warmth through texture. Overlays, tonal gradients, and photography-forward layouts.

**Fonts** are loaded from Google Fonts:
- `Playfair Display` — headings, logo, section labels
- `Montserrat` (300, 400, 500, 600) — body, navigation, buttons, captions

---

## Colors

| Token | Hex | Role |
|---|---|---|
| `brown` | `#33231a` | Primary dark — body text on light, hero overlay |
| `beige` | `#9c8469` | Brand accent — section labels, buttons, links |
| `rust` | `#c16e4d` | Interactive accent — hover states, focus rings |
| `cream` | `#f4f1ea` | Light background option |
| `sand` | `#a68a61` | Intro / tagline band background |
| `terracotta` | `#c47254` | Contact / CTA section background |
| `paper` | `#fcfbf9` | Alternating soft section background |
| `footer-surface` | `#a35d41` | Footer main background |
| `footer-bar` | `#8e4c32` | Bottom copyright bar |

**Image fallback colors** (not brand tokens — only for broken-image states):
- Story card 2: `#7ba07e` (sage green)
- Story card 3: `#1f607c` (dark teal)

---

## Typography

Two typefaces only. Playfair Display carries all headings and display text. Montserrat handles all UI and body copy.

| Token | Family | Size | Weight | Notes |
|---|---|---|---|---|
| `display` | Playfair Display | clamp(3rem, 8vw, 6rem) | 600 | Hero H1 only |
| `h1` | Playfair Display | 2.5rem | 700 | Page titles |
| `h2` | Playfair Display | 2rem | 600 | Section headings |
| `h3` | Playfair Display | 1.125rem | 400 | Card titles, uppercase + tracked |
| `section-label` | Playfair Display | 1.5rem | 500 | `letter-spacing: 0.25em`, uppercase |
| `body` | Montserrat | 1rem | 400 | Default prose |
| `body-sm` | Montserrat | 0.875rem | 500 | Card captions, taglines |
| `caption` | Montserrat | 0.625rem | 400 | Tags, badges, fine print |
| `nav` | Montserrat | 0.875rem | 500 | Navigation links |
| `button` | Montserrat | 0.875rem | 500 | `letter-spacing: 0.2em`, uppercase |

---

## Layout

**Max-width containers:**
| Name | Value | Used for |
|---|---|---|
| `prose` | 48rem (max-w-3xl) | Forms, centered text blocks |
| `content` | 56rem (max-w-4xl) | Intro statement |
| `wide` | 80rem (max-w-7xl) | Portfolio grid, stories grid, nav |

**Grid:**
- Portfolio: 3-column, `gap-12`, collapses to 1-column on mobile
- Stories: 3-column, `gap-10`, collapses to 1-column on mobile

**Breakpoints** (Tailwind defaults):
- `sm`: 640px
- `md`: 768px — primary responsive switch point used in this design
- `lg`: 1024px
- `xl`: 1280px

**Spacing scale:** 8pt grid. All padding/margin values are multiples of 8px. Section vertical padding: `py-24` (96px).

---

## Elevation & Depth

Minimal use. Depth is achieved through image overlays, not shadows.

| Layer | Technique |
|---|---|
| Hero overlay | `bg-black/40` on full-bleed image |
| Button hover | `hover:scale-105` transform |
| Card hover | `group-hover:scale-105` on inner image |
| No box-shadows | Intentional — flat editorial aesthetic |

---

## Shapes

Sharp corners throughout. No border-radius on cards, buttons, or inputs. This reinforces the editorial, non-playful character of the brand.

| Token | Value | Usage |
|---|---|---|
| `none` | `0px` | All cards, buttons, inputs, containers |
| `full` | `9999px` | Social media icon buttons only |

---

## Components

### Navigation

- Position: `absolute top-0`, full-width overlay on hero, `z-20`
- Logo: Playfair Display, bold, `tracking-widest`, uppercase, two-line ("Humans of / Phnom Penh")
- Links: `Home · About Us · Products · Stories · Artist · Career · Contact Us`
- Cart icon: visible on desktop alongside nav links
- CTA button: "Free Quote" — `bg-beige/80`, no border-radius
- Hover effect: 1px white underline animates from left to right (`width: 0 → 100%`, 0.3s ease)
- Mobile: hamburger collapses all nav links; cart remains visible

### Hero

- Full viewport height (`h-screen`, min `700px`)
- Full-bleed background image, `object-cover`
- `bg-black/40` overlay for text contrast
- Centered content: display H1 + primary CTA button
- CTA: "WORK WITH US" — `bg-beige/90`, uppercase, `letter-spacing: 0.2em`

### Intro Band

- `bg-sand`, `py-24`, `text-center`, `text-white`
- H2 in Playfair Display (3xl–4xl)
- Subtext in Montserrat light, `opacity-90`, max-w-2xl centered

### Portfolio Grid

- Section label: "OUR WORK" — `section-label` token, `text-beige`, centered
- 3-column grid, each card: `aspect-[4/5]` image + title + tag badges
- Image zoom on hover: `group-hover:scale-105`, duration 700ms
- Tags: `caption` token, `border border-gray-200`, uppercase, no fill

### Story Card

- Section label: "LATEST STORIES"
- `aspect-video` image + short caption below (no title, no tags)
- Section background: `paper`

### Contact / CTA Section

- `bg-terracotta`, full-width
- H2 "Start a Project" in Playfair Display
- Form: Name + Email (2-col grid) + Project Details textarea
- Submit: full-width outline button (`border-2 border-white/40`), hover fills white with rust text
- Social icons: circular outline buttons (`rounded-full`, `border border-white/30`)
- Contact info below icons: phone + email, `tracking-widest`, `opacity-80`

### Footer

- Two-zone:
  - Upper: `bg-footer-surface`, copyright left + nav links right
  - Lower bar: `bg-footer-bar`, Privacy Policy + Terms
- All text: `caption` token, `tracking-widest`, uppercase, `opacity-80`

---

## Do's and Don'ts

**Do:**
- Use Playfair Display for all headings — never Montserrat for headings
- Use `section-label` token (tracked, uppercase, beige) for every section title
- Let photography breathe — avoid overcrowding images with overlaid text
- Keep buttons sharp-cornered and uppercase
- Use `py-24` consistently for all major section vertical padding
- Use `sand`, `terracotta`, and `paper` for section backgrounds in alternating rhythm

**Don't:**
- Add border-radius to cards, buttons, or form inputs
- Use hex values directly in component files — always reference a token
- Use more than 2 typefaces or introduce new font weights beyond the declared set
- Add box-shadows — depth comes from color and imagery, not shadows
- Use `dangerouslySetInnerHTML` anywhere without DOMPurify
- Place navigation as `position: static` — it will scroll out of view on the hero
