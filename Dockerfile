FROM php:8.2-apache

# Récupération de Composer depuis son image officielle
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libonig-dev \
    libssl-dev \
    libzip-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP
RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \
    mbstring \
    curl \
    zip

# Installation de l'extension MongoDB
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Activation du module Apache
RUN a2enmod rewrite

WORKDIR /var/www/html

EXPOSE 80