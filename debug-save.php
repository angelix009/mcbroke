<?php
// debug-save.php - Script pour tester l'enregistrement des scores
// Placez ce fichier dans le même dossier que votre jeu

// Activer l'affichage de toutes les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Enregistrer les données reçues
$data = [
    'method' => $_SERVER['REQUEST_METHOD'],
    'post_data' => $_POST,
    'get_data' => $_GET,
    'raw_input' => file_get_contents('php://input')
];

// Écrire dans un fichier de log
file_put_contents('debug_log.txt', print_r($data, true) . "\n\n", FILE_APPEND);

// Vérifier les permissions d'écriture
$permissions = [
    'current_dir' => getcwd(),
    'is_writable' => is_writable('.'),
    'parent_writable' => is_writable('..'),
    'sqlite_file_exists' => file_exists('fry_game.db'),
    'sqlite_file_writable' => file_exists('fry_game.db') ? is_writable('fry_game.db') : 'N/A'
];

// Écrire les permissions dans le log
file_put_contents('debug_log.txt', print_r($permissions, true) . "\n\n", FILE_APPEND);

// Tester la connexion SQLite
try {
    $db_path = 'fry_game.db';
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $result = $pdo->query("SELECT count(*) FROM sqlite_master WHERE type='table' AND name='scores'")->fetchColumn();
    
    $db_test = [
        'connection' => 'success',
        'tables_exist' => $result > 0 ? 'yes' : 'no'
    ];
    
    // Si la table existe, essayer d'insérer une donnée de test
    if ($result > 0) {
        $stmt = $pdo->prepare("INSERT INTO scores (username, score, date_created) 
            VALUES ('TEST_ADDRESS', 999, datetime('now', 'localtime'))");
        $stmt->execute();
        $db_test['insert_test'] = 'success';
    }
    
} catch (PDOException $e) {
    $db_test = [
        'connection' => 'failed',
        'error' => $e->getMessage()
    ];
}

// Écrire le test de base de données dans le log
file_put_contents('debug_log.txt', print_r($db_test, true) . "\n\n", FILE_APPEND);

// Renvoyer toutes les informations en JSON
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'debug_info' => [
        'data' => $data,
        'permissions' => $permissions,
        'db_test' => $db_test
    ]
]);
?>