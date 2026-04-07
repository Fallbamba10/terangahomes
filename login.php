<?php
// Page de connexion

// Configuration agressive de la session AVANT de la démarrer
ini_set('session.save_path', '/tmp');
ini_set('session.cookie_domain', '');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 0);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.gc_maxlifetime', 86400);
ini_set('session.cookie_lifetime', 86400);

session_start();

// Forcer la régénération de l'ID de session si nécessaire
if (!isset($_SESSION['initialized'])) {
    session_regenerate_id(false);
    $_SESSION['initialized'] = true;
}

// Rediriger si déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: user_dashboard.php');
    exit;
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("=== DÉBUT CONNEXION ===");
    error_log("Email soumis: " . ($_POST['email'] ?? 'NOT SET'));
    error_log("Session ID avant connexion: " . session_id());
    
    require_once 'config/config.php';
    require_once 'core/Database.php';
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        $db = Database::getInstance();
        
        // Vérifier les identifiants
        $user = $db->fetch("SELECT * FROM users WHERE email = ? AND is_active = 1", [$email]);
        
        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
            $_SESSION['user_role'] = $user['role'] ?? 'utilisateur';
            
            // Rediriger vers le dashboard approprié
            if ($_SESSION['user_role'] === 'admin') {
                error_log("Redirection vers admin_dashboard.php");
                header('Location: admin_dashboard.php');
            } else {
                error_log("Redirection vers user_dashboard.php");
                header('Location: user_dashboard.php');
            }
            exit;
        } else {
            $error = 'Email ou mot de passe incorrect';
        }
    }
}

// Langues supportées
$supported_langs = [
    'fr' => 'Français',
    'en' => 'English',
    'es' => 'Español',
    'ar' => 'العربية',
    'zh' => '中文',
    'pt' => 'Português'
];

// Langue actuelle
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'fr';
$_SESSION['lang'] = $lang;

// Traductions
$translations = [
    'fr' => [
        'site_title' => 'TerangaHomes - Connexion',
        'login' => 'Connexion',
        'register' => 'Inscription',
        'email' => 'Email',
        'password' => 'Mot de passe',
        'remember_me' => 'Se souvenir de moi',
        'forgot_password' => 'Mot de passe oublié ?',
        'no_account' => 'Pas encore de compte ?',
        'login_button' => 'Se connecter',
        'back_home' => 'Retour à l\'accueil',
        'welcome_back' => 'Bon retour parmi nous',
        'login_subtitle' => 'Connectez-vous pour accéder à votre compte'
    ],
    'en' => [
        'site_title' => 'TerangaHomes - Login',
        'login' => 'Login',
        'register' => 'Register',
        'email' => 'Email',
        'password' => 'Password',
        'remember_me' => 'Remember me',
        'forgot_password' => 'Forgot password?',
        'no_account' => 'No account yet?',
        'login_button' => 'Sign in',
        'back_home' => 'Back to home',
        'welcome_back' => 'Welcome back',
        'login_subtitle' => 'Sign in to access your account'
    ]
];

$t = $translations[$lang];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['site_title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/booking-styles.css">
    <style>
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #003580 0%, #001840 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
            min-height: 600px;
        }
        
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #003580 0%, #001840 100%);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .login-right {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-logo {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #fff, #e3f2fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .login-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #003580;
            margin-bottom: 10px;
        }
        
        .login-subtitle {
            color: #6c757d;
            margin-bottom: 40px;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #003580;
            box-shadow: 0 0 0 0.2rem rgba(0, 53, 128, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #003580 0%, #001840 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 53, 128, 0.3);
        }
        
        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
        }
        
        .divider span {
            background: white;
            padding: 0 20px;
            color: #6c757d;
        }
        
        .social-login {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .social-btn {
            flex: 1;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            background: white;
            color: #6c757d;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .social-btn:hover {
            border-color: #003580;
            color: #003580;
            transform: translateY(-2px);
        }
        
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        
        .back-link a {
            color: #003580;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-link a:hover {
            color: #001840;
        }
        
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 400px;
            }
            
            .login-left {
                padding: 40px 20px;
            }
            
            .login-right {
                padding: 40px 20px;
            }
            
            .login-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-left">
                <div class="login-logo">🏠</div>
                <h2 class="text-white mb-3"><?= $t['welcome_back'] ?></h2>
                <p class="text-white-50"><?= $t['login_subtitle'] ?></p>
                <div class="mt-4">
                    <i class="fas fa-home fa-3x text-white-50"></i>
                </div>
            </div>
            
            <div class="login-right">
                <h1 class="login-title"><?= $t['login'] ?></h1>
                <p class="login-subtitle"><?= $t['login_subtitle'] ?></p>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="login.php">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="<?= $t['email'] ?>" required>
                        <label for="email"><?= $t['email'] ?></label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="<?= $t['password'] ?>" required>
                        <label for="password"><?= $t['password'] ?></label>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                <?= $t['remember_me'] ?>
                            </label>
                        </div>
                        <a href="#" class="text-decoration-none"><?= $t['forgot_password'] ?></a>
                    </div>
                    
                    <button type="submit" class="btn btn-login">
                        <?= $t['login_button'] ?>
                    </button>
                </form>
                
                <div class="divider">
                    <span>OU</span>
                </div>
                
                <div class="social-login">
                    <a href="#" class="social-btn">
                        <i class="fab fa-google"></i>
                    </a>
                    <a href="#" class="social-btn">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-btn">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
                
                <div class="text-center">
                    <span><?= $t['no_account'] ?> </span>
                    <a href="#" class="text-decoration-none fw-bold"><?= $t['register'] ?></a>
                </div>
                
                <div class="back-link">
                    <a href="accueil_booking_fixed.php">
                        <i class="fas fa-arrow-left me-2"></i><?= $t['back_home'] ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
