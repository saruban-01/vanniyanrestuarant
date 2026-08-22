# Backup Policy — Vanniyan Restaurant

Goal: be able to restore the site and its data after accidental deletion, corruption,
or a security incident, with minimal data loss.

## 1. What must be backed up

| Asset | Why | Where (production) |
|-------|-----|--------------------|
| MySQL database `lightupl_project` | All content, orders, users, settings, media metadata | localhost (socket `/var/lib/mysql/mysql.sock`) |
| Uploaded files | Raster images in `storage/app/public` (media library, menu images) | `~/repositories/vanniyanrestuarant/storage/app/public` |
| `.env` | Secrets + DB + app config | `~/repositories/vanniyanrestuarant/.env` (gitignored) |
| Code | Recoverable from Git, but keep a tagged release snapshot | GitHub `saruban-01/vanniyanrestuarant` |

**Not needed in backup:** `vendor/`, `node_modules/`, `bootstrap/cache/`, `storage/framework/cache|sessions|views` — all regenerable.

## 2. Database backup

Dump with `mysqldump` using the cPanel MySQL credentials (read from `.env`):

```bash
php -r '$l=parse_ini_file(".env");echo $l["DB_DATABASE"]."\n".$l["DB_USERNAME"]."\n".$l["DB_PASSWORD"];'
mysqldump -u lightupl_lightuplanka -p'2ZR6MpHxNmfgJ4F' lightupl_project \
  > /home/lightupl/backups/db_$(date +%F_%H%M).sql
# compress
gzip /home/lightupl/backups/db_$(date +%F_%H%M).sql
```

> Note: there is **no** `mysql` CLI client preinstalled on this cPanel instance.
> Use the cPanel **Backup** / **phpMyAdmin** UI, or install a `mysql` client, or dump
> via PHP (`new PDO(...)` + `SHOW TABLES` / `SELECT ... INTO OUTFILE`). The cPanel
> uapi `Database` module was found broken during audit — prefer the UI or a PHP script.

## 3. Files backup

```bash
tar czf /home/lightupl/backups/files_$(date +%F).tgz \
  -C /home/lightupl/repositories/vanniyanrestuarant storage/app/public .env
```

## 4. Schedule (recommended)

- **Database:** daily dump (cron `@daily`), keep **14 daily + 8 weekly + 6 monthly**.
- **Files:** weekly (images rarely change mid-week).
- **Offsite:** copy the latest backup to a second location (e.g. another cPanel account,
  object storage, or a pulled copy on the developer machine) at least weekly.

Example cron (cPanel → Cron Jobs):
```
0 3 * * * cd ~/repositories/vanniyanrestuarant && bash ~/backup.sh >> ~/backups/cron.log 2>&1
```

## 5. Retention & integrity

- Keep at least **30 days** of daily DB dumps.
- Verify a random backup monthly: `gunzip -t backup.sql.gz` and spot-check a table count.
- Label backups with the Git commit hash of the running release for correlation.

## 6. Restore test (quarterly)

At least once a quarter, restore the latest dump into a scratch database and confirm
row counts match production (`SELECT COUNT(*) FROM takeaway_orders`, `menu_items`, etc.).
