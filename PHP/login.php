<?php 
require 'base.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['statut'] == 'refused') {
                echo "<script>alert('Accès refusé par l\'administrateur');window.location.href='../index.html';</script>";
                exit;
            } elseif ($user['statut'] == 'pending') {
                echo "<script>alert('Votre compte est en cours de validation.');window.location.href='../index.html';</script>";
                exit;
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['pseudo'];
                $_SESSION['role'] = $user['role'];
                
                switch ($user['role']) {
                    case 'admin':
                        header('Location: admin.php');
                        break;
                    case 'student':
                        header('Location: student.php');
                        break;
                    case 'teacher':
                        header('Location: teacher.php');
                        break;
                    case 'agent':
                        header('Location: agent.php');
                        break;
                    default:
                        header('Location: ../index.html');
                }
                exit;
            }
        } else {
            echo "<script>alert('Email ou mot de passe incorrect');window.location.href='../index.html';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erreur de connexion: " . $e->getMessage() . "');window.location.href='../index.html';</script>";
    }
}
?>