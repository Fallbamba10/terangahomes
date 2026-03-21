<?php
class HomeController extends Controller {
    
    public function index() {
        // Récupérer les annonces featured
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
        
        // Récupérer les dernières annonces
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
        
        // Statistiques
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
