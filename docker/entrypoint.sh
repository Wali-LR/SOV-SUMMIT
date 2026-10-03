#!/bin/bash
set -e

echo "=========================================="
echo " Starting Laravel Dokploy Container       "
echo "=========================================="

# Export defaults
export AUTORUN_WORKER=${AUTORUN_WORKER:-false}
export AUTORUN_SCHEDULER=${AUTORUN_SCHEDULER:-false}

# 1. Ensure storage and cache directory structures exist
echo "==> Setting up storage and bootstrap cache directories..."
mkdir -p \
    /var/www/html/storage/app/public \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 2. Symlink storage/app/public to public/storage if not linked
if [ ! -L /var/www/html/public/storage ]; then
    echo "==> Creating storage symlink..."
    php artisan storage:link || true
fi

# 3. Wait for database connection if DB_HOST is configured
if [ -n "$DB_HOST" ] && [ "$DB_CONNECTION" = "mysql" ]; then
    echo "==> Checking database connection on $DB_HOST:${DB_PORT:-3306}..."
    php -r '
    $host = getenv("DB_HOST");
    $port = getenv("DB_PORT") ?: 3306;
    $db   = getenv("DB_DATABASE");
    $user = getenv("DB_USERNAME");
    $pass = getenv("DB_PASSWORD");
    $maxTries = 30;
    $connected = false;

    for ($i = 1; $i <= $maxTries; $i++) {
        try {
            $pdo = new PDO("mysql:host={$host};port={$port};dbname={$db}", $user, $pass, [
                PDO::ATTR_TIMEOUT => 2,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $connected = true;
            echo "==> Successfully connected to database.\n";
            exit(0);
        } catch (Throwable $e) {
            echo "Waiting for database ($i/$maxTries)... \n";
            sleep(1);
        }
    }
    echo "WARNING: Could not connect to database after {$maxTries} attempts: " . $e->getMessage() . "\n";
    exit(0);
    '
fi

# 4. Run database migrations if requested
if [ "${AUTORUN_MIGRATIONS:-false}" = "true" ] || [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "==> Running database migrations..."
    php artisan migrate --force --isolated || true
fi

# 5. Cache configurations and routes in production
if [ "${APP_ENV:-production}" = "production" ] && [ "${SKIP_OPTIMIZE:-false}" != "true" ]; then
    echo "==> Optimizing configuration, routes, and views..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

echo "==> Initialization complete. Launching: $@"
exec "$@"
