<?php
// config.php - Configuration for SQLite database
$db_path = __DIR__ . '/fry_game.db';

try {
    // Create/connect to SQLite database
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS scores (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        score INTEGER NOT NULL,
        date_created TEXT NOT NULL
    )");
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $pdo = null;
}
?>