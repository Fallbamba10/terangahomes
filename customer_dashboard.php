<?php
// Dashboard client (pour les locataires et acheteurs)
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

require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();
$userId = $_SESSION['user_id'];

// Statistiques du client
$userStats = [
    'total_favorites' => 0,
    'total_bookings' => 0,
    'unread_messages' => 0,
    'recent_searches' => 0
];

// Récupérer les favoris du client
$favoriteCount = $db->fetch("SELECT COUNT(*) as count FROM favorites WHERE user_id = ?", [$userId]);
$userStats['total_favorites'] = $favoriteCount['count'] ?? 0;

// Récupérer les réservations de voitures
$bookingCount = $db->fetch("SELECT COUNT(*) as count FROM car_bookings WHERE user_id = ?", [$userId]);
$userStats['total_bookings'] = $bookingCount['count'] ?? 0;

// Récupérer les messages non lus
$messageCount = $db->fetch("SELECT COUNT(*) as count FROM messages WHERE receiver_id = ? AND is_read = 0", [$userId]);
$userStats['unread_messages'] = $messageCount['count'] ?? 0;

// Récupérer les activités récentes
$recentFavorites = $db->fetchAll("SELECT f.*, a.titre, a.prix, a.ville, a.images
    FROM favorites f 
    LEFT JOIN annonces a ON f.annonce_id = a.id 
    WHERE f.user_id = ? 
    ORDER BY f.created_at DESC LIMIT 3", [$userId]);

$recentBookings = $db->fetchAll("SELECT cb.*, c.brand, c.model, c.daily_price
    FROM car_bookings cb 
    LEFT JOIN cars c ON cb.car_id = c.id 
    WHERE cb.user_id = ? 
    ORDER BY cb.created_at DESC LIMIT 3", [$userId]);

$recentMessages = $db->fetchAll("SELECT m.*, u.prenom, u.nom 
    FROM messages m 
    LEFT JOIN users u ON m.sender_id = u.id 
    WHERE m.receiver_id = ? 
    ORDER BY m.created_at DESC LIMIT 3", [$userId]);

// Message de succès
$success = '';
if (isset($_GET['success'])) {
    $success = urldecode($_GET['success']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client - TerangaHomes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .dashboard-header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .welcome-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
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
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #666;
            font-weight: 500;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .action-btn {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-decoration: none;
            color: #333;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .action-btn:hover {
            transform: translateY(-5px);
            color: #667eea;
            text-decoration: none;
        }
        
        .action-icon {
            font-size: 2rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.1);
        }
        
        .recent-activity {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
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
            font-size: 1.2rem;
        }
        
        .icon-favorite { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
        .icon-booking { background: rgba(40, 167, 69, 0.1); color: #28a745; }
        .icon-message { background: rgba(23, 162, 184, 0.1); color: #17a2b8; }
        
        .quick-actions {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .quick-btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            margin: 5px;
            transition: all 0.3s;
        }
        
        .quick-btn-primary {
            background: #667eea;
            color: white;
        }
        
        .quick-btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .quick-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-home me-2"></i>TerangaHomes
                    </h1>
                    <p class="text-muted mb-0">Votre plateforme immobilière et de location de voiture</p>
                </div>
                <div class="text-end">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong><br>
                            <small class="text-muted">Client</small>
                        </div>
                        <div>
                            <a href="user_profile.php" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-user"></i>
                            </a>
                            <a href="logout.php" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i><?= $success ?>
            </div>
        <?php endif; ?>
        
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-3">Bonjour <?= htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]) ?> !</h2>
                    <p class="mb-4">Bienvenue sur votre dashboard client. Trouvez votre logement idéal ou réservez une voiture en quelques clics.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="accueil_booking_fixed.php" class="quick-btn quick-btn-primary">
                            <i class="fas fa-search me-2"></i>Rechercher un logement
                        </a>
                        <a href="car_rental.php" class="quick-btn quick-btn-primary">
                            <i class="fas fa-car me-2"></i>Louer une voiture
                        </a>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <i class="fas fa-user-circle" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $userStats['total_favorites'] ?></div>
                <div class="stat-label">Favoris</div>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-danger" style="width: <?= min(100, ($userStats['total_favorites'] / 10) * 100) ?>%"></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?= $userStats['total_bookings'] ?></div>
                <div class="stat-label">Réservations</div>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: <?= min(100, ($userStats['total_bookings'] / 5) * 100) ?>%"></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?= $userStats['unread_messages'] ?></div>
                <div class="stat-label">Messages non lus</div>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-info" style="width: <?= min(100, ($userStats['unread_messages'] / 3) * 100) ?>%"></div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="accueil_booking_fixed.php" class="action-btn">
                <div class="action-icon text-primary">
                    <i class="fas fa-home"></i>
                </div>
                <div>
                    <div class="fw-bold">Rechercher un logement</div>
                    <small class="text-muted">Parcourir les annonces immobilières</small>
                </div>
            </a>
            
            <a href="car_rental.php" class="action-btn">
                <div class="action-icon text-success">
                    <i class="fas fa-car"></i>
                </div>
                <div>
                    <div class="fw-bold">Louer une voiture</div>
                    <small class="text-muted">Réserver un véhicule</small>
                </div>
            </a>
            
            <a href="favorites.php" class="action-btn">
                <div class="action-icon text-danger">
                    <i class="fas fa-heart"></i>
                </div>
                <div>
                    <div class="fw-bold">Mes favoris</div>
                    <small class="text-muted"><?= $userStats['total_favorites'] ?> sauvegardés</small>
                </div>
            </a>
            
            <a href="messaging_system.php" class="action-btn">
                <div class="action-icon text-info">
                    <i class="fas fa-comments"></i>
                </div>
                <div>
                    <div class="fw-bold">Messagerie</div>
                    <small class="text-muted"><?= $userStats['unread_messages'] ?> non lus</small>
                </div>
            </a>
        </div>
        
        <!-- Recent Activity -->
        <div class="row">
            <div class="col-md-12">
                <div class="recent-activity">
                    <h5 class="mb-4"><i class="fas fa-clock me-2"></i>Activité Récente</h5>
                    
                    <?php if (empty($recentFavorites) && empty($recentBookings) && empty($recentMessages)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Commencez votre recherche</h6>
                            <p class="text-muted">Explorez nos annonces immobilières ou nos voitures disponibles</p>
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="accueil_booking_fixed.php" class="btn btn-primary">
                                    <i class="fas fa-home me-2"></i>Voir les logements
                                </a>
                                <a href="car_rental.php" class="btn btn-success">
                                    <i class="fas fa-car me-2"></i>Voir les voitures
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Favoris récents -->
                        <?php foreach ($recentFavorites as $favorite): ?>
                            <div class="activity-item">
                                <div class="d-flex align-items-center">
                                    <div class="activity-icon icon-favorite">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="activity-title">Ajouté aux favoris : <?= htmlspecialchars($favorite['titre']) ?></div>
                                        <div class="activity-meta">
                                            <?= htmlspecialchars($favorite['ville']) ?> - 
                                            <?= number_format($favorite['prix'], 0, ' ', ' ') ?> FCFA
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Réservations récentes -->
                        <?php foreach ($recentBookings as $booking): ?>
                            <div class="activity-item">
                                <div class="d-flex align-items-center">
                                    <div class="activity-icon icon-booking">
                                        <i class="fas fa-car"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="activity-title">Réservation : <?= htmlspecialchars($booking['brand'] . ' ' . $booking['model']) ?></div>
                                        <div class="activity-meta">
                                            Du <?= $booking['pickup_date'] ?> au <?= $booking['dropoff_date'] ?> - 
                                            <?= number_format($booking['total_price'], 0, ' ', ' ') ?> FCFA
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Messages récents -->
                        <?php foreach ($recentMessages as $message): ?>
                            <div class="activity-item">
                                <div class="d-flex align-items-center">
                                    <div class="activity-icon icon-message">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="activity-title">Message de <?= htmlspecialchars($message['prenom'] . ' ' . $message['nom']) ?></div>
                                        <div class="activity-meta">
                                            <?= htmlspecialchars(substr($message['message'], 0, 50)) ?>...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
