# Stage de base
FROM php:8.4-fpm-alpine AS base

# Variables d'environnement
ENV PHP_OPCACHE_ENABLE=1
ENV PHP_OPCACHE_ENABLE_CLI=0
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0
ENV PHP_OPCACHE_REVALIDATE_FREQ=0

# Installation des dépendances système
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    icu-dev \
    oniguruma-dev \
    postgresql-dev \
    imagemagick-dev \
    supervisor \
    nginx \
    && rm -rf /var/cache/apk/*

# Installation et configuration des extensions PHP
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    --with-xpm \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    gd \
    zip \
    intl \
    mbstring \
    bcmath \
    opcache \
    exif \
    pcntl
    #sockets

# Installation de Imagick
RUN apk add --no-cache --virtual .build-deps autoconf g++ make \
    && apk add --no-cache --virtual .imagick-deps imagemagick-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .imagick-deps .build-deps

# Installation de Redis extension
RUN (php -m | grep -q redis) || (pecl install redis && docker-php-ext-enable redis)


# Installation de Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Création de l'utilisateur www
RUN addgroup -g 1000 www \
    && adduser -u 1000 -G www -s /bin/sh -D www

# Configuration du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers de configuration
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Configuration des permissions
RUN chown -R www:www /var/www/html

# Stage de développement
FROM base AS development

# Installation de Xdebug pour le développement
RUN apk add --no-cache autoconf gcc g++ make linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del autoconf gcc g++ make linux-headers

# Configuration Xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

USER www

EXPOSE 9000

CMD ["php-fpm"]

# Stage de production
FROM base AS production

# Configuration optimisée pour la production
ENV PHP_OPCACHE_ENABLE=1
ENV PHP_OPCACHE_ENABLE_CLI=1
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0
ENV PHP_OPCACHE_REVALIDATE_FREQ=0

# Copie du projet (sera fait lors du build)
COPY --chown=www:www . /var/www/html

# Installation des dépendances Composer (production uniquement)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress \
    && composer clear-cache

# Optimisation Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Configuration des permissions pour la production
RUN chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www

EXPOSE 9000

CMD ["php-fpm"]
