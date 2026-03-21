# TerangaHomes - Plateforme Immobilière Multi-Services

TerangaHomes est une plateforme web full stack permettant la location, la vente de biens immobiliers, la réservation d'hôtels et la location de voitures, avec des fonctionnalités sociales interactives.

## 🚀 Fonctionnalités

### Fonctionnalités Principales
- ✅ **Authentification sécurisée** (inscription, connexion, rôles)
- ✅ **CRUD complet** des annonces (créer, lire, modifier, supprimer)
- ✅ **Recherche avancée** avec filtres multiples
- ✅ **Upload d'images** avec validation
- ✅ **Système de favoris** et commentaires
- ✅ **Dashboard administrateur** complet

### Fonctionnalités Avancées
- 🗺️ **Géolocalisation** avec Google Maps
- 💬 **Chat en temps réel** entre utilisateurs
- 📱 **Design responsive** avec Bootstrap 5
- 🎨 **Interface moderne** et professionnelle
- 🔔 **Notifications** en temps réel
- 📊 **Statistiques** et analytics

## 🛠️ Architecture Technique

### Stack Technique
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript (ES6+)
- **Backend**: PHP 8+ (Architecture MVC personnalisée)
- **Base de données**: MySQL 8+
- **Serveur**: Apache (avec XAMPP)

### Structure du Projet
```
terangaHomes/
├── app/
│   ├── controllers/     # Contrôleurs MVC
│   ├── models/         # Modèles de données
│   ├── views/          # Vues/templates
│   └── core/           # Cœur du framework
├── config/             # Fichiers de configuration
├── database/           # Scripts SQL
├── assets/             # Ressources statiques
│   ├── css/
│   ├── js/
│   └── images/
├── uploads/            # Fichiers uploadés
│   ├── images/
│   └── avatars/
└── vendor/             # Dépendances externes
```

## 📋 Prérequis

- PHP 8.0 ou supérieur
- MySQL 8.0 ou supérieur
- Apache avec mod_rewrite activé
- Extensions PHP requises : PDO, MySQLi, GD, cURL, JSON

## 🚀 Installation

### 1. Cloner le projet
```bash
git clone https://github.com/votre-username/terangahomes.git
cd terangahomes
```

### 2. Configuration de la base de données
```bash
# Importer la base de données
mysql -u root -p < database/terangahomes.sql
```

### 3. Configuration
```bash
# Copier le fichier de configuration
cp config/config.example.php config/config.php

# Éditer les paramètres de base de données
nano config/config.php
```

### 4. Permissions des dossiers
```bash
chmod 755 uploads -R
chmod 755 assets -R
```

### 5. Configuration Apache
Assurez-vous que le module rewrite est activé :
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 6. Accès à l'application
Ouvrez votre navigateur et accédez à :
```
http://localhost/terangahomes
```

## 👤 Utilisateurs par Défaut

### Administrateur
- **Email**: admin@terangahomes.com
- **Mot de passe**: password

## 🎯 Rôles et Permissions

### Visiteur
- Consulter les annonces
- Rechercher des biens
- Voir les détails

### Utilisateur
- Fonctionnalités visiteur +
- Ajouter aux favoris
- Commenter les annonces
- Contacter les propriétaires
- Chat avec les utilisateurs

### Propriétaire
- Fonctionnalités utilisateur +
- Créer/modifier ses annonces
- Gérer ses réservations
- Statistiques de ses annonces

### Administrateur
- Gestion complète de la plateforme
- Modération des annonces
- Gestion des utilisateurs
- Statistiques globales

## 📊 Base de Données

### Tables Principales
- `users` - Utilisateurs et rôles
- `annonces` - Annonces immobilières
- `categories` - Catégories d'annonces
- `messages` - Messagerie interne
- `favorites` - Favoris des utilisateurs
- `commentaires` - Commentaires et notes
- `reservations` - Réservations et bookings
- `paiements` - Transactions financières

## 🔧 Configuration

### Variables d'Environnement
```php
// config/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'terangahomes');
define('DB_USER', 'root');
define('DB_PASS', '');

// Google Maps API
define('GOOGLE_MAPS_API_KEY', 'votre_clé_api');
```

### Upload d'Images
- Taille maximale : 5MB par image
- Formats acceptés : JPG, PNG, GIF, WebP
- Stockage : `uploads/images/`

## 🎨 Personnalisation

### Thème et Design
Le design utilise Bootstrap 5 avec un thème personnalisé. Les couleurs principales sont définies dans `assets/css/style.css` :

```css
:root {
    --primary-color: #0066cc;
    --accent-color: #ff6b35;
    --success-color: #28a745;
    /* ... */
}
```

### Logo et Branding
Remplacez les fichiers dans `assets/images/` :
- `logo.png` - Logo principal
- `favicon.ico` - Icône du site

## 🚀 Déploiement

### Production
1. Activer le mode production :
```php
define('DEBUG', false);
define('ENVIRONMENT', 'production');
```

2. Configurer HTTPS
3. Optimiser les performances (cache, CDN)
4. Sauvegardes régulières

### Sécurité
- Validation des entrées
- Protection XSS et CSRF
- Hashage des mots de passe
- Upload sécurisé des fichiers

## 📝 API Endpoints

### Authentification
- `POST /login` - Connexion
- `POST /register` - Inscription
- `POST /logout` - Déconnexion

### Annonces
- `GET /annonces` - Lister les annonces
- `GET /annonces/{id}` - Détails d'une annonce
- `POST /annonces` - Créer une annonce
- `PUT /annonces/{id}` - Modifier une annonce
- `DELETE /annonces/{id}` - Supprimer une annonce

### Messagerie
- `GET /messages` - Conversations
- `POST /messages/send` - Envoyer un message
- `GET /messages/ajax/{id}` - Messages en temps réel

## 🤝 Contribuer

1. Fork le projet
2. Créer une branche (`git checkout -b feature/nouvelle-fonctionnalite`)
3. Commiter les changements (`git commit -am 'Ajout nouvelle fonctionnalité'`)
4. Pusher la branche (`git push origin feature/nouvelle-fonctionnalite`)
5. Créer une Pull Request

## 📄 Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 📞 Support

Pour toute question ou support technique :
- **Email**: contact@terangahomes.com
- **Téléphone**: +221 33 123 45 67
- **Adresse**: Dakar, Sénégal

## 🎯 Roadmap

### Version 2.0
- [ ] Application mobile native
- [ ] Paiements en ligne intégrés
- [ ] Système d'évaluation avancé
- [ ] Notifications push
- [ ] API REST complète

### Version 3.0
- [ ] Intelligence artificielle
- [ ] Visites virtuelles 3D
- [ ] Blockchain pour la sécurité
- [ ] Multi-langues

---

**Développé avec ❤️ pour la communauté TerangaHomes**
