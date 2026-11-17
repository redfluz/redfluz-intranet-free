# Redfluz Intranet (Free)

An internal-operations workspace built with Laravel — staff news, messaging, tasks,
leave, claims, attendance and role-based menus. This is **not** an accounting or ERP
application; those modules belong to the separate **Redfluz Accounting Premium** product.

## Free vs Premium

This edition is deliberately scoped to the intranet. The table compares it with the
premium (accounting/ERP) edition of the Redfluz family.

| Capability | Intranet Free | Accounting Premium |
|---|:---:|:---:|
| Personnel dashboard & news feed | ✅ | — |
| Internal chat (messages, attachments, realtime) | ✅ | ✅ |
| Personal task board & task dashboard | ✅ | ✅ |
| Leave applications, calendar & holidays | ✅ | ✅ |
| Claim applications & approval workflow | ✅ | ✅ |
| Self-service clock in / clock out | ✅ | ✅ |
| HR review screens (leave, claim, attendance) | ✅ | ✅ |
| Role / permission / menu management | ✅ | ✅ |
| System settings, users & notifications | ✅ | ✅ |
| Multi-language UI (EN / ID / MS) | ✅ | ✅ |
| CRM (contacts, companies) | — | ✅ |
| Inventory & stock | — | ✅ |
| Finance (journal, ledger, chart of accounts) | — | ✅ |
| Bank & payments | — | ✅ |
| Purchase & sales documents | — | ✅ |
| Fixed assets | — | ✅ |
| Payroll / salary | — | ✅ |
| Onboarding & recruitment | — | ✅ |
| Financial posting periods | — | ✅ |
| WebSocket notifications | ✅ (chat via Reverb) | ✅ |

> The premium edition is a separate repository. Features missing here are intentionally
> absent, not disabled — the free codebase contains no accounting/ERP models,
> migrations, controllers or views.

## Requirements

- PHP >= 8.4
- Composer
- Node.js >= 18 & NPM
- Database: SQLite (default, zero configuration) or MySQL/MariaDB

Recommended local environments: Laragon (Windows), Laravel Herd (macOS), or Docker.

## Quick Install

```bash
git clone <repository-url>
cd redfluz-intranet-free

composer install
npm install && npm run build

cp .env.example .env
php artisan app:install

# Optional demo data (sample news, messages, tasks, leave and claims):
php artisan app:install --demo
```

The installer generates the app key, creates/connects the database, runs the
migrations, seeds roles, users, menus and settings, and creates the storage symlink.

**Default login:** `admin@example.com` / `password`

## Realtime chat (Reverb)

Chat delivery uses Laravel Echo with Reverb. For live messages and the unread badge,
fill in the Reverb block in `.env`:

```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=app-id
REVERB_APP_KEY=app-key
REVERB_APP_SECRET=app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

Then rebuild assets so Vite picks up the `VITE_REVERB_*` values, and run the Reverb
server alongside the web server:

```bash
npm run build
php artisan reverb:start
```

`composer dev` starts the web server, queue worker, Reverb and Vite together.

## Switching to MySQL (optional)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=redfluz_intranet
DB_USERNAME=root
DB_PASSWORD=your_password
```

Then re-run `php artisan app:install`.

## Reset (Factory Reset)

Wipes the database, uploaded files, logs, sessions and caches:

```bash
php artisan app:reset
php artisan app:reset --force   # no confirmation prompt
```

Follow with `php artisan app:install` to set up fresh.

## Manual Install

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed          # clean install
# or: php artisan db:seed --demo
php artisan storage:link
php artisan serve
```

## Quality checks

```bash
vendor/bin/phpstan analyse --no-progress
php artisan verify:inline-js
composer ci
```

## Production

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
