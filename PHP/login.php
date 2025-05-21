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

<form method="post">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br>
    <button type="submit">Se connecter</button>
</form>

<?php if (isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>
