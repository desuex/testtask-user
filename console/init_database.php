<?php
require_once __DIR__ . '/../vendor/autoload.php';

$fullPath = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..');
$dotenv = Dotenv\Dotenv::createImmutable($fullPath);
$dotenv->load();
if (empty($_ENV['DB_PATH'])) {
    throw new Exception('DB_PATH environment variable is required to run this script!');
}
$databaseFile = $fullPath . DIRECTORY_SEPARATOR . $_ENV['DB_PATH'];
// Create the SQLite database file if it doesn't exist
if (!file_exists($databaseFile)) {
    if (!touch($databaseFile)) {
        trigger_error("Cannot create file $databaseFile" . PHP_EOL, E_USER_ERROR);
    }
    print_r("Database file created successfully: $databaseFile" . PHP_EOL);
} else {
    print_r("File $databaseFile already exists!" . PHP_EOL);
}

// Establish a connection to the SQLite database
$pdo = new PDO('sqlite:' . $databaseFile);

// Create the `users` table
$createUsersTableQuery = "
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(64) NOT NULL,
        email VARCHAR(256) NOT NULL,
        created DATETIME NOT NULL,
        deleted DATETIME,
        notes TEXT
    );
";

$pdo->exec($createUsersTableQuery);

// Create unique indexes
$createEmailIndexQuery = "CREATE UNIQUE INDEX IF NOT EXISTS users_email_uindex ON users (email);";
$pdo->exec($createEmailIndexQuery);

$createNameIndexQuery = "CREATE UNIQUE INDEX IF NOT EXISTS users_name_uindex ON users (name);";
$pdo->exec($createNameIndexQuery);

print_r("Table `users` created successfully!" . PHP_EOL);

// Create the `user_logs` table
$createUserLogsTableQuery = "
    CREATE TABLE IF NOT EXISTS user_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        action TEXT NOT NULL,
        timestamp DATETIME NOT NULL
    );
";

$pdo->exec($createUserLogsTableQuery);

$createUserLogsUserIndexQuery = "CREATE INDEX IF NOT EXISTS user_logs_user_id_index ON user_logs (user_id);";
$pdo->exec($createUserLogsUserIndexQuery);

print_r("Table `user_logs` created successfully!" . PHP_EOL);
