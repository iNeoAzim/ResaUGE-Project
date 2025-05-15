<?php
session_start();
// Supposons que le nom d'utilisateur est stocké dans $_SESSION['username']
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Enseignant';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Enseignant - Université Eiffel</title>
    <link rel="stylesheet" href="../CSS/styleteacher.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: #f4f6fb;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 220px;
            background: #1976d2;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 30px 0;
            min-height: 100vh;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .sidebar nav a {
            color: #fff;
            text-decoration: none;
            padding: 15px 30px;
            display: block;
            transition: background 0.2s;
            font-size: 1.1em;
        }
        .sidebar nav a:hover {
            background: #1565c0;
        }
        .main-content {
            flex: 1;
            padding: 40px 60px;
        }
        .welcome {
            font-size: 2em;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #1976d2;
            margin-bottom: 30px;
        }
        .teacher-cards {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .teacher-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(60,60,100,0.08);
            padding: 30px 24px;
            flex: 1 1 260px;
            min-width: 260px;
            max-width: 320px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .teacher-card:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 8px 24px rgba(60,60,100,0.15);
        }
        .teacher-card img {
            width: 64px;
            height: 64px;
            margin-bottom: 18px;
        }
        .teacher-card h3 {
            margin: 0 0 10px 0;
            font-size: 1.2em;
            color: #1976d2;
        }
        .teacher-card p {
            color: #555;
            text-align: center;
            font-size: 1em;
        }
        footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100vw;
            background: #1976d2;
            color: #fff;
            text-align: center;
            padding: 12px 0;
            font-size: 0.95em;
            letter-spacing: 1px;
        }
        @media (max-width: 900px) {
            .main-content { padding: 20px 10px; }
            .teacher-cards { flex-direction: column; gap: 18px; }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>Enseignant</h2>
        <nav>
            <a href="#">Accueil</a>
            <a href="#">Notifications</a>
            <a href="#">Réservation</a>
            <a href="#">Disponibilité</a>
            <a href="#">Déconnexion</a>
        </nav>
    </aside>
    <main class="main-content">
        <div class="welcome">Bonjour, <?php echo $username; ?> !</div>
        <div class="subtitle">Espace enseignant</div>
        <div class="teacher-cards">
            <div class="teacher-card">
                <img src="/img/emplacement.png" alt="Réserver une salle">
                <h3>Réserver une salle</h3>
                <p>Consultez la disponibilité des salles et effectuez vos réservations en quelques clics.</p>
            </div>
            <div class="teacher-card">
                <img src="/img/cadenas-verrouille.png" alt="Réserver du matériel">
                <h3>Réserver du matériel</h3>
                <p>Réservez le matériel pédagogique nécessaire pour vos cours ou événements.</p>
            </div>
            <div class="teacher-card">
                <img src="/img/nom.png" alt="Informations personnelles">
                <h3>Informations personnelles</h3>
                <p>Gérez vos informations, consultez vos réservations et modifiez vos disponibilités.</p>
            </div>
        </div>
    </main>
    <footer>
        &copy;2025 Université Eiffel. Tous droits réservés.
    </footer>
</body>
</html>