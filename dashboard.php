<?php
// Page directe du dashboard utilisateur

require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'models/Favorite.php';

// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = Database::getInstance();
$userId = $_SESSION['user_id'];

// Récupérer les statistiques de l'utilisateur
try {
    // Annonces de l'utilisateur
    $userAnnonces = $db->fetchAll(
        "SELECT COUNT(*) as total, SUM(CASE WHEN statut = 'active' THEN 1 ELSE 0 END) as active,
         SUM(CASE WHEN statut = 'sold' OR statut = 'rented' THEN 1 ELSE 0 END) as sold_rented
         FROM annonces WHERE user_id = ?",
        [$userId]
    );
    
    // Vues totales sur les annonces de l'utilisateur
    $totalViews = $db->fetch(
        "SELECT SUM(views_count) as total FROM annonces WHERE user_id = ?",
        [$userId]
    );
    
    // Favoris reçus sur les annonces de l'utilisateur
    $totalFavorites = $db->fetch(
        "SELECT COUNT(*) as total FROM favorites f 
         JOIN annonces a ON f.annonce_id = a.id 
         WHERE a.user_id = ?",
        [$userId]
    );
    
    // Messages non lus
    $unreadMessages = $db->fetch(
        "SELECT COUNT(*) as total FROM messages WHERE receiver_id = ? AND is_read = 0",
        [$userId]
    );
    
    // Dernières annonces
    $recentAnnonces = $db->fetchAll(
        "SELECT * FROM annonces WHERE user_id = ? ORDER BY created_at DESC LIMIT 5",
        [$userId]
    );
    
    // Favoris de l'utilisateur
    $favoriteModel = new Favorite();
    $userFavorites = $favoriteModel->getUserFavorites($userId, 1, 3);
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TerangaHomes</title>
    
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
            background: white !important;
            border-bottom: 3px solid #0066cc;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            color: #0066cc !important;
            font-weight: 700;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: white;
            padding: 60px 0;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-left: 4px solid #0066cc;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #0066cc;
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
        
        .annonce-item {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }
        
        .annonce-item:hover {
            background: #f8f9fa;
        }
        
        .annonce-item:last-child {
            border-bottom: none;
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
        
        .quick-action {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .quick-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            color: #0066cc;
        }
        
        .quick-action i {
            font-size: 1.5rem;
            color: #0066cc;
            margin-right: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home me-2"></i>TerangaHomes
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="annonces">Annonces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search">Recherche</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="favorites.php">
                            <i class="fas fa-heart me-1"></i>Favoris
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a></li>
                            <li><a class="dropdown-item" href="my-annonces">
                                <i class="fas fa-list me-2"></i>Mes annonces
                            </a></li>
                            <li><a class="dropdown-item" href="favorites.php">
                                <i class="fas fa-heart me-2"></i>Mes favoris
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <section class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1>Bonjour, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?> !</h1>
                    <p class="lead">Bienvenue dans votre espace personnel TerangaHomes</p>
                </div>
                <div class="col-lg-4">
                    <div class="text-center">
                        <i class="fas fa-tachometer-alt" style="font-size: 150px; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Content -->
    <section class="container py-5">
        <!-- Statistiques -->
        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <div class="stat-number"><?= $userAnnonces[0]['total'] ?? 0 ?></div>
                    <div class="stat-label">Mes annonces</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <div class="stat-number"><?= $totalViews['total'] ?? 0 ?></div>
                    <div class="stat-label">Vues totales</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <div class="stat-number"><?= $totalFavorites['total'] ?? 0 ?></div>
                    <div class="stat-label">Favoris reçus</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <div class="stat-number"><?= $unreadMessages['total'] ?? 0 ?></div>
                    <div class="stat-label">Messages non lus</div>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="row mb-5">
            <div class="col-12">
                <h4 class="mb-4">Actions rapides</h4>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="annonces/create" class="quick-action">
                            <i class="fas fa-plus"></i>
                            <div>
                                <div class="fw-bold">Déposer une annonce</div>
                                <small class="text-muted">Ajouter un nouveau bien</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="my-annonces" class="quick-action">
                            <i class="fas fa-list"></i>
                            <div>
                                <div class="fw-bold">Mes annonces</div>
                                <small class="text-muted">Gérer mes biens</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="favorites.php" class="quick-action">
                            <i class="fas fa-heart"></i>
                            <div>
                                <div class="fw-bold">Mes favoris</div>
                                <small class="text-muted">Voir mes favoris</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="quick-action">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <div class="fw-bold">Messages</div>
                                <small class="text-muted"><?= $unreadMessages['total'] ?? 0 ?> non lu(s)</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dernières annonces -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-home me-2"></i>Mes dernières annonces
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentAnnonces)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-home fa-3x text-muted mb-3"></i>
                                <h5>Vous n'avez pas encore d'annonces</h5>
                                <p class="text-muted">Commencez par déposer votre première annonce</p>
                                <a href="annonces/create" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Déposer une annonce
                                </a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentAnnonces as $annonce): ?>
                                <div class="annonce-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="mb-1"><?= htmlspecialchars($annonce['titre']) ?></h6>
                                            <p class="mb-1 text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($annonce['ville']) ?>
                                                <span class="ms-3">
                                                    <i class="fas fa-eye me-1"></i><?= $annonce['views_count'] ?> vues
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="badge badge-status <?= $annonce['statut'] === 'active' ? 'bg-success' : ($annonce['statut'] === 'sold' || $annonce['statut'] === 'rented' ? 'bg-warning' : 'bg-secondary') ?>">
                                                <?= ucfirst($annonce['statut']) ?>
                                            </span>
                                            <div class="mt-2">
                                                <a href="annonces/<?= $annonce['id'] ?>" class="btn btn-sm btn-outline-primary me-1">Voir</a>
                                                <a href="annonces/<?= $annonce['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Favoris récents -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-heart me-2"></i>Mes favoris récents
                    </div>
                    <div class="card-body">
                        <?php if (empty($userFavorites['annonces'])): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-heart fa-2x text-muted mb-2"></i>
                                <h6>Aucun favori</h6>
                                <p class="text-muted small">Explorez les annonces et ajoutez vos préférées</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($userFavorites['annonces'] as $favorite): ?>
                                <div class="annonce-item">
                                    <h6 class="mb-1"><?= htmlspecialchars($favorite['titre']) ?></h6>
                                    <p class="mb-1 text-muted small">
                                        <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($favorite['ville']) ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold">
                                            <?= number_format($favorite['prix'], 0, '.', ' ') ?> FCFA
                                        </span>
                                        <a href="annonces/<?= $favorite['id'] ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
                    <h5>TerangaHomes</h5>
                    <p>Votre plateforme de confiance pour la location, la vente et la réservation d'hébergements et véhicules au Sénégal.</p>
                </div>
                <div class="col-md-6">
                    <h5>Liens utiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-decoration-none">Accueil</a></li>
                        <li><a href="annonces" class="text-decoration-none">Annonces</a></li>
                        <li><a href="favorites.php" class="text-decoration-none">Favoris</a></li>
                        <li><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> TerangaHomes. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
