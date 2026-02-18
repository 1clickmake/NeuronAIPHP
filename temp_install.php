<?php
$host = 'localhost';
$db   = 'ai_php';
$user = 'root';
$pass = 'W**sang12ae';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // 1. Create DB if not exists
    $pdo = new PDO("mysql:host=$host", $user, $pass, $options);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET $charset COLLATE utf8mb4_unicode_ci");
    
    // 2. Connect to DB
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 3. Run setup.sql
    $sqlFile = __DIR__ . '/views/install/setup.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        
        // Remove comments to avoid issues with some drivers/parsers if needed, 
        // but PDO::exec should handle multiple statements if emulation is allowed or depending on driver.
        // Actually PDO::exec might fail on multiple statements depending on config.
        // Let's try splitting by semicolon if simple exec fails, but usually it works if configured right.
        // However, standard PDO might not support multiple statements in one go depending on MySQL version/settings.
        // A robust way is to split via regex, but setup.sql is simple. 
        // Let's try executing the whole block.
        
        try {
            $pdo->exec($sql);
            echo "Database initialized successfully.\n";
        } catch (PDOException $e) {
            // Fallback: Split by semicolon
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($statements as $stmt) {
                if (!empty($stmt)) {
                    $pdo->exec($stmt);
                }
            }
            echo "Database initialized (split mode).\n";
        }

        // 4. Create Default Admin User
        $adminId = 'admin';
        $adminPass = password_hash('1234', PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE user_id = ?");
        $stmt->execute([$adminId]);
        if ($stmt->fetchColumn() == 0) {
            $stmt = $pdo->prepare("INSERT INTO users (user_id, username, password, email, role, level) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$adminId, 'Super Admin', $adminPass, 'admin@example.com', 'admin', 10]);
            echo "Default admin created (ID: admin / PW: 1234)\n";
        } else {
            echo "Admin user already exists.\n";
        }
        
    } else {
        echo "Error: setup.sql not found.\n";
    }

} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
