<?php 
require 'base.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== "admin") {
    die("Accès refusé");
}

$stmt = $pdo->query("SELECT * FROM utilisateurs WHERE role != 'admin'");
$users = $stmt->fetchAll();
?>

<h1>Utilisateurs à valider</h1>

<table>
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Pseudo</th>
        <th>Code Postal</th>
        <th>Date de Naissance</th>
        <th>Statut</th>
        <th>Action</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user['nom'] ?></td>
        <td><?= $user['prenom'] ?></td>
        <td><?= $user['email'] ?></td>
        <td><?= $user['statut'] ?></td>
        <td>
            <form action="validation.php" method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <button name="action" value="accept">Valider</button>
                <button name="action" value="refuse">Refuser</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>