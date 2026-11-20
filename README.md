# TractorDuniya.in

A tractor-industry marketplace and information platform for the Indian
market: browse and compare tractors and farm implements, locate dealers,
buy or sell used equipment, and generate/manage leads — with a Laravel +
Filament admin a non-technical person can run day to day.

Built with Laravel 11, Filament 3, MySQL (SQLite for local dev), and
Tailwind CSS.

## Local setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm run build   # or `npm run dev` while working on views/CSS
php artisan serve
```

This seeds base reference data (roles, a default admin, Indian states/
districts, brands/categories) plus removable demo content (sample
tractors, implements, dealers, blog posts). Log into `/admin` with:

```
admin@tractorduniya.in / ChangeMe@2026
```

**Change this password before going anywhere near production.**

Strip the demo content back out at any time (keeps roles/users/states/
brands/categories) with:

```bash
php artisan demo:remove
```

## Running tests

```bash
php artisan test
```

Tests run against the real SQLite dev database (not an in-memory one —
see `phpunit.xml`), so re-run `php artisan migrate --seed` afterward if
you want a clean demo dataset again.

## Multilingual (EN/HI)

Every public route exists at both `/path` (English) and `/hi/path`
(Hindi). Static UI strings are translated via `resources/lang/hi.json`
(wrap new strings in `__('...')` and add the Hindi value there). Per-record
content translations (blog/news title, excerpt, body) are managed under
**Admin → Translations**.

## Deploying

See [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md) for the full Hostinger
hPanel deployment guide and go-live checklist.

## About Laravel

This project is built on the [Laravel](https://laravel.com) framework.
See [laravel.com/docs](https://laravel.com/docs) for framework-level
documentation.
