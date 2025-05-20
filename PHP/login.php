<?php 
require 'base.php';

session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    if ($user['statut'] == 'refused') {
        echo "<script>alert('Accès refusé par l'administrateur');window.location.href='index.html';</script>";
    } elseif ($user['statut'] ==='pending') {
        echo "<script>alert('Votre compte est en cours de validation.');window.location.href='index.html';</script>";
    } else {
        $_SESSION['user'] = $user;

        if ($user['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location : student.php');
        }
        exit;
    }
} else {
    echo "<script>alert('Email ou mot de passe incorrect');window.Location.href='index.html';</script>";
}
?>