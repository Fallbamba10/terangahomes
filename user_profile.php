<?php
// Système de profil utilisateur complet

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

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'src/config/config.php';
require_once 'src/core/Database.php';

$db = Database::getInstance();
$userId = $_SESSION['user_id'];

// Traitement des actions
$action = $_GET['action'] ?? '';

if ($action === 'update_profile' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'prenom' => $_POST['prenom'] ?? '',
        'nom' => $_POST['nom'] ?? '',
        'email' => $_POST['email'] ?? '',
        'telephone' => $_POST['telephone'] ?? '',
        'adresse' => $_POST['adresse'] ?? '',
        'ville' => $_POST['ville'] ?? '',
        'pays' => $_POST['pays'] ?? '',
        'bio' => $_POST['bio'] ?? '',
        'id' => $userId
    ];
    
    if (!empty($data['prenom']) && !empty($data['nom']) && !empty($data['email'])) {
        try {
            // Vérifier si l'email est déjà utilisé par un autre utilisateur
            $existingUser = $db->fetch("SELECT id FROM users WHERE email = ? AND id != ?", [$data['email'], $userId]);
            
            if ($existingUser) {
                $error = "Cet email est déjà utilisé par un autre utilisateur";
            } else {
                $sql = "UPDATE users SET prenom = :prenom, nom = :nom, email = :email, telephone = :telephone, 
                        adresse = :adresse, ville = :ville, pays = :pays, bio = :bio, updated_at = NOW()
                        WHERE id = :id";
                
                $db->execute($sql, $data);
                
                // Mettre à jour la session
                $_SESSION['user_name'] = $data['prenom'] . ' ' . $data['nom'];
                $_SESSION['user_email'] = $data['email'];
                
                $success = "Profil mis à jour avec succès !";
                header("Location: user_profile.php?success=" . urlencode($success));
                exit;
            }
            
        } catch (Exception $e) {
            $error = "Erreur lors de la mise à jour: " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires";
    }
}

if ($action === 'change_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (!empty($currentPassword) && !empty($newPassword) && !empty($confirmPassword)) {
        // Vérifier le mot de passe actuel
        $user = $db->fetch("SELECT * FROM users WHERE id = ?", [$userId]);
        
        if ($user && password_verify($currentPassword, $user['password'])) {
            if ($newPassword === $confirmPassword) {
                if (strlen($newPassword) >= 8) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $db->execute("UPDATE users SET password = ? WHERE id = ?", [$hashedPassword, $userId]);
                    
                    $success = "Mot de passe changé avec succès !";
                    header("Location: user_profile.php?success=" . urlencode($success));
                    exit;
                } else {
                    $error = "Le mot de passe doit contenir au moins 8 caractères";
                }
            } else {
                $error = "Les mots de passe ne correspondent pas";
            }
        } else {
            $error = "Mot de passe actuel incorrect";
        }
    } else {
        $error = "Veuillez remplir tous les champs";
    }
}

if ($action === 'upload_avatar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (in_array($_FILES['avatar']['type'], $allowedTypes) && $_FILES['avatar']['size'] <= $maxSize) {
            $uploadDir = 'uploads/avatars/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = 'avatar_' . $userId . '_' . time() . '.' . pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $filePath)) {
                // Supprimer l'ancien avatar s'il existe
                $oldAvatar = $db->fetch("SELECT avatar FROM users WHERE id = ?", [$userId]);
                if ($oldAvatar['avatar'] && file_exists($oldAvatar['avatar'])) {
                    unlink($oldAvatar['avatar']);
                }
                
                $db->execute("UPDATE users SET avatar = ? WHERE id = ?", [$filePath, $userId]);
                
                $success = "Avatar mis à jour avec succès !";
                header("Location: user_profile.php?success=" . urlencode($success));
                exit;
            } else {
                $error = "Erreur lors du téléchargement de l'image";
            }
        } else {
            $error = "Format d'image non valide ou taille trop grande (max 5MB)";
        }
    } else {
        $error = "Veuillez sélectionner une image";
    }
}

// Récupérer les informations de l'utilisateur
$user = $db->fetch("SELECT * FROM users WHERE id = ?", [$userId]);

// Récupérer les statistiques de l'utilisateur
$stats = [
    'total_annonces' => $db->fetch("SELECT COUNT(*) as count FROM annonces WHERE user_id = ?", [$userId])['count'],
    'active_annonces' => $db->fetch("SELECT COUNT(*) as count FROM annonces WHERE user_id = ? AND statut = 'active'", [$userId])['count'],
    'total_favorites' => $db->fetch("SELECT COUNT(*) as count FROM favorites WHERE user_id = ?", [$userId])['count'],
    'total_messages' => $db->fetch("SELECT COUNT(*) as count FROM messages WHERE (sender_id = ? OR receiver_id = ?)", [$userId, $userId])['count']
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerangaHomes - Mon Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-container {
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #003580 0%, #001840 100%);
            color: white;
            padding: 40px 0;
        }
        
        .avatar-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
        }
        
        .avatar-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        .avatar-upload {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #28a745;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .avatar-upload:hover {
            background: #218838;
            transform: scale(1.1);
        }
        
        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #003580;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section h5 {
            color: #003580;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #003580;
        }
        
        .nav-pills .nav-link {
            border-radius: 8px;
            color: #003580;
            font-weight: 500;
        }
        
        .nav-pills .nav-link.active {
            background: #003580;
        }
        
        .bio-textarea {
            min-height: 100px;
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <!-- Header -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="user_dashboard.php">
                    <i class="fas fa-home me-2"></i>TerangaHomes
                </a>
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="user_dashboard.php">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                    <a class="nav-link active" href="user_profile.php">
                        <i class="fas fa-user me-1"></i>Profil
                    </a>
                    <a class="nav-link" href="messaging_system.php">
                        <i class="fas fa-comments me-1"></i>Messagerie
                    </a>
                    <a class="nav-link" href="manage_annonces.php">
                        <i class="fas fa-list me-1"></i>Annonces
                    </a>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                    </a>
                </div>
            </div>
        </nav>
        
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="container text-center">
                <div class="avatar-container">
                    <?php if ($user['avatar']): ?>
                        <img src="<?= $user['avatar'] ?>" alt="Avatar" class="avatar-img">
                    <?php else: ?>
                        <div class="avatar-img d-flex align-items-center justify-content-center bg-white">
                            <i class="fas fa-user fa-4x text-primary"></i>
                        </div>
                    <?php endif; ?>
                    <label for="avatar-upload" class="avatar-upload">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatar-upload" style="display: none;" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
                </div>
                <h2 class="mt-3"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>
                <p class="mb-0"><?= htmlspecialchars($user['email']) ?></p>
                <p class="mb-0"><small><?= $user['role'] === 'admin' ? 'Administrateur' : ($user['role'] === 'proprietaire' ? 'Propriétaire' : 'Utilisateur') ?></small></p>
            </div>
        </div>
        
        <!-- Profile Content -->
        <div class="container py-5">
            <!-- Messages -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Avatar Upload Form -->
            <form id="avatar-form" method="post" action="user_profile.php?action=upload_avatar" enctype="multipart/form-data" style="display: none;">
                <input type="file" name="avatar" accept="image/*">
            </form>
            
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <div class="profile-card">
                        <h6 class="mb-3">Statistiques</h6>
                        <div class="stats-card mb-3">
                            <div class="stats-number"><?= $stats['total_annonces'] ?></div>
                            <small>Annonces</small>
                        </div>
                        <div class="stats-card mb-3">
                            <div class="stats-number"><?= $stats['active_annonces'] ?></div>
                            <small>Actives</small>
                        </div>
                        <div class="stats-card mb-3">
                            <div class="stats-number"><?= $stats['total_favorites'] ?></div>
                            <small>Favoris</small>
                        </div>
                        <div class="stats-card">
                            <div class="stats-number"><?= $stats['total_messages'] ?></div>
                            <small>Messages</small>
                        </div>
                    </div>
                    
                    <div class="profile-card">
                        <h6 class="mb-3">Actions rapides</h6>
                        <div class="d-grid gap-2">
                            <a href="annonces_direct_fixed.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvelle annonce
                            </a>
                            <a href="manage_annonces.php" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-list me-2"></i>Gérer annonces
                            </a>
                            <a href="messaging_system.php" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-comments me-2"></i>Messagerie
                            </a>
                            <a href="user_dashboard.php" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-home me-2"></i>Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="col-md-9">
                    <div class="profile-card">
                        <!-- Navigation Tabs -->
                        <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                                    <i class="fas fa-user me-2"></i>Informations
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                                    <i class="fas fa-lock me-2"></i>Mot de passe
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab">
                                    <i class="fas fa-cog me-2"></i>Préférences
                                </button>
                            </li>
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content" id="profileTabContent">
                            <!-- Informations Tab -->
                            <div class="tab-pane fade show active" id="info" role="tabpanel">
                                <form method="post" action="user_profile.php?action=update_profile">
                                    <div class="form-section">
                                        <h5><i class="fas fa-user me-2"></i>Informations personnelles</h5>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Prénom *</label>
                                                <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nom *</label>
                                                <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Email *</label>
                                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Téléphone</label>
                                                <input type="tel" class="form-control" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Adresse</label>
                                            <input type="text" class="form-control" name="adresse" value="<?= htmlspecialchars($user['adresse']) ?>">
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Ville</label>
                                                <input type="text" class="form-control" name="ville" value="<?= htmlspecialchars($user['ville']) ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Pays</label>
                                                <select class="form-select" name="pays">
                                                    <option value="">Sélectionner...</option>
                                                    <option value="Sénégal" <?= $user['pays'] === 'Sénégal' ? 'selected' : '' ?>>Sénégal</option>
                                                    <option value="France" <?= $user['pays'] === 'France' ? 'selected' : '' ?>>France</option>
                                                    <option value="Belgique" <?= $user['pays'] === 'Belgique' ? 'selected' : '' ?>>Belgique</option>
                                                    <option value="Canada" <?= $user['pays'] === 'Canada' ? 'selected' : '' ?>>Canada</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Bio</label>
                                            <textarea class="form-control bio-textarea" name="bio" placeholder="Parlez-nous de vous..."><?= htmlspecialchars($user['bio']) ?></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Enregistrer
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Password Tab -->
                            <div class="tab-pane fade" id="password" role="tabpanel">
                                <form method="post" action="user_profile.php?action=change_password">
                                    <div class="form-section">
                                        <h5><i class="fas fa-lock me-2"></i>Changer le mot de passe</h5>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Mot de passe actuel *</label>
                                            <input type="password" class="form-control" name="current_password" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Nouveau mot de passe *</label>
                                            <input type="password" class="form-control" name="new_password" required minlength="8">
                                            <small class="text-muted">Minimum 8 caractères</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Confirmer le nouveau mot de passe *</label>
                                            <input type="password" class="form-control" name="confirm_password" required minlength="8">
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-key me-2"></i>Changer le mot de passe
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Preferences Tab -->
                            <div class="tab-pane fade" id="preferences" role="tabpanel">
                                <div class="form-section">
                                    <h5><i class="fas fa-cog me-2"></i>Préférences</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Langue</label>
                                        <select class="form-select">
                                            <option value="fr" selected>Français</option>
                                            <option value="en">English</option>
                                            <option value="es">Español</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Fuseau horaire</label>
                                        <select class="form-select">
                                            <option value="UTC+0" selected>UTC+0 (GMT)</option>
                                            <option value="UTC+1">UTC+1 (CET)</option>
                                            <option value="UTC-5">UTC-5 (EST)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="notifications" checked>
                                            <label class="form-check-label" for="notifications">
                                                Recevoir les notifications par email
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="newsletter">
                                            <label class="form-check-label" for="newsletter">
                                                S'abonner à la newsletter
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="public_profile">
                                            <label class="form-check-label" for="public_profile">
                                                Rendre mon profil public
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Enregistrer les préférences
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Avatar upload
        document.getElementById('avatar-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('avatar-form').submit();
            }
        });
    </script>
</body>
</html>
