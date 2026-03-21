<?php
class Favorite {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function add($userId, $annonceId) {
        // Vérifier si déjà en favori
        $existing = $this->db->fetch(
            "SELECT id FROM favorites WHERE user_id = ? AND annonce_id = ?",
            [$userId, $annonceId]
        );
        
        if ($existing) {
            return false; // Déjà en favori
        }
        
        $sql = "INSERT INTO favorites (user_id, annonce_id, created_at) VALUES (?, ?, NOW())";
        $this->db->query($sql, [$userId, $annonceId]);
        return true;
    }
    
    public function remove($userId, $annonceId) {
        $sql = "DELETE FROM favorites WHERE user_id = ? AND annonce_id = ?";
        $this->db->query($sql, [$userId, $annonceId]);
        return true;
    }
    
    public function isFavorite($userId, $annonceId) {
        $favorite = $this->db->fetch(
            "SELECT id FROM favorites WHERE user_id = ? AND annonce_id = ?",
            [$userId, $annonceId]
        );
        return !empty($favorite);
    }
    
    public function getUserFavorites($userId, $page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                c.nom as categorie_nom, f.created_at as favorited_at
                FROM favorites f
                JOIN annonces a ON f.annonce_id = a.id
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN categories c ON a.categorie_id = c.id
                WHERE f.user_id = ? AND a.statut = 'active'
                ORDER BY f.created_at DESC
                LIMIT $limit OFFSET $offset";
        
        $annonces = $this->db->fetchAll($sql, [$userId]);
        
        // Compter le total
        $totalSql = "SELECT COUNT(*) as total 
                    FROM favorites f 
                    JOIN annonces a ON f.annonce_id = a.id 
                    WHERE f.user_id = ? AND a.statut = 'active'";
        $totalResult = $this->db->fetch($totalSql, [$userId]);
        $total = $totalResult['total'] ?? 0;
        
        return [
            'annonces' => $annonces,
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total,
            'total_pages' => ceil($total / $limit)
        ];
    }
    
    public function getFavoriteCount($userId) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM favorites WHERE user_id = ?",
            [$userId]
        );
        return $result['total'] ?? 0;
    }
}
