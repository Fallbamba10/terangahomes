<?php
// Page d'inscription
session_start();

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

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/config.php';
    require_once 'core/Database.php';
    
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $role = $_POST['role'] ?? '';
    
    if (empty($prenom) || empty($nom) || empty($email) || empty($password) || empty($role)) {
        $error = 'Veuillez remplir tous les champs obligatoires';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas';
    } elseif (strlen($password) < 8) {
        $error = 'Le mot de passe doit contenir au moins 8 caractères';
    } else {
        $db = Database::getInstance();
        
        // Vérifier si l'email existe déjà
        $existingUser = $db->fetch("SELECT id FROM users WHERE email = ?", [$email]);
        
        if ($existingUser) {
            $error = 'Cet email est déjà utilisé';
        } else {
            try {
                // Insérer le nouvel utilisateur
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO users (prenom, nom, email, password, telephone, role, is_active, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, 1, NOW())";
                
                $result = $db->execute($sql, [$prenom, $nom, $email, $hashedPassword, $telephone, $role]);
                
                if ($result) {
                    $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                    header('Location: login.php?success=' . urlencode($success));
                    exit;
                } else {
                    $error = 'Erreur lors de l\'inscription';
                }
                
            } catch (Exception $e) {
                $error = 'Erreur : ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - TerangaHomes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .register-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #003580 0%, #001840 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
            min-height: 600px;
        }
        
        .register-left {
            flex: 1;
            background: linear-gradient(135deg, #003580 0%, #001840 100%);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .register-right {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .logo {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: white;
        }
        
        .tagline {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .features {
            list-style: none;
            padding: 0;
            margin-top: 30px;
        }
        
        .features li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .features i {
            margin-right: 15px;
            font-size: 1.2rem;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #003580;
            box-shadow: 0 0 0 0.2rem rgba(0, 53, 128, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(135deg, #003580 0%, #001840 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-size: 18px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 53, 128, 0.3);
        }
        
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s;
        }
        
        .strength-weak { background: #dc3545; width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: #28a745; width: 100%; }
        
        @media (max-width: 768px) {
            .register-card {
                flex-direction: column;
                margin: 20px;
            }
            
            .register-left, .register-right {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-left">
                <div class="logo">
                    <i class="fas fa-home me-2"></i>TerangaHomes
                </div>
                <div class="tagline">
                    Votre plateforme immobilière et de location de voiture au Sénégal
                </div>
                <p>
                    Rejoignez des milliers d'utilisateurs qui font confiance à TerangaHomes pour trouver leur logement idéal ou louer leur véhicule.
                </p>
                <ul class="features">
                    <li><i class="fas fa-check-circle"></i> Annonces immobilières vérifiées</li>
                    <li><i class="fas fa-check-circle"></i> Location de voiture sécurisée</li>
                    <li><i class="fas fa-check-circle"></i> Contact direct avec les propriétaires</li>
                    <li><i class="fas fa-check-circle"></i> Interface simple et intuitive</li>
                </ul>
            </div>
            
            <div class="register-right">
                <h2 class="mb-4">Créer un compte</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                    </div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom" required>
                                <label for="prenom">Prénom *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
                                <label for="nom">Nom *</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email">Email *</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Téléphone">
                        <label for="telephone">Téléphone</label>
                    </div>
                    
                    <div class="form-floating">
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Choisissez votre profil</option>
                            <option value="client">Client (je cherche un logement/voiture)</option>
                            <option value="proprietaire">Propriétaire (je veux déposer des annonces)</option>
                        </select>
                        <label for="role">Je suis un *</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required onkeyup="checkPasswordStrength()">
                        <label for="password">Mot de passe *</label>
                        <div id="passwordStrength" class="password-strength"></div>
                        <small class="text-muted">Minimum 8 caractères</small>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                        <label for="confirm_password">Confirmer le mot de passe *</label>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            J'accepte les <a href="#" class="text-decoration-none">conditions d'utilisation</a> et la <a href="#" class="text-decoration-none">politique de confidentialité</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-register w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i>S'inscrire
                    </button>
                    
                    <div class="text-center">
                        <span>Vous avez déjà un compte ? </span>
                        <a href="login.php" class="text-decoration-none fw-bold">Se connecter</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('passwordStrength');
            
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength';
            
            if (password.length === 0) {
                strengthBar.style.width = '0';
            } else if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 4) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        }
        
        // Validation des mots de passe
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
