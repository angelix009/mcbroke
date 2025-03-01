<?php
// simplified-save.php - Version simplifiée pour sauvegarder les scores

// Activer l'affichage de toutes les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Créer la base de données et la table si elles n'existent pas
try {
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
    
    // Si nous arrivons ici, c'est que tout va bien avec la base de données
    
    // Vérifions s'il s'agit d'une requête POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $score = isset($_POST['score']) ? intval($_POST['score']) : 0;
        
        // Insérer les données
        $stmt = $pdo->prepare("INSERT INTO scores (username, score, date_created) VALUES (?, ?, datetime('now', 'localtime'))");
        $stmt->execute([$username, $score]);
        
        // Renvoyer un succès
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
    
    // Si ce n'est pas une requête POST, renvoyer une erreur
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Use POST.']);
    
} catch (Exception $e) {
    // En cas d'erreur, renvoyer le message d'erreur
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>

<?php
// simplified-get-scores.php - Version simplifiée pour récupérer les scores

// Activer l'affichage de toutes les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
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
    
    // Récupérer les scores
    $stmt = $pdo->query("SELECT username, score, date_created as date FROM scores ORDER BY score DESC LIMIT 10");
    $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Renvoyer les scores
    header('Content-Type: application/json');
    echo json_encode($scores);
    
} catch (Exception $e) {
    // En cas d'erreur, renvoyer le message d'erreur
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>