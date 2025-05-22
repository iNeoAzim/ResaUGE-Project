<?php
require 'connexion.php';

$token = isset($_GET['token']) ? $_GET['token'] : '';
$error = null;
$success = null;

if (empty($token)) {
    $error = "Token invalide.";
} else {
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE reset_token = ? AND reset_token_expiry > NOW() AND valide = 1");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = "Ce lien de réinitialisation est invalide ou a expiré.";
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (strlen($password) < 8) {
            $error = "Le mot de passe doit contenir au moins 8 caractères.";
        } elseif ($password !== $confirm_password) {
            $error = "Les mots de passe ne correspondent pas.";
        } else {
            $stmt = $pdo->prepare("UPDATE utilisateur SET mot_de_passe = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
            if ($stmt->execute([$password, $user['id']])) {
                $success = "Votre mot de passe a été réinitialisé avec succès.";
            } else {
                $error = "Une erreur est survenue lors de la réinitialisation du mot de passe.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe - ResaUGE</title>
    <link rel="stylesheet" href="../CSS/styleindex.css">
</head>
<body id="login-page">
    <div class="container">
        <div class="form-container">
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                    <br>
                    <?php if ($error === "Ce lien de réinitialisation est invalide ou a expiré."): ?>
                        <p>Veuillez demander un nouveau lien de réinitialisation.</p>
                        <a href="forgot_password.php" class="btn">Demander un nouveau lien</a>
                    <?php else: ?>
                        <a href="../index.html">Retour à la connexion</a>
                    <?php endif; ?>
                </div>
            <?php elseif ($success): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success); ?>
                    <br>
                    <a href="../index.html">Retour à la connexion</a>
                </div>
            <?php else: ?>
                <form method="POST">
                    <h1>Réinitialisation du mot de passe</h1>
                    <p>Veuillez entrer votre nouveau mot de passe.</p>
                    <input type="password" name="password" placeholder="Nouveau mot de passe" required minlength="8">
                    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required minlength="8">
                    <button type="submit">Réinitialiser le mot de passe</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 