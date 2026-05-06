#!/bin/sh
set -e

# vendor volume izinlerini düzelt (named volume root sahibinde geliyor)
chown -R www:www /var/www/vendor /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

# vendor yoksa container içinde composer install çalıştır (www olarak)
if [ ! -f "/var/www/vendor/autoload.php" ]; then
    echo "[entrypoint] Running composer install..."
    su-exec www composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# php-fpm root olarak başlar — pool.conf'u aracılığıyla www kullanıcısıyla çalışır
exec "$@"
