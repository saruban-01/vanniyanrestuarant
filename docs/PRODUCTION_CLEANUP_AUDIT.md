# PRODUCTION CLEANUP AUDIT

Prepared: 2026-08-20 — Vanniyan Restaurant Laravel application
Backup location: `/var/folders/d6/2ys7q2193657gtn1_m9tb0_h0000gn/T/opencode/vanniyan-backup/`
(database .sqlite copy, media copy, full source .tar.gz — taken before any change)

---

## 1. Environment & Deployment

| Item | Status | Notes |
|---|---|---|
| PHP/Laravel | Laravel Framework 13.25.0 | |
| DB | SQLite (`database/database.sqlite`) | single-file, local |
| Sessions | `database` driver | 64 dev sessions present → cleaned |
| Queue | — | jobs=0, failed_jobs=0, job_batches=0 → clean |
| Vercel config | `vercel.json` present | **APP_URL points to `https://vanniyan.vercel.app`** (dev/preview domain) → fixed to production domain; missing ADMIN_PATH/GOOGLE envs → added |
| Local `.env` | dev values (APP_ENV=local, APP_DEBUG=true, APP_URL=http://localhost:8000) | local dev only; production values documented in `docs/PRODUCTION_ENVIRONMENT.md` |
| GOOGLE_MAPS_API_KEY | **empty** | Google Reviews live fetch cannot work until a real key is supplied (fallback curated reviews in use) |

## 2. Routes (61 registered)

- All 35 `admin.*` routes under configurable path `vanniyan-control` (legacy `/admin` → 404).
- Public: home, menu, offers, booking selection/table/venue(+status), our-story(+detail), contact, order status, takeaway confirmation, sitemap/robots.
- **No test/debug routes.** No standalone `/venue` route (venue lives under `/booking/venue`). No `/events` route exists — but the footer links to `/events` (broken link → fixed).
- Old `EventBooking` system: **no tables, no routes remain**; only cosmetic leftovers (AuditLog context helper `event_booking_id`).

## 3. Database content classification

| Table | Count | Classification | Action |
|---|---|---|---|
| `admin_users` | 1 (admin) | REAL — password already rotated by owner (audit log `PASSWORD_CHANGED` 2026-08-20 13:13:07; `admin123` no longer valid) | keep, do not touch |
| `audit_logs` | 53 | TEST/DEV — all entries 18–20 Aug during development | cleaned (fresh-launch policy) |
| `takeaway_orders` | 5 | TEST — names "cfgyu", "saruban", "sinth", no real customers | deleted |
| `takeaway_order_items` | 8 | TEST (children of above) | deleted |
| `reservations` | 2 | TEST — "Saruban", no phone/reference | deleted |
| `venue_bookings` | 3 | TEST — "Saruban", no phone, all completed during dev | deleted |
| `contact_messages` | 1 | TEST — "cfghj / sss@gmail.com / ubuhbuhbuhb" | deleted |
| `menu_categories` | 36 | REAL — full Vanniyan menu structure | kept |
| `menu_items` | 141 | REAL — all linked via `menu_category_id`, real prices | kept |
| `offers` | 4 | REAL-but-inactive — created 18 Aug, plausible real offers | kept (flagged) |
| `stories` | 5 | REAL — Vanni Kingdom, Traditional Vanni Food, etc. | kept |
| `restaurant_settings` | 22 | REAL — name, phone +94 21 228 6624, email, address, maps URL, socials | kept |
| `restaurant_tables` | 9 | REAL — T1–T9 layout | kept |
| `business_hours` | 7 | REAL — 10:30–23:00 daily | kept |
| `venues` | 1 | REAL — OUTDOOR GARDEN | kept |
| `venue_event_types` | 1 | UNKNOWN — only "BIRTHDAY"; keep (flagged) |
| `venue_blackouts` | 1 | TEST — empty junk row (all columns null) | deleted |
| `cms_pages` / `cms_page_versions` | 2 / 12 | REAL — Home + Global content history | kept |
| `seo_metadata` | 3 | UNKNOWN — all rows empty (no route_name/title) | kept (harmless, flagged) |
| `sessions` | 64 | DEV | cleared |
| `cache` / `cache_locks` | — | DEV | cleared |
| `users` (web) | 0 | — | n/a |
| `media` | 0 | — | n/a |
| `redirects` / `slug_redirects` | 0 | — | n/a |
| `loyalty_configs` | 1 | REAL | kept |
| `jobs` / `failed_jobs` / `job_batches` | 0 / 0 / 0 | — | clean |

### Placeholder content found
- Home CMS content: `google_reviews_write_url = https://search.google.com/local/writereview?placeid=test` → replaced with real place ID URL (field is legacy; live section reads from service, but stale placeholder removed).

## 4. Files

| Path | Classification | Action |
|---|---|---|
| `storage/logs/laravel.log` (2.4 MB) | DEV log | deleted; no secrets found (only "API key not configured" warnings) |
| `resources/views/welcome.blade.php` | Default Laravel landing view, unreferenced | deleted |
| `.DS_Store` (6 files) | macOS junk | deleted |
| `public/vercel.svg|next.svg|default.svg` | not present | n/a |
| `public/images/*` (logo, logo-footer, cards/loyalty-front+back) | REAL, all referenced | kept |
| `public/videos/hero.mp4` | REAL, referenced by hero | kept |
| favicon set (ico/16/32/apple/android/manifest) | FINAL set | kept |
| `bootstrap/cache` compiled files | DEV | cleared via `optimize:clear` |
| `storage/framework/{cache,views,sessions}` | DEV | cleared via `optimize:clear` |

## 5. Code audit results

- **No debug statements** (`dd`, `dump`, `var_dump`, `print_r`, `ray`) in app/.
- **No `console.log`/`debugger`** in frontend.
- Logging: only intentional `Log::warning` for Google Reviews failures — kept.
- **No test/demo Artisan commands** — only `CreateAdminUser` (real provisioning tool, kept).
- Seeders: `DatabaseSeeder` calls Admin/Offers/Settings/Tables/Stories seeders — all production-safe real content; no fake data anywhere.
- Factories: `UserFactory`, `AdminUserFactory` — test-only, never run in production (kept for tests).
- Hardcoded contact info: only a form placeholder `+94 77 123 4567` in reservation phone input (UX placeholder, kept); footer "Kilinochchi, Sri Lanka" text is a generic label.
- Broken link: footer `Events` → `/events` (route does not exist) → **removed** (booking covered by "Reserve a Table" + venue booking).

## 6. Dependencies & security

- `composer audit` / `npm audit` — run, see `docs/SECURITY_AUDIT.md`.

## 7. Final state target

Database = real content only (menu, offers, stories, settings, tables, venue config, admin).
Storage/public = real assets only. Cache/sessions/logs = empty. Build = fresh production build.