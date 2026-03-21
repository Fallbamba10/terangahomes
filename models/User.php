<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        $sql = "INSERT INTO users (nom, prenom, email, password, telephone, role) 
                VALUES (:nom, :prenom, :email, :password, :telephone, :role)";
        
        $params = [
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':telephone' => $data['telephone'] ?? null,
            ':role' => $data['role'] ?? 'utilisateur'
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email AND is_active = 1";
        return $this->db->fetch($sql, [':email' => $email]);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id AND is_active = 1";
        return $this->db->fetch($sql, [':id' => $id]);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function updateProfile($id, $data) {
        $sql = "UPDATE users SET nom = :nom, prenom = :prenom, telephone = :telephone, 
                bio = :bio, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        
        $params = [
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':telephone' => $data['telephone'] ?? null,
            ':bio' => $data['bio'] ?? null,
            ':id' => $id
        ];
        
        return $this->db->query($sql, $params);
    }
    
    public function updateAvatar($id, $avatarPath) {
        $sql = "UPDATE users SET avatar = :avatar, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        return $this->db->query($sql, [':avatar' => $avatarPath, ':id' => $id]);
    }
    
    public function updatePassword($id, $password) {
        $sql = "UPDATE users SET password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        return $this->db->query($sql, [
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':id' => $id
        ]);
    }
    
    public function getAll($page = 1, $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT id, nom, prenom, email, telephone, role, is_active, created_at 
                FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        $params = [
            ':limit' => $perPage,
            ':offset' => $offset
        ];
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function toggleStatus($id) {
        $sql = "UPDATE users SET is_active = NOT is_active WHERE id = :id";
        return $this->db->query($sql, [':id' => $id]);
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM users";
        return $this->db->fetch($sql)['total'];
    }
    
    public function search($query, $page = 1, $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT id, nom, prenom, email, telephone, role, is_active, created_at 
                FROM users 
                WHERE nom LIKE :query OR prenom LIKE :query OR email LIKE :query 
                ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        $params = [
            ':query' => "%$query%",
            ':limit' => $perPage,
            ':offset' => $offset
        ];
        
        return $this->db->fetchAll($sql, $params);
    }
}
