<?php
// Informations de connexion à la base de données
define('DB_HOST', 'localhost');     // L'hôte de la base de données
define('DB_NAME', 'resauge');       // Le nom de la base de données
define('DB_USER', 'root');          // L'utilisateur de la base de données (par défaut 'root' pour XAMPP)
define('DB_PASS', '');              // Le mot de passe (vide par défaut pour XAMPP)

try {
    // Création de la connexion PDO
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
} catch(PDOException $e) {
    // En cas d'erreur, afficher un message d'erreur
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier le rôle de l'utilisateur
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// Fonction pour nettoyer les entrées utilisateur
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?> 