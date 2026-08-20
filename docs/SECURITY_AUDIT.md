# SECURITY AUDIT

Prepared: 2026-08-20 — Vanniyan Restaurant Laravel application

## Dependency advisories

| Tool | Result |
|---|---|
| `composer audit` | **No security vulnerability advisories found** (0) |
| `npm audit --omit=dev` | **0 vulnerabilities** |

## Application security review

| Area | Status | Notes |
|---|---|---|
| Admin authentication | OK | Guard `admin` (session, `AdminUser`), dedicated `EnsureAuthenticatedAdmin` middleware on all 35 admin routes; login page serves under configurable `/vanniyan-control/login`; legacy `/admin` → 404 without redirect |
| Admin authorization | OK | Web users cannot access the panel; deactivated admins signed out on contact |
| Password policy | OK | `password` hashed (`bcrypt`, `hashed` cast); admin password already rotated by owner 2026-08-20 (audit log `PASSWORD_CHANGED`); no default/test passwords remain valid |
| Session security | OK | `session()->regenerate()` on login; session invalidated + token regenerated on logout; `SESSION_DRIVER=database` (server-side); dev sessions cleared |
| Login rate limiting | OK | 5 attempts / 60s per IP, generic errors (no username enumeration), `LOGIN_FAILED` audit entries |
| CSRF | OK | Laravel `PreventRequestForgery` active on all state-changing routes; verified end-to-end over HTTP |
| XSS | OK | All user data rendered through Blade escaping / Livewire `{{ }}`; no raw `{!! !!}` on user-controlled content found in audit |
| SQL injection | OK | Eloquent/query builder only; no raw SQL string concatenation found |
| File upload security | OK | No public upload endpoints in routes (Media library route commented out); only admin-managed assets |
| Secrets management | OK | `.env`/`vercel.json` hold no committed secrets; no API keys in logs (only "not configured" warnings); no credentials in git history scan |
| Production debug | OK | `APP_DEBUG=false`, `APP_ENV=production` set in `vercel.json` deploy env; local `.env` remains dev (documented) |
| Security headers | OK | Global `SecureHeadersMiddleware`: X-Frame-Options SAMEORIGIN, X-Content-Type-Options nosniff, HSTS, Referrer-Policy; admin pages `no-store` (`AdminNoCache`) |
| Robots/SEO | OK | `/robots.txt` allows full crawl of public site; admin login page `noindex,nofollow`; sitemap contains only real public URLs |
| Admin URL disclosure | OK | `/admin`, `/admin/*` → 404, no redirect; panel path only in `ADMIN_PATH` env |

## Open items (pre-launch, not code defects)

1. **`GOOGLE_MAPS_API_KEY` is empty** — live Google Reviews fetch is disabled until a real API key is set (curated review fallback shows instead). This is a configuration blocker, not a vulnerability.
2. **Session persistence on Vercel** — `vercel.json` sets `SESSION_DRIVER=database` (SQLite). Serverless cold starts use a shared/persistent SQLite file; verify session persistence across instances in the target environment, otherwise switch to cookie driver or external store (see `docs/PRODUCTION_DEPLOYMENT.md`).
3. **`seo_metadata` rows** — 3 empty placeholder rows exist (no route_name). Harmless; delete or populate during content setup.

## Verdict

No critical or high-severity vulnerabilities found. All security controls verified working (automated tests in `tests/Feature/AdminAuthTest.php`).