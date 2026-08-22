# Disaster Recovery — Vanniyan Restaurant

This is the runbook to bring the site back after data loss, corruption, defacement,
or a failed deploy. Keep RTO (recovery time) and RPO (data loss window) explicit:

- **RTO target:** 2 hours
- **RPO target:** 24 hours (daily DB backup)

---

## Scenario A — Database lost or corrupted

1. Identify a good backup in `/home/lightupl/backups/` (prefer the latest pre-incident dump).
2. Restore:
   ```bash
   # if mysql client available:
   gunzip -c db_YYYY-MM-DD_HHMM.sql.gz | mysql -u lightupl_lightuplanka -p'DBPASS' lightupl_project
   # otherwise: cPanel → phpMyAdmin → Import, or uapi Backup restore
   ```
3. Verify: `SELECT COUNT(*) FROM menu_items;` should match expected (~141) and
   `SELECT * FROM admin_users;` shows the active admin.
4. Clear caches: `ea-php84 artisan optimize:clear`.

## Scenario B — Code / deploy broke the site (500 errors)

The app is served from a git checkout at `~/repositories/vanniyanrestuarant`
(symlinked into `public_html`). Roll back to the last good commit:

```bash
cd ~/repositories/vanniyanrestuarant
git fetch origin
git reset --hard <last_good_commit>      # e.g. git log --oneline -5
ea-php84 artisan optimize:clear
```

If a bad `.env` change caused it, restore `.env` from the backup tarball
(`tar xzf files_*.tgz -C ~/repositories/vanniyanrestuarant .env`).

## Scenario C — Uploaded files (images) lost

```bash
tar xzf /home/lightupl/backups/files_YYYY-MM-DD.tgz \
  -C ~/repositories/vanniyanrestuarant storage/app/public
```

## Scenario D — Defacement / compromise

1. Take the site offline or switch to a maintenance page immediately.
2. **Preserve evidence:** copy `storage/logs/laravel.log`, the current `.env`, and a
   DB dump **before** you change anything.
3. Rotate all secrets: MySQL password, admin password, any API keys.
4. Redeploy a known-good commit from Git (Scenario B) onto a cleaned `storage/`.
5. Restore the DB from the last known-clean backup (Scenario A).
6. Review `admin_notifications` / `audit_logs` for the entry point (new admin user,
   unexpected media upload, etc.).
7. See `INCIDENT_RESPONSE.md` for notification steps.

## Scenario E — DNS / domain issue (`vanniyanrestaurant.lk` not resolving)

The app currently serves on `lightuplanka.org`. If the branded domain's DNS fails:
- Point the DNS A/AAAA record back to the server IP, or
- Temporarily use `https://lightuplanka.org` and update canonical base
  (`seo_canonical_base` in `restaurant_settings`) accordingly.

## Post-recovery checklist

- [ ] Site returns 200 on `/`, `/menu`, `/contact`, `/health`
- [ ] `/health` returns `{"status":"ok",...}`
- [ ] Admin login works at `/vanniyan-control`
- [ ] Security headers present (CSP, HSTS) — `curl -I https://.../`
- [ ] DB row counts match expectations
- [ ] Caches cleared
