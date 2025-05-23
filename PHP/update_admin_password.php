<?php
require_once 'connexion.php';

$password = 'Admin123!';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE utilisateur SET password = ? WHERE email = 'admin@resauge.fr'");
    $result = $stmt->execute([$hash]);
    
    if($result) {
        echo "Le mot de passe admin a été mis à jour avec succès.<br>";
        echo "Email: admin@resauge.fr<br>";
        echo "Mot de passe: Admin123!<br>";
        echo "Nouveau hash: " . $hash;
    } else {
        echo "Erreur lors de la mise à jour du mot de passe.";
    }
} catch(PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?> 