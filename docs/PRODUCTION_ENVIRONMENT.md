# PRODUCTION ENVIRONMENT

Required environment variables for the Vanniyan Restaurant production deployment.
Values are documented here **without exposing secrets**.

## Required variables

| Variable | Production value | Notes |
|---|---|---|
| `APP_NAME` | `Vanniyan Restaurant` | |
| `APP_ENV` | `production` | set in `vercel.json` |
| `APP_KEY` | 32-char base64 key | **generate fresh for the new database**: `php artisan key:generate`; never reuse dev key if encrypted data exists |
| `APP_DEBUG` | `false` | set in `vercel.json` |
| `APP_URL` | `https://www.vanniyanrestaurant.com` | used by sitemap, canonicals, Open Graph, redirects |
| `APP_LOCALE` / `APP_FALLBACK_LOCALE` / `APP_FAKER_LOCALE` | `en` | |
| `DB_CONNECTION` | `sqlite` | `database/database.sqlite` (deployed with the app) |
| `SESSION_DRIVER` | `database` | server-side sessions; verify persistence on serverless (see Deployment doc) |
| `SESSION_LIFETIME` | `120` | minutes |
| `SESSION_ENCRYPT` | `false` | |
| `SESSION_PATH` | `/` | |
| `SESSION_DOMAIN` | `null` | |
| `CACHE_STORE` | `array` (Vercel) | or `database`/`redis` on a persistent host |
| `QUEUE_CONNECTION` | `sync` | no queue workers on Vercel |
| `FILESYSTEM_DISK` | `public` | |
| `LOG_CHANNEL` | `stack` | logs to `storage/logs/laravel.log` |
| `GOOGLE_PLACE_ID` | `ChIJ631GQQCV_joRnLrzpEuXyYo` | real Google Business Profile place ID |
| `GOOGLE_MAPS_API_KEY` | **real production key (empty today)** | required for live Google Reviews fetch |
| `GOOGLE_REVIEWS_URL` | `https://www.google.com/maps/search/?api=1&query=Vanniyan+Restaurant&query_place_id=ChIJ631GQQCV_joRnLrzpEuXyYo` | |
| `GOOGLE_WRITE_REVIEW_URL` | `https://search.google.com/local/writereview?placeid=ChIJ631GQQCV_joRnLrzpEuXyYo` | |
| `GOOGLE_REVIEWS_CACHE_MINUTES` | `1440` | |
| `ADMIN_PATH` | `vanniyan-control` | admin panel path (never `admin`) |

## Not needed in production

Remove/unset: `MAIL_*` (no mail in current flows), `AWS_*`, `REDIS_*`, `MEMCACHED_*`, `BROADCAST_CONNECTION` (no broadcaster). These exist in the default Laravel `.env` template and are unused by the application.

## Local development

The local `.env` intentionally keeps `APP_ENV=local`, `APP_DEBUG=true`, `APP_URL=http://localhost:8000`.
Production values are enforced by `vercel.json` (`APP_ENV=production`, `APP_DEBUG=false`,
`APP_URL=https://www.vanniyanrestaurant.com`) and by this document for other hosts.