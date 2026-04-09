<?php
// Dashboard utilisateur simplifié et focalisé

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

// Empêcher l'accès aux administrateurs
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: admin_dashboard.php');
    exit;
}

require_once 'src/config/config.php';
require_once 'src/core/Database.php';

$db = Database::getInstance();
$userId = $_SESSION['user_id'];

// Récupérer les statistiques de l'utilisateur
try {
    $userStats = [
        'total_annonces' => $db->fetch("SELECT COUNT(*) as total FROM annonces WHERE user_id = ?", [$userId])['total'] ?? 0,
        'annonces_actives' => $db->fetch("SELECT COUNT(*) as total FROM annonces WHERE user_id = ? AND statut = 'active'", [$userId])['total'] ?? 0,
        'annonces_pending' => $db->fetch("SELECT COUNT(*) as total FROM annonces WHERE user_id = ? AND statut = 'pending'", [$userId])['total'] ?? 0,
        'total_favorites' => $db->fetch("SELECT COUNT(*) as total FROM favorites WHERE user_id = ?", [$userId])['total'] ?? 0,
        'unread_messages' => $db->fetch("SELECT COUNT(*) as total FROM messages WHERE receiver_id = ? AND is_read = 0", [$userId])['total'] ?? 0,
    ];
    
    // Annonces récentes de l'utilisateur
    $recentAnnonces = $db->fetchAll("SELECT a.*, 
        (SELECT COUNT(*) FROM favorites f WHERE f.annonce_id = a.id) as favorite_count,
        (SELECT COUNT(*) FROM messages m WHERE m.annonce_id = a.id) as message_count
        FROM annonces a 
        WHERE a.user_id = ? 
        ORDER BY a.created_at DESC LIMIT 5", [$userId]);
    
    // Messages récents
    $recentMessages = $db->fetchAll("SELECT m.*, u.prenom as sender_prenom, u.nom as sender_nom, a.titre as annonce_titre
        FROM messages m 
        LEFT JOIN users u ON m.sender_id = u.id 
        LEFT JOIN annonces a ON m.annonce_id = a.id 
        WHERE m.receiver_id = ? OR m.sender_id = ?
        ORDER BY m.created_at DESC LIMIT 5", [$userId, $userId]);
    
    // Favoris récents
    $recentFavorites = $db->fetchAll("SELECT f.*, a.titre, a.prix, a.ville, a.images
        FROM favorites f 
        LEFT JOIN annonces a ON f.annonce_id = a.id 
        WHERE f.user_id = ? 
        ORDER BY f.created_at DESC LIMIT 5", [$userId]);
    
} catch (Exception $e) {
    $error = "Erreur de chargement des données: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerangaHomes - Mon Espace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .user-dashboard {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        
        .user-header {
            background: linear-gradient(135deg, #003580 0%, #001840 100%);
            color: white;
            padding: 40px 0;
        }
        
        .welcome-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-top: 4px solid #003580;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #003580;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .quick-actions {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            margin-bottom: 10px;
        }
        
        .action-btn:hover {
            background: #003580;
            color: white;
            transform: translateX(5px);
            border-color: #003580;
        }
        
        .action-btn i {
            font-size: 1.2rem;
            margin-right: 15px;
            width: 30px;
            text-align: center;
        }
        
        .recent-activity {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #f1f3f4;
            display: flex;
            align-items: center;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.1rem;
        }
        
        .icon-annonce {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .icon-message {
            background: #f3e5f5;
            color: #7b1fa2;
        }
        
        .icon-favorite {
            background: #ffebee;
            color: #c62828;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .activity-meta {
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #003580;
            margin: 0 auto 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .progress-ring {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
        }
        
        .progress-ring circle {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
    </style>
</head>
<body>
    <div class="user-dashboard">
        <!-- Header -->
        <div class="user-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h1 class="mb-1">Bienvenue, <?= htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]) ?> !</h1>
                                <p class="mb-0 opacity-75">Gérez vos annonces et votre profil immobilier</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="user_profile.php" class="btn btn-outline-light">
                                <i class="fas fa-user me-2"></i>Mon Profil
                            </a>
                            <a href="logout.php" class="btn btn-light">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="container py-5">
            <!-- Messages -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $userStats['total_annonces'] ?></div>
                    <div class="stat-label">Mes Annonces</div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: <?= min(100, ($userStats['total_annonces'] / 10) * 100) ?>%"></div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?= $userStats['annonces_actives'] ?></div>
                    <div class="stat-label">Annonces Actives</div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: <?= $userStats['total_annonces'] > 0 ? ($userStats['annonces_actives'] / $userStats['total_annonces']) * 100 : 0 ?>%"></div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?= $userStats['total_favorites'] ?></div>
                    <div class="stat-label">Favoris</div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: <?= min(100, ($userStats['total_favorites'] / 20) * 100) ?>%"></div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?= $userStats['unread_messages'] ?></div>
                    <div class="stat-label">Messages Non Lus</div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: <?= min(100, ($userStats['unread_messages'] / 10) * 100) ?>%"></div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Actions Rapides -->
                <div class="col-md-4">
                    <div class="quick-actions">
                        <h5 class="mb-4"><i class="fas fa-bolt me-2"></i>Actions Rapides</h5>
                        
                        <a href="annonces_direct_fixed.php" class="action-btn">
                            <i class="fas fa-plus-circle text-primary"></i>
                            <div>
                                <div class="fw-bold">Déposer une annonce</div>
                                <small class="text-muted">Ajouter un nouveau bien</small>
                            </div>
                        </a>
                        
                        <a href="manage_annonces.php" class="action-btn">
                            <i class="fas fa-list text-success"></i>
                            <div>
                                <div class="fw-bold">Gérer mes annonces</div>
                                <small class="text-muted">Modifier ou supprimer</small>
                            </div>
                        </a>
                        
                        <a href="messaging_system.php" class="action-btn">
                            <i class="fas fa-comments text-info"></i>
                            <div>
                                <div class="fw-bold">Messagerie</div>
                                <small class="text-muted"><?= $userStats['unread_messages'] ?> non lus</small>
                            </div>
                        </a>
                        
                        <a href="user_profile.php" class="action-btn">
                            <i class="fas fa-user-edit text-warning"></i>
                            <div>
                                <div class="fw-bold">Mon profil</div>
                                <small class="text-muted">Informations personnelles</small>
                            </div>
                        </a>
                        
                        <a href="favorites.php" class="action-btn">
                            <i class="fas fa-heart text-danger"></i>
                            <div>
                                <div class="fw-bold">Mes favoris</div>
                                <small class="text-muted"><?= $userStats['total_favorites'] ?> sauvegardés</small>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Activité Récente -->
                <div class="col-md-8">
                    <div class="recent-activity">
                        <h5 class="mb-4"><i class="fas fa-clock me-2"></i>Activité Récente</h5>
                        
                        <?php if (empty($recentAnnonces) && empty($recentMessages) && empty($recentFavorites)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-home fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Aucune activité récente</h6>
                                <p class="text-muted">Commencez par déposer votre première annonce</p>
                                <a href="annonces_direct_fixed.php" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Déposer une annonce
                                </a>
                            </div>
                        <?php else: ?>
                            <!-- Annonces récentes -->
                            <?php foreach ($recentAnnonces as $annonce): ?>
                                <div class="activity-item">
                                    <div class="activity-icon icon-annonce">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Nouvelle annonce : <?= htmlspecialchars($annonce['titre']) ?></div>
                                        <div class="activity-meta">
                                            <?= htmlspecialchars($annonce['ville']) ?> • 
                                            <?= number_format($annonce['prix'], 0, ' ', ' ') ?> FCFA • 
                                            <?= date('d/m/Y', strtotime($annonce['created_at'])) ?>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">
                                            <i class="fas fa-heart me-1"></i><?= $annonce['favorite_count'] ?>
                                            <i class="fas fa-envelope ms-2 me-1"></i><?= $annonce['message_count'] ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <!-- Messages récents -->
                            <?php foreach ($recentMessages as $message): ?>
                                <div class="activity-item">
                                    <div class="activity-icon icon-message">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Message de <?= htmlspecialchars($message['sender_prenom'] . ' ' . $message['sender_nom']) ?></div>
                                        <div class="activity-meta">
                                            <?= htmlspecialchars(substr($message['message'], 0, 80)) ?>...
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted"><?= date('H:i', strtotime($message['created_at'])) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <!-- Favoris récents -->
                            <?php foreach ($recentFavorites as $favorite): ?>
                                <div class="activity-item">
                                    <div class="activity-icon icon-favorite">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Ajouté aux favoris : <?= htmlspecialchars($favorite['titre']) ?></div>
                                        <div class="activity-meta">
                                            <?= htmlspecialchars($favorite['ville']) ?> • 
                                            <?= number_format($favorite['prix'], 0, ' ', ' ') ?> FCFA
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted"><?= date('d/m/Y', strtotime($favorite['created_at'])) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation des statistiques au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const statNumbers = document.querySelectorAll('.stat-number');
            
            statNumbers.forEach(stat => {
                const finalValue = parseInt(stat.textContent);
                let currentValue = 0;
                const increment = finalValue / 30;
                
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        currentValue = finalValue;
                        clearInterval(timer);
                    }
                    stat.textContent = Math.floor(currentValue);
                }, 50);
            });
        });
    </script>
</body>
</html>
