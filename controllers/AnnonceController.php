<?php
class AnnonceController extends Controller {
    private $annonceModel;
    
    public function __construct() {
        parent::__construct();
        $this->annonceModel = new Annonce();
    }
    
    public function index() {
        try {
            // Récupérer les paramètres de filtrage
            $page = $_GET['page'] ?? 1;
            $filters = [
                'type' => $_GET['type'] ?? '',
                'ville' => $_GET['ville'] ?? '',
                'prix_min' => $_GET['prix_min'] ?? '',
                'prix_max' => $_GET['prix_max'] ?? '',
                'categorie_id' => $_GET['categorie_id'] ?? ''
            ];
            
            $result = $this->annonceModel->getAll($page, $filters);
            
            // Récupérer les catégories pour les filtres
            $categories = $this->db->fetchAll("SELECT * FROM categories ORDER BY nom");
            
            $this->view('annonces/index_new', [
                'annonces' => $result['annonces'],
                'pagination' => $result,
                'filters' => $filters,
                'categories' => $categories
            ]);
            
        } catch (Exception $e) {
            // En cas d'erreur, utiliser la nouvelle vue
            $annonces = [];
            $categories = [];
            $pagination = ['total_pages' => 0, 'current_page' => 1, 'total' => 0];
            $query = $_GET['q'] ?? '';
            $filters = $_GET;
            include 'views/annonces/index_new.php';
        }
    }
    
    public function show($id) {
        $annonce = $this->annonceModel->findById($id);
        
        if (!$annonce || $annonce['statut'] !== 'active') {
            $this->redirect('/annonces');
        }
        
        // Incrémenter les vues
        $this->annonceModel->incrementViews($id);
        
        // Récupérer les annonces similaires
        $similar = $this->db->fetchAll(
            "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom 
             FROM annonces a 
             LEFT JOIN users u ON a.user_id = u.id 
             WHERE a.type = ? AND a.ville = ? AND a.id != ? AND a.statut = 'active' 
             ORDER BY a.created_at DESC LIMIT 4",
            [$annonce['type'], $annonce['ville'], $id]
        );
        
        // Vérifier si l'utilisateur a mis en favori
        $isFavorite = false;
        if ($this->isLoggedIn()) {
            $favorite = $this->db->fetch(
                "SELECT id FROM favorites WHERE user_id = ? AND annonce_id = ?",
                [$_SESSION['user_id'], $id]
            );
            $isFavorite = !empty($favorite);
        }
        
        $this->view('annonces/show', [
            'annonce' => $annonce,
            'similar' => $similar,
            'isFavorite' => $isFavorite
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        // Récupérer les catégories
        $categories = $this->db->fetchAll("SELECT * FROM categories ORDER BY nom");
        
        $this->view('annonces/create', ['categories' => $categories]);
    }
    
    public function store() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/annonces/create');
        }
        
        // Validation
        $errors = $this->validateAnnonceData($_POST);
        
        if (!empty($errors)) {
            $categories = $this->db->fetchAll("SELECT * FROM categories ORDER BY nom");
            $this->view('annonces/create', [
                'errors' => $errors,
                'old' => $_POST,
                'categories' => $categories
            ]);
            return;
        }
        
        // Gestion des images
        $images = [];
        if (!empty($_FILES['images']['name'][0])) {
            $files = $_FILES['images'];
            foreach ($files['name'] as $key => $name) {
                if (!empty($name)) {
                    $file = [
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    ];
                    
                    $upload = $this->uploadImage($file, 'annonces');
                    if ($upload['success']) {
                        $images[] = $upload['path'];
                    }
                }
            }
        }
        
        // Préparer les données
        $data = [
            'titre' => $this->sanitize($_POST['titre']),
            'description' => $this->sanitize($_POST['description']),
            'prix' => floatval($_POST['prix']),
            'type' => $this->sanitize($_POST['type']),
            'categorie_id' => intval($_POST['categorie_id']) ?: null,
            'user_id' => $_SESSION['user_id'],
            'ville' => $this->sanitize($_POST['ville']),
            'quartier' => $this->sanitize($_POST['quartier'] ?? ''),
            'adresse' => $this->sanitize($_POST['adresse'] ?? ''),
            'latitude' => !empty($_POST['latitude']) ? floatval($_POST['latitude']) : 0,
            'longitude' => !empty($_POST['longitude']) ? floatval($_POST['longitude']) : 0,
            'superficie' => !empty($_POST['superficie']) ? floatval($_POST['superficie']) : 0,
            'chambres' => intval($_POST['chambres'] ?? 0),
            'salles_bain' => intval($_POST['salles_bain'] ?? 0),
            'parking' => isset($_POST['parking']) ? 1 : 0,
            'meuble' => isset($_POST['meuble']) ? 1 : 0,
            'climatisation' => isset($_POST['climatisation']) ? 1 : 0,
            'wifi' => isset($_POST['wifi']) ? 1 : 0,
            'piscine' => isset($_POST['piscine']) ? 1 : 0,
            'images' => $images
        ];
        
        // Créer l'annonce
        try {
            $annonceId = $this->annonceModel->create($data);
            $_SESSION['flash_success'] = 'Annonce créée avec succès !';
            $this->redirect('/annonces/' . $annonceId);
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Erreur lors de la création de l\'annonce.';
            $this->redirect('/annonces/create');
        }
    }
    
    public function edit($id) {
        $this->requireAuth();
        
        $annonce = $this->annonceModel->findById($id);
        
        if (!$annonce || $annonce['user_id'] != $_SESSION['user_id']) {
            $this->redirect('/my-annonces');
        }
        
        $categories = $this->db->fetchAll("SELECT * FROM categories ORDER BY nom");
        
        $this->view('annonces/edit', [
            'annonce' => $annonce,
            'categories' => $categories
        ]);
    }
    
    public function update($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/annonces/' . $id . '/edit');
        }
        
        $annonce = $this->annonceModel->findById($id);
        
        if (!$annonce || $annonce['user_id'] != $_SESSION['user_id']) {
            $this->redirect('/my-annonces');
        }
        
        // Validation
        $errors = $this->validateAnnonceData($_POST);
        
        if (!empty($errors)) {
            $categories = $this->db->fetchAll("SELECT * FROM categories ORDER BY nom");
            $this->view('annonces/edit', [
                'errors' => $errors,
                'old' => $_POST,
                'annonce' => $annonce,
                'categories' => $categories
            ]);
            return;
        }
        
        // Préparer les données
        $data = [
            'titre' => $this->sanitize($_POST['titre']),
            'description' => $this->sanitize($_POST['description']),
            'prix' => floatval($_POST['prix']),
            'type' => $this->sanitize($_POST['type']),
            'categorie_id' => intval($_POST['categorie_id']) ?: null,
            'ville' => $this->sanitize($_POST['ville']),
            'quartier' => $this->sanitize($_POST['quartier'] ?? ''),
            'adresse' => $this->sanitize($_POST['adresse'] ?? ''),
            'latitude' => !empty($_POST['latitude']) ? floatval($_POST['latitude']) : 0,
            'longitude' => !empty($_POST['longitude']) ? floatval($_POST['longitude']) : 0,
            'superficie' => !empty($_POST['superficie']) ? floatval($_POST['superficie']) : 0,
            'chambres' => intval($_POST['chambres'] ?? 0),
            'salles_bain' => intval($_POST['salles_bain'] ?? 0),
            'parking' => isset($_POST['parking']) ? 1 : 0,
            'meuble' => isset($_POST['meuble']) ? 1 : 0,
            'climatisation' => isset($_POST['climatisation']) ? 1 : 0,
            'wifi' => isset($_POST['wifi']) ? 1 : 0,
            'piscine' => isset($_POST['piscine']) ? 1 : 0
        ];
        
        // Gestion des images
        if (!empty($_FILES['images']['name'][0])) {
            $files = $_FILES['images'];
            $newImages = [];
            
            foreach ($files['name'] as $key => $name) {
                if (!empty($name)) {
                    $file = [
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    ];
                    
                    $upload = $this->uploadImage($file, 'annonces');
                    if ($upload['success']) {
                        $newImages[] = $upload['path'];
                    }
                }
            }
            
            // Ajouter les nouvelles images aux existantes
            $existingImages = json_decode($annonce['images'] ?? '[]', true);
            $data['images'] = array_merge($existingImages, $newImages);
        }
        
        try {
            $this->annonceModel->update($id, $data);
            $_SESSION['flash_success'] = 'Annonce mise à jour avec succès !';
            $this->redirect('/annonces/' . $id);
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Erreur lors de la mise à jour de l\'annonce.';
            $this->redirect('/annonces/' . $id . '/edit');
        }
    }
    
    public function toggleStatus($id) {
        $this->requireAuth();
        
        $annonce = $this->annonceModel->findById($id);
        
        if (!$annonce || $annonce['user_id'] != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Annonce non trouvée ou accès non autorisé']);
            exit;
        }
        
        $newStatus = $annonce['statut'] === 'active' ? 'inactive' : 'active';
        
        $result = $this->annonceModel->updateStatus($id, $newStatus);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour du statut']);
        }
    }
    
    public function delete($id) {
        $this->requireAuth();
        
        $annonce = $this->annonceModel->findById($id);
        
        if (!$annonce || $annonce['user_id'] != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Annonce non trouvée ou accès non autorisé']);
            exit;
        }
        
        $result = $this->annonceModel->delete($id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Annonce supprimée avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }
    
    public function myAnnonces() {
        $this->requireAuth();
        
        $page = $_GET['page'] ?? 1;
        $result = $this->annonceModel->getByUser($_SESSION['user_id'], $page);
        
        $this->view('annonces/my-annonces', [
            'annonces' => $result['annonces'],
            'pagination' => $result
        ]);
    }
    
    public function search() {
        try {
            $query = $this->sanitize($_GET['q'] ?? '');
            $filters = [
                'type' => $this->sanitize($_GET['type'] ?? ''),
                'prix_min' => $this->sanitize($_GET['prix_min'] ?? ''),
                'prix_max' => $this->sanitize($_GET['prix_max'] ?? ''),
                'categorie_id' => $this->sanitize($_GET['categorie_id'] ?? '')
            ];
            
            $page = $_GET['page'] ?? 1;
            $result = $this->annonceModel->search($query, $filters, $page);
            
            // Récupérer les catégories pour les filtres
            $categories = $this->db->fetchAll("SELECT * FROM categories ORDER BY nom");
            
            $this->view('annonces/search_simple', [
                'annonces' => $result['annonces'],
                'pagination' => $result,
                'query' => $query,
                'filters' => $filters,
                'categories' => $categories
            ]);
            
        } catch (Exception $e) {
            // En cas d'erreur, utiliser la nouvelle vue
            $annonces = [];
            $categories = [];
            $pagination = ['total_pages' => 0, 'current_page' => 1, 'total' => 0];
            $query = $_GET['q'] ?? '';
            $filters = $_GET;
            include 'views/annonces/search_simple.php';
        }
    }
    
    private function validateAnnonceData($data) {
        $errors = [];
        
        if (empty($data['titre'])) {
            $errors['titre'] = 'Le titre est requis';
        } elseif (strlen($data['titre']) < 5) {
            $errors['titre'] = 'Le titre doit contenir au moins 5 caractères';
        }
        
        if (empty($data['description'])) {
            $errors['description'] = 'La description est requise';
        } elseif (strlen($data['description']) < 20) {
            $errors['description'] = 'La description doit contenir au moins 20 caractères';
        }
        
        if (empty($data['prix']) || !is_numeric($data['prix']) || $data['prix'] <= 0) {
            $errors['prix'] = 'Le prix doit être un nombre positif';
        }
        
        if (empty($data['type'])) {
            $errors['type'] = 'Le type est requis';
        } elseif (!in_array($data['type'], ['location', 'vente', 'hotel', 'voiture'])) {
            $errors['type'] = 'Type invalide';
        }
        
        if (empty($data['ville'])) {
            $errors['ville'] = 'La ville est requise';
        }
        
        return $errors;
    }
}
