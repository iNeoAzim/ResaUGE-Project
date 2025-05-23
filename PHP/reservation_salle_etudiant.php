<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est connecté et est un étudiant ou un enseignant
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['student', 'teacher'])) {
    header('Location: ../index.php');
    exit();
}

// Traitement du formulaire de réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salle_id = $_POST['salle_id'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $motif = $_POST['motif'];
    $utilisateur_id = $_SESSION['user']['id'];

    // Vérifier si la salle est disponible pour cette période
    $stmt = $conn->prepare("SELECT * FROM reservation WHERE salle_id = ? AND 
        ((date_debut BETWEEN ? AND ?) OR (date_fin BETWEEN ? AND ?)) AND 
        statut = 'validee'");
    $stmt->execute([$salle_id, $date_debut, $date_fin, $date_debut, $date_fin]);
    
    if ($stmt->rowCount() === 0) {
        // Insérer la réservation
        $stmt = $conn->prepare("INSERT INTO reservation (utilisateur_id, salle_id, date_debut, date_fin, motif) 
            VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$utilisateur_id, $salle_id, $date_debut, $date_fin, $motif])) {
            $success = "Votre demande de réservation a été enregistrée et est en attente de validation.";
        } else {
            $error = "Une erreur est survenue lors de la réservation.";
        }
    } else {
        $error = "La salle n'est pas disponible pour cette période.";
    }
}

// Récupérer la liste des salles disponibles
$stmt = $conn->query("SELECT * FROM salle WHERE disponible = 1");
$salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de salle - Université Eiffel</title>
    <link rel="stylesheet" href="../CSS/stylestudent.css">
    <style>
        .reservation-form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
    </style>
</head>
<body>
    <nav>
        <a href="student.php">Accueil</a>
        <a href="#">Notifications</a>
        <a href="#">Disponibilité</a>
        <a href="logout.php">Déconnexion</a>
    </nav>

    <div class="main-content">
        <h1>Réservation de salle</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="reservation-form">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="salle_id">Salle :</label>
                    <select name="salle_id" id="salle_id" required>
                        <option value="">Sélectionnez une salle</option>
                        <?php foreach ($salles as $salle): ?>
                            <option value="<?php echo $salle['id']; ?>">
                                <?php echo htmlspecialchars($salle['nom'] . ' (Capacité: ' . $salle['capacite'] . ' personnes)'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date_debut">Date et heure de début :</label>
                    <input type="datetime-local" name="date_debut" id="date_debut" required>
                </div>

                <div class="form-group">
                    <label for="date_fin">Date et heure de fin :</label>
                    <input type="datetime-local" name="date_fin" id="date_fin" required>
                </div>

                <div class="form-group">
                    <label for="motif">Motif de la réservation :</label>
                    <textarea name="motif" id="motif" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn">Envoyer la demande de réservation</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Université Eiffel. Tous droits réservés.</p>
    </footer>
</body>
</html> 