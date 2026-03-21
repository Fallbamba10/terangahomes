<?php
// Script AJAX pour gérer les favoris

require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'models/Favorite.php';

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit;
}

// Vérifier la méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$annonceId = $_POST['annonce_id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$annonceId) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'ID d\'annonce invalide']);
    exit;
}

try {
    $favoriteModel = new Favorite();
    
    // Vérifier si l'annonce existe
    $db = Database::getInstance();
    $annonce = $db->fetch("SELECT id FROM annonces WHERE id = ? AND statut = 'active'", [$annonceId]);
    
    if (!$annonce) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Annonce non trouvée']);
        exit;
    }
    
    // Ajouter ou retirer des favoris
    if ($favoriteModel->isFavorite($userId, $annonceId)) {
        $favoriteModel->remove($userId, $annonceId);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Retiré des favoris']);
    } else {
        $favoriteModel->add($userId, $annonceId);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Ajouté aux favoris']);
    }
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
}
?>
