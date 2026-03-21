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
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            max-width: 450px;
            width: 100%;
            margin: 2rem;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .login-logo {
            font-size: 3rem;
            color: #0066cc;
            margin-bottom: 1rem;
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #0066cc;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: #666;
            font-size: 1rem;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #0066cc;
            box-shadow: 0 0 0 0.2rem rgba(0,102,204,0.25);
        }
        
        .input-group-text {
            background: #0066cc;
            border: 2px solid #0066cc;
            color: white;
            border-radius: 10px 0 0 10px;
        }
        
        .btn-login {
            background: #0066cc;
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 10px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-login:hover {
            background: #004499;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,102,204,0.3);
        }
        
        .btn-outline-primary {
            background: transparent;
            border: 2px solid #0066cc;
            color: #0066cc;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 10px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-outline-primary:hover {
            background: #0066cc;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,102,204,0.3);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .demo-accounts {
            background: rgba(0,102,204,0.1);
            border: 1px solid rgba(0,102,204,0.3);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .demo-accounts h6 {
            color: #0066cc;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .demo-account {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border: 1px solid rgba(0,102,204,0.2);
        }
        
        .demo-account:last-child {
            margin-bottom: 0;
        }
        
        .demo-account strong {
            color: #0066cc;
        }
        
        .demo-account small {
            color: #666;
            font-size: 0.85rem;
        }
        
        .back-link {
            text-align: center;
            margin-top: 2rem;
        }
        
        .back-link a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
        
        .back-link a:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-home"></i>
            </div>
            <h1 class="login-title">TerangaHomes</h1>
            <p class="login-subtitle">Connectez-vous à votre compte</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
            </div>
        <?php endif; ?>
        
        <!-- Comptes de démonstration -->
        <div class="demo-accounts">
            <h6><i class="fas fa-info-circle me-2"></i>Comptes de démonstration</h6>
            
            <div class="demo-account">
                <div><strong>Utilisateur normal</strong></div>
                <small>Email: jean.dupont@terangahomes.com</small><br>
                <small>Mot de passe: password123</small>
            </div>
            
            <div class="demo-account">
                <div><strong>Administrateur</strong></div>
                <small>Email: admin@terangahomes.com</small><br>
                <small>Mot de passe: admin123</small>
            </div>
        </div>
        
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="Entrez votre email" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Entrez votre mot de passe" required>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember">
                        Se souvenir de moi
                    </label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-login mb-3">
                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
            </button>
            
            <div class="text-center mb-3">
                <a href="accueil_original.php" class="btn btn-outline-primary">
                    <i class="fas fa-home me-2"></i>Déposer une annonce
                </a>
            </div>
            
            <div class="text-center">
                <small class="text-muted">
                    Mot de passe oublié ? 
                    <a href="#" class="text-primary">Réinitialiser</a>
                </small>
            </div>
        </form>
        
        <div class="back-link">
            <a href="accueil_simple.php">
                <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
            </a>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Auto-remplissage pour les tests
    document.addEventListener('DOMContentLoaded', function() {
        // Vous pouvez décommenter ces lignes pour auto-remplir
        // document.getElementById('email').value = 'jean.dupont@terangahomes.com';
        // document.getElementById('password').value = 'password123';
    });
    </script>
</body>
</html>
