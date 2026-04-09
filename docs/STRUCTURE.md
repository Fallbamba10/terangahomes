# Structure du Projet TerangaHomes

## Organisation des Dossiers

```
terangaHomes/
                  
public/                  # Fichiers accessibles publiquement
  index.php             # Point d'entrée principal
  assets/               # CSS, JS, images
  uploads/              # Fichiers uploadés (images, avatars)

src/                    # Code source de l'application
  config/               # Configuration de l'application
    config.php         # Base de données, constantes, etc.
  core/                # Classes fondamentales
    Controller.php     # Contrôleur de base
    Database.php       # Gestion de la base de données
    Router.php         # Routage des requêtes
  controllers/         # Contrôleurs spécifiques
    AnnonceController.php
    AuthController.php
    UserController.php
    etc.
  models/              # Modèles de données
    Annonce.php
    User.php
    Message.php
    etc.
  views/               # Vues (templates)
    annonces/
    home/
    auth/
    etc.
  database/            # Scripts SQL
    terangahomes.sql   # Structure de la base de données

temp/                  # Fichiers temporaires et de debug
  check_images.php     # Scripts de diagnostic
  add_missing_columns.php
  etc.

scripts/               # Scripts d'administration
docs/                  # Documentation
```

## Fichiers Principaux

### Point d'entrée
- `public/index.php` - Routeur principal

### Fichiers de configuration
- `src/config/config.php` - Configuration principale

### Contrôleurs
- `src/controllers/AnnonceController.php` - Gestion des annonces
- `src/controllers/AuthController.php` - Authentification
- `src/controllers/UserController.php` - Gestion des utilisateurs

### Modèles
- `src/models/Annonce.php` - Modèle des annonces
- `src/models/User.php` - Modèle des utilisateurs

### Vues
- `src/views/annonces/` - Vues liées aux annonces
- `src/views/auth/` - Vues d'authentification
- `src/views/home/` - Page d'accueil

## Fichiers Obsolètes Déplacés

Les fichiers suivants ont été déplacés dans `temp/` car ils sont soit des doublons, soit des fichiers temporaires :

- `user_dashboard_old.php` - Ancienne version du dashboard
- `admin.php` - Doublon de admin_dashboard.php
- `messages.php` - Version ancienne du système de messagerie
- `check_images.php` - Script de diagnostic
- `add_missing_columns.php` - Script de migration BDD
- `fix_decimal_columns.php` - Script de correction
- `setup_car_tables.php` - Script de création de tables
- `*.sql` - Scripts SQL temporaires

## Pattern MVC

Le projet suit le pattern Model-View-Controller :

1. **Models** (`src/models/`) : Logique métier et accès aux données
2. **Views** (`src/views/`) : Templates et présentation
3. **Controllers** (`src/controllers/`) : Logique de contrôle et routage

## Sécurité

- Les fichiers sensibles sont en dehors du dossier public
- Configuration des permissions via .htaccess
- Validation des entrées utilisateur
- Protection contre les injections SQL

## Maintenance

- Les fichiers temporaires sont dans `temp/`
- Les scripts d'administration sont dans `scripts/`
- La documentation est dans `docs/`
