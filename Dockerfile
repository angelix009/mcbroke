FROM php:8.1-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Copier tous les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Configurer le site Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Activer le VirtualHost sans essayer de redémarrer Apache
RUN a2ensite 000-default.conf

# Fixer les permissions des fichiers et dossiers
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exposer le port 80
EXPOSE 80

# Utiliser un script de démarrage
CMD ["/usr/local/bin/start.sh"]
