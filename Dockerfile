FROM php:8.1-apache

# Installer SQLite et extensions PHP
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Copier les fichiers du projet
COPY . /var/www/html/

# Copier le script de démarrage
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Configurer Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

# Vérifier que index.html est bien accessible
RUN ls -l /var/www/html/

# Appliquer les bonnes permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exposer le port 80
EXPOSE 80

# Démarrer Apache via start.sh
CMD ["/usr/local/bin/start.sh"]
