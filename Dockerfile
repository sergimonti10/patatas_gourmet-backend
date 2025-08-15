# Etapa 1: solo para instalar dependencias PHP con Composer (no es la imagen final)
FROM composer:2 AS build
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-ansi --no-progress
COPY . .
RUN composer dump-autoload --optimize

# Etapa 2: runtime con Nginx + PHP-FPM (lo que se ejecuta en Koyeb)
FROM webdevops/php-nginx:8.2-alpine
ENV WEB_DOCUMENT_ROOT=/app/public
WORKDIR /app
COPY --from=build /app /app

# permisos para las carpetas de cache/logs de Laravel
RUN chown -R application:application storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Copiamos el script de arranque y lo dejamos ejecutable
COPY docker/start.sh /app/docker/start.sh
RUN chmod +x /app/docker/start.sh && sed -i 's/\r$//' /app/docker/start.sh

EXPOSE 80
CMD ["/app/docker/start.sh"]
