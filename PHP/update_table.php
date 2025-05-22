<?php
require 'connexion.php';

try {
    // Ajouter les colonnes manquantes si elles n'existent pas
    $columns = [
        'nom' => 'VARCHAR(50) NOT NULL',
        'prenom' => 'VARCHAR(50) NOT NULL',
        'email' => 'VARCHAR(100) NOT NULL',
        'password' => 'VARCHAR(255) NOT NULL',
        'pseudo' => 'VARCHAR(50) NOT NULL',
        'code_postal' => 'VARCHAR(5) NOT NULL',
        'date_naissance' => 'DATE NOT NULL',
        'role' => "ENUM('admin', 'agent', 'teacher', 'student') NOT NULL",
        'valide' => 'TINYINT(1) DEFAULT 0'
    ];
    
    foreach ($columns as $column => $definition) {
        try {
            $sql = "ALTER TABLE utilisateur ADD COLUMN IF NOT EXISTS $column $definition";
            $pdo->exec($sql);
        } catch (PDOException $e) {
            // Si la colonne existe déjà, on la modifie
            if ($e->getCode() == '42S21') {
                $sql = "ALTER TABLE utilisateur MODIFY COLUMN $column $definition";
                $pdo->exec($sql);
            }
        }
    }
    
    // Vérifier si le compte admin existe
    $stmt = $pdo->prepare("SELECT id FROM utilisateur WHERE email = ?");
    $stmt->execute(['admin@resauge.fr']);
    
    if (!$stmt->fetch()) {
        // Créer le compte admin s'il n'existe pas
        $admin = [
            'nom' => 'Admin',
            'prenom' => 'System',
            'email' => 'admin@resauge.fr',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'pseudo' => 'admin',
            'code_postal' => '77420',
            'date_naissance' => '2000-01-01',
            'role' => 'admin',
            'valide' => 1
        ];
        
        $sql = "INSERT INTO utilisateur (nom, prenom, email, password, pseudo, code_postal, date_naissance, role, valide) 
                VALUES (:nom, :prenom, :email, :password, :pseudo, :code_postal, :date_naissance, :role, :valide)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($admin);
        
        echo "Compte admin créé avec succès !\n";
    } else {
        // Mettre à jour le mot de passe admin
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "UPDATE utilisateur SET password = ?, valide = 1 WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$password, 'admin@resauge.fr']);
        
        echo "Mot de passe admin mis à jour !\n";
    }
    
    echo "Email: admin@resauge.fr\n";
    echo "Mot de passe: admin123\n";
    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    echo "Code : " . $e->getCode() . "\n";
}
?> 