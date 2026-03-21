<?php
// Page de connexion ultra-simple

// Inclure les fichiers nécessaires
require_once 'config/config.php';
require_once 'core/Database.php';

session_start();

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validation simple
    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        // Vérifier les identifiants dans la base de données
        $db = Database::getInstance();
        
        try {
            $user = $db->fetch("SELECT * FROM users WHERE email = ? AND is_active = 1", [$email]);
            
            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_prenom'] = $user['prenom'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_telephone'] = $user['telephone'];
                
                // Redirection selon le rôle
                if ($user['role'] === 'admin') {
                    header('Location: admin.php');
                } else {
                    header('Location: dashboard.php');
                }
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        } catch (Exception $e) {
            $error = 'Erreur de connexion. Veuillez réessayer.';
        }
    }
}

// Traitement de la déconnexion
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    session_destroy();
    header('Location: connexion_simple.php');
    exit;
} else {
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['user_role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: accueil_simple.php');
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - TerangaHomes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --seloger-blue: #0066cc;
            --seloger-dark-blue: #004499;
            --seloger-light-blue: #e6f2ff;
            --seloger-gray: #6c757d;
            --seloger-light-gray: #f8f9fa;
            --seloger-border: #dee2e6;
            --seloger-success: #28a745;
            --seloger-orange: #ff6b35;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header SeLoger style */
        .login-header {
            background: white;
            border-bottom: 1px solid var(--seloger-border);
            padding: 15px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo-seloger {
            font-size: 2rem;
            font-weight: 700;
            color: var(--seloger-blue);
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logo-seloger i {
            margin-right: 10px;
            font-size: 1.8rem;
        }
        
        .header-nav {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        
        .header-link {
            color: var(--seloger-gray);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .header-link:hover {
            color: var(--seloger-blue);
        }
        
        /* Main content */
        .login-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            max-width: 1200px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
            min-height: 600px;
        }
        
        /* Left panel - Form */
        .login-form-panel {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-form-header {
            margin-bottom: 40px;
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            line-height: 1.2;
        }
        
        .login-subtitle {
            color: var(--seloger-gray);
            font-size: 16px;
            line-height: 1.5;
        }
        
        .login-form {
            max-width: 400px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--seloger-border);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--seloger-blue);
            box-shadow: 0 0 0 3px rgba(0,102,204,0.1);
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--seloger-gray);
            font-size: 16px;
        }
        
        .input-with-icon .form-control {
            padding-left: 45px;
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--seloger-gray);
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
        }
        
        .password-toggle:hover {
            color: var(--seloger-blue);
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid var(--seloger-border);
            border-radius: 4px;
            cursor: pointer;
        }
        
        .form-check-label {
            font-size: 14px;
            color: #2c3e50;
            cursor: pointer;
            user-select: none;
        }
        
        .forgot-password {
            color: var(--seloger-blue);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .forgot-password:hover {
            color: var(--seloger-dark-blue);
            text-decoration: underline;
        }
        
        .btn-login {
            background: var(--seloger-blue);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-login:hover {
            background: var(--seloger-dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,102,204,0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
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
            background: var(--seloger-border);
        }
        
        .divider span {
            background: white;
            padding: 0 15px;
            color: var(--seloger-gray);
            font-size: 14px;
            position: relative;
            z-index: 1;
        }
        
        .social-login {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
        }
        
        .btn-social {
            flex: 1;
            padding: 12px;
            border: 2px solid var(--seloger-border);
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .btn-social:hover {
            border-color: var(--seloger-blue);
            background: var(--seloger-light-blue);
        }
        
        .btn-social i {
            font-size: 18px;
        }
        
        .register-link {
            text-align: center;
            color: var(--seloger-gray);
            font-size: 14px;
        }
        
        .register-link a {
            color: var(--seloger-blue);
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        /* Right panel - Visual */
        .login-visual-panel {
            background: linear-gradient(135deg, var(--seloger-blue) 0%, var(--seloger-dark-blue) 100%);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .login-visual-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="20" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="80" cy="30" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .visual-content {
            position: relative;
            z-index: 1;
        }
        
        .visual-icon {
            font-size: 4rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .visual-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .visual-description {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 40px;
            opacity: 0.9;
            max-width: 400px;
        }
        
        .visual-features {
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-align: left;
        }
        
        .visual-feature {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .visual-feature i {
            font-size: 20px;
            opacity: 0.8;
        }
        
        .visual-feature-text {
            font-size: 16px;
            line-height: 1.4;
        }
        
        /* Alert styles */
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-danger {
            background: #fee;
            color: #c33;
            border-left: 4px solid #c33;
        }
        
        .alert-success {
            background: #efe;
            color: #3c3;
            border-left: 4px solid #3c3;
        }
        
        .alert i {
            font-size: 18px;
        }
        
        /* Demo accounts */
        .demo-accounts {
            background: var(--seloger-light-blue);
            border: 1px solid rgba(0,102,204,0.2);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
        }
        
        .demo-accounts h6 {
            color: var(--seloger-blue);
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .demo-account {
            background: white;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid rgba(0,102,204,0.1);
            font-size: 13px;
        }
        
        .demo-account:last-child {
            margin-bottom: 0;
        }
        
        .demo-account strong {
            color: var(--seloger-blue);
            display: block;
            margin-bottom: 4px;
        }
        
        .demo-account small {
            color: var(--seloger-gray);
            line-height: 1.4;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 500px;
            }
            
            .login-visual-panel {
                display: none;
            }
            
            .login-form-panel {
                padding: 40px 30px;
            }
        }
        
        @media (max-width: 576px) {
            .header-container {
                padding: 0 15px;
            }
            
            .logo-seloger {
                font-size: 1.5rem;
            }
            
            .login-form-panel {
                padding: 30px 20px;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
            
            .social-login {
                flex-direction: column;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-form-panel > * {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        .login-form-panel > *:nth-child(2) { animation-delay: 0.1s; }
        .login-form-panel > *:nth-child(3) { animation-delay: 0.2s; }
        .login-form-panel > *:nth-child(4) { animation-delay: 0.3s; }
    </style>
</head>
<body>
    <!-- Header SeLoger style -->
    <header class="login-header">
        <div class="header-container">
            <a href="accueil_booking_fixed.php" class="logo-seloger">
                <i class="fas fa-home"></i>
                TerangaHomes
            </a>
            <nav class="header-nav">
                <a href="accueil_booking_fixed.php" class="header-link">Accueil</a>
                <a href="#" class="header-link">Aide</a>
                <a href="#" class="header-link">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Main content -->
    <main class="login-main">
        <div class="login-container">
            <!-- Left panel - Form -->
            <div class="login-form-panel">
                <div class="login-form-header">
                    <h1 class="login-title">Connectez-vous à votre espace</h1>
                    <p class="login-subtitle">Accédez à votre tableau de bord pour gérer vos annonces</p>
                </div>
                
                <form class="login-form" method="POST">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <span><?= $error ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Comptes de démonstration -->
                    <div class="demo-accounts">
                        <h6><i class="fas fa-info-circle"></i>Comptes de démonstration</h6>
                        <div class="demo-account">
                            <strong>Utilisateur normal</strong>
                            <small>Email: jean.dupont@terangahomes.com</small><br>
                            <small>Mot de passe: password123</small>
                        </div>
                        <div class="demo-account">
                            <strong>Administrateur</strong>
                            <small>Email: admin@terangahomes.com</small><br>
                            <small>Mot de passe: admin123</small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Adresse email</label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="exemple@email.com" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Entrez votre mot de passe" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <div class="form-check">
                            <input type="checkbox" id="remember" name="remember" class="form-check-input">
                            <label for="remember" class="form-check-label">Se souvenir de moi</label>
                        </div>
                        <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                    </div>
                    
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        Se connecter
                    </button>
                    
                    <div class="divider">
                        <span>ou</span>
                    </div>
                    
                    <div class="social-login">
                        <button type="button" class="btn-social">
                            <i class="fab fa-google"></i>
                            Google
                        </button>
                        <button type="button" class="btn-social">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </button>
                    </div>
                    
                    <div class="register-link">
                        Pas encore de compte ? <a href="#">Créer un compte</a>
                    </div>
                </form>
            </div>
            
            <!-- Right panel - Visual -->
            <div class="login-visual-panel">
                <div class="visual-content">
                    <div class="visual-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h2 class="visual-title">La plateforme immobilière de référence au Sénégal</h2>
                    <p class="visual-description">
                        Rejoignez des milliers de propriétaires et locataires qui font confiance à TerangaHomes pour leurs transactions immobilières.
                    </p>
                    
                    <div class="visual-features">
                        <div class="visual-feature">
                            <i class="fas fa-shield-alt"></i>
                            <div class="visual-feature-text">
                                <strong>100% Sécurisé</strong><br>
                                Transactions protégées et vérifiées
                            </div>
                        </div>
                        <div class="visual-feature">
                            <i class="fas fa-bolt"></i>
                            <div class="visual-feature-text">
                                <strong>Rapide & Efficace</strong><br>
                                Publication instantanée de vos annonces
                            </div>
                        </div>
                        <div class="visual-feature">
                            <i class="fas fa-users"></i>
                            <div class="visual-feature-text">
                                <strong>Support 24/7</strong><br>
                                Assistance professionnelle disponible
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Toggle password visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    
    // Auto-remplissage pour les tests
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des éléments du formulaire
        const formElements = document.querySelectorAll('.login-form-panel > *');
        formElements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                el.style.transition = 'all 0.6s ease';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Gestion des boutons sociaux (demo)
        document.querySelectorAll('.btn-social').forEach(btn => {
            btn.addEventListener('click', function() {
                const provider = this.textContent.trim();
                alert(`Connexion avec ${provider} - Fonctionnalité de démonstration`);
            });
        });
        
        // Gestion du mot de passe oublié
        document.querySelector('.forgot-password').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Fonctionnalité de réinitialisation de mot de passe - Contactez l\'administrateur');
        });
        
        // Gestion du lien de création de compte
        document.querySelector('.register-link a').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Page d\'inscription - Fonctionnalité de démonstration');
        });
        
        // Validation du formulaire avant soumission
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs');
                return false;
            }
            
            // Animation de chargement
            const submitBtn = document.querySelector('.btn-login');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion en cours...';
            submitBtn.disabled = true;
            
            // Restaurer après 2 secondes (demo)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
        
        // Auto-remplissage pour les tests (optionnel)
        // Décommentez pour activer
        /*
        document.getElementById('email').value = 'jean.dupont@terangahomes.com';
        document.getElementById('password').value = 'password123';
        */
    });
    </script>
</body>
</html>
