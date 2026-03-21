<?php
class HomeController_modern extends Controller {
    
    public function index() {
        try {
            // Récupérer les annonces featured
            $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                    c.nom as categorie_nom 
                    FROM annonces a
                    LEFT JOIN users u ON a.user_id = u.id
                    LEFT JOIN categories c ON a.categorie_id = c.id
                    WHERE a.statut = 'active' AND a.featured = 1
                    ORDER BY a.created_at DESC
                    LIMIT 6";
            
            $featuredAnnonces = $this->db->fetchAll($sql);
            
            // Récupérer les dernières annonces
            $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                    c.nom as categorie_nom 
                    FROM annonces a
                    LEFT JOIN users u ON a.user_id = u.id
                    LEFT JOIN categories c ON a.categorie_id = c.id
                    WHERE a.statut = 'active'
                    ORDER BY a.created_at DESC
                    LIMIT 8";
            
            $latestAnnonces = $this->db->fetchAll($sql);
            
            // Statistiques
            $stats = [
                'total_annonces' => $this->db->fetch("SELECT COUNT(*) as total FROM annonces WHERE statut = 'active'")['total'] ?? 0,
                'total_users' => $this->db->fetch("SELECT COUNT(*) as total FROM users WHERE is_active = 1")['total'] ?? 0,
                'total_categories' => $this->db->fetch("SELECT COUNT(*) as total FROM categories")['total'] ?? 0
            ];
            
            // Utiliser la vue moderne
            $this->view('home/modern', [
                'featuredAnnonces' => $featuredAnnonces,
                'latestAnnonces' => $latestAnnonces,
                'stats' => $stats
            ]);
            
        } catch (Exception $e) {
            echo "<h1>Erreur dans HomeController</h1>";
            echo "<p>Erreur: " . $e->getMessage() . "</p>";
        }
    }
}
