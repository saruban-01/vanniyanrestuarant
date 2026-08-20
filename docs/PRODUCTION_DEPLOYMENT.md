# PRODUCTION DEPLOYMENT

Deployment checklist for the Vanniyan Restaurant Laravel application (PHP 8.4 / Laravel 13, SQLite, Vite).

## Pre-deployment

- [x] Backups taken (database, media, source) — see `PRODUCTION_CLEANUP_AUDIT.md`
- [x] Test data removed (orders, bookings, messages, audit dev history, sessions, cache)
- [x] `/admin` legacy URLs return 404; panel at `/vanniyan-control`
- [x] `composer audit` / `npm audit` → 0 vulnerabilities
- [x] `npm run build` succeeds; `php artisan test` → 13/13 pass
- [ ] **Set `GOOGLE_MAPS_API_KEY`** (real key) to enable live Google Reviews
- [ ] **Generate a fresh `APP_KEY`** for the new database: `php artisan key:generate`
- [ ] Confirm production admin password (already rotated by owner — do not reset)

## 1. Backup

```bash
cp database/database.sqlite database/database.sqlite.bak-$(date +%Y%m%d)
```

## 2. Deploy code

Push repository; on Vercel the build is automatic (`vercel.json` verified: API entry + static `/public/**` + routes).

## 3. Install dependencies

```bash
composer install --no-dev --optimize-autoloader --prefer-dist
npm ci
```

## 4. Migrate / seed

```bash
php artisan migrate --force
php artisan db:seed --force          # production-safe: admin, settings, offers, tables, stories
```

Database is SQLite (`database/database.sqlite`) — deploy the existing clean file or run migrations on an empty one.
**Do not run `migrate:fresh` on production data.**

## 5. Build frontend

```bash
npm run build
```

## 6. Optimize Laravel

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link          # if serving storage assets
```

## 7. Verify storage

- `storage/` writable by the web process
- `public/images`, `public/videos`, favicon set present
- no world-writable (`777`) permissions anywhere

## 8. Verify environment

- `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://www.vanniyanrestaurant.com`
- `ADMIN_PATH=vanniyan-control`, `GOOGLE_PLACE_ID` correct, `GOOGLE_MAPS_API_KEY` set
- `.env` not committed; secrets only in deployment env (`vercel.json` has non-secret config only)

## 9. Health check

```bash
curl -I https://www.vanniyanrestaurant.com          # 200
curl -s https://www.vanniyanrestaurant.com/sitemap.xml | grep -c "<loc>"
curl -s https://www.vanniyanrestaurant.com/admin    # 404 expected
```

## 10. Smoke test

Public: `/` `/menu` `/offers` `/booking` `/booking/table` `/booking/venue` `/our-story` `/contact` — all 200.
Admin: `/vanniyan-control/login` → login → dashboard → menu/offers/stories/settings.

## Vercel-specific notes

- `vercel.json` sets: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://www.vanniyanrestaurant.com`, `ADMIN_PATH`, Google config, `SESSION_DRIVER=database`, `CACHE_DRIVER=array`, `QUEUE_CONNECTION=sync`.
- **Session persistence:** SQLite sessions must persist across function instances — confirm the SQLite file is deployed as a shared artifact; if sessions drop between requests, switch `SESSION_DRIVER` to `cookie` (acceptable for this site, no sensitive server-side session data) or an external store (Redis/DynamoDB).
- `api/index.php` is the PHP entry (requires `vercel-php` build).
- Preview deployments will use preview URLs — ensure no canonical/sitemap hardcode; all links derive from `APP_URL`.

## Rollback

Restore `database/database.sqlite` backup + redeploy previous commit.