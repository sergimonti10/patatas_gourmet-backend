# === Etapa 1: build con PHP 8.3 + Composer ===
FROM php:8.3-cli AS build
# Dependencias para extensiones/composer
RUN apt-get update && apt-get install -y git unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip
# Instalar Composer (copiándolo de la imagen oficial)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-ansi --no-progress
COPY . .
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
