<?php
class Annonce {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        $sql = "INSERT INTO annonces (titre, description, prix, type, categorie_id, user_id, ville, quartier, 
                adresse, latitude, longitude, superficie, chambres, salles_bain, parking, meuble, 
                climatisation, wifi, piscine, images, statut, featured, duree_minimale, type_location, 
                services_complementaires) 
                VALUES (:titre, :description, :prix, :type, :categorie_id, :user_id, :ville, :quartier, 
                :adresse, :latitude, :longitude, :superficie, :chambres, :salles_bain, :parking, :meuble, 
                :climatisation, :wifi, :piscine, :images, :statut, :featured, :duree_minimale, :type_location, 
                :services_complementaires)";
        
        $params = [
            ':titre' => $data['titre'],
            ':description' => $data['description'],
            ':prix' => $data['prix'],
            ':type' => $data['type'],
            ':categorie_id' => $data['categorie_id'] ?? null,
            ':user_id' => $data['user_id'],
            ':ville' => $data['ville'],
            ':quartier' => $data['quartier'] ?? null,
            ':adresse' => $data['adresse'] ?? null,
            ':latitude' => $data['latitude'] ?? null,
            ':longitude' => $data['longitude'] ?? null,
            ':superficie' => $data['superficie'] ?? null,
            ':chambres' => $data['chambres'] ?? 0,
            ':salles_bain' => $data['salles_bain'] ?? 0,
            ':parking' => $data['parking'] ?? 0,
            ':meuble' => $data['meuble'] ?? 0,
            ':climatisation' => $data['climatisation'] ?? 0,
            ':wifi' => $data['wifi'] ?? 0,
            ':piscine' => $data['piscine'] ?? 0,
            ':images' => json_encode($data['images'] ?? []),
            ':statut' => $data['statut'] ?? 'active',
            ':featured' => $data['featured'] ?? 0,
            ':duree_minimale' => $data['duree_minimale'] ?? null,
            ':type_location' => $data['type_location'] ?? null,
            ':services_complementaires' => json_encode($data['services_complementaires'] ?? [])
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function findById($id) {
        $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                u.email as proprietaire_email, u.telephone as proprietaire_telephone,
                c.nom as categorie_nom 
                FROM annonces a
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN categories c ON a.categorie_id = c.id
                WHERE a.id = :id";
        
        return $this->db->fetch($sql, [':id' => $id]);
    }
    
    public function getAll($page = 1, $filters = []) {
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        
        $where = ["a.statut = 'active'"];
        $params = [];
        
        // Filtres
        if (!empty($filters['type'])) {
            $where[] = "a.type = :type";
            $params[':type'] = $filters['type'];
        }
        
        if (!empty($filters['ville'])) {
            $where[] = "a.ville LIKE :ville";
            $params[':ville'] = '%' . $filters['ville'] . '%';
        }
        
        if (!empty($filters['prix_min'])) {
            $where[] = "a.prix >= :prix_min";
            $params[':prix_min'] = $filters['prix_min'];
        }
        
        if (!empty($filters['prix_max'])) {
            $where[] = "a.prix <= :prix_max";
            $params[':prix_max'] = $filters['prix_max'];
        }
        
        if (!empty($filters['categorie_id'])) {
            $where[] = "a.categorie_id = :categorie_id";
            $params[':categorie_id'] = $filters['categorie_id'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        // Compter le total
        $countSql = "SELECT COUNT(*) as total FROM annonces a WHERE $whereClause";
        $total = $this->db->fetch($countSql, $params)['total'];
        
        // Récupérer les annonces
        $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                c.nom as categorie_nom 
                FROM annonces a
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN categories c ON a.categorie_id = c.id
                WHERE $whereClause
                ORDER BY a.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $params[':limit'] = ITEMS_PER_PAGE;
        $params[':offset'] = $offset;
        
        $annonces = $this->db->fetchAll($sql, $params);
        
        return [
            'annonces' => $annonces,
            'total' => $total,
            'per_page' => ITEMS_PER_PAGE,
            'current_page' => $page,
            'total_pages' => ceil($total / ITEMS_PER_PAGE)
        ];
    }
    
    public function getByUser($userId, $page = 1) {
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        
        $sql = "SELECT a.*, c.nom as categorie_nom 
                FROM annonces a
                LEFT JOIN categories c ON a.categorie_id = c.id
                WHERE a.user_id = :user_id
                ORDER BY a.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $annonces = $this->db->fetchAll($sql, [
            ':user_id' => $userId,
            ':limit' => ITEMS_PER_PAGE,
            ':offset' => $offset
        ]);
        
        // Compter le total
        $total = $this->db->fetch("SELECT COUNT(*) as total FROM annonces WHERE user_id = ?", [$userId])['total'];
        
        return [
            'annonces' => $annonces,
            'total' => $total,
            'per_page' => ITEMS_PER_PAGE,
            'current_page' => $page,
            'total_pages' => ceil($total / ITEMS_PER_PAGE)
        ];
    }
    
    public function update($id, $data) {
        $sql = "UPDATE annonces SET titre = :titre, description = :description, prix = :prix, 
                type = :type, categorie_id = :categorie_id, ville = :ville, quartier = :quartier, 
                adresse = :adresse, latitude = :latitude, longitude = :longitude, superficie = :superficie, 
                chambres = :chambres, salles_bain = :salles_bain, parking = :parking, meuble = :meuble, 
                climatisation = :climatisation, wifi = :wifi, piscine = :piscine, updated_at = CURRENT_TIMESTAMP";
        
        $params = [
            ':titre' => $data['titre'],
            ':description' => $data['description'],
            ':prix' => $data['prix'],
            ':type' => $data['type'],
            ':categorie_id' => $data['categorie_id'] ?? null,
            ':ville' => $data['ville'],
            ':quartier' => $data['quartier'] ?? null,
            ':adresse' => $data['adresse'] ?? null,
            ':latitude' => $data['latitude'] ?? null,
            ':longitude' => $data['longitude'] ?? null,
            ':superficie' => $data['superficie'] ?? null,
            ':chambres' => $data['chambres'] ?? 0,
            ':salles_bain' => $data['salles_bain'] ?? 0,
            ':parking' => $data['parking'] ?? 0,
            ':meuble' => $data['meuble'] ?? 0,
            ':climatisation' => $data['climatisation'] ?? 0,
            ':wifi' => $data['wifi'] ?? 0,
            ':piscine' => $data['piscine'] ?? 0
        ];
        
        // Mettre à jour les images si fournies
        if (isset($data['images'])) {
            $sql .= ", images = :images";
            $params[':images'] = json_encode($data['images']);
        }
        
        $sql .= " WHERE id = :id AND user_id = :user_id";
        $params[':id'] = $id;
        $params[':user_id'] = $_SESSION['user_id'] ?? null;
        
        return $this->db->query($sql, $params);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM annonces WHERE id = :id AND user_id = :user_id";
        return $this->db->query($sql, [
            ':id' => $id,
            ':user_id' => $_SESSION['user_id'] ?? null
        ]);
    }
    
    public function toggleStatus($id) {
        $sql = "UPDATE annonces SET statut = CASE 
                WHEN statut = 'active' THEN 'inactive' 
                ELSE 'active' 
                END WHERE id = :id AND user_id = :user_id";
        return $this->db->query($sql, [
            ':id' => $id,
            ':user_id' => $_SESSION['user_id'] ?? null
        ]);
    }
    
    public function incrementViews($id) {
        $sql = "UPDATE annonces SET views_count = views_count + 1 WHERE id = :id";
        return $this->db->query($sql, [':id' => $id]);
    }
    
    public function getFeatured($limit = 6) {
        $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                c.nom as categorie_nom 
                FROM annonces a
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN categories c ON a.categorie_id = c.id
                WHERE a.statut = 'active' AND a.featured = 1
                ORDER BY a.created_at DESC
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [':limit' => $limit]);
    }
    
    public function getLatest($limit = 8) {
        $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                c.nom as categorie_nom 
                FROM annonces a
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN categories c ON a.categorie_id = c.id
                WHERE a.statut = 'active'
                ORDER BY a.created_at DESC
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [':limit' => $limit]);
    }
    
    public function search($query, $filters = [], $page = 1) {
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        
        $where = ["a.statut = 'active'"];
        $params = [];
        
        // Recherche textuelle
        if (!empty($query)) {
            $where[] = "(a.titre LIKE :query OR a.description LIKE :query OR a.ville LIKE :query)";
            $params[':query'] = '%' . $query . '%';
        }
        
        // Appliquer les filtres supplémentaires
        if (!empty($filters['type'])) {
            $where[] = "a.type = :type";
            $params[':type'] = $filters['type'];
        }
        
        if (!empty($filters['prix_min'])) {
            $where[] = "a.prix >= :prix_min";
            $params[':prix_min'] = $filters['prix_min'];
        }
        
        if (!empty($filters['prix_max'])) {
            $where[] = "a.prix <= :prix_max";
            $params[':prix_max'] = $filters['prix_max'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        // Compter le total
        $countSql = "SELECT COUNT(*) as total FROM annonces a WHERE $whereClause";
        $total = $this->db->fetch($countSql, $params)['total'];
        
        // Récupérer les résultats
        $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                c.nom as categorie_nom 
                FROM annonces a
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN categories c ON a.categorie_id = c.id
                WHERE $whereClause
                ORDER BY a.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $params[':limit'] = ITEMS_PER_PAGE;
        $params[':offset'] = $offset;
        
        $annonces = $this->db->fetchAll($sql, $params);
        
        return [
            'annonces' => $annonces,
            'total' => $total,
            'per_page' => ITEMS_PER_PAGE,
            'current_page' => $page,
            'total_pages' => ceil($total / ITEMS_PER_PAGE),
            'query' => $query
        ];
    }
}
