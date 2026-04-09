<?php
// Dashboard administrateur spécialisé et distinct
session_start();

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'src/config/config.php';
require_once 'src/core/Database.php';

$db = Database::getInstance();

// Traitement des actions administratives
$action = $_GET['action'] ?? '';

if ($action === 'toggle_user' && isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $user = $db->fetch("SELECT * FROM users WHERE id = ?", [$userId]);
    
    if ($user) {
        $newStatus = $user['is_active'] ? 0 : 1;
        $db->execute("UPDATE users SET is_active = ? WHERE id = ?", [$newStatus, $userId]);
        $message = $newStatus ? "Utilisateur activé" : "Utilisateur désactivé";
        header("Location: admin_dashboard.php?message=" . urlencode($message));
        exit;
    }
}

if ($action === 'toggle_annonce' && isset($_GET['annonce_id'])) {
    $annonceId = $_GET['annonce_id'];
    $annonce = $db->fetch("SELECT * FROM annonces WHERE id = ?", [$annonceId]);
    
    if ($annonce) {
        $newStatus = $annonce['statut'] === 'active' ? 'inactive' : 'active';
        $db->execute("UPDATE annonces SET statut = ? WHERE id = ?", [$newStatus, $annonceId]);
        $message = $newStatus === 'active' ? "Annonce activée" : "Annonce désactivée";
        header("Location: admin_dashboard.php?message=" . urlencode($message));
        exit;
    }
}

if ($action === 'delete_user' && isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    
    if ($userId != $_SESSION['user_id']) {
        $db->execute("DELETE FROM favorites WHERE user_id = ?", [$userId]);
        $db->execute("DELETE FROM messages WHERE sender_id = ? OR receiver_id = ?", [$userId, $userId]);
        $db->execute("DELETE FROM annonces WHERE user_id = ?", [$userId]);
        $db->execute("DELETE FROM users WHERE id = ?", [$userId]);
        
        $message = "Utilisateur supprimé avec succès";
        header("Location: admin_dashboard.php?message=" . urlencode($message));
        exit;
    } else {
        $error = "Vous ne pouvez pas supprimer votre propre compte";
    }
}

if ($action === 'delete_annonce' && isset($_GET['annonce_id'])) {
    $annonceId = $_GET['annonce_id'];
    $db->execute("DELETE FROM favorites WHERE annonce_id = ?", [$annonceId]);
    $db->execute("DELETE FROM messages WHERE annonce_id = ?", [$annonceId]);
    $db->execute("DELETE FROM annonces WHERE id = ?", [$annonceId]);
    
    $message = "Annonce supprimée avec succès";
    header("Location: admin_dashboard.php?message=" . urlencode($message));
    exit;
}

// Statistiques administratives détaillées
try {
    // Statistiques globales
    $globalStats = [
        'total_annonces' => $db->fetch("SELECT COUNT(*) as total FROM annonces")['total'] ?? 0,
        'annonces_actives' => $db->fetch("SELECT COUNT(*) as total FROM annonces WHERE statut = 'active'")['total'] ?? 0,
        'annonces_pending' => $db->fetch("SELECT COUNT(*) as total FROM annonces WHERE statut = 'pending'")['total'] ?? 0,
        'total_users' => $db->fetch("SELECT COUNT(*) as total FROM users")['total'] ?? 0,
        'users_actives' => $db->fetch("SELECT COUNT(*) as total FROM users WHERE is_active = 1")['total'] ?? 0,
        'users_new_today' => $db->fetch("SELECT COUNT(*) as total FROM users WHERE DATE(created_at) = CURDATE()")['total'] ?? 0,
        'total_messages' => $db->fetch("SELECT COUNT(*) as total FROM messages")['total'] ?? 0,
        'total_favorites' => $db->fetch("SELECT COUNT(*) as total FROM favorites")['total'] ?? 0,
    ];
    
    // Statistiques financières
    $financialStats = [
        'total_value' => $db->fetch("SELECT SUM(prix) as total FROM annonces WHERE statut = 'active'")['total'] ?? 0,
        'avg_price' => $db->fetch("SELECT AVG(prix) as avg FROM annonces WHERE statut = 'active'")['avg'] ?? 0,
        'highest_price' => $db->fetch("SELECT MAX(prix) as max FROM annonces WHERE statut = 'active'")['max'] ?? 0,
        'lowest_price' => $db->fetch("SELECT MIN(prix) as min FROM annonces WHERE statut = 'active'")['min'] ?? 0,
    ];
    
    // Annonces par ville
    $annoncesByCity = $db->fetchAll("SELECT ville, COUNT(*) as count FROM annonces GROUP BY ville ORDER BY count DESC LIMIT 10");
    
    // Annonces par type
    $annoncesByType = $db->fetchAll("SELECT type, COUNT(*) as count FROM annonces GROUP BY type ORDER BY count DESC");
    
    // Utilisateurs récents
    $recentUsers = $db->fetchAll("SELECT * FROM users ORDER BY created_at DESC LIMIT 10");
    
    // Annonces récentes
    $recentAnnonces = $db->fetchAll("SELECT a.*, u.prenom as proprietaire_prenom, u.nom as proprietaire_nom 
                                      FROM annonces a 
                                      LEFT JOIN users u ON a.user_id = u.id 
                                      ORDER BY a.created_at DESC LIMIT 10");
    
    // Messages récents
    $recentMessages = $db->fetchAll("SELECT m.*, u.prenom as sender_prenom, u.nom as sender_nom, a.titre as annonce_titre
                                     FROM messages m 
                                     LEFT JOIN users u ON m.sender_id = u.id 
                                     LEFT JOIN annonces a ON m.annonce_id = a.id 
                                     ORDER BY m.created_at DESC LIMIT 10");
    
    // Activité du jour
    $todayActivity = [
        'new_users' => $db->fetch("SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = CURDATE()")['count'] ?? 0,
        'new_annonces' => $db->fetch("SELECT COUNT(*) as count FROM annonces WHERE DATE(created_at) = CURDATE()")['count'] ?? 0,
        'new_messages' => $db->fetch("SELECT COUNT(*) as count FROM messages WHERE DATE(created_at) = CURDATE()")['count'] ?? 0,
        'new_favorites' => $db->fetch("SELECT COUNT(*) as count FROM favorites WHERE DATE(created_at) = CURDATE()")['count'] ?? 0,
    ];
    
} catch (Exception $e) {
    $error = "Erreur de chargement des données: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerangaHomes - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container {
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 20px 0;
        }
        
        .admin-sidebar {
            background: white;
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .admin-sidebar .nav-link {
            color: #495057;
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.3s ease;
        }
        
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background: #dc3545;
            color: white;
        }
        
        .admin-content {
            padding: 20px;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
            border-left: 4px solid #dc3545;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #dc3545;
        }
        
        .admin-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .admin-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-active {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-action {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .btn-edit {
            background: #007bff;
            color: white;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-toggle {
            background: #28a745;
            color: white;
        }
        
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .progress-bar-custom {
            height: 8px;
            border-radius: 4px;
            background: #dc3545;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="mb-0"><i class="fas fa-shield-alt me-3"></i>Administration TerangaHomes</h1>
                        <p class="mb-0">Panneau de contrôle administrateur</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="me-3"><i class="fas fa-user me-2"></i><?= $_SESSION['user_name'] ?></span>
                        <a href="logout.php" class="btn btn-light btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-2 admin-sidebar p-0">
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="#overview">
                            <i class="fas fa-tachometer-alt me-2"></i>Vue d'ensemble
                        </a>
                        <a class="nav-link" href="#users">
                            <i class="fas fa-users me-2"></i>Utilisateurs
                        </a>
                        <a class="nav-link" href="#annonces">
                            <i class="fas fa-home me-2"></i>Annonces
                        </a>
                        <a class="nav-link" href="#messages">
                            <i class="fas fa-comments me-2"></i>Messages
                        </a>
                        <a class="nav-link" href="#statistics">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                        </a>
                        <a class="nav-link" href="#settings">
                            <i class="fas fa-cog me-2"></i>Paramètres
                        </a>
                        <a class="nav-link" href="accueil_booking_fixed.php">
                            <i class="fas fa-home me-2"></i>Voir le site
                        </a>
                    </nav>
                </div>
                
                <!-- Main Content -->
                <div class="col-md-10 admin-content">
                    <!-- Messages -->
                    <?php if (isset($message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Vue d'ensemble -->
                    <div id="overview">
                        <h2 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Vue d'ensemble</h2>
                        
                        <!-- Statistiques principales -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="stats-card">
                                    <div class="stats-number"><?= $globalStats['total_annonces'] ?></div>
                                    <p class="mb-0">Total annonces</p>
                                    <small class="text-muted"><?= $globalStats['annonces_actives'] ?> actives</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card">
                                    <div class="stats-number"><?= $globalStats['total_users'] ?></div>
                                    <p class="mb-0">Total utilisateurs</p>
                                    <small class="text-muted"><?= $globalStats['users_actives'] ?> actifs</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card">
                                    <div class="stats-number"><?= $globalStats['total_messages'] ?></div>
                                    <p class="mb-0">Total messages</p>
                                    <small class="text-muted">Échanges utilisateurs</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card">
                                    <div class="stats-number"><?= $globalStats['total_favorites'] ?></div>
                                    <p class="mb-0">Total favoris</p>
                                    <small class="text-muted">Sauvegardes annonces</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activité du jour -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="chart-container">
                                    <h5><i class="fas fa-calendar-day me-2"></i>Activité du jour</h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-primary"><?= $todayActivity['new_users'] ?></h4>
                                                <small>Nouveaux utilisateurs</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-success"><?= $todayActivity['new_annonces'] ?></h4>
                                                <small>Nouvelles annonces</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-info"><?= $todayActivity['new_messages'] ?></h4>
                                                <small>Nouveaux messages</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-warning"><?= $todayActivity['new_favorites'] ?></h4>
                                                <small>Nouveaux favoris</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statistiques financières -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="chart-container">
                                    <h5><i class="fas fa-dollar-sign me-2"></i>Statistiques financières</h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-success"><?= number_format($financialStats['total_value'], 0, ' ', ' ') ?> FCFA</h4>
                                                <small>Valeur totale des annonces</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-info"><?= number_format($financialStats['avg_price'], 0, ' ', ' ') ?> FCFA</h4>
                                                <small>Prix moyen</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-primary"><?= number_format($financialStats['highest_price'], 0, ' ', ' ') ?> FCFA</h4>
                                                <small>Prix le plus élevé</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-warning"><?= number_format($financialStats['lowest_price'], 0, ' ', ' ') ?> FCFA</h4>
                                                <small>Prix le plus bas</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Utilisateurs -->
                    <div id="users" class="mt-5">
                        <h2 class="mb-4"><i class="fas fa-users me-2"></i>Gestion des utilisateurs</h2>
                        
                        <div class="admin-table">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Rôle</th>
                                            <th>Statut</th>
                                            <th>Inscription</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentUsers as $user): ?>
                                            <tr>
                                                <td><?= $user['id'] ?></td>
                                                <td><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></td>
                                                <td><?= htmlspecialchars($user['email']) ?></td>
                                                <td>
                                                    <span class="admin-badge badge-<?= $user['role'] === 'admin' ? 'primary' : 'secondary' ?>">
                                                        <?= ucfirst($user['role']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="admin-badge <?= $user['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                                        <?= $user['is_active'] ? 'Actif' : 'Inactif' ?>
                                                    </span>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="admin_dashboard.php?action=toggle_user&user_id=<?= $user['id'] ?>" 
                                                           class="btn-action btn-toggle">
                                                            <i class="fas fa-power-off"></i>
                                                        </a>
                                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                            <a href="admin_dashboard.php?action=delete_user&user_id=<?= $user['id'] ?>" 
                                                               class="btn-action btn-delete"
                                                               onclick="return confirm('Supprimer cet utilisateur ?')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Annonces -->
                    <div id="annonces" class="mt-5">
                        <h2 class="mb-4"><i class="fas fa-home me-2"></i>Gestion des annonces</h2>
                        
                        <div class="admin-table">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Titre</th>
                                            <th>Propriétaire</th>
                                            <th>Prix</th>
                                            <th>Ville</th>
                                            <th>Statut</th>
                                            <th>Création</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentAnnonces as $annonce): ?>
                                            <tr>
                                                <td><?= $annonce['id'] ?></td>
                                                <td><?= htmlspecialchars($annonce['titre']) ?></td>
                                                <td><?= htmlspecialchars($annonce['proprietaire_prenom'] . ' ' . $annonce['proprietaire_nom']) ?></td>
                                                <td><?= number_format($annonce['prix'], 0, ' ', ' ') ?> FCFA</td>
                                                <td><?= htmlspecialchars($annonce['ville']) ?></td>
                                                <td>
                                                    <span class="admin-badge badge-<?= $annonce['statut'] ?>">
                                                        <?= ucfirst($annonce['statut']) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($annonce['created_at'])) ?></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="admin_dashboard.php?action=toggle_annonce&annonce_id=<?= $annonce['id'] ?>" 
                                                           class="btn-action btn-toggle">
                                                            <i class="fas fa-power-off"></i>
                                                        </a>
                                                        <a href="admin_dashboard.php?action=delete_annonce&annonce_id=<?= $annonce['id'] ?>" 
                                                           class="btn-action btn-delete"
                                                           onclick="return confirm('Supprimer cette annonce ?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Messages -->
                    <div id="messages" class="mt-5">
                        <h2 class="mb-4"><i class="fas fa-comments me-2"></i>Messages récents</h2>
                        
                        <div class="admin-table">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Expéditeur</th>
                                            <th>Annonce</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentMessages as $message): ?>
                                            <tr>
                                                <td><?= $message['id'] ?></td>
                                                <td><?= htmlspecialchars($message['sender_prenom'] . ' ' . $message['sender_nom']) ?></td>
                                                <td><?= htmlspecialchars($message['annonce_titre'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars(substr($message['message'], 0, 50)) ?>...</td>
                                                <td><?= date('d/m/Y H:i', strtotime($message['created_at'])) ?></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="#" class="btn-action btn-edit">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navigation smooth
        document.querySelectorAll('.admin-sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                        
                        // Update active state
                        document.querySelectorAll('.admin-sidebar .nav-link').forEach(l => l.classList.remove('active'));
                        this.classList.add('active');
                    }
                }
            });
        });
    </script>
</body>
</html>
