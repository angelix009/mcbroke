<?php
// Afficher toutes les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Contenu du répertoire</h1>";
echo "<pre>";
system('ls -la /var/www/html');
echo "</pre>";

echo "<h1>Permissions du répertoire</h1>";
echo "<pre>";
system('ls -la /var/www/');
echo "</pre>";

echo "<h1>Configuration Apache</h1>";
echo "<pre>";
system('cat /etc/apache2/sites-available/000-default.conf');
echo "</pre>";

echo "<h1>PHP Info</h1>";
phpinfo();
?>
