<?php
class HomeController extends Controller {
    
    public function index() {
        // Rediriger vers la page d'accueil principale
        $this->redirect('/accueil');
    }
    
    public function accueil() {
        // Inclure la page d'accueil principale
        require_once __DIR__ . '/../accueil_booking_fixed.php';
    }
    
    public function carRental() {
        // Inclure la page de location de voitures
        require_once __DIR__ . '/../car_rental.php';
    }
    
    public function airportTransfer() {
        // Inclure la page de transfert aéroport
        require_once __DIR__ . '/../airport_transfer.php';
    }
    
    public function bookingConfirmation() {
        // Inclure la page de confirmation de réservation
        require_once __DIR__ . '/../booking_confirmation.php';
    }
    
    public function payment() {
        // Inclure la page de paiement
        require_once __DIR__ . '/../payment.php';
    }
    
    public function favorites() {
        // Inclure la page des favoris
        require_once __DIR__ . '/../favorites.php';
    }
    
    public function homeIndex() {
        // Version MVC de l'accueil (alternative)
        $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                c.nom as categorie_nom, GROUP_CONCAT(ai.image_path) as images
                FROM annonces a
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN categories c ON a.categorie_id = c.id
                LEFT JOIN (SELECT annonce_id, image_path FROM annonce_images GROUP BY annonce_id) ai ON a.id = ai.annonce_id
                WHERE a.statut = 'active' AND a.featured = 1
                GROUP BY a.id
                ORDER BY a.created_at DESC
                LIMIT 6";
        
        $featuredAnnonces = $this->db->fetchAll($sql);
        
        $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                c.nom as categorie_nom, GROUP_CONCAT(ai.image_path) as images
                FROM annonces a
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN categories c ON a.categorie_id = c.id
                LEFT JOIN (SELECT annonce_id, image_path FROM annonce_images GROUP BY annonce_id) ai ON a.id = ai.annonce_id
                WHERE a.statut = 'active'
                GROUP BY a.id
                ORDER BY a.created_at DESC
                LIMIT 8";
        
        $latestAnnonces = $this->db->fetchAll($sql);
        
        $stats = [
            'total_annonces' => $this->db->fetch("SELECT COUNT(*) as total FROM annonces WHERE statut = 'active'")['total'],
            'total_users' => $this->db->fetch("SELECT COUNT(*) as total FROM users WHERE is_active = 1")['total'],
            'total_categories' => $this->db->fetch("SELECT COUNT(*) as total FROM categories")['total']
        ];
        
        $this->view('home/index', [
            'featuredAnnonces' => $featuredAnnonces,
            'latestAnnonces' => $latestAnnonces,
            'stats' => $stats
        ]);
    }
}
