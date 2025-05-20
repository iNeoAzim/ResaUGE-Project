<?php
session_start();
require 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $mot_de_passe === $user['mot_de_passe']) { // pas sécurisé (on verra le hash plus tard)
        $_SESSION['utilisateur'] = $user;
        header('Location: dashboard.php');
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect";
    }
}
?>

<form method="post">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br>
    <button type="submit">Se connecter</button>
</form>

<?php if (isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>
