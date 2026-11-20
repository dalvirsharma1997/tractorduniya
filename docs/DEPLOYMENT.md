# Deploying TractorDuniya.in to Hostinger

This guide is written for your specific Hostinger plan — confirmed during
development to have **SSH access** and **PHP 8.2.36 + Composer 2.9.8**
available (`u864959779@46.28.45.181:65002`). It also notes the no-SSH
fallback for each step, in case you ever move to a plan without it.

Stack: **Laravel 11 + Filament 3**, MySQL 8, Tailwind compiled locally
(no Node needed on the server).

---

## 0. Before you start

- Your domain should already be pointed at Hostinger (or parked/added in
  hPanel) and the site's hosting plan active.
- Have the GitHub repo URL and a way to authenticate the clone (a GitHub
  personal access token, or deploy key) ready if you're cloning via SSH.
- Decide your production domain, e.g. `https://tractorduniya.in`.

---

## 1. Create the MySQL database

In **hPanel → Databases → MySQL Databases**:

1. Create a new database (note the full name, Hostinger prefixes it,
   e.g. `u864959779_tractorduniya`).
2. Create a new database user with a strong password, and attach it to
   that database with **all privileges**.
3. Note down: database name, username, password, and host (usually
   `localhost` on Hostinger shared/business plans).

---

## 2. Get the code onto the server

### With SSH (your plan)

```bash
ssh -p 65002 u864959779@46.28.45.181
```

Pick a folder **outside** `public_html` to hold the full application —
this is what keeps `.env`, `app/`, and everything else away from public
URLs regardless of which document-root option you use in step 5.

```bash
cd ~
git clone https://github.com/gulshanmehto/framerexport.git tractorduniya
cd tractorduniya
git checkout main   # or whichever branch you're deploying
```

If the repo is private, generate a GitHub personal access token and clone
with `https://<token>@github.com/gulshanmehto/framerexport.git`, or add an
SSH deploy key to this server's `~/.ssh` and use the `git@github.com:...`
URL instead.

### Without SSH (File Manager fallback)

1. On your own machine, zip the project (excluding `vendor/`, `node_modules/`,
   `.git/`, and `.env`).
2. In **hPanel → Files → File Manager**, upload the zip to a folder outside
   `public_html` (e.g. `~/tractorduniya`) and extract it there.
3. You'll need another way to run Composer/artisan commands without SSH —
   see the **Cron Jobs workaround** in step 8.

---

## 3. Install PHP dependencies

Still inside `~/tractorduniya` over SSH:

```bash
composer install --no-dev --optimize-autoloader
```

`--no-dev` skips testing/dev tooling; `--optimize-autoloader` speeds up
class loading in production.

---

## 4. Build the frontend assets (do this locally, not on the server)

Per the project's design, Tailwind is compiled **locally** and the built
files are uploaded as static assets — no Node.js is required on Hostinger.

On your own machine (or this development session), from the project root:

```bash
npm ci
npm run build
```

This produces `public/build/` (a `manifest.json` plus hashed CSS/JS files).
**This folder is gitignored** — it won't come down with `git clone`. Upload
it separately:

- **With SSH**: `scp -P 65002 -r public/build u864959779@46.28.45.181:~/tractorduniya/public/build`
- **Without SSH**: zip `public/build` and upload/extract it via File Manager
  into `~/tractorduniya/public/build`.

Re-run `npm run build` and re-upload this folder **every time you change
any Blade view, CSS, or JS** — the hashed filenames in `manifest.json` are
how `@vite(...)` finds the right compiled files. A missing or stale
`public/build` is the most common cause of a broken-looking (unstyled)
site after deployment.

---

## 5. Document root — the part that's specific to shared hosting

Laravel expects the webserver's document root to point at the `public/`
folder, with everything else (`app/`, `.env`, `vendor/`, etc.) **outside**
it. Hostinger's basic shared plans only let you serve from `public_html`
directly, with no way to point it elsewhere. Pick whichever applies to you:

### Option A — your plan allows a custom document root (check first)

In **hPanel → Websites → [your domain] → Advanced**, look for a
**"Document root"** setting. If it's there, point it at:

```
/home/u864959779/tractorduniya/public
```

That's it — nothing else to do here, the rest of this guide applies as-is,
and `public_html` for that domain is unused. This is the cleaner option
when available.

### Option B — no custom document root (pure `public_html`)

If there's no such setting, `public_html` itself must serve the app.
**Never put the whole Laravel app inside `public_html`** — that would
expose `.env`, `app/`, and everything else to the internet. Instead:

1. Keep the full app at `~/tractorduniya` (outside `public_html`, as
   cloned in step 2).
2. Move the **contents** of `~/tractorduniya/public/` (not the folder
   itself) into `public_html`:
   ```bash
   cp -r ~/tractorduniya/public/* ~/public_html/
   cp ~/tractorduniya/public/.htaccess ~/public_html/.htaccess
   ```
3. Edit `~/public_html/index.php` and change the two paths so they point
   up to the real app location instead of the old sibling `../`:
   ```php
   // before:
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';

   // after (adjust the folder name if you used something other than "tractorduniya"):
   require __DIR__.'/../tractorduniya/vendor/autoload.php';
   $app = require_once __DIR__.'/../tractorduniya/bootstrap/app.php';
   ```
4. Every time you deploy an update, repeat step 2's copy (the `public/`
   contents, including the rebuilt `build/` folder from step 4) — but
   never copy over your edited `index.php`.

Either way, `.env` and the rest of the app stay unreachable from a browser.

---

## 6. Configure `.env`

Copy the example and edit it for production. If you're following Option B,
do this inside `~/tractorduniya` (NOT inside `public_html`):

```bash
cp .env.example .env
nano .env
```

Set at minimum:

```
APP_NAME="TractorDuniya"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tractorduniya.in

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u864959779_tractorduniya
DB_USERNAME=u864959779_dbuser
DB_PASSWORD="your-db-password"

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=your-mailbox@tractorduniya.in
MAIL_PASSWORD="your-mailbox-password"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=your-mailbox@tractorduniya.in
```

`APP_DEBUG=false` is important — leaving it `true` in production leaks
stack traces (including database credentials in error pages) to visitors.

Set up a mailbox for your domain in **hPanel → Emails** first if you want
the site to actually send email (lead notifications, password resets);
`MAIL_MAILER=log` is fine temporarily if you're not ready for that yet.

---

## 7. Finish the Laravel setup

Still in `~/tractorduniya`:

```bash
php artisan key:generate --force
php artisan storage:link
php artisan migrate --force
php artisan db:seed --class=RolePermissionSeeder --force
php artisan db:seed --class=StateDistrictSeeder --force
php artisan db:seed --class=BrandCategorySeeder --force
```

This creates the schema and the **base reference data** (roles, the
default admin account, Indian states/districts, brands/categories) —
**without** the removable demo tractors/dealers/blog content. If you want
that demo content too (to see the site fully populated before you add real
listings), also run:

```bash
php artisan db:seed --class=DemoDataSeeder --force
```

and remove it later with `php artisan demo:remove --force` once your real
catalog is in place.

**Never run `php artisan migrate:fresh` in production** — it drops every
table first. Use plain `migrate --force` for updates.

### Cron Jobs workaround (no SSH)

If your plan has no SSH, **hPanel → Advanced → Cron Jobs** is usually
available regardless. You can run any of the commands above once via a
one-off cron entry:

- Command: `php /home/u864959779/tractorduniya/artisan migrate --force`
- Schedule: anything (it only needs to fire once)
- After it has run (check **Cron Jobs → logs**, or just wait a minute and
  confirm the tables exist), **delete the cron job** so it doesn't re-run.

Repeat for each `db:seed` / `demo:remove` command you need.

---

## 8. File permissions

Laravel needs to write to `storage/` and `bootstrap/cache/`:

```bash
cd ~/tractorduniya
chmod -R 755 storage bootstrap/cache
```

If uploads still fail after this, Hostinger's PHP process may run as a
different user than your shell — try `chmod -R 775` instead, or ask
Hostinger support which user PHP-FPM runs as for your account.

---

## 9. SSL

In **hPanel → SSL**, issue a free SSL certificate for your domain (Hostinger
auto-issues Let's Encrypt for most plans — this is usually already on by
the time you reach this step). Confirm:

- The site loads over `https://` with a padlock, no warnings.
- `http://` requests redirect to `https://` (hPanel has an "Force HTTPS"
  toggle under SSL if not already on).
- `APP_URL` in `.env` uses `https://` (step 6) — sitemap/canonical URLs are
  generated from this value.

---

## 10. Go-live checklist

- [ ] `APP_ENV=production` and `APP_DEBUG=false` in `.env`
- [ ] Visit `/admin`, log in with `admin@tractorduniya.in` /
      `ChangeMe@2026`, and **immediately change this password** (Users →
      edit your own account)
- [ ] Add your real site info in **Admin → Settings** (contact phone/email,
      social links, EMI default rate)
- [ ] Replace or remove the demo banners, tractors, implements, dealers,
      and blog posts — run `php artisan demo:remove --force` once your
      real catalog is entered, or edit/delete individual demo rows from
      the admin if you'd rather keep some as a starting point
- [ ] Add your real tractor/implement catalog (or bulk-import via the CSV
      tool on the Tractors page) and at least one real, verified dealer
- [ ] Confirm `https://yourdomain/sitemap.xml` and `/robots.txt` resolve
      correctly and the `Sitemap:` line in `robots.txt` matches your real
      domain
- [ ] Submit the sitemap in **Google Search Console** (Search Console →
      Sitemaps → enter `sitemap.xml`)
- [ ] Submit a test lead through a tractor page and confirm it appears
      under **Admin → Leads**
- [ ] Submit a test "Sell your tractor" listing and confirm it appears
      under **Admin → Used Listings** as *Pending*
- [ ] Check `/hi/` loads correctly and the language switcher works
- [ ] Set up a mailbox and real `MAIL_*` settings if you want email
      notifications to actually send
- [ ] Decide a backup routine — **hPanel → Backups** can schedule automatic
      backups on most plans; at minimum, know how to trigger a manual one
      before any future update

---

## 11. Updating the site later

```bash
cd ~/tractorduniya
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear
php artisan view:clear
```

Then, from your own machine, rebuild and re-upload `public/build/` (step 4)
whenever a view/CSS/JS change was part of the update.
