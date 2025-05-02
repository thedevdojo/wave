FROM serversideup/php:8.4-unit AS base
ENV AUTORUN_ENABLED=true PHP_OPCACHE_ENABLE=1
USER root
WORKDIR /var/www/html/
RUN apt-get update && install-php-extensions exif gd intl

FROM base AS composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.* ./
COPY . .
USER www-data
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist \
    && composer dump-autoload --classmap-authoritative --no-dev --no-scripts --optimize

FROM node:22-slim AS frontend
WORKDIR /app
COPY package*.json *.config.js ./
COPY public/ ./public
COPY resources/ ./resources
COPY --from=composer /var/www/html/vendor ./vendor
RUN npm ci && npm run build

FROM base
COPY --chown=www-data:www-data . .
COPY --from=composer --chown=www-data:www-data /var/www/html/vendor ./vendor
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build
USER www-data
COPY --chmod=755 ./entrypoint.d/ /etc/entrypoint.d/
