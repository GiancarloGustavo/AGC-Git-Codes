<?php
$host = 'localhost';
$dbname = '';
$username = '';
$password = '';

try {
    // Connexion avec utf8mb4
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
        $username, 
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ]
    );

    // Vérification du charset actif
    $stmt = $pdo->query("SHOW VARIABLES LIKE 'character_set_connection'");
    $charset = $stmt->fetch();
    
    if ($charset['Value'] !== 'utf8mb4') {
        throw new Exception("Le charset de connexion n'est pas utf8mb4");
    }
    
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
} catch (Exception $e) {
    die("Erreur de configuration : " . $e->getMessage());
}
?>

