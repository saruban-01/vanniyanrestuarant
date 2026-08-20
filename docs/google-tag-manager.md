# Google Tag Manager (GTM)

## Overview

Google Tag Manager is the **single tag layer** for the public Vanniyan site. GA4,
Meta Pixel, and any future marketing/analytics tags are published inside a GTM
container — never hard-coded into the site. This keeps tracking configuration
entirely in the hands of the admin panel and lets tags change without redeploys.

- Admin panel: **Settings → Analytics & Marketing** (`/vanniyan-control/settings/analytics`)
- IDs live in the `restaurant_settings` table (`analytics_gtm_container_id`), not
  in code or environment files.

## How the container is injected

The official GTM head snippet is rendered by
`resources/views/components/analytics/gtm.blade.php` inside the `<head>` of the
public layout (`components/layouts/app.blade.php`), as high as possible, before
SEO meta tags. The `<noscript>` fallback is injected immediately after `<body>`
by `components/analytics/gtm-noscript.blade.php`.

Nothing is rendered at all unless **both** of these are true:

1. `analytics_gtm_enabled` is on, **and**
2. `analytics_gtm_container_id` is a valid `GTM-` ID.

### Validation

- Format: `GTM-` followed by 4–15 uppercase letters/digits (`^GTM-[A-Z0-9]{4,15}$`).
- Anything else — lowercase, spaces, HTML, URLs — is rejected. A malformed ID
  never reaches the page (`AnalyticsService::gtmContainerId()` returns `''`).

## Data layer configuration

Before the GTM snippet loads, the site pushes:

1. **`window.vanniyanConfig`** — runtime flags consumed by `resources/js/analytics.js`
   (`events_enabled`, `consent_enabled`, `environment`, `test_mode`, `currency`).
2. **Consent Mode defaults** (when consent management is on) — `ad_storage`,
   `analytics_storage`, etc. all `denied` except necessary/security categories.
3. **A `vanniyan` data layer object** with `environment`, `currency`,
   `meta_pixel_id` (when the Pixel is enabled), and the same flags. This is the
   controlled channel through which the Meta Pixel ID reaches the container.

Use these variables in GTM, e.g. a Meta Pixel tag with the Pixel ID read from
`vanniyan.meta_pixel_id`.

## Consent Mode

When **Enable consent management** is on:

- Non-essential storage defaults to `denied` **before** GTM loads.
- The visitor sees the consent banner (`components/analytics/consent.blade.php`)
  with three choices: **Accept All**, **Analytics Only**, **Necessary Only**.
- Choosing pushes `consent_update` (GTM Consent Mode v2 keys) and `consent_choice`,
  then flushes any buffered marketing events.
- The client (`resources/js/analytics.js`) buffers **marketing**-category events
  until marketing consent is granted, so no ad data reaches GTM prematurely.

When consent management is **off**, tags fire without a prompt — make sure this
matches the privacy notice shown to visitors.

## Setting up a container

1. Create a container at <https://tagmanager.google.com>.
2. Paste the `GTM-…` ID into the panel and enable GTM.
3. Use **Test Configuration** for a summary (it never claims a verified
   connection — real verification happens in GTM Preview mode).
4. Publish tags: GA4 configuration + event tags, the Meta Pixel tag (see
   `meta-pixel.md`), and any conversion tags. Events are named in
   `analytics-events.md`.

## Environment & test mode

- **Test mode** adds `debug_mode: true` to every data layer payload so test
  traffic is identifiable in GA4 debug view / GTM preview.
- Every payload carries `vanniyan.environment` (`production`, `local`, `testing`).
  Do not use test mode in production.