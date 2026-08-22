# Incident Response — Vanniyan Restaurant

Use this when something is wrong: defacement, data leak, outage, suspicious admin
activity, or a reported vulnerability.

## 1. Triage (first 15 minutes)

1. **Confirm** the incident — screenshot, note URL, time, and what you saw.
2. **Contain** if active (defacement / compromise):
   - Put up a maintenance page, or restrict `public_html` access.
   - Preserve evidence **before** touching anything (see Step 3 of Disaster Recovery).
3. **Classify severity:**

| Severity | Examples | Response time |
|----------|----------|---------------|
| Critical | Site defaced, DB dumped/stolen, admin account created by attacker, ransom | Immediate (< 1h) |
| High | Order PII exposed, active brute-force success, broken auth | < 4h |
| Medium | Suspicious admin login, odd traffic spike, CSP violation flood | < 24h |
| Low | Dependency CVE with no exploit path, minor header gap | Next scheduled window |

## 2. Communications

- Site owner / maintainer: (fill in) — primary contact.
- Hosting provider: cPanel account `lightupl` @ `lightuplanka.org`.
- For a confirmed **data breach involving customer PII** (names/phones/emails from
  takeaway orders), prepare a breach notice to affected customers and consider legal
  reporting obligations.

## 3. Common playbooks

### Brute-force / credential stuffing on `/vanniyan-control`
- Confirm via `audit_logs` / failed login counts.
- Rotate the admin password immediately.
- If cPanel exposes it, enable 2FA / restrict admin IP; otherwise add a WAF
  (Cloudflare) rate rule on the admin path.

### Suspicious new admin user
- Check `admin_users` for unexpected rows; delete if illegitimate.
- Rotate all admin passwords and the MySQL password.
- Review `audit_logs` for the creation source IP.

### Data leak (PII exposure)
- The takeaway confirmation page now requires an unguessable `?token=` — confirm it
  is enforced (returns 403 without token). If a leak occurred, invalidate by rotating
  all `access_token`s (`UPDATE takeaway_orders SET access_token = NULL` then force
  re-issue on next view — or simply rotate DB credentials and notify customers).

### Defacement / file modification
- Follow Disaster Recovery Scenario D (preserve → rotate → redeploy clean commit →
  restore clean DB).

### DDoS / outage
- Enable Cloudflare "Under Attack" mode if available.
- Confirm `/health` and that the server is reachable; scale/restart PHP-FPM via cPanel.

## 4. Post-incident

- Write a short timeline (what happened, detection, actions, downtime).
- Update `SECURITY_AUDIT.md` / `SECURITY.md` with the lesson.
- Add the missing control that would have prevented it (e.g. 2FA, WAF, M3 sanitization).

## 5. Evidence to collect

- `storage/logs/laravel.log`
- Current `.env` (for timeline, **not** to share publicly)
- DB dump taken at incident time
- `audit_logs` and `admin_notifications` rows around the event
- Web server access logs (cPanel → Raw Access Logs)
