FROM php:8.1-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Copier tous les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Copier et donner les bons droits au script de démarrage
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Configurer le site Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Activer le VirtualHost
RUN a2ensite 000-default.conf

# Fixer les permissions des fichiers et dossiers
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exposer le port 80
EXPOSE 80

# Utiliser le script comme commande de démarrage
CMD ["/usr/local/bin/start.sh"]
