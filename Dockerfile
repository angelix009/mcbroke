FROM php:8.1-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Créer le dossier data s'il n'existe pas et configurer les permissions
RUN mkdir -p /var/www/html/data && \
    chown -R www-data:www-data /var/www/html/data && \
    chmod -R 755 /var/www/html/data

# Exposer le port 80
EXPOSE 80

# Copier et rendre exécutable le script de démarrage
COPY start.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/start.sh
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \;

# S'assurer que les scripts PHP sont exécutables
RUN find /var/www/html -name "*.php" -exec chmod 755 {} \;

# Utiliser le script comme commande de démarrage
CMD ["start.sh"]
