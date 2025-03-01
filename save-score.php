<?php
// save-score.php - Version corrigée pour SQLite
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log les données reçues pour débogage
$logData = "[" . date('Y-m-d H:i:s') . "] POST data: " . print_r($_POST, true) . "\n";
file_put_contents('debug.log', $logData, FILE_APPEND);

try {
    // Connexion directe à la base de données SQLite (sans passer par config.php)
    $db_path = 'fry_game.db';
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Créer la table si elle n'existe pas
    $pdo->exec("CREATE TABLE IF NOT EXISTS scores (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        score INTEGER NOT NULL,
        date_created TEXT NOT NULL
    )");
    
    // Récupérer les données POST
    $solanaAddress = isset($_POST['username']) ? trim($_POST['username']) : '';
    $score = isset($_POST['score']) ? intval($_POST['score']) : 0;
    
    // Vérification explicite
    if (empty($solanaAddress)) {
        throw new Exception("L'adresse Solana ne peut pas être vide");
    }
    
    if ($score <= 0) {
        throw new Exception("Le score doit être supérieur à zéro");
    }
    
    // Insérer les données avec les paramètres explicites
    $stmt = $pdo->prepare("INSERT INTO scores (username, score, date_created) 
                          VALUES (:username, :score, datetime('now', 'localtime'))");
    $stmt->bindParam(':username', $solanaAddress, PDO::PARAM_STR);
    $stmt->bindParam(':score', $score, PDO::PARAM_INT);
    $stmt->execute();
    
    // Renvoyer un succès
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
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

