# Deployment

1. PHP 8.3+, MySQL 8, Redis, Composer, Node.
2. Copy `.env.example`. Set `APP_KEY`, `DB_*`, `REDIS_*`, `ADMIN_PANEL_PREFIX=desk`, `MILL_BRAND`.
3. `composer install --no-dev` and `php artisan mill:install`.
4. Point nginx `root` at `public`. FastPanel: site root `public`, PHP 8.3.
5. Supervisor runs `php artisan horizon` and `php artisan reverb:start`.
6. Cron: `* * * * * php /path/artisan schedule:run`.
7. Queue the daily MIS at 07:00, issue escalation hourly, and the SQLite/file backup at 02:00.
8. Health: `GET /up`.

Zero-downtime: build a release directory, `composer install`, `php artisan migrate --force`, then switch the `current` symlink and reload php-fpm. Do not run `migrate:fresh` on a live mill.

Backup: `storage/app/backups` keeps the last 7 copies. Restore by replacing the database file or importing the MySQL dump taken from that copy.
