# FINAL PRODUCTION CLEANUP

Prepared: 2026-08-20 — result of the full production-reset workflow (audit → classify → remove → clean → verify).

## Removed

**Test data (database):**
- 5 takeaway orders + 8 order items (dev tests: "cfgyu", "saruban", "sinth")
- 2 table reservations (test, no real customer info)
- 3 venue bookings (test, no contact details)
- 1 contact message (random-keyboard test: "cfghj / sss@gmail.com / ubuhbuhbuhb")
- 1 empty junk venue blackout row
- 53 development audit-log entries (all pre-launch dev activity; fresh-launch policy)
- 64 development sessions
- cache/cache_locks rows

**Files:**
- `storage/logs/laravel.log` (2.4 MB dev log; no secrets contained — verified)
- `resources/views/welcome.blade.php` (default Laravel landing view, unreferenced)
- 6 `.DS_Store` macOS junk files
- All framework caches (config/route/view/compiled/events) via `optimize:clear`

**Obsolete routes:** none existed — 61 routes audited; no `/events`, no test/debug routes, no standalone `/venue` route.

**Obsolete database structures:** old Event Booking system already absent (no tables/columns); nothing to drop.

**Fixed during cleanup:**
- Broken footer link `Events → /events` (dead route) → replaced with `Book Venue → /booking/venue`
- Placeholder `placeid=test` in Home CMS review URL → real Place ID `ChIJ631GQQCV_joRnLrzpEuXyYo`
- `vercel.json` pointed at preview domain `https://vanniyan.vercel.app` → `https://www.vanniyanrestaurant.com`; added `ADMIN_PATH`, Google config, `SESSION_DRIVER=database`

## Preserved

- Menu: 33 categories, 141 real items with prices
- Stories: 5 real Vanni stories
- Offers: 4 (inactive — real planned offers, kept)
- Restaurant settings: 22 (name, phone +94 21 228 6624 / +94 74 310 4294, email, address, maps URL, socials)
- Tables layout (T1–T9), business hours, outdoor garden venue + event type
- CMS Home/Global content history (12 versions)
- Admin account (password already rotated by owner — not touched)
- All real assets: logo, footer logo, loyalty cards, hero video, favicon set

## Verified

- **Tests:** `php artisan test` → 13/13 passed (incl. 11 admin-auth security tests)
- **Security:** `composer audit` + `npm audit` → 0 vulnerabilities; full checklist in `docs/SECURITY_AUDIT.md`
- **Build:** `npm run build` clean (CSS 102 KB, JS expected-empty — Livewire/Alpine via CDN)
- **Pages:** `/`, `/menu`, `/offers`, `/booking`, `/booking/table`, `/booking/venue`, `/our-story`, `/contact`, `/vanniyan-control/login`, `/sitemap.xml`, `/robots.txt` → all 200; `/admin` → 404
- **Database:** no test records; no jobs/failed jobs; admin auth working end-to-end over HTTP
- **Storage:** only referenced assets remain; favicon set final
- **Environment:** production vars documented (`docs/PRODUCTION_ENVIRONMENT.md`); deploy steps in `docs/PRODUCTION_DEPLOYMENT.md`

## Remaining warnings

1. **`GOOGLE_MAPS_API_KEY` is empty** — live Google Reviews disabled until a real key is set (curated fallback in use). Must be set before launch for the reviews section to show live data.
2. **Vercel session persistence** — `SESSION_DRIVER=database` (SQLite) must be verified across serverless instances; fallback: cookie driver.
3. `seo_metadata` has 3 empty placeholder rows (no route_name) — harmless, optionally delete/populate.
4. Local `.env` is dev (APP_ENV=local, APP_DEBUG=true) — production values enforced by `vercel.json`; see `PRODUCTION_ENVIRONMENT.md`.

## Production readiness

**READY FOR PRODUCTION** — provided the two configuration items above (GOOGLE_MAPS_API_KEY, Vercel session persistence) are confirmed at deploy time. No critical test data, no security issues, no broken pages, no debug artifacts remain.