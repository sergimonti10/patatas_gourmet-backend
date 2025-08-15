# === Etapa 1: build con PHP 8.3 + Composer ===
FROM php:8.3-cli AS build
RUN apt-get update && apt-get install -y git unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
# 1) Instala dependencias sin scripts para aprovechar caché
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-ansi --no-progress --no-scripts

# 2) Copia el resto del proyecto (ahora ya llega 'artisan')
COPY . .

# 3) Re-ejecuta install (ahora sí con scripts) y optimiza autoload
RUN composer install --no-dev --prefer-dist --no-interaction --no-ansi --no-progress
RUN composer dump-autoload --optimize

# === Etapa 2: runtime con Nginx + PHP-FPM 8.2 ===
FROM webdevops/php-nginx:8.2-alpine
ENV WEB_DOCUMENT_ROOT=/app/public
WORKDIR /app
COPY --from=build /app /app

# Permisos Laravel
RUN chown -R application:application storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Script de arranque
COPY docker/start.sh /app/docker/start.sh
RUN chmod +x /app/docker/start.sh && sed -i 's/\r$//' /app/docker/start.sh

EXPOSE 80
CMD ["/app/docker/start.sh"]
