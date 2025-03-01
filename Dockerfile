FROM php:8.1-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Configurer Apache pour autoriser l'accès aux fichiers
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/access-control.conf \
    && a2enconf access-control

# Copier tous les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Créer le script de démarrage s'il n'existe pas déjà dans le projet
RUN if [ ! -f /var/www/html/start.sh ]; then \
    echo '#!/bin/bash\n\
php /var/www/html/db_setup.php\n\
apache2-foreground' > /usr/local/bin/start.sh; \
else \
    cp /var/www/html/start.sh /usr/local/bin/; \
fi

# Rendre le script exécutable
RUN chmod +x /usr/local/bin/start.sh

# Configurer les permissions correctes pour tous les fichiers
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \; && \
    find /var/www/html -name "*.php" -exec chmod 755 {} \;
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN ls -la /var/www/html > /var/www/html/files.txt

# Exposer le port 80
EXPOSE 80

# Utiliser le script comme commande de démarrage
CMD ["/usr/local/bin/start.sh"]
