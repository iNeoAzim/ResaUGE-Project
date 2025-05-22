<?php
require 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
    $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $pseudo = filter_var($_POST['pseudo'], FILTER_SANITIZE_STRING);
    $postal = filter_var($_POST['postal'], FILTER_SANITIZE_STRING);
    $birthdate = $_POST['birthdate'];
    $role = $_POST['role'];
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            echo "<script>
                alert('Cette adresse email est déjà utilisée.');
                window.location.href = '../index.php';
            </script>";
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, code_postal, date_naissance, role, valide) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->execute([$nom, $prenom, $email, $password, $pseudo, $postal, $birthdate, $role]);

        $to = "admin@resauge.fr";
        $subject = "Nouvelle demande d'inscription - ResaUGE";
        $message = "Une nouvelle demande d'inscription a été reçue :\n\n";
        $message .= "Nom : $nom\n";
        $message .= "Prénom : $prenom\n";
        $message .= "Email : $email\n";
        $message .= "Rôle : $role\n\n";
        $message .= "Pour valider ou rejeter cette demande, connectez-vous à l'interface d'administration.";
        
        $headers = "From: noreply@resauge.fr\r\n";
        $headers .= "Reply-To: noreply@resauge.fr\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        mail($to, $subject, $message, $headers);

        echo "<script>
            alert('Votre compte a été créé avec succès et est en attente de validation par l\\'administrateur. Vous recevrez un email lorsque votre compte sera validé.');
            window.location.href = '../index.php';
        </script>";
        exit();
        
    } catch (PDOException $e) {
        echo "<script>
            alert('Une erreur est survenue lors de l\\'inscription. Veuillez réessayer.');
            window.location.href = '../index.php';
        </script>";
        exit();
    }
}
?> 