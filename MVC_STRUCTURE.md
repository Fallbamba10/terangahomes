# Structure MVC TerangaHomes

## 📁 Organisation des Dossiers

### `/config/`
- `config.php` - Configuration principale (DB, constantes, etc.)
- `bootstrap.php` - Point d'entrée qui charge tous les composants

### `/core/`
- `Database.php` - Gestion de la base de données
- `Router.php` - Système de routage URL → Controller
- `Controller.php` - Classe de base pour tous les contrôleurs

### `/models/`
- `Annonce.php` - Modèle pour les annonces immobilières
- `User.php` - Modèle pour les utilisateurs
- `Favorite.php` - Modèle pour les favoris
- `Message.php` - Modèle pour les messages

### `/controllers/`
- `HomeController.php` - Page d'accueil et pages principales
- `AnnonceController.php` - Gestion des annonces (CRUD)
- `AuthController.php` - Authentification (login/register)
- `UserController.php` - Profil utilisateur
- `AdminController.php` - Administration
- `FavoriteController.php` - Gestion des favoris
- `MessageController.php` - Messagerie
- `InteractionController.php` - Interactions (commentaires, etc.)
- `ErrorController.php` - Gestion des erreurs

### `/views/`
- `home/` - Vues de l'accueil
- `annonces/` - Vues des annonces
- `auth/` - Vues d'authentification
- `errors/` - Vues d'erreurs
- `layouts/` - Templates de base

### Fichiers Racine (Compatibilité)
- `accueil_booking_fixed.php` - Page d'accueil principale
- `car_rental.php` - Location de voitures
- `airport_transfer.php` - Transfert aéroport
- `booking_confirmation.php` - Confirmation réservation
- `payment.php` - Paiement
- `favorites.php` - Page des favoris
- `user_dashboard.php` - Dashboard utilisateur
- `admin.php` - Dashboard admin
- `index.php` - Point d'entrée MVC

## 🔄 Flux MVC

1. **URL** → `.htaccess` → `index.php`
2. **index.php** → `bootstrap.php` → `Router.php`
3. **Router** → **Controller** approprié
4. **Controller** → **Model** (si besoin)
5. **Controller** → **View** avec les données
6. **View** → **HTML** renvoyé au navigateur

## 🛣️ Routes Principales

### Pages Publiques
- `/` → HomeController@accueil (page d'accueil)
- `/annonces` → AnnonceController@index (liste des annonces)
- `/annonces/{id}` → AnnonceController@show (détail annonce)

### Authentification
- `/login` → AuthController@showLogin
- `/register` → AuthController@showRegister

### Utilisateur Connecté
- `/dashboard` → UserController@dashboard
- `/my-annonces` → AnnonceController@myAnnonces

### Administration
- `/admin` → AdminController@index

## 🎯 Avantages de cette Structure

1. **Séparation des responsabilités** : MVC clair
2. **Compatibilité** : Fichiers existants préservés
3. **Extensibilité** : Facile d'ajouter de nouvelles fonctionnalités
4. **Maintenabilité** : Code organisé et structuré
5. **Sécurité** : Points d'entrée contrôlés

## 📝 Notes

- Les fichiers PHP à la racine sont conservés pour compatibilité
- Le routeur gère les URLs modernes MVC
- Les contrôleurs peuvent inclure les fichiers existants si nécessaire
- Structure hybride : MVC + fichiers directs pour transition progressive
