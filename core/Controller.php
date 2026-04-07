<?php
abstract class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    protected function view($view, $data = []) {
        extract($data);
        
        $viewPath = 'views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Vue $view non trouvée");
        }
    }
    
    protected function redirect($url) {
        header("Location: " . APP_URL . $url);
        exit();
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    protected function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['user_role'] === 'admin';
    }
    
    protected function isOwner() {
        return $this->isLoggedIn() && ($_SESSION['user_role'] ?? 'utilisateur') === 'proprietaire';
    }
    
    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }
    
    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('/');
        }
    }
    
    protected function sanitize($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    protected function uploadImage($file, $folder = 'images') {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'error' => 'Aucun fichier uploadé'];
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ALLOWED_EXTENSIONS)) {
            return ['success' => false, 'error' => 'Extension non autorisée'];
        }
        
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['success' => false, 'error' => 'Fichier trop volumineux'];
        }
        
        $uploadDir = UPLOAD_PATH . $folder . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['success' => true, 'filename' => $filename, 'path' => $folder . '/' . $filename];
        }
        
        return ['success' => false, 'error' => 'Erreur lors de l\'upload'];
    }
    
    protected function paginate($query, $page = 1, $perPage = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $perPage;
        
        $countQuery = "SELECT COUNT(*) as total FROM ($query) as count_query";
        $total = $this->db->fetch($countQuery)['total'];
        
        $dataQuery = $query . " LIMIT $perPage OFFSET $offset";
        $items = $this->db->fetchAll($dataQuery);
        
        return [
            'items' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage)
        ];
    }
}
