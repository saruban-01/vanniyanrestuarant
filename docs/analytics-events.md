# Analytics Events

All events flow through a single controlled client
(`resources/js/analytics.js`) into the GTM data layer. The site never talks to
Google or Meta directly. Events are only emitted when **event tracking** is
enabled **and** the relevant group toggle is on.

Every payload carries a `vanniyan` envelope with:

- `consent_category` — `analytics` or `marketing`
- `environment` — `production` / `local` / `testing`
- `currency` — `LKR`
- `debug_mode` — true in test mode or non-production environments

## Event groups

Toggled from the panel under **Settings → Analytics & Marketing → Event tracking**.

| Group            | Events                                                        |
| ---------------- | ------------------------------------------------------------- |
| Menu views       | `view_menu`, `view_item`                                      |
| Cart activity    | `add_to_cart`, `remove_from_cart`, `view_cart`                |
| Takeaway orders  | `begin_checkout`, `purchase`                                   |
| Table bookings   | `booking_started`, `booking_submitted`, `booking_confirmed`    |
| Venue bookings   | `venue_booking_started`, `venue_booking_submitted`, `venue_booking_confirmed` |
| Offers & deals   | `offer_viewed`, `offer_clicked`                                |
| Stories          | `story_viewed`                                                 |
| Google Reviews   | `google_reviews_clicked`, `google_write_review_clicked`        |

## Event reference

### Menu

- **`view_menu`** — fired on menu page mount. `mode` (`dinein`/`takeaway`).
- **`view_item`** — fired when a category modal opens, with `category_id`,
  `category_name`, `mode` and the visible `items[]` (`item_id`, `item_name`, `price`).

### Cart

- **`add_to_cart`** — `item_id`, `item_name`, `category`, `quantity`, `price`.
  Also fired by the quantity-increment control. **marketing** category.
- **`remove_from_cart`** — same fields plus removed `quantity`. Fired by Remove
  and by decrementing to zero. **marketing** category.
- **`view_cart`** — fired on the takeaway cart panel mount when the cart is
  non-empty: `item_count`, `value`.

### Takeaway orders

- **`begin_checkout`** — fired when the checkout flow opens: `item_count`,
  `value`, `items[]` (menu item ids).
- **`purchase`** — fired on the order-confirmation page only, with
  `transaction_id` (order reference), `value`, `currency`, `items[]`
  (`item_id`, `item_name`, `quantity`, `price`). **marketing** category.
  **Server-authoritative one-shot:** the `purchase_event_sent` flag on the order
  is set the first time the page renders, so refreshing can never duplicate the
  event. The flag is only consumed when the *orders* group is enabled — if it is
  off at render time, nothing fires and the flag stays clear.

### Table bookings (`/booking/table`)

- **`booking_started`** — fired on page mount: `guests`.
- **`booking_submitted`** — fired on successful submission:
  `booking_id`, `date`, `time`, `guests`.
- **`booking_confirmed`** — fired only when the customer returns to the booking
  page and the reservation is `confirmed` by the admin. One-shot via the
  `analytics_confirmed_sent` flag on the reservation. **marketing** category.
  Never fired while the booking is pending — it cannot be sent before the admin
  confirms.

### Venue bookings (`/booking/venue`)

- **`venue_booking_started`** — fired on form mount: `venue_id`, `venue_name`.
- **`venue_booking_submitted`** — fired once on the status page right after
  submission (session-guarded): `booking_id`, `venue_id`, `venue_name`,
  `event_date`, `guest_count`.
- **`venue_booking_confirmed`** — fired when the customer views their status
  page and the booking is `approved` by the admin. One-shot via
  `analytics_confirmed_sent`. **marketing** category.

### Offers

- **`offer_viewed`** — fired on the offers page mount: `offer_ids[]`.
- **`offer_clicked`** — fired when an offer card CTA is clicked (declarative
  `data-track-event`): `offer_id`, `offer_title`. **marketing** category.

### Stories

- **`story_viewed`** — fired on a story page: `story_id`, `story_title`,
  `story_slug`, `story_source` (`qr` when reached via a QR code, else `web`).

### Google Reviews

- **`google_reviews_clicked`** / **`google_write_review_clicked`** — fired when
  the corresponding buttons are clicked (declarative `data-track-event`).

## Consent behaviour

- **Analytics**-category events always reach the data layer (GTM's Consent Mode
  blocks their tags when `analytics_storage` is denied).
- **Marketing**-category events are buffered client-side until the visitor
  grants marketing consent, then flushed. If consent management is off, no
  buffering occurs.

## Adding a new event

1. Emit from the client: `window.VanniyanAnalytics.push(name, data, { consent })`,
   or from Livewire: `$this->dispatch('vanniyan-track', event: name, data: [...], consent: '...')`,
   or declaratively with `data-track-event` / `data-track-data` / `data-track-consent`
   attributes on any clickable element.
2. Add the group toggle under `AnalyticsService::EVENT_TRACKS` and the panel if it
   does not exist yet.
3. Document it here.