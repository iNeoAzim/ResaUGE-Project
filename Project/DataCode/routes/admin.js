const express = require('express');
const router = express.Router();
const User = require('../models/User');
const bcrypt = require('bcrypt');
const { verifyAdmin } = require('../middleware/auth');

// Route pour créer un compte administrateur
router.post('/create-admin', verifyAdmin, async (req, res) => {
    try {
        const { username, password } = req.body;

        // Vérifiez que les champs requis sont fournis
        if (!username || !password) {
            return res.status(400).json({ message: 'Nom d\'utilisateur et mot de passe requis.' });
        }

        // Hachez le mot de passe
        const hashedPassword = await bcrypt.hash(password, 10);

        // Créez un nouvel utilisateur avec le rôle administrateur
        const newAdmin = new User({
            username,
            password: hashedPassword,
            isAdmin: true,
        });

        await newAdmin.save();
        res.status(201).json({ message: 'Compte administrateur créé avec succès.' });
    } catch (error) {
        res.status(500).json({ message: 'Erreur lors de la création du compte administrateur.', error });
    }
});

module.exports = router;
