FROM php:8.4-fpm

# UID/GID de l'utilisateur hôte (passé par docker-compose)
ARG USER_UID=1001
ARG USER_GID=1001

# Dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libxml2-dev libonig-dev librabbitmq-dev libssl-dev curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo pdo_mysql zip gd intl opcache bcmath xml mbstring \
    && pecl install amqp && docker-php-ext-enable amqp \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Remapper www-data sur l'UID/GID de l'hôte
RUN usermod -u ${USER_UID} www-data && groupmod -g ${USER_GID} www-data

# Config PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Créer les dossiers nécessaires avec les bons droits
RUN mkdir -p /var/www/html/var /var/www/html/public/uploads/images /var/www/.npm \
    && chown -R www-data:www-data /var/www

WORKDIR /var/www/html

# Copier le projet et installer les dépendances en tant que www-data
COPY --chown=www-data:www-data . .
USER www-data
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install && npm run build

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
