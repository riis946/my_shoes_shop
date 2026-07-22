FROM php:8.4-apache

# Installation des dépendances système et des extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libicu-dev \
    && docker-php-ext-install pdo pdo_pgsql intl opcache

# Activation du module rewrite d'Apache
RUN a2enmod rewrite

# Configuration du document root Apache vers le dossier public/ de Symfony
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# Copie du code du projet
WORKDIR /var/www/html
COPY . .

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Création du dossier var s'il n'existe pas et attribution des permissions
RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html/var
