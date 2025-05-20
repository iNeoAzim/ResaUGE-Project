<?php
$host = 'localhost';
$db = 'authentification';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$authentification;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn,$user,$pass,$options);
} catch (\PDOException $e) {
    die('Connexion échouée : ' . $e->getMessage());
}
?>