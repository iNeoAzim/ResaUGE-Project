<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['utilisateur'];
echo "<h2>Bonjour " . htmlspecialchars($user['prenom']) . " (" . $user['role'] . ")</h2>";

switch ($user['role']) {
    case 'etudiant':
        echo "<a href='reserver_materiel.php'>Réserver un matériel</a><br>";
        echo "<a href='reserver_salle.php'>Réserver une salle</a>";
        break;
    case 'agent':
    case 'administrateur':
        echo "<a href='gerer_reservations.php'>Gérer les réservations</a>";
        break;
    case 'enseignant':
        echo "<a href='reserver_salle.php'>Réserver une salle</a>";
        break;
}
?>
<a href="logout.php">Déconnexion</a>
