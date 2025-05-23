<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est connecté et est un étudiant ou un enseignant
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['student', 'teacher'])) {
    header('Location: ../index.php');
    exit();
}

// Récupérer les informations de l'utilisateur depuis la session
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservations étudiants - Université Eiffel</title>
    <link rel="stylesheet" href="../CSS/stylestudent.css">
    <style>
        .course-card {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .course-card:hover {
            transform: translateY(-5px);
        }
        .course-card a {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
        .logout-link {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            background-color: #dc3545;
            border-radius: 4px;
        }
        .logout-link:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body id="page0">
    <a href="logout.php" class="logout-link">Déconnexion</a>
   
    <nav>
        <a href="student.php">Accueil</a>
        <a href="#">Notifications</a>
        <a href="#">Réservation</a>
        <a href="#">Disponibilité</a>
    </nav>

    <div class="main-content">
        <h1>Bonjour <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> !</h1>
        <h2>Réserver</h2>
        <div class="course-cards">
            <div class="course-card">
                <a href="reservation_salle.php">
                    <img src="../images/salle.png" alt="Réservation de salle">
                    <h3>Salle</h3>
                    <p>Réserver une salle pour vos activités</p>
                </a>
            </div>
            <div class="course-card">
                <a href="reservation_materiel.php">
                    <img src="../images/materiel.png" alt="Réservation de matériel">
                    <h3>Matériel</h3>
                    <p>Réserver du matériel pour vos besoins</p>
                </a>
            </div>
            <div class="course-card">
                <a href="#">
                    <img src="../images/info.png" alt="Informations">
                    <h3>Information</h3>
                    <p>Consulter les informations importantes</p>
                </a>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy;2025 Université Eiffel. Tous droits réservés.</p>
    </footer>

</body>
</html>