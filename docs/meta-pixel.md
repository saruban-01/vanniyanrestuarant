# Meta Pixel

The Meta (Facebook) Pixel is **never loaded directly on the site**. The site has
no `fbq` calls anywhere. Instead:

1. The Pixel ID is stored in the database (`analytics_meta_pixel_id`) and managed
   from **Settings → Analytics & Marketing**.
2. When **Enable Meta Pixel** is on, the ID is exposed to the GTM container
   through the data layer (`vanniyan.meta_pixel_id`).
3. The official Meta Pixel tag is published **inside GTM** and reads the ID from
   that data layer variable. GTM decides when it fires, including consent.

## Validation

- Format: 13–17 digits only (`^\d{13,17}$`).
- Rejected otherwise (letters, spaces, HTML). A malformed ID never reaches the page.

## Setting up the Pixel

1. Create the Pixel in Meta Events Manager and copy its numeric ID.
2. In the admin panel, enable **Meta Pixel**, paste the ID, save.
3. In GTM:
   - Create a tag of type **Meta Pixel**, using the Pixel ID variable
     `vanniyan.meta_pixel_id`.
   - Trigger it on page view and on the standard + custom events you want
     (see `analytics-events.md`).
4. Verify in **GTM Preview** mode. The admin panel deliberately does **not**
   show a "verified" state — only the configured state.

## Consent

When consent management is enabled, the Meta Pixel tag must respect Consent Mode.
The data layer already carries `consent_update` with `ad_storage` /
`ad_user_data` / `ad_personalization` states; configure the Meta Pixel tag to
fire only when `ad_storage = granted`, or use a consent-based trigger. Marketing
events are also buffered client-side until the visitor grants marketing consent.

## Privacy notes

- No personally identifiable information is ever added to data layer events.
- The Pixel ID is a public identifier (it appears in page source); it is **not**
  treated as a secret, but it is also not logged anywhere except masked audit
  entries (`configured (…1234)`).