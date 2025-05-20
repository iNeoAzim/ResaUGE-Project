<?php
require 'base.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header('Location: ../index.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['action'])) {
    $id = (int)$_POST['id'];
    $action = $_POST['action'];

    if (in_array($action, ['accept', 'refuse'])) {
        $statut = $action === 'accept' ? 'accepted' : 'refused';
        
        try {
            $stmt = $pdo->prepare("UPDATE Utilisateurs SET statut = ? WHERE id = ?");
            $stmt->execute([$statut, $id]);
            
            $message = $statut === 'accepted' ? 'Utilisateur validé avec succès.' : 'Utilisateur refusé avec succès.';
            echo "<script>alert('$message');window.location.href='validation_admin.php';</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Erreur lors de la validation: " . $e->getMessage() . "');window.location.href='validation_admin.php';</script>";
        }
    } else {
        echo "<script>alert('Action non valide');window.location.href='validation_admin.php';</script>";
    }
} else {
    header('Location: validation_admin.php');
    exit;
}
?>
