# ResaUGE-Project

**ResaUGE-Project** est une application web de gestion de réservations pour l'Université Gustave Eiffel. Elle permet de réserver des salles et du matériel via plusieurs interfaces adaptées aux différents profils utilisateurs (administrateur, enseignant, étudiant, agent).

## Fonctionnalités principales
- Authentification multi-profils (admin, enseignant, étudiant, agent)
- Réservation de salles et de matériel
- Gestion des utilisateurs et des droits
- Suivi des réservations et export de rapports
- Interface moderne, responsive et accessible

## Structure du projet
```
ResaUGE-Project/
│
├── CSS/                # Feuilles de style pour chaque interface
├── img/                # Images et icônes utilisées dans l'app
├── JS/                 # Scripts JavaScript pour l'interactivité
├── PHP/                # Pages PHP pour chaque profil utilisateur
├── README.md           # Ce fichier
├── index.html          # Page d'accueil
└── ...
```

## Installation
1. Cloner le dépôt :
   ```bash
   git clone <repo-url>
   ```
2. Placer le dossier sur un serveur local (ex : XAMPP, WAMP, MAMP, Docker...)
3. Importer la base de données fournie dans le dossier `SQL/` (voir APPBASE)
4. Accéder à l'application via `http://localhost/ResaUGE-Project/`

## Technologies utilisées
- PHP (backend)
- HTML5, CSS3 (frontend)
- JavaScript (animations et interactions)
- MySQL (base de données)

## Auteurs
- Projet réalisé par l'équipe ResaUGE, Université Gustave Eiffel

## Licence
Ce projet est sous licence MIT.

---
Pour toute question ou contribution, merci de contacter l'équipe ou d'ouvrir une issue sur le dépôt.
