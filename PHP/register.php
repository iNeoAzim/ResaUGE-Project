<?php 
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $postal = htmlspecialchars($_POST['postal']);
    $birthdate = $_POST['birthdate'];
    $role = $_POST['role'];
    
    try {
        $check = $pdo->prepare("SELECT COUNT(*) FROM Utilisateurs WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetchColumn() > 0) {
            echo "<script>alert('Cet email est déjà utilisé.');window.location.href='../index.html';</script>";
            exit;
        }
        
        $check = $pdo->prepare("SELECT COUNT(*) FROM Utilisateurs WHERE pseudo = ?");
        $check->execute([$pseudo]);
        if ($check->fetchColumn() > 0) {
            echo "<script>alert('Ce pseudo est déjà utilisé.');window.location.href='../index.html';</script>";
            exit;
        }
        
        $stmt = $pdo->prepare("INSERT INTO Utilisateurs (nom, prenom, email, password, pseudo, postal_code, birthdate, role, statut) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        
        $stmt->execute([$nom, $prenom, $email, $password, $pseudo, $postal, $birthdate, $role]);
        
        echo "<script>alert('Inscription envoyée pour validation. Vous recevrez une confirmation par email.');window.location.href='../index.html';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erreur lors de l\'inscription: " . $e->getMessage() . "');window.location.href='../index.html';</script>";
    }
}
?>