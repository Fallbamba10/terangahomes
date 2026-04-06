<?php
// Page directe du dashboard administrateur

session_start();
require_once 'config/config.php';
require_once 'core/Database.php';

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Rediriger vers la page de connexion
    header('Location: login.php');
    exit;
}

$db = Database::getInstance();

try {
    // Statistiques globales
    $stats = [
        'total_annonces' => $db->fetch("SELECT COUNT(*) as total FROM annonces")['total'] ?? 0,
        'annonces_actives' => $db->fetch("SELECT COUNT(*) as total FROM annonces WHERE statut = 'active'")['total'] ?? 0,
        'total_users' => $db->fetch("SELECT COUNT(*) as total FROM users")['total'] ?? 0,
        'users_actives' => $db->fetch("SELECT COUNT(*) as total FROM users WHERE is_active = 1")['total'] ?? 0,
        'total_messages' => $db->fetch("SELECT COUNT(*) as total FROM messages")['total'] ?? 0,
        'total_favorites' => $db->fetch("SELECT COUNT(*) as total FROM favorites")['total'] ?? 0,
    ];
    
    // Dernières activités
    $recent_annonces = $db->fetchAll("SELECT a.*, u.prenom, u.nom 
            FROM annonces a 
            LEFT JOIN users u ON a.user_id = u.id 
            ORDER BY a.created_at DESC LIMIT 5");
    
    $recent_users = $db->fetchAll("SELECT * FROM users 
            ORDER BY created_at DESC LIMIT 5");
    
    // Annonces par type
    $annonces_by_type = $db->fetchAll("SELECT type, COUNT(*) as count 
            FROM annonces 
            GROUP BY type");
    
    // Annonces par ville (top 10)
    $annoncesByCity = $db->fetchAll("SELECT ville, COUNT(*) as count FROM annonces GROUP BY ville ORDER BY count DESC LIMIT 10");
    
    // Utilisateurs récents
    $recentUsers = $db->fetchAll("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
    
    // Annonces récentes
    $recentAnnonces = $db->fetchAll("SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom 
                                      FROM annonces a 
                                      JOIN users u ON a.user_id = u.id 
                                      ORDER BY a.created_at DESC LIMIT 5");
    
    // Messages récents
    $recentMessages = $db->fetchAll("SELECT m.*, u1.nom as sender_nom, u1.prenom as sender_prenom, 
                                     u2.nom as receiver_nom, u2.prenom as receiver_prenom, a.titre as annonce_titre
                                     FROM messages m 
                                     JOIN users u1 ON m.sender_id = u1.id 
                                     JOIN users u2 ON m.receiver_id = u2.id 
                                     JOIN annonces a ON m.annonce_id = a.id 
                                     ORDER BY m.created_at DESC LIMIT 5");
    
    // Requêtes système
    $systemInfo = [
        'php_version' => PHP_VERSION,
        'mysql_version' => $db->fetch("SELECT VERSION() as version")['version'] ?? 'Unknown',
        'server_time' => date('Y-m-d H:i:s'),
        'uptime' => 'N/A' // Pourrait être implémenté avec exec('uptime')
    ];
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - TerangaHomes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
        }
        
        .navbar {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
            border-bottom: 3px solid #bd2130;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: 700;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 60px 0;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-left: 4px solid #dc3545;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #dc3545;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .card {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
        }
        
        .admin-badge {
            background: #dc3545;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .sidebar {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            height: fit-content;
        }
        
        .sidebar .nav-link {
            color: #333;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link:hover {
            background: #f8f9fa;
            color: #dc3545;
        }
        
        .sidebar .nav-link.active {
            background: #dc3545;
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }
        
        .list-group-item {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }
        
        .list-group-item:hover {
            background: #f8f9fa;
        }
        
        .badge-status {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .footer {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            padding: 40px 0 20px;
            margin-top: 60px;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home me-2"></i>TerangaHomes
                <span class="admin-badge ms-2">ADMIN</span>
            </a>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="bg-white text-danger rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item active" href="admin.php">
                            <i class="fas fa-cog me-2"></i>Administration
                        </a></li>
                        <li><a class="dropdown-item" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Mon Dashboard
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Admin Header -->
    <section class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1><i class="fas fa-cog me-3"></i>Administration TerangaHomes</h1>
                    <p class="lead">Gestion complète de la plateforme immobilière</p>
                </div>
                <div class="col-lg-4">
                    <div class="text-center">
                        <i class="fas fa-shield-alt" style="font-size: 150px; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Admin Content -->
    <section class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="sidebar">
                    <h6 class="mb-3">
                        <i class="fas fa-cog me-2"></i>Panneau de contrôle
                    </h6>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="admin.php">
                            <i class="fas fa-tachometer-alt"></i>Dashboard
                        </a>
                        <a class="nav-link" href="#users">
                            <i class="fas fa-users"></i>Utilisateurs
                        </a>
                        <a class="nav-link" href="#annonces">
                            <i class="fas fa-home"></i>Annonces
                        </a>
                        <a class="nav-link" href="#messages">
                            <i class="fas fa-envelope"></i>Messages
                        </a>
                        <a class="nav-link" href="#reports">
                            <i class="fas fa-flag"></i>Signalements
                        </a>
                        <a class="nav-link" href="#settings">
                            <i class="fas fa-cog"></i>Paramètres
                        </a>
                        <a class="nav-link" href="#logs">
                            <i class="fas fa-file-alt"></i>Logs
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Statistiques -->
                <div class="row mb-5">
                    <div class="col-md-3 mb-4">
                        <div class="stat-card">
                            <div class="stat-number"><?= $stats['total_users'] ?></div>
                            <div class="stat-label">Utilisateurs</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="stat-card">
                            <div class="stat-number"><?= $stats['total_annonces'] ?></div>
                            <div class="stat-label">Annonces totales</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="stat-card">
                            <div class="stat-number"><?= $stats['active_annonces'] ?></div>
                            <div class="stat-label">Annonces actives</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="stat-card">
                            <div class="stat-number"><?= number_format($stats['total_views'], 0, '.', ' ') ?></div>
                            <div class="stat-label">Vues totales</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-3 mb-4">
                        <div class="stat-card">
                            <div class="stat-number"><?= $stats['total_messages'] ?></div>
                            <div class="stat-label">Messages</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="stat-card">
                            <div class="stat-number"><?= $stats['total_favorites'] ?></div>
                            <div class="stat-label">Favoris</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="stat-card">
                            <div class="stat-number"><?= date('d') ?></div>
                            <div class="stat-label">Inscriptions aujourd'hui</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="stat-card">
                            <div class="stat-number">98.5%</div>
                            <div class="stat-label">Uptime</div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques et Données -->
                <div class="row mb-5">
                    <div class="col-md-6 mb-4">
                        <div class="chart-container">
                            <h6>Annonces par type</h6>
                            <canvas id="annoncesTypeChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="chart-container">
                            <h6>Top 10 villes</h6>
                            <canvas id="villesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Activités récentes -->
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-users me-2"></i>Utilisateurs récents
                            </div>
                            <div class="card-body">
                                <?php if (empty($recentUsers)): ?>
                                    <p class="text-muted">Aucun utilisateur récent</p>
                                <?php else: ?>
                                    <?php foreach ($recentUsers as $user): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong>
                                                    <br><small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                                                </div>
                                                <span class="badge badge-status bg-<?= $user['is_active'] ? 'success' : 'secondary' ?>">
                                                    <?= $user['is_active'] ? 'Actif' : 'Inactif' ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-home me-2"></i>Annonces récentes
                            </div>
                            <div class="card-body">
                                <?php if (empty($recentAnnonces)): ?>
                                    <p class="text-muted">Aucune annonce récente</p>
                                <?php else: ?>
                                    <?php foreach ($recentAnnonces as $annonce): ?>
                                        <div class="list-group-item">
                                            <div>
                                                <strong><?= htmlspecialchars($annonce['titre']) ?></strong>
                                                <br><small class="text-muted">
                                                    <?= htmlspecialchars($annonce['proprietaire_prenom'] . ' ' . $annonce['proprietaire_nom']) ?>
                                                </small>
                                            </div>
                                            <div class="mt-2">
                                                <span class="badge badge-status bg-<?= $annonce['statut'] === 'active' ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($annonce['statut']) ?>
                                                </span>
                                                <small class="text-muted ms-2"><?= date('d/m', strtotime($annonce['created_at'])) ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-envelope me-2"></i>Messages récents
                            </div>
                            <div class="card-body">
                                <?php if (empty($recentMessages)): ?>
                                    <p class="text-muted">Aucun message récent</p>
                                <?php else: ?>
                                    <?php foreach ($recentMessages as $message): ?>
                                        <div class="list-group-item">
                                            <div>
                                                <strong><?= htmlspecialchars($message['sender_prenom']) ?> → <?= htmlspecialchars($message['receiver_prenom']) ?></strong>
                                                <br><small class="text-muted"><?= htmlspecialchars(substr($message['content'], 0, 50)) ?>...</small>
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted"><?= date('H:i', strtotime($message['created_at'])) ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations système -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-server me-2"></i>Informations système
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Version PHP:</strong><br>
                                        <?= $systemInfo['php_version'] ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Version MySQL:</strong><br>
                                        <?= $systemInfo['mysql_version'] ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Heure serveur:</strong><br>
                                        <?= $systemInfo['server_time'] ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Uptime:</strong><br>
                                        <?= $systemInfo['uptime'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>TerangaHomes Admin</h5>
                    <p>Panneau d'administration de la plateforme immobilière</p>
                </div>
                <div class="col-md-6">
                    <h5>Actions rapides</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary">Backup DB</button>
                        <button class="btn btn-sm btn-outline-warning">Clear Cache</button>
                        <button class="btn btn-sm btn-outline-danger">Maintenance</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> TerangaHomes. Administration - Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
    // Graphique Annonces par type
    const annoncesTypeCtx = document.getElementById('annoncesTypeChart').getContext('2d');
    new Chart(annoncesTypeCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($annoncesByType, 'type')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($annoncesByType, 'count')); ?>,
                backgroundColor: ['#dc3545', '#ffc107', '#28a745', '#17a2b8', '#6f42c1'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Graphique Top villes
    const villesCtx = document.getElementById('villesChart').getContext('2d');
    new Chart(villesCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($annoncesByCity, 'ville')); ?>,
            datasets: [{
                label: 'Annonces',
                data: <?php echo json_encode(array_column($annoncesByCity, 'count')); ?>,
                backgroundColor: '#dc3545',
                borderColor: '#c82333',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    </script>
</body>
</html>
