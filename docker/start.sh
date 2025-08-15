#!/bin/sh
set -e
# -e: si algún comando falla, el script termina con error (mejor que arrancar a medias)

echo "[start] limpiando caches de Laravel..."
php artisan config:clear  || true
php artisan cache:clear   || true
php artisan route:clear   || true
php artisan view:clear    || true
# '|| true' evita que falle si alguna cache no existía aún

echo "[start] ejecutando migraciones..."
php artisan migrate --force || true
# --force: permite migrar en 'production'
# --no-interaction: no pide confirmaciones

echo "[start] lanzando Nginx + PHP-FPM..."
exec /entrypoint supervisord
# La imagen 'webdevops/php-nginx' ya trae un entrypoint que inicia supervisord con nginx y php-fpm.
# 'exec' sustituye el proceso del shell por supervisord (señales correctas para Docker).
