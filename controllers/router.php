<?php
// Router pour les actions du contrôleur
require_once '../config/config.php';
require_once '../core/Database.php';

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Router l'action
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($action === 'update' && !empty($id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inclure le contrôleur
    require_once 'AnnonceController.php';
    
    $controller = new AnnonceController();
    $controller->update($id);
} else {
    // Redirection en cas d'accès direct
    header('Location: ../user_dashboard.php');
    exit;
}
?>
