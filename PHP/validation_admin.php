<?php 
require 'base.php';
session_start();

// Vérification que l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header('Location: ../index.html');
    exit;
}

// Récupération de tous les utilisateurs en attente de validation
$stmt = $pdo->query("SELECT * FROM Utilisateurs WHERE statut = 'pending'");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation des utilisateurs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #005e55;
            margin-bottom: 30px;
        }
        .table {
            margin-top: 20px;
        }
        .btn-success, .btn-danger {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Utilisateurs en attente de validation</h1>
        
        <?php if (count($users) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Pseudo</th>
                        <th>Code Postal</th>
                        <th>Date de Naissance</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['prenom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['pseudo']) ?></td>
                        <td><?= htmlspecialchars($user['postal_code']) ?></td>
                        <td><?= htmlspecialchars($user['birthdate']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <form action="valider.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Valider</button>
                                <button type="submit" name="action" value="refuse" class="btn btn-danger btn-sm">Refuser</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">Aucun utilisateur en attente de validation.</div>
        <?php endif; ?>
        
        <a href="admin.php" class="btn btn-primary">Retour au tableau de bord</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
