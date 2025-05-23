<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Traitement des actions (validation/refus)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['reservation_id'])) {
        $action = $_POST['action'];
        $reservation_id = $_POST['reservation_id'];
        
        if ($action === 'valider') {
            $stmt = $conn->prepare("UPDATE reservation SET statut = 'validee' WHERE id = ?");
            $message = "La réservation a été validée avec succès.";
        } elseif ($action === 'refuser') {
            $stmt = $conn->prepare("UPDATE reservation SET statut = 'refusee' WHERE id = ?");
            $message = "La réservation a été refusée.";
        }
        
        if (isset($stmt) && $stmt->execute([$reservation_id])) {
            $success = $message;
        } else {
            $error = "Une erreur est survenue lors du traitement de la demande.";
        }
    }
}

// Récupération des réservations en attente
$stmt = $conn->query("
    SELECT r.*, u.nom, u.prenom, u.role,
           s.nom as salle_nom, s.capacite as salle_capacite,
           m.nom as materiel_nom, m.description as materiel_description
    FROM reservation r
    LEFT JOIN utilisateur u ON r.utilisateur_id = u.id
    LEFT JOIN salle s ON r.salle_id = s.id
    LEFT JOIN materiel m ON r.materiel_id = m.id
    WHERE r.statut = 'en_attente'
    ORDER BY r.date_debut ASC
");
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des réservations - Administration</title>
    <link rel="stylesheet" href="../CSS/styleadmin.css">
    <style>
        .reservations-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .reservation-card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
        }
        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .reservation-info {
            margin-bottom: 15px;
        }
        .reservation-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .no-reservations {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>Admin</h2>
        <nav>
            <a href="admin.php">Accueil</a>
            <a href="#">Tableau de bord</a>
            <a href="validation_compte.php">Validation des utilisateurs</a>
            <a href="#">Utilisateurs</a>
            <a href="#">Suivi</a>
            <a href="#">Objets & Salles</a>
            <a href="gestion_reservations.php">Gestion des réservations</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </aside>

    <main class="main-content">
        <h1>Gestion des réservations</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="reservations-container">
            <?php if (empty($reservations)): ?>
                <div class="no-reservations">
                    <p>Aucune réservation en attente de validation.</p>
                </div>
            <?php else: ?>
                <?php foreach ($reservations as $reservation): ?>
                    <div class="reservation-card">
                        <div class="reservation-header">
                            <h3>
                                <?php if ($reservation['salle_id']): ?>
                                    Réservation de salle : <?php echo htmlspecialchars($reservation['salle_nom']); ?>
                                <?php else: ?>
                                    Réservation de matériel : <?php echo htmlspecialchars($reservation['materiel_nom']); ?>
                                <?php endif; ?>
                            </h3>
                            <span>
                                Demandé par : <?php echo htmlspecialchars($reservation['prenom'] . ' ' . $reservation['nom']); ?> 
                                (<?php echo htmlspecialchars($reservation['role']); ?>)
                            </span>
                        </div>
                        
                        <div class="reservation-info">
                            <p><strong>Date de début :</strong> <?php echo date('d/m/Y H:i', strtotime($reservation['date_debut'])); ?></p>
                            <p><strong>Date de fin :</strong> <?php echo date('d/m/Y H:i', strtotime($reservation['date_fin'])); ?></p>
                            <?php if ($reservation['salle_id']): ?>
                                <p><strong>Capacité de la salle :</strong> <?php echo htmlspecialchars($reservation['salle_capacite']); ?> personnes</p>
                            <?php endif; ?>
                            <?php if ($reservation['materiel_id']): ?>
                                <p><strong>Description du matériel :</strong> <?php echo htmlspecialchars($reservation['materiel_description']); ?></p>
                            <?php endif; ?>
                            <p><strong>Motif :</strong> <?php echo htmlspecialchars($reservation['motif']); ?></p>
                        </div>

                        <div class="reservation-actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                <input type="hidden" name="action" value="valider">
                                <button type="submit" class="btn btn-success">Valider</button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                <input type="hidden" name="action" value="refuser">
                                <button type="submit" class="btn btn-danger">Refuser</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        &copy;2025 Université Eiffel. Tous droits réservés.
    </footer>
</body>
</html> 