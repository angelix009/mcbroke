#!/bin/bash

# Exécuter le script d'initialisation de la base de données
php /var/www/html/db_setup.php

# Démarrer Apache en premier plan
exec apache2-foreground
