<?php
class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function showLogin() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login');
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }
        
        $email = $this->sanitize($_POST['email']);
        $password = $_POST['password'];
        $remember = isset($_POST['remember']);
        
        // Validation
        $errors = [];
        
        if (empty($email)) {
            $errors['email'] = 'L\'email est requis';
        } elseif (!$this->validateEmail($email)) {
            $errors['email'] = 'Email invalide';
        }
        
        if (empty($password)) {
            $errors['password'] = 'Le mot de passe est requis';
        }
        
        if (!empty($errors)) {
            $this->view('auth/login', ['errors' => $errors, 'old' => ['email' => $email]]);
            return;
        }
        
        // Tentative de connexion
        $user = $this->userModel->findByEmail($email);
        
        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_avatar'] = $user['avatar'];
            $_SESSION['login_time'] = time();
            
            // Cookie "Remember me"
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/'); // 30 jours
                // TODO: Stocker le token en base de données
            }
            
            // Redirection selon le rôle
            switch ($user['role']) {
                case 'admin':
                    $this->redirect('/admin');
                    break;
                case 'proprietaire':
                    $this->redirect('/my-annonces');
                    break;
                default:
                    $this->redirect('/dashboard');
                    break;
            }
        } else {
            $errors['login'] = 'Email ou mot de passe incorrect';
            $this->view('auth/login', ['errors' => $errors, 'old' => ['email' => $email]]);
        }
    }
    
    public function showRegister() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/register');
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }
        
        $data = [
            'nom' => $this->sanitize($_POST['nom']),
            'prenom' => $this->sanitize($_POST['prenom']),
            'email' => $this->sanitize($_POST['email']),
            'password' => $_POST['password'],
            'password_confirm' => $_POST['password_confirm'],
            'telephone' => $this->sanitize($_POST['telephone'] ?? ''),
            'role' => $this->sanitize($_POST['role'] ?? 'utilisateur')
        ];
        
        // Validation
        $errors = [];
        
        if (empty($data['nom'])) {
            $errors['nom'] = 'Le nom est requis';
        } elseif (strlen($data['nom']) < 2) {
            $errors['nom'] = 'Le nom doit contenir au moins 2 caractères';
        }
        
        if (empty($data['prenom'])) {
            $errors['prenom'] = 'Le prénom est requis';
        } elseif (strlen($data['prenom']) < 2) {
            $errors['prenom'] = 'Le prénom doit contenir au moins 2 caractères';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'L\'email est requis';
        } elseif (!$this->validateEmail($data['email'])) {
            $errors['email'] = 'Email invalide';
        } elseif ($this->userModel->findByEmail($data['email'])) {
            $errors['email'] = 'Cet email est déjà utilisé';
        }
        
        if (empty($data['password'])) {
            $errors['password'] = 'Le mot de passe est requis';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $data['password'])) {
            $errors['password'] = 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre';
        }
        
        if ($data['password'] !== $data['password_confirm']) {
            $errors['password_confirm'] = 'Les mots de passe ne correspondent pas';
        }
        
        if (!empty($data['telephone']) && !preg_match('/^[0-9\+\-\s\(\)]+$/', $data['telephone'])) {
            $errors['telephone'] = 'Numéro de téléphone invalide';
        }
        
        if (!empty($errors)) {
            $this->view('auth/register', ['errors' => $errors, 'old' => $data]);
            return;
        }
        
        // Création de l'utilisateur
        try {
            $userId = $this->userModel->create($data);
            
            // Envoyer email de confirmation (TODO)
            
            // Connexion automatique
            $user = $this->userModel->findById($userId);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_avatar'] = $user['avatar'];
            $_SESSION['login_time'] = time();
            
            $this->redirect('/dashboard');
            
        } catch (Exception $e) {
            $errors['general'] = 'Une erreur est survenue lors de l\'inscription';
            $this->view('auth/register', ['errors' => $errors, 'old' => $data]);
        }
    }
    
    public function logout() {
        // Destruction de la session
        session_destroy();
        
        // Suppression du cookie "Remember me"
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        $this->redirect('/login');
    }
}
