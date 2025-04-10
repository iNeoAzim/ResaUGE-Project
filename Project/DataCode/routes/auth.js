const jwt = require('jsonwebtoken');
const User = require('./User');

// Middleware pour vérifier si l'utilisateur est administrateur
const verifyAdmin = async (req, res, next) => {
    try {
        const token = req.headers.authorization?.split(' ')[1];
        if (!token) {
            return res.status(401).json({ message: 'Accès non autorisé.' });
        }

        const decoded = jwt.verify(token, process.env.JWT_SECRET);
        const user = await User.findById(decoded.id);

        if (!user || !user.isAdmin) {
            return res.status(403).json({ message: 'Accès réservé aux administrateurs.' });
        }

        req.user = user;
        next();
    } catch (error) {
        res.status(401).json({ message: 'Accès non autorisé.', error });
    }
};

module.exports = { verifyAdmin };
