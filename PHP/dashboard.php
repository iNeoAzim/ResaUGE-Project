<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservations étudiants - Université Eiffel</title>
    <link rel="stylesheet" href="../CSS/stylestudent.css">
</head>
<body id="page0">

   
    <nav>
        <a href="#">Accueil</a>
        <a href="#">Notifications</a>
        <a href="#">Réservation</a>
        <a href="#">Disponibilité</a>
        <a href="logout.php">Déconnexion</a>
    </nav>

    <div class="main-content">
        <h1>Bonjour ! {username}</h1>
        <h2>Réserver</h2>
        <div class="course-cards">
            <div class="course-card">
                <img src="." alt="reserv0">
                <h3>Salle</h3>
                <p>.</p>
            </div>
            <div class="course-card">
                <img src="." alt="reserv1">
                <h3>Matériel</h3>
                <p>.</p>
            </div>
            <div class="course-card">
                <img src="." alt="reserv2">
                <h3>Information</h3>
                <p>.</p>
            </div>
        </div>
    </div>


    <footer>
        <p>&copy;2025 Université Eiffel. Tous droits réservés.</p>
    </footer>

</body>
</html>


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

