# Admin Authentication & Security

This document explains how the Vanniyan Restaurant admin panel authentication works,
what was broken, what was fixed, and how to operate it securely.

---

## The Problem (root causes found)

The request was "admin authentication/login is not working correctly" plus a
security hardening pass. Investigation revealed several distinct issues:

| # | Issue | Impact |
|---|-------|--------|
| 1 | The panel lived at a guessable `/admin` URL with no dedicated middleware — only the generic `auth:admin` route middleware | Anyone can find the login page and attack it |
| 2 | `redirect()->intended('/admin')` in the login component hard-coded the panel URL | Breaking the login if the panel ever moved; also a maintenance trap |
| 3 | `redirectGuestsTo()` was configured, but Laravel uses **`redirectUsersTo()`** for the `guest:` middleware. Authenticated admins who visited `/admin/login` were bounced to the **public homepage** instead of the dashboard | Confusing UX that looks like "login is broken" |
| 4 | The login error message enumerated the username ("INVALID LOGIN DETAILS") | Lets attackers confirm which usernames exist |
| 5 | `/admin` responded with the login page (200) instead of 404 | The panel path was discoverable |
| 6 | The `guest:admin` / auth redirects referenced `route('login')`, which does not exist | 500 risk on the guest-redirect path |

The core authentication stack (guard, provider, password hashing, session
regeneration on login, session invalidation on logout, database sessions,
rate limiting) was verified working end-to-end over real HTTP — the failures
were in routing, redirects, and hardening, not in the credential check itself.

---

## What the login flow does now

```
GET  /vanniyan-control/login          → login page (guest:admin)
POST /livewire/update (login())       → validates → rate limit check (5/60s per IP)
                                        → Auth::guard('admin')->attempt([...is_active])
                                        → session()->regenerate()
                                        → last_login_at updated
                                        → AuditLog LOGIN entry (username, IP, UA)
                                        → redirect()->intended(route('admin.dashboard'))
Failed credentials                    → AuditLog LOGIN_FAILED entry + generic error
GET  /vanniyan-control                → EnsureAuthenticatedAdmin
GET  /admin, /admin/*                 → 404 (never redirected, never reveals panel path)
POST /vanniyan-control/logout         → logout + session invalidate + token regenerate
```

---

## Architecture

### Guard & provider (`config/auth.php`)

- Guard: `admin` — session driver.
- Provider: `admins` — Eloquent, model `App\Models\AdminUser`.
- `AdminUser` is an `Authenticatable` with a **hashed** `password` cast, `is_active`
  boolean, `last_login_at`, `password_changed_at`. No email, no registration flow.

### Panel path (`config/admin.php`)

The whole panel is served under a configurable path:

```php
'path' => env('ADMIN_PATH', 'vanniyan-control'),
```

Set `ADMIN_PATH=vanniyan-control` in `.env` (already done). Change it any time —
all internal links use named routes (`admin.*`), so nothing breaks.

When the path is not literally `admin`, the legacy `/admin` and `/admin/*` URLs
return **404 with no redirect** (`routes/web.php`), so scanners never discover
the panel location.

### Dedicated middleware (`app/Http/Middleware/EnsureAuthenticatedAdmin.php`)

Applied to **every** admin route (replaces the generic `auth:admin`):

1. Not logged in → `redirect()->guest(route('admin.login'))` (remembers the
   intended page so login returns you where you were headed).
2. Logged in but `is_active = false` → logout, invalidate session, redirect to login.
3. Otherwise → continue (with `AdminNoCache` headers: no-store).

### Redirect wiring (`bootstrap/app.php`)

- `redirectGuestsTo(...)` — guests hitting protected admin pages go to
  `route('admin.login')`; everyone else goes `route('home')`.
- `redirectUsersTo(...)` — authenticated admins visiting the login page are sent
  straight to `route('admin.dashboard')`.

### Login hardening (`app/Livewire/Admin/Auth/Login.php`)

- Rate limit: **5 attempts per IP per minute**, cleared on success, generic
  "Too many login attempts…" message on the 6th.
- Generic credential error — never reveals whether the username exists.
- `session()->regenerate()` on success (prevents session fixation).
- `LOGIN` and `LOGIN_FAILED` audit-log entries with IP + user agent.

### Sessions

- `SESSION_DRIVER=database` — server-side sessions.
- Logout invalidates the session and regenerates the CSRF token.
- Set `SESSION_SECURE_COOKIE=true` in production (HTTPS).

---

## Running the tests

```bash
php artisan test --filter=AdminAuthTest
```

Covers: login page at configured path, legacy `/admin` 404 (no redirect),
guest redirect to login, successful login (redirect + session + last_login_at +
audit log), wrong credentials (generic error + LOGIN_FAILED audit), rate limiting
(6th attempt blocked), authenticated admin visiting login → dashboard,
deactivated admin signed out, web users denied, logout session invalidation.

---

## Changing the panel path

```bash
# .env
ADMIN_PATH=your-secret-path
```

Then:

```bash
php artisan route:clear && php artisan config:clear
```

Nothing else needs to change — all links use `route('admin.*')` names. Always
pick something unguessable and NOT `admin`.

---

## Deployment notes (Vercel / serverless)

- **Sessions:** `SESSION_DRIVER=database` does not survive across serverless
  function instances (Vercel). On Vercel use a shared store (e.g. Redis /
  Upstash) or the cookie driver, and make sure sessions write to shared storage.
  Verify: log in, then load another admin page — session must persist.
- **Environment:** `APP_ENV=production`, `APP_DEBUG=false`, real `APP_URL`,
  `SESSION_SECURE_COOKIE=true`, `ADMIN_PATH` set.
- **Config cache:** after any `ADMIN_PATH` change run `php artisan config:cache`
  on deploy (and `route:cache`).
- The admin is created via `php artisan admin:create` style commands — there is
  no public registration endpoint.

---

## Quick checklist

- [x] Panel moved off `/admin` (config-driven path)
- [x] `/admin` and `/admin/*` return 404 with no redirect
- [x] Dedicated `EnsureAuthenticatedAdmin` middleware on every admin route
- [x] Session regeneration on login, invalidation on logout
- [x] Login rate limiting (5/min per IP) + generic errors (no user enumeration)
- [x] `LOGIN` / `LOGIN_FAILED` audit logging
- [x] Deactivated admins are logged out immediately
- [x] Regular web users cannot access the panel
- [x] Automated tests (11) + docs