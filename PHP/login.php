<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../CSS/styleindex.css">
    <title>Modern Login Page</title>
</head>

<?php
session_start();
require '../PHP/connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $mot_de_passe === $user['mot_de_passe']) {
        $_SESSION['utilisateur'] = $user;
        header('Location: dashboard.php');
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect";
    }
}
?>
<body id="login-page">
    <div class="container" id="container">
        <div class="form-container sign-up">
            <div class="register-title"><h1>Créer un compte</h1></div>
            <form action="register.php" method="POST">
                <input type="text" name="nom" placeholder="Nom" id="register-name" required> 
                <input type="text" name="prenom" placeholder="Prénom" id="register-firstname" required>
                <input type="email" name="email" placeholder="Email" id="register-email" required>
                <input type="password" name="password" placeholder="Mot de passe" id="register-password" required>
                <input type="text" name="pseudo" placeholder="Pseudo" id="register-pseudo" required>
                <input type="text" name="postal" placeholder="Code Postal" id="register-postal" pattern="^\d{5}$" title="Entrez un code postal Français à 5chiffres" required>
                <input type="date" name="birthdate" placeholder="Date de naissance" id="register-birthdate" required>
                <select name="role" id="register-role" required>
                    <option value="student">Etudiant</option>
                    <option value="teacher">Enseignant</option>
                    <option value="agent">Agent</option>
                    <option value="admin">Admin</option>
                </select>
                <button>S'inscrire</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="login.php" method="POST">
                <h1>Se connecter</h1>
                <input type="email" name="email" placeholder="Email" id="login-email" required>
                <input type="password" name="password" placeholder="Mot de passe" id="login-password" required>
                <a href="#">Mot de passe oublié?</a>
                <button>Se connecter</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Bienvenue !</h1>
                    <p id="role-description-login">Saisissez vos données personnelles pour profiter pleinement de toutes les fonctionnalités du service.</p>
                    <button class="hidden" id="login">Se connecter</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Ravi de vous revoir !</h1>
                    <p id="role-description-register">Connectez vous avec vos informations de connexion pour accéder à toutes les fonctionnalités du service.</p>
                    <button class="hidden" id="register">S'inscrire</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../JS/script.js"></script>
</body>
</html>

<?php if (isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>