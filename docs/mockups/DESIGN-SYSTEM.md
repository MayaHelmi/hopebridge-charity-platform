---
name: HopeBridge
colors:
  surface: '#f8f9ff'
  surface-dim: '#d0dbed'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e6eeff'
  surface-container-high: '#dee9fc'
  surface-container-highest: '#d9e3f6'
  on-surface: '#121c2a'
  on-surface-variant: '#3d4947'
  inverse-surface: '#27313f'
  inverse-on-surface: '#eaf1ff'
  outline: '#6d7a77'
  outline-variant: '#bcc9c6'
  surface-tint: '#006a61'
  primary: '#00685f'
  on-primary: '#ffffff'
  primary-container: '#008378'
  on-primary-container: '#f4fffc'
  inverse-primary: '#6bd8cb'
  secondary: '#0058be'
  on-secondary: '#ffffff'
  secondary-container: '#2170e4'
  on-secondary-container: '#fefcff'
  tertiary: '#924628'
  on-tertiary: '#ffffff'
  tertiary-container: '#b05e3d'
  on-tertiary-container: '#fffbff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#89f5e7'
  primary-fixed-dim: '#6bd8cb'
  on-primary-fixed: '#00201d'
  on-primary-fixed-variant: '#005049'
  secondary-fixed: '#d8e2ff'
  secondary-fixed-dim: '#adc6ff'
  on-secondary-fixed: '#001a42'
  on-secondary-fixed-variant: '#004395'
  tertiary-fixed: '#ffdbce'
  tertiary-fixed-dim: '#ffb59a'
  on-tertiary-fixed: '#370e00'
  on-tertiary-fixed-variant: '#773215'
  background: '#f8f9ff'
  on-background: '#121c2a'
  surface-variant: '#d9e3f6'
typography:
  display:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '700'
    lineHeight: '1.1'
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.3'
  headline-sm:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: '1.4'
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: '1'
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: '1'
    letterSpacing: 0.05em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  container-max: 1280px
  gutter: 24px
  margin-desktop: 40px
  margin-mobile: 16px
  stack-sm: 8px
  stack-md: 16px
  stack-lg: 32px
---

## Brand & Style

The design system is engineered to facilitate trust, transparency, and operational efficiency for non-profit administrators and donors. The visual identity follows a **Corporate / Modern** aesthetic, prioritizing a "Safe" and reliable dashboard experience. It balances the high-stakes nature of charitable work with an approachable, emotionally warm interface.

The brand personality is authoritative yet empathetic. We use generous whitespace to reduce cognitive load, ensuring that complex data—such as beneficiary management and donation tracking—remains legible and non-intimidating. The style avoids visual clutter, favoring precise alignment and a clear information hierarchy to foster immediate confidence in the platform's stability.

## Colors

The palette is anchored by a **Deep Teal** primary color, chosen to evoke growth and institutional trust. **Warm Blue** serves as the secondary color, reinforcing the platform's reliability. For high-conversion actions such as "Donate Now" or urgent calls to action, a **Soft Orange** accent provides necessary contrast without appearing aggressive.

The background uses a subtle **Off-white** to reduce screen glare, while UI surfaces utilize pure white to create a clear "layering" effect. Text is rendered in **Dark Charcoal** to maintain high accessibility standards (WCAG AA/AAA) and ensure readability across all demographic groups. Functional colors for success, warning, and error states follow industry-standard conventions to ensure intuitive navigation.

## Typography

This design system utilizes **Inter** for all roles, leveraging its exceptional legibility and utilitarian character. The type scale is built on a 16px base to accommodate professional users who interact with the platform for extended periods.

Headlines use tighter letter spacing and heavier weights to establish a firm visual anchor. Body copy is optimized with a 1.6 line-height to maximize readability in data-heavy views. For mobile devices, `headline-lg` should scale down to 28px to prevent excessive line-breaking, while body sizes remain constant to ensure accessibility.

## Layout & Spacing

The layout philosophy follows a **12-column fluid grid** for the main dashboard content, housed within a fixed maximum container width of 1280px for desktop. This ensures that data tables and KPI cards do not become overly wide on ultra-large monitors.

Spacing is governed by an **8px linear scale**. Use `stack-md` (16px) for internal component padding and `stack-lg` (32px) for vertical separation between distinct sections. On mobile, the side margins compress to 16px, and complex grids should reflow into a single-column stack. Sidebars in the dashboard view should remain fixed at 280px on desktop to provide a consistent navigation anchor.

## Elevation & Depth

Elevation in the design system is communicated through **Tonal Layers** and **Ambient Shadows**. This creates a sense of "physicality" where the background feels like a base floor, and cards feel like interactive objects placed upon it.

- **Level 0 (Background):** #F9FAFB. Used for the main canvas.
- **Level 1 (Surfaces):** Pure white (#FFFFFF). Used for cards, navigation bars, and inputs. These surfaces use a very soft, diffused shadow (0px 4px 12px rgba(0, 0, 0, 0.05)).
- **Level 2 (Overlays):** Used for dropdowns and modals. These require higher contrast shadows (0px 8px 24px rgba(0, 0, 0, 0.10)) to separate them from the Level 1 surfaces below.
- **Interactive Depth:** On hover, cards should subtly lift by increasing shadow spread or adding a 1px border in the primary color to indicate focus.

## Shapes

The design system utilizes **Rounded** (Level 2) geometry to soften the professional interface and make it feel more welcoming. 

- **Standard (8px):** Applied to buttons, input fields, and standard cards.
- **Large (16px):** Applied to main dashboard KPI containers and large program featured cards.
- **Pill:** Reserved exclusively for status badges (e.g., "Approved") and tags to distinguish them from interactive buttons.
- **Progress Bars:** Should always use a fully rounded (pill) cap to emphasize fluidity and completion.

## Components

### Buttons
- **Primary:** Deep Teal background, white text. Used for main actions (e.g., "Save Changes").
- **Secondary:** Warm Blue background, white text. Used for supporting actions.
- **Accent:** Soft Orange background, white text. Reserved for "Donate Now" or "Emergency Appeal."
- **Ghost:** No background, primary color text. Used for "Cancel" or "Go Back."

### Cards & Data
- **Charity Program Cards:** Must include a horizontal progress bar (Teal fill on Gray track) and a "Percentage Raised" label in `label-md`.
- **KPI Cards:** Display a large number in `headline-lg` with a secondary `body-sm` label. Use a subtle top-border in the primary color for visual grouping.
- **Data Tables:** Use a clean, borderless style with a subtle horizontal rule between rows. The header row should have a light gray background (#F3F4F6) and use `label-sm` typography.

### Form Inputs
- **Text/Select:** 8px rounded corners, 1px light gray border. On focus, the border transitions to Deep Teal with a soft glow.
- **Badges:** Use low-saturation backgrounds with high-saturation text (e.g., Success: Light Green bg / Dark Green text) for high legibility and a modern look.

### Navigation
- **Public Nav:** Transparent or white background with centered links and a prominent "Donate" button in the Accent color.
- **Dashboard Nav:** Vertical sidebar with icons. Active states are indicated by a 4px vertical "tab" on the left and a subtle light-teal background highlight.