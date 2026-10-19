#!/bin/sh

set -eu

mkdir -p \
    /app/bootstrap/cache \
    /app/storage/app/private \
    /app/storage/app/public \
    /app/storage/framework/cache/data \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/storage/logs \
    /var/lib/ai-solution

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    database_path="${DB_DATABASE:-/var/lib/ai-solution/database.sqlite}"
    mkdir -p "$(dirname "$database_path")"
    touch "$database_path"
fi

php artisan package:discover --ansi >/dev/null
php artisan storage:link --force >/dev/null 2>&1 || true
php artisan migrate --force >/dev/null 2>&1 || true
php artisan articles:publish-scheduled >/dev/null 2>&1 || true

# Background scheduler loop di dalam container (setiap 60 detik)
(
    while true; do
        sleep 60
        php /app/artisan schedule:run --no-interaction >/dev/null 2>&1 || true
    done
) &

exec "$@"
