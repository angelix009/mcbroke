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

# Créer script de démarrage
RUN echo '#!/bin/bash\nphp /var/www/html/db_setup.php\napache2-foreground' > /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Configurer les permissions correctes
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \; && \
    find /var/www/html -name "*.php" -exec chmod 755 {} \;

# Exposer le port 80
EXPOSE 80

# Utiliser le script comme commande de démarrage
CMD ["/usr/local/bin/start.sh"]
