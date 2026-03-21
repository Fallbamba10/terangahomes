<?php
// Page d'accueil avec le thème bleu précédent et navbar simple

session_start();

// Forcer le mode visiteur - détruire toute session existante
if (isset($_SESSION['user_id'])) {
    session_destroy();
    session_start();
}

require_once 'config/config.php';
require_once 'core/Database.php';

// Récupérer quelques données pour l'affichage
$db = Database::getInstance();
try {
    $annonces = $db->fetchAll("SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom 
            FROM annonces a 
            LEFT JOIN users u ON a.user_id = u.id 
            WHERE a.statut = 'active' 
            ORDER BY a.created_at DESC LIMIT 12");
    
    $stats = [
        'total_annonces' => $db->fetch("SELECT COUNT(*) as total FROM annonces WHERE statut = 'active'")['total'] ?? 0,
        'total_users' => $db->fetch("SELECT COUNT(*) as total FROM users WHERE is_active = 1")['total'] ?? 0,
    ];
} catch (Exception $e) {
    $annonces = [];
    $stats = ['total_annonces' => 0, 'total_users' => 0];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerangaHomes - Plateforme Immobilière</title>
    
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
        
        /* Navbar simple et bleue */
        .navbar {
            background: white !important;
            border-bottom: 3px solid #0066cc;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            color: #0066cc !important;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-nav .nav-link {
            color: #333 !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease;
            font-size: 1.1rem;
        }
        
        .navbar-nav .nav-link:hover {
            color: #0066cc !important;
        }
        
        .navbar-nav .nav-link.active {
            color: #0066cc !important;
            font-weight: 600;
        }
        
        .navbar-nav .nav-link i {
            font-size: 1.2rem;
        }
        
        .btn-orange {
            background: #ff6600 !important;
            border-color: #ff6600 !important;
            color: white !important;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-orange:hover {
            background: #e55a00 !important;
            border-color: #e55a00 !important;
            color: white !important;
        }
        
        /* Hero Section bleue */
        .hero-section {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: white;
            padding: 100px 0;
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero-content .lead {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }
        
        .btn-primary {
            background: #0066cc;
            border-color: #0066cc;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #004499;
            border-color: #004499;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,102,204,0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            border-color: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108,117,125,0.3);
        }
        
        .btn-orange-hero {
            background: #ff6600 !important;
            border-color: #ff6600 !important;
            color: white !important;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-orange-hero:hover {
            background: #e55a00 !important;
            border-color: #e55a00 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,102,0,0.3);
            color: white !important;
        }
        
        /* Search Section */
        .search-section {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin: -50px auto 3rem;
            position: relative;
            z-index: 10;
        }
        
        .search-section h3 {
            color: #0066cc;
            font-weight: 600;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #0066cc;
            box-shadow: 0 0 0 0.2rem rgba(0,102,204,0.25);
        }
        
        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: white;
            padding: 80px 0;
        }
        
        .stat-card {
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* Annonces Section */
        .annonces-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #0066cc;
            margin-bottom: 1rem;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: #666;
        }
        
        .annonce-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .annonce-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .annonce-image {
            height: 220px;
            position: relative;
            overflow: hidden;
        }
        
        .annonce-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .annonce-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #0066cc;
            color: white;
            padding: 6px 15px;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .annonce-favorite {
            position: absolute;
            top: 15px;
            right: 15px;
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .annonce-favorite:hover {
            background: #ff6600;
            color: white;
            transform: scale(1.1);
        }
        
        .annonce-content {
            padding: 1.5rem;
        }
        
        .annonce-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .annonce-location {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .annonce-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0066cc;
            margin-bottom: 1rem;
        }
        
        .annonce-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }
        
        .annonce-owner {
            color: #666;
            font-size: 0.9rem;
        }
        
        .btn-detail {
            background: #0066cc;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-detail:hover {
            background: #004499;
            color: white;
            transform: translateY(-2px);
        }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            padding: 60px 0 30px;
        }
        
        .footer-brand {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #0066cc;
        }
        
        .footer-links a {
            color: #666;
            text-decoration: none;
            margin-bottom: 0.5rem;
            display: block;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #0066cc;
        }
        
        .footer-bottom {
            border-top: 1px solid #dee2e6;
            margin-top: 2rem;
            padding-top: 2rem;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Navigation avec 3 onglets au milieu + boutons à droite -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="accueil_bleu_simple.php">
                <i class="fas fa-home me-2"></i>TerangaHomes
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="accueil_bleu_simple.php">
                            <i class="fas fa-home me-1"></i>Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="annonces_direct_fixed.php">
                            <i class="fas fa-list me-1"></i>Annonces
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search_with_map.php">
                            <i class="fas fa-search me-1"></i>Recherche
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="dashboard.php">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                                <li><a class="dropdown-item" href="annonces_direct_fixed.php">
                                    <i class="fas fa-list me-2"></i>Mes annonces
                                </a></li>
                                <li><a class="dropdown-item" href="favorites.php">
                                    <i class="fas fa-heart me-2"></i>Mes favoris
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="connexion_simple.php?logout=1">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="connexion_simple.php">
                                <i class="fas fa-user-circle"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="connexion_simple.php">
                                <i class="fas fa-bell"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="connexion_simple.php">
                                <i class="fas fa-heart"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-orange" href="connexion_simple.php">
                                <i class="fas fa-plus"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section bleue -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1>Trouvez votre logement idéal au Sénégal</h1>
                        <p class="lead">Plus de 500 annonces de location, vente et réservation dans tout le pays</p>
                        <div class="d-flex gap-3">
                            <a href="annonces_direct_fixed.php" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Rechercher
                            </a>
                            <a href="search_with_map.php" class="btn btn-orange-hero btn-lg">
                                <i class="fas fa-plus me-2"></i>Déposer une annonce
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-building" style="font-size: 250px; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="container">
        <div class="search-section">
            <h3><i class="fas fa-search me-2"></i>Recherchez votre bien idéal</h3>
            <form method="GET" action="search_with_map.php" class="row g-3">
                <div class="col-md-3">
                    <select class="form-select" name="type">
                        <option value="">Tous les types</option>
                        <option value="location">🏠 Location</option>
                        <option value="vente">🏡 Vente</option>
                        <option value="hotel">🏨 Hôtel</option>
                        <option value="voiture">🚗 Voiture</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="ville" placeholder="Ville">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="prix_min" placeholder="Prix min">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="prix_max" placeholder="Prix max">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['total_annonces'] ?>+</div>
                        <div class="stat-label">Annonces actives</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['total_users'] ?>+</div>
                        <div class="stat-label">Utilisateurs</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support client</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Annonces Section -->
    <section class="annonces-section">
        <div class="container">
            <div class="section-title">
                <h2>Dernières annonces</h2>
                <p>Découvrez nos dernières opportunités immobilières</p>
            </div>
            
            <div class="row">
                <?php foreach ($annonces as $annonce): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="annonce-card">
                        <div class="annonce-image">
                            <?php 
                            $images = json_decode($annonce['images'] ?? '[]', true);
                            $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                            ?>
                            <img src="uploads/images/<?= $firstImage ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                            <div class="annonce-badge"><?= ucfirst($annonce['type']) ?></div>
                            <div class="annonce-favorite" onclick="toggleFavorite(this)">
                                <i class="far fa-heart"></i>
                            </div>
                        </div>
                        <div class="annonce-content">
                            <h5 class="annonce-title"><?= htmlspecialchars($annonce['titre']) ?></h5>
                            <p class="annonce-location">
                                <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($annonce['ville']) ?>
                            </p>
                            <div class="annonce-price">
                                <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                                <?php if ($annonce['type'] === 'location'): ?>
                                    <small>/mois</small>
                                <?php endif; ?>
                            </div>
                            <div class="annonce-footer">
                                <span class="annonce-owner">
                                    <?= htmlspecialchars($annonce['proprietaire_prenom'] . ' ' . $annonce['proprietaire_nom']) ?>
                                </span>
                                <a href="annonce_detail_maps.php?id=<?= $annonce['id'] ?>" class="btn-detail">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-5">
                <a href="annonces_direct_fixed.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-list me-2"></i>Voir toutes les annonces
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand">
                        <i class="fas fa-home me-2"></i>TerangaHomes
                    </div>
                    <p>Votre plateforme de confiance pour la location, la vente et la réservation d'hébergements et véhicules au Sénégal.</p>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">Liens rapides</h5>
                    <div class="footer-links">
                        <a href="accueil_bleu_simple.php">Accueil</a>
                        <a href="annonces_direct_fixed.php">Annonces</a>
                        <a href="search_with_map.php">Recherche</a>
                        <a href="connexion_simple.php">Connexion</a>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">Contact</h5>
                    <div class="footer-links">
                        <a href="#"><i class="fas fa-phone me-2"></i>+221 33 123 45 67</a>
                        <a href="#"><i class="fas fa-envelope me-2"></i>contact@terangahomes.com</a>
                        <a href="#"><i class="fas fa-map-marker-alt me-2"></i>Dakar, Sénégal</a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> TerangaHomes. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    function toggleFavorite(element) {
        const icon = element.querySelector('i');
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            element.style.background = '#ff6600';
            element.style.color = 'white';
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            element.style.background = 'white';
            element.style.color = '#333';
        }
    }
    </script>
</body>
</html>
