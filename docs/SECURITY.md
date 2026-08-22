# Security Guide — Vanniyan Restaurant (Laravel)

This document is the operational companion to [`SECURITY_AUDIT.md`](./SECURITY_AUDIT.md).
It lists what has been **fixed**, what is **pending**, and the day-to-day security
habits for this deployment.

## 1. What is already hardened (production, verified 2026-08-22)

| # | Issue | Fix | Status |
|---|-------|-----|--------|
| H1 | Takeaway confirmation leaked customer PII (name/phone/email) to anyone who guessed an order reference | Orders now get an unguessable `access_token`; `/takeaway/confirmation/{reference}` requires `?token=` for orders that have one | ✅ Live |
| H2 | `TrustProxies('*')` allowed client-supplied `X-Forwarded-For` spoofing → throttle/IP-bypass | `bootstrap/app.php` now trusts only `TRUSTED_PROXIES` (default `127.0.0.1`), env-configurable | ✅ Live |
| M1 | JSON-LD output used raw `json_encode` → stored-XSS via CMS/settings values | All JSON-LD now uses `JSON_HEX_TAG\|JSON_HEX_APOS\|JSON_HEX_AMP\|JSON_HEX_QUOT` | ✅ Live |
| M2 | No CSP / Permissions-Policy / COOP | `SecureHeadersMiddleware` now sets a baseline CSP, Permissions-Policy, COOP, CORP | ✅ Live |
| M4 | SVG / executable uploads accepted; PHP execution possible in `storage` | Admin media upload restricted to `mimes:jpeg,png,webp,gif`; `storage/app/public/.htaccess` denies PHP execution | ✅ Live |
| M5 | 12 models used `$guarded = []` (mass-assign everything incl. `id`) | All changed to `$guarded = ['id']` | ✅ Live |
| L1 | Session cookie not forced secure | `SESSION_SECURE_COOKIE=true` set in production `.env` | ✅ Live |
| — | No uptime signal | `/health` endpoint returns `{"status":"ok",...}` | ✅ Live |
| — | No dependency update automation | `.github/dependabot.yml` added (composer/npm/actions, weekly) | ✅ Live |

## 2. Still recommended (not yet implemented — schedule these)

- **M3 (High)** — CMS / Story / legal-document rich text is rendered with `{!! ... !!}`.
  Install `mews/purifier` and sanitize on **output** (or on save) so a compromised
  admin account cannot inject script. Validation rules already restrict *which*
  fields an admin can set, but defense-in-depth is recommended.
- **L5 (Low)** — Security-critical notifications (new admin, failed login spikes) are
  written to the `admin_notifications` table but there is no worker flushing them.
  Either poll the table in the admin dashboard or run `php artisan queue:work` against
  the `database` connection so alerts surface promptly.
- **CSP tightening** — The current CSP permits `'unsafe-inline'` and `'unsafe-eval'`
  for Livewire/Alpine/Tailwind compatibility. Run a CSP report-only phase, then move to
  nonces/hashes to remove `unsafe-eval`.

## 3. Secrets & configuration

- `.env` is **gitignored** and never committed. Only `.env.example` is tracked.
- Production DB is MySQL `lightupl_project` on localhost socket. Credentials live only
  in the server `.env`.
- `APP_DEBUG=false`, `APP_ENV=production` in production.
- Rotate the admin password and the MySQL password if any server compromise is suspected.

## 4. Dependencies

- Run `composer audit` and `npm audit` in CI (Dependabot already opens PRs).
- Do **not** `composer update` blindly on production — stage in a branch, test, then deploy.

## 5. Reporting a vulnerability

Email the site owner / maintainer. Do not open a public GitHub issue for live
vulnerabilities. Treat order PII and admin credentials as sensitive.
