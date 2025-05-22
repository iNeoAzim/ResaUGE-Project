<?php
require 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ? AND valide = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $pdo->prepare("UPDATE utilisateur SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->execute([$token, $expiry, $email]);
        
        $to = $email;
        $subject = "Réinitialisation de votre mot de passe - ResaUGE";
        $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/PHP/reset_password.php?token=" . $token;
        
        $message = "Bonjour,\n\n";
        $message .= "Vous avez demandé la réinitialisation de votre mot de passe.\n";
        $message .= "Cliquez sur le lien suivant pour réinitialiser votre mot de passe :\n";
        $message .= $reset_link . "\n\n";
        $message .= "Ce lien expirera dans 1 heure.\n";
        $message .= "Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.\n\n";
        $message .= "Cordialement,\nL'équipe ResaUGE";
        
        $headers = "From: noreply@resauge.fr\r\n";
        $headers .= "Reply-To: noreply@resauge.fr\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $message, $headers)) {
            $success = "Un email de réinitialisation a été envoyé à votre adresse email.";
        } else {
            $error = "Une erreur est survenue lors de l'envoi de l'email.";
        }
    } else {
        $success = "Si votre email est associé à un compte validé, vous recevrez un lien de réinitialisation.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - ResaUGE</title>
    <link rel="stylesheet" href="../CSS/styleindex.css">
</head>
<body id="login-page">
    <div class="container">
        <div class="form-container">
            <form method="POST" action="forgot_password.php">
                <h1>Mot de passe oublié</h1>
                <?php if (isset($error)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <p>Entrez votre adresse email pour recevoir un lien de réinitialisation.</p>
                <input type="email" name="email" placeholder="Votre email" required>
                <button type="submit">Envoyer le lien</button>
                <a href="../index.html">Retour à la connexion</a>
            </form>
        </div>
    </div>
</body>
</html> 