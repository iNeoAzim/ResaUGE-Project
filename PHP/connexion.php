<?php
$host = 'localhost';
$dbname = 'reservation';
$user = 'root';        // à adapter selon ton serveur
$pass = '';            // vide si tu es en local (MAMP/XAMPP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
