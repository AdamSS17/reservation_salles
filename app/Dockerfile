FROM php:8.3-fpm

RUN apt-get update && apt-get install -y unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json ./
COPY composer.lock* ./

RUN composer install --no-interaction --no-dev --optimize-autoloader --no-scripts

COPY . .

RUN composer dump-autoload --optimize

EXPOSE 8000

CMD ["sh", "-c", "php database/migrate.php && php database/seed.php && exec php -S 0.0.0.0:8000 -t public"]