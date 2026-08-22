# Production Readiness Checklist — Vanniyan Restaurant

Status legend: ✅ Done · 🟡 Partial / recommended · ❌ Not done

## Security

- ✅ Debug off, env=production (`APP_DEBUG=false`)
- ✅ HTTPS enforced (HSTS header present)
- ✅ Baseline CSP + Permissions-Policy + COOP/CORP headers
- ✅ X-Frame-Options, X-Content-Type-Options, Referrer-Policy
- ✅ Proxy IP spoofing fixed (`TRUSTED_PROXIES`, not `*`)
- ✅ Order PII confirmation gated behind unguessable token (H1)
- ✅ JSON-LD XSS-escaped (M1)
- ✅ Mass-assignment guarded (M5)
- ✅ Upload MIME restricted to raster; PHP execution denied in `storage` (M4)
- ✅ Session cookie forced secure (L1)
- 🟡 **M3** CMS/Story HTML output not sanitized — add `mews/purifier` (defense-in-depth)
- 🟡 **L5** Security notifications not actively flushed (no queue worker)
- 🟡 CSP still allows `unsafe-inline`/`unsafe-eval` — tighten with nonces

## Availability & ops

- ✅ `/health` endpoint for monitoring
- ✅ All public pages return 200 (home, menu, offers, reservation, contact, takeaway)
- 🟡 No external uptime monitor configured (add UptimeRobot/Pingdom → `/health`)
- 🟡 No firewall (ufw/fail2ban) — consider Cloudflare WAF
- 🟡 OPcache status unconfirmed — verify/enable for performance

## Data & backup

- ✅ MySQL database in use (migrated from SQLite)
- ✅ Backup policy documented (daily DB, weekly files, offsite recommended)
- 🟡 Automated backup cron **not yet scheduled** — set up per `BACKUP_POLICY.md`
- 🟡 Quarterly restore test not yet performed

## Deployment

- ✅ Git-based deploy documented (`DEPLOYMENT.md`)
- ✅ Rollback procedure documented
- ✅ Dependency automation (Dependabot) enabled
- 🟡 CI (lint/test) not configured — add GitHub Actions to run `composer audit`,
  `npm audit`, and `php artisan test` before merge

## Compliance / privacy

- 🟡 Privacy policy & cookie notice — confirm present and linked (legal docs exist
  as CMS pages; verify they are published and reachable)
- 🟡 Data-retention statement for takeaway order PII

## Immediate next actions (priority order)

1. Schedule automated backups (cron + offsite).
2. Add external uptime monitoring on `/health`.
3. Implement M3 HTML sanitization.
4. Add a minimal CI workflow (audit + tests).
5. Consider Cloudflare (WAF, DDoS, free TLS, rate limiting on `/vanniyan-control`).
