#!/bin/bash

# Vérifier si db_setup.php existe avant de l'exécuter
if [ -f "/var/www/html/db_setup.php" ]; then
    php /var/www/html/db_setup.php
else
    echo "⚠️ Warning: db_setup.php not found, skipping database setup"
fi

# Démarrer Apache
exec apache2-foreground
