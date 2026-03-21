<?php
class FavoriteController extends Controller {
    private $favoriteModel;
    
    public function __construct() {
        parent::__construct();
        $this->favoriteModel = new Favorite();
    }
    
    public function toggle() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $annonceId = $_POST['annonce_id'] ?? null;
        $userId = $_SESSION['user_id'];
        
        if (!$annonceId) {
            $this->json(['success' => false, 'message' => 'ID d\'annonce invalide']);
            return;
        }
        
        // Vérifier si l'annonce existe
        $annonce = $this->db->fetch("SELECT id FROM annonces WHERE id = ? AND statut = 'active'", [$annonceId]);
        if (!$annonce) {
            $this->json(['success' => false, 'message' => 'Annonce non trouvée']);
            return;
        }
        
        // Ajouter ou retirer des favoris
        if ($this->favoriteModel->isFavorite($userId, $annonceId)) {
            $this->favoriteModel->remove($userId, $annonceId);
            $this->json(['success' => true, 'action' => 'removed', 'message' => 'Retiré des favoris']);
        } else {
            $this->favoriteModel->add($userId, $annonceId);
            $this->json(['success' => true, 'action' => 'added', 'message' => 'Ajouté aux favoris']);
        }
    }
    
    public function index() {
        $this->requireAuth();
        
        $page = $_GET['page'] ?? 1;
        $userId = $_SESSION['user_id'];
        
        $favorites = $this->favoriteModel->getUserFavorites($userId, $page);
        
        $this->view('favorites/index', [
            'favorites' => $favorites,
            'count' => $this->favoriteModel->getFavoriteCount($userId)
        ]);
    }
}
