<?php
// db_setup.php - Script to create SQLite database and table
$db_path = 'fry_game.db';

try {
    // Create/connect to SQLite database
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if table exists
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='scores'");
    $tableExists = $stmt->fetchColumn();
    
    if (!$tableExists) {
        // Create scores table
        $pdo->exec("CREATE TABLE scores (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            score INTEGER NOT NULL,
            date_created TEXT NOT NULL
        )");
        echo "Scores table created successfully.<br>";
    } else {
        echo "Scores table already exists.<br>";
    }
    
    echo "Database setup completed.<br>";
    echo "Using SQLite database at: " . realpath($db_path);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>