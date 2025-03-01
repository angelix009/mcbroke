<?php
// get-scores.php - Version corrigée pour SQLite
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Connexion directe à la base de données SQLite (sans passer par config.php)
    $db_path = 'fry_game.db';
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer les scores
    $stmt = $pdo->query("SELECT username, score, date_created as date 
                         FROM scores 
                         ORDER BY score DESC 
                         LIMIT 10");
    $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Renvoyer les scores en JSON
    header('Content-Type: application/json');
    echo json_encode($scores);
} catch (Exception $e) {
    // Log l'erreur
    $errorLog = "[" . date('Y-m-d H:i:s') . "] Error: " . $e->getMessage() . "\n";
    file_put_contents('error.log', $errorLog, FILE_APPEND);
    
    // Renvoyer l'erreur
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>