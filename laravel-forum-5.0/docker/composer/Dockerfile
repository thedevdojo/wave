FROM php:8.0-cli

# System dependencies
RUN apt-get update && apt-get install -y libzip-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer
