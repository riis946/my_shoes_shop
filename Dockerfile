FROM php:8.4-apache

# Installation des dépendances système et des extensions PHP
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libicu-dev \
    && docker-php-ext-install pdo pdo_pgsql intl opcache

# Activation du module rewrite d'Apache
RUN a2enmod rewrite

# Configuration du document root Apache vers public/ + Autorisation du .htaccess
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copie du projet
WORKDIR /var/www/html
COPY . .

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Création du dossier var et permissions
RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html/var

# Exécution automatique des migrations et démarrage d'Apache
CMD php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration && apache2-foreground
