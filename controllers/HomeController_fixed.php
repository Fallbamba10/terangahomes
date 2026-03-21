<?php
class HomeController_fixed extends Controller {
    
    public function index() {
        try {
            // Récupérer les annonces featured
            $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom 
                    FROM annonces a
                    LEFT JOIN users u ON a.user_id = u.id
                    WHERE a.statut = 'active' AND a.featured = 1
                    ORDER BY a.created_at DESC
                    LIMIT 6";
            
            $featuredAnnonces = $this->db->fetchAll($sql);
            
            // Récupérer les dernières annonces
            $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom 
                    FROM annonces a
                    LEFT JOIN users u ON a.user_id = u.id
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
            
            echo "<h1>🏠 TerangaHomes - Version Corrigée</h1>";
            echo "<nav style='background: #0066cc; padding: 15px;'>";
            echo "<a href='index.php' style='color: white; margin-right: 20px;'>Accueil</a>";
            echo "<a href='login.php' style='color: white; margin-right: 20px;'>Connexion</a>";
            echo "<a href='register.php' style='color: white;'>Inscription</a>";
            echo "</nav>";
            
            echo "<div style='padding: 40px;'>";
            echo "<h2>Bienvenue sur TerangaHomes !</h2>";
            echo "<p>" . count($featuredAnnonces) . " annonces featured</p>";
            echo "<p>" . count($latestAnnonces) . " dernières annonces</p>";
            echo "<p>Statistiques: " . $stats['total_annonces'] . " annonces, " . $stats['total_users'] . " utilisateurs</p>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<h1>Erreur dans HomeController</h1>";
            echo "<p>Erreur: " . $e->getMessage() . "</p>";
        }
    }
}
