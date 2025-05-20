<?php 
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $pseudo = $_POST['pseudo'];
    $postal = $_POST['postal'];
    $birthdate = $_POST['birthdate'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (nom,prenom,email,password,pseudo, postal_code , birthdate, role)
                            VALUES (:nom, :prenom, :email, :password, :pseudo, :postal, :birthdate, :role)");

    $stmt->execute([$nom,$prenom,$email,$password,$pseudo,$postal,$birthdate,$role]);

    echo "Incription envoyée pour validation.";
}
?>
