# Security Audit — Vanniyan Restaurant (Laravel)

**Audit date:** 2026-08-22
**Auditor:** Automated security review (codebase inspection + server environment check)
**Scope:** Full Laravel application (web + Livewire + admin), configuration, deployment, infrastructure
**Status of code during audit:** Read-only. No code was modified.

---

## Environment Summary

| Item | Value |
|------|-------|
| Laravel | `^13.17` |
| Livewire | `^3.0` |
| PHP (required) | `^8.3` |
| PHP (server, cPanel `ea-php84`) | **8.4.24** |
| Database | MySQL / MariaDB (`lightupl_project`) |
| Cache / Session / Queue driver | `database` |
| Redis | PHP extension present, **no Redis server** → drivers effectively database-only |
| Web server | Apache (cPanel shared hosting), direct origin (no Cloudflare observed) |
| `APP_ENV` / `APP_DEBUG` | `production` / `false` ✔ |
| Document root | `public_html` → `public/` (symlink) ✔ |
| `.env` exposed via web | No (404) ✔ |
| `storage/logs` exposed via web | No (404) ✔ |
| OPcache | Not enabled for PHP 8.4 (host limitation) |
| API surface | None (`routes/api.php` absent) |
| Payment integration | None (no provider / webhook surface) |

---

## Severity Legend

- **CRITICAL** — directly exploitable, severe impact
- **HIGH** — exploitable, significant impact (PII leak, auth bypass)
- **MEDIUM** — exploitable under specific conditions or defense-in-depth gap
- **LOW** — hardening / configuration
- **INFO** — noted, no action or already adequate

---

## Findings

### H1 — Order confirmation exposes customer PII without token (HIGH)

- **Location:** `routes/web.php:154-157`, `app/Services/TakeawayOrderService.php:50-60`, `app/Services/OrderStatusService.php:58-71`, `app/Livewire/Takeaway/CheckoutFlow.php:138`, `resources/views/livewire/takeaway/order-confirmation.blade.php`
- **Why it matters:** `takeaway.confirmation` loads an order by `reference` alone via `TakeawayOrder::where('reference', $reference)->firstOrFail()` — **no `access_token`, no auth**. The intended token gate (`OrderStatusService::validateAccess`) is dead code because `createOrder` never generates/persists `access_token` and the post-order redirect never passes it. An unauthenticated visitor who learns/guesses a `reference` (or receives a leaked link) can read the customer's **name, phone, optional email, order note, items, and totals**. Reference entropy mitigates brute force but not link/referrer leakage.
- **Recommended fix:** Generate `access_token = Str::random(40)` on order creation, persist it, and require `?token=` on the confirmation route (return 403 without it). Wire the token into the `CheckoutFlow` redirect.
- **Already protected?** Partial — security control designed but never wired.
- **Code change required?** Yes.
- **Severity:** HIGH

### H2 — `trustProxies('*')` makes client IP spoofable → rate-limit bypass (HIGH)

- **Location:** `bootstrap/app.php:15` (`$middleware->trustProxies(at: '*')`); consumed at `app/Livewire/Admin/Auth/Login.php:23` (throttle key `admin_login|{ip}`) and `app/Livewire/Pages/ContactPage.php:37`.
- **Why it matters:** Trusting **all** proxies means `request()->ip()` reads client-supplied `X-Forwarded-For`. An attacker can rotate `X-Forwarded-For` per request to bypass the admin login throttle (5/IP) and contact-form throttle (3/hour/IP), enabling credential brute-force and spam.
- **Recommended fix:** Trust only the real upstream proxy IP(s) (e.g. `['127.0.0.1', '<server-ip>']`). On this cPanel host (no Cloudflare) the client connects directly, so restricting proxies prevents external `XFF` spoofing while keeping correct IPs for logs.
- **Already protected?** No.
- **Code change required?** Yes.
- **Severity:** HIGH

### M1 — JSON-LD XSS via unescaped `json_encode` (MEDIUM)

- **Location:** `resources/views/components/seo/structured-data.blade.php:62` (`{!! json_encode($schema) !!}`), `resources/views/livewire/pages/contact-page.blade.php:439` (`{!! $schemaJson !!}`)
- **Why it matters:** `json_encode` does not escape `<`, `>`, `/`. Schema fields come from admin-editable `RestaurantSetting` values (name, phone, address) and event titles/descriptions. A value containing `</script><script>alert(1)</script>` breaks out and executes. Affects home, contact, event pages.
- **Recommended fix:** Emit with `json_encode($schema, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES)`.
- **Already protected?** No.
- **Code change required?** Yes.
- **Severity:** MEDIUM

### M2 — No Content-Security-Policy / Permissions-Policy (MEDIUM)

- **Location:** `app/Http/Middleware/SecureHeadersMiddleware.php:16-27` (sets HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy only; no CSP). No CSP in `.htaccess`.
- **Why it matters:** Absence of CSP removes the primary second-layer defense for the XSS sinks (M1/M3/M4). `X-XSS-Protection` is deprecated.
- **Recommended fix:** Add a baseline CSP (`default-src 'self'`), `Permissions-Policy`, `Cross-Origin-Opener-Policy`. Must allow Livewire, Google Fonts, Unsplash imagery. Tighten over time with nonces.
- **Already protected?** No.
- **Code change required?** Yes.
- **Severity:** MEDIUM

### M3 — Stored XSS from raw CMS / Story HTML (MEDIUM)

- **Location:** `resources/views/components/legal/document.blade.php:81`, `resources/views/livewire/pages/story-detail-page.blade.php:113`
- **Why it matters:** Admin-authored rich HTML is rendered unescaped with no sanitization. If a lower-privilege/compromised author or future integration writes this content, or HTML is pasted with `<script>`/`<img onerror>`, it executes in visitors' browsers (public-facing legal/story pages).
- **Recommended fix:** Sanitize stored HTML with HTMLPurifier on save (or restrict to a safe subset), combined with CSP (M2).
- **Already protected?** No.
- **Code change required?** Yes.
- **Severity:** MEDIUM

### M4 — SVG upload → stored XSS + no PHP-execution lockdown in storage (MEDIUM)

- **Location:** `app/Services/MediaService.php:17` (preserves original extension), `app/Livewire/Admin/Media/Index.php:24,31` (`image|max:5120`), `public/storage` (no `.htaccess` disabling PHP).
- **Why it matters:** The `image` rule permits **SVG**, which can embed JavaScript and executes as `image/svg+xml`. Additionally, no rule disables PHP execution under `public/storage`; the `image` rule is the only thing blocking `.php` uploads (defense-in-depth gap).
- **Recommended fix:** Restrict uploads to raster types (`mimes:jpeg,png,webp,gif`); add `public/storage/.htaccess` with `php_flag engine off` (+ `Options -ExecCGI`). Re-validate MIME in `MediaService`.
- **Already protected?** Partial (extension blocking present, but no exec lockdown / SVG sanitize).
- **Code change required?** Yes.
- **Severity:** MEDIUM

### M5 — Mass-assignment anti-pattern `$guarded = []` on security-relevant models (MEDIUM)

- **Location:** `app/Models/AdminUser.php:14`, `TakeawayOrder.php:12`, `MenuItem.php:12`, `Offer.php:13`, `MenuCategory.php:12`, `CmsPage.php:9`, `Media.php:9`, `AuditLog.php:12`, `LoyaltyConfig.php:12`, `AdminNotification.php:12`, `CmsPageVersion.php:9`
- **Why it matters:** `$guarded = []` makes every attribute mass-assignable via `create()`/`update()`/`fill()`. Currently mitigated by per-field validation in admin editors, but it removes the safety net: a future `update($request->all())` or loosened rule could let `is_active`, `price`, `status`, `owner_id`, or admin flags be set.
- **Recommended fix:** Define explicit `$fillable` on each model, especially `AdminUser`, `MenuItem`, `Offer`, `TakeawayOrder`.
- **Already protected?** Partial (validation mitigates today).
- **Code change required?** Yes (recommended).
- **Severity:** MEDIUM

### L1 — `SESSION_SECURE_COOKIE` not forced (LOW)

- **Location:** `config/session.php:172`
- **Why it matters:** With `null`, Laravel auto-secures only on HTTPS requests. On shared hosting, set explicitly `true` to guarantee the session cookie is never sent over HTTP.
- **Recommended fix:** Set `SESSION_SECURE_COOKIE=true` in production `.env`.
- **Code change required?** No (config only).

### L2 — Session/cache data unencrypted at rest (LOW/INFO)

- **Location:** `config/session.php:50` (`encrypt=false`)
- **Why it matters:** Session payloads/cache readable if the DB is compromised. Minor for this app.
- **Recommended fix:** Consider `SESSION_ENCRYPT=true` if PII sits in session.

### L3 — No granular admin roles (INFO)

- **Location:** `app/Models/AdminUser.php` (only `is_active`); `EnsureAuthenticatedAdmin`. All admins have full panel access.
- **Note:** Acceptable for a single-tenant restaurant; noted for awareness.

### L4 — Livewire admin actions rely solely on route-level auth (LOW)

- **Location:** `routes/web.php:69-72` (`EnsureAuthenticatedAdmin` wraps GET routes); `/livewire/update` is not wrapped. Safe in practice (valid encrypted snapshot required), but privileged actions should defensively re-check `auth('admin')->check()`.
- **Recommended fix:** Add defensive `auth('admin')->check()` re-checks in sensitive Livewire actions.

### L5 — Queue worker dependency for security notifications (LOW/INFO)

- **Location:** `config/queue.php:16` (`database`); `AdminNotification::notify` used for failed-logins / new-orders.
- **Why it matters:** With `database` driver, a running `php artisan queue:work` is required; without it, security-relevant notifications are silently never delivered.
- **Recommended fix:** Verify a worker runs on cPanel, or dispatch security events `sync`/log directly.

### L6 — Experience-card icon output (INFO)

- **Location:** `resources/views/components/site/experience-card.blade.php:18` — static SVG array keyed by `$icon`; not user-supplied. Safe.

---

## Verified-Good (no action required)

- **Secrets:** No hard-coded DB/API/mail/payment keys in code; `.env.example` placeholders only; `.env*` gitignored; no secrets in git history. ✔
- **CSRF:** Laravel `VerifyCsrfToken` active; Livewire auto-handles CSRF. ✔
- **Auth:** BCRYPT `hashed` cast (`BCRYPT_ROUNDS=12`); login requires `is_active`; logout invalidates session + regenerates token. ✔
- **Authorization:** All admin GET routes + logout wrapped in `EnsureAuthenticatedAdmin` + `AdminNoCache`; legacy `/admin` 404; admin path obscured via `ADMIN_PATH`. ✔
- **SQL injection:** None — Eloquent throughout; `DB::raw` only in comments; `selectRaw` hardcoded. ✔
- **Race conditions:** `ReservationService::createReservation` uses `DB::transaction` + `lockForUpdate()` + idempotency; `TakeawayOrderService::createOrder` uses a transaction. No inventory decrement. ✔
- **Debug exposure:** `APP_DEBUG=false` in production `.env`; no Whoops. ✔
- **Web-root exposure:** `.env` outside `public/`; `Options -Indexes`; no `.env`/`.git`/`vendor`/`storage` served. ✔
- **Dependencies:** Laravel 13 / Livewire 3 / PHP 8.3+ current. Run `composer audit` in CI.

---

## Remediation Priority

1. **H1** — enforce `access_token` on order confirmation (PII leak).
2. **H2** — restrict `trustProxies` to the real proxy IP.
3. **M1 + M2** — escape JSON-LD and add a CSP (closes XSS vectors).
4. **M4** — lock down upload MIME types + disable PHP execution in `storage`.
5. **M3 / M5** — sanitize CMS HTML; explicit `$fillable` on models.
6. **L1 / L5** — force `SESSION_SECURE_COOKIE=true`; confirm queue worker.

All HIGH/MEDIUM items require code changes. LOW/INFO are config or defense-in-depth.
