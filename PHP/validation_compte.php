<?php
$users = [
    [
        'id' => 1,
        'nom' => 'Dupont',
        'prenom' => 'Jean',
        'email' => 'jean.dupont@example.com',
        'password' => 'motdepasse123',
        'role' => 'Étudiant',
        'valide' => false
    ],
    [
        'id' => 2,
        'nom' => 'Martin',
        'prenom' => 'Sophie',
        'email' => 'sophie.martin@example.com',
        'password' => 'sophie2023',
        'role' => 'Enseignant',
        'valide' => false
    ],
    [
        'id' => 3,
        'nom' => 'Dubois',
        'prenom' => 'Pierre',
        'email' => 'pierre.dubois@example.com',
        'password' => 'pierre123',
        'role' => 'Agent',
        'valide' => false
    ],
    [
        'id' => 4,
        'nom' => 'Leroy',
        'prenom' => 'Marie',
        'email' => 'marie.leroy@example.com',
        'password' => 'marie456',
        'role' => 'Étudiant',
        'valide' => true
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'])) {
    $userId = (int)$_POST['user_id'];
    $action = $_POST['action'];
    
    foreach ($users as &$user) {
        if ($user['id'] === $userId) {
            if ($action === 'validate') {
                $user['valide'] = true;
            } elseif ($action === 'reject') {
                $user['valide'] = false;
            }
            break;
        }
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de comptes - Admin</title>
    <link rel="stylesheet" href="../CSS/stylevalidation.css">
</head>
<body>
    <div class="container">
        <h1>Comptes à Valider</h1>
        <p>Voici la liste des comptes en attente de validation.</p>
                <div class="controls">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" placeholder="Rechercher par nom, prénom, email...">
            </div>
            
            <div class="filter-group">
                <select id="roleFilter">
                    <option value="all">Tous les rôles</option>
                    <option value="Étudiants">Étudiant</option>
                    <option value="Agent">Agent</option>
                    <option value="Enseignant">Enseignant</option>
                </select>
                
                <select id="statusFilter">
                    <option value="all">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="validated">Validés</option>
                </select>
                
                <button class="toggle-password" id="togglePassword">Afficher MDP</button>
            </div>
        </div>
        
        <div class="table-container">
            <table id="usersTable">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="nom">Nom</th>
                        <th class="sortable" data-sort="prenom">Prénom</th>
                        <th class="sortable" data-sort="email">Email</th>
                        <th>Mot de passe</th>
                        <th class="sortable" data-sort="role">Rôle</th>
                        <th class="sortable" data-sort="valide">Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr data-id="<?= $user['id'] ?>" data-role="<?= htmlspecialchars($user['role']) ?>" data-status="<?= $user['valide'] ? 'validated' : 'pending' ?>">
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['prenom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td class="password-cell">
                            <span class="password-hidden">••••••••</span>
                            <span class="password-visible hidden"><?= htmlspecialchars($user['password']) ?></span>
                        </td>
                        <td>
                            <?php if ($user['role'] === 'Enseignant'): ?>
                                <span class="badge badge-danger"><?= htmlspecialchars($user['role']) ?></span>
                            <?php elseif ($user['role'] === 'Agent'): ?>
                                <span class="badge badge-primary"><?= htmlspecialchars($user['role']) ?></span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?= htmlspecialchars($user['role']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['valide']): ?>
                                <span class="badge badge-success">Validé</span>
                            <?php else: ?>
                                <span class="badge badge-warning">En attente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <?php if (!$user['valide']): ?>
                                    <button class="btn-validate" onclick="openModal('validate', <?= $user['id'] ?>, '<?= htmlspecialchars($user['prenom']) ?>', '<?= htmlspecialchars($user['nom']) ?>')">Valider</button>
                                    <button class="btn-reject" onclick="openModal('reject', <?= $user['id'] ?>, '<?= htmlspecialchars($user['prenom']) ?>', '<?= htmlspecialchars($user['nom']) ?>')">Rejeter</button>
                                <?php else: ?>
                                    <button class="btn-reject" onclick="openModal('reject', <?= $user['id'] ?>, '<?= htmlspecialchars($user['prenom']) ?>', '<?= htmlspecialchars($user['nom']) ?>')">Invalider</button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div id="noResults" class="text-center hidden">
            <p>Aucun compte ne correspond aux critères de recherche.</p>
        </div>
        <ul class="pagination" id="pagination">
            <li class="disabled"><a href="#" aria-label="Previous">&laquo;</a></li>
            <li class="active"><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#" aria-label="Next">&raquo;</a></li>
        </ul>
        
        <div class="text-muted">
            <span id="statsText">Affichage de <?= count($users) ?> compte(s) sur <?= count($users) ?></span>
        </div>
    </div>
    
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Confirmation</h3>
            </div>
            <div class="modal-body" id="modalBody">
                Êtes-vous sûr de vouloir effectuer cette action ?
            </div>
            <div class="modal-footer">
                <button class="secondary" onclick="closeModal()">Annuler</button>
                <form method="POST" id="actionForm">
                    <input type="hidden" name="user_id" id="modalUserId">
                    <input type="hidden" name="action" id="modalAction">
                    <button type="submit" id="confirmButton">Confirmer</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../JS/scriptvalidation.js"></script>
</body>
</html>
