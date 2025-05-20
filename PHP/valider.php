<?php
require 'base.php';
session_start();

if ($_SESSION['user']['role'] !== "admin") {
    die("Accès refusé");
}

$id = $_POST['id'];
$action = $_POST['action'];

if (in array($action, ['accept' ,'refuse'])){
    $statut = $action === 'accept' ? ' acccepted' : 'refused';
    $stmt = $pdo->prepare("UPDATE utilisateurs SET statut = ? WHERE id = ?");
    $stmt->execute([$statut, $id]);
}

header('Location : validation_admin.php');
exit;
?>