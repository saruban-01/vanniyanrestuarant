# Deployment Guide — Vanniyan Restaurant (Laravel on cPanel)

Current model: source is version-controlled on GitHub and checked out on the cPanel
server; a manual sync pulls the latest `main` into the live directory.

## Environment facts (verified 2026-08-22)

- **Server:** cPanel, Apache, PHP **8.4** (`/opt/cpanel/ea-php84/root/usr/bin/php`).
  The bare `php` on PATH is 8.3 and will **fail** Composer platform checks — always
  use `ea-php84` for Artisan/Composer.
- **Repo on server:** `/home/lightupl/repositories/vanniyanrestuarant`
- **Web root:** `public_html` → symlink to `…/repositories/vanniyanrestuarant/public`
- **DB:** MySQL `lightupl_project` (localhost, socket `/var/lib/mysql/mysql.sock`)
- **Admin:** `https://lightuplanka.org/vanniyan-control`
- **Live domain (branded):** `vanniyanrestaurant.lk` (currently DNS not pointed;
  app serves on `lightuplanka.org`)

## Standard deploy

```bash
KEY=~/.ssh/cpanel_lightupl
ssh -p 22 -i "$KEY" lightupl@lightuplanka.org '
  cd /home/lightupl/repositories/vanniyanrestuarant
  git fetch origin
  git reset --hard origin/main
  PHP=/opt/cpanel/ea-php84/root/usr/bin/php
  $PHP artisan optimize:clear
  $PHP artisan config:cache
  $PHP artisan route:cache
'
```

> `.env` is gitignored and **persists across deploys** — do not `git clean` the repo.
> If you must change env (e.g. add `TRUSTED_PROXIES`, `SESSION_SECURE_COOKIE`), edit
> `.env` on the server directly, then `ea-php84 artisan config:cache`.

## Front-end build (only when CSS/JS changes)

Run locally, commit the built `public/build` assets, then deploy:

```bash
npm install
npm run build          # produces public/build
git add -A && git commit -m "build assets" && git push origin main
# then run the Standard deploy above
```

## Post-deploy verification

```bash
B=https://lightuplanka.org
for p in / /menu /contact /health /vanniyan-control; do
  printf "%-18s -> " "$p"; curl -sk -o /dev/null -w "%{http_code}\n" "$B$p"
done
curl -sk -D - -o /dev/null "$B/" | grep -i "Content-Security-Policy"
```

## Rollback

```bash
cd /home/lightupl/repositories/vanniyanrestuarant
git reset --hard <previous_commit>
ea-php84 artisan optimize:clear
```

## Notes / gotchas

- **No `mysql` CLI** on the server; use cPanel Backup/phpMyAdmin for DB ops.
- **No firewall** (ufw/fail2ban) observed; rely on cPanel/Apache and the app-layer
  throttles. Consider Cloudflare for DDoS + WAF + free certs.
- **OPcache** is not confirmed enabled — if enabled, `ea-php84 artisan optimize:clear`
  also clears OPcache; otherwise restart PHP-FPM after deploys if you see stale code.
- Keep `public/storage/.htaccess` (denies PHP execution) — it is outside git, so
  re-create it after a full wipe (see `DISASTER_RECOVERY.md`).
