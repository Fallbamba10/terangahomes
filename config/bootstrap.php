<?php
// Point d'entrée de l'application
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Controller.php';

// Charger les modèles
require_once __DIR__ . '/../models/Annonce.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Favorite.php';
require_once __DIR__ . '/../models/Message.php';

// Charger les contrôleurs
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/AnnonceController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/FavoriteController.php';
require_once __DIR__ . '/../controllers/MessageController.php';
require_once __DIR__ . '/../controllers/InteractionController.php';
require_once __DIR__ . '/../controllers/ErrorController.php';

// Démarrer la session
session_start();

// Initialiser le routeur
$router = new Router();
$router->dispatch();
