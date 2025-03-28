<?php
$host = 'localhost';
$dbname = 'screentl_data_db';
$user = 'screentl_data_db'; 
$pass = 'c&N)]RWFU9vX'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
}
?>
