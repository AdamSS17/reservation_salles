FROM php:8.3-fpm

# Extensions nécessaires : pdo_mysql pour Eloquent, zip pour Composer
RUN apt-get update && apt-get install -y unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer (binaire copié depuis l'image officielle Composer)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier composer.json (et composer.lock si présent) avant le reste :
# tant que les dépendances ne changent pas, Docker réutilise le cache
# et ne relance pas composer install à chaque modification de code.
COPY composer.json ./
COPY composer.lock* ./

RUN composer install --no-interaction --no-dev --optimize-autoloader --no-scripts

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]