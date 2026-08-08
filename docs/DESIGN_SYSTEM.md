# Hospital CMMS Design System

## Purpose

This document is the visual foundation for Hospital CMMS. It makes future UI work consistent without redesigning every page at once.

## Design direction

- Modern healthcare SaaS: calm, bright, trustworthy, spacious, and professional.
- Inspired by the restraint of Apple, Linear, Stripe Dashboard, and Notion.
- Prioritize clarity and accessibility over decoration.
- Avoid neon colors, gaming aesthetics, heavy gradients, and low-contrast glass effects.

## Technology

- Laravel Blade
- Tailwind CSS 3 (the version currently installed in this repository)
- Inline SVG icons using a Lucide-style 24px outline treatment. No icon package has been added.

## Tokens

Defined in `resources/css/app.css`.

| Token | Value | Use |
| --- | --- | --- |
| `--cmms-bg` | `#F5F7FB` | Application background |
| `--cmms-primary` | `#4F7CFF` | Primary actions and active states |
| `--cmms-success` | `#22C55E` | Success status |
| `--cmms-warning` | `#F59E0B` | Warning status |
| `--cmms-danger` | `#EF4444` | Destructive actions and error status |
| `--cmms-radius-card` | `24px` | Cards, panels, and floating surfaces |
| `--cmms-radius-control` | `14px` | Buttons and inputs |

## Reusable classes

These styles are ready for deliberate, page-by-page adoption. They are not applied to existing CRUD pages in this first pass.

| Class | Use |
| --- | --- |
| `.ds-card` | Glass surface for a content card |
| `.ds-topbar` | Floating topbar surface |
| `.ds-button-primary` | Main positive action |
| `.ds-button-secondary` | Neutral action |
| `.ds-button-danger` | Destructive action |
| `.ds-field` | Input, select, or textarea surface |
| `.ds-table` | Rounded, lightly interactive table container |

## Sidebar

The sidebar is the visual reference component for v1:

- Fixed-width floating glass panel with a 24px radius.
- Restrained blue active state; no gradients or bright neon effects.
- 18px outline icons with labels.
- User profile surface at the bottom.
- Existing routes and navigation order remain unchanged.

## Dashboard reference card

Only the first dashboard stat card uses `.dashboard-stat--reference`. Its frosted surface, 24px radius, soft shadow, and subtle hover elevation are the reference for future cards.

## Adoption rule

When updating a page later, reuse the classes and tokens above. Do not introduce a competing card, button, form, table, color, radius, or shadow system without an explicit design-system update.

## Accessibility

- Keep text and controls legible against glass surfaces.
- Use color as a supporting signal, never the only signal.
- Preserve visible focus states for keyboard users.
- Prefer restrained motion; transitions should be short and non-essential.
