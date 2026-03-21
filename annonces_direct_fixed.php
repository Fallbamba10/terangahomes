<?php
// Page directe des annonces pour éviter les problèmes de routing

require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

try {
    // Récupérer les annonces avec pagination
    $page = $_GET['page'] ?? 1;
    $limit = 12;
    $offset = ($page - 1) * $limit;
    
    $annonces = $db->fetchAll("SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
            c.nom as categorie_nom 
            FROM annonces a
            LEFT JOIN users u ON a.user_id = u.id
            LEFT JOIN categories c ON a.categorie_id = c.id
            WHERE a.statut = 'active'
            ORDER BY a.created_at DESC
            LIMIT $limit OFFSET $offset");
    
    $total = $db->fetch("SELECT COUNT(*) as total FROM annonces WHERE statut = 'active'")['total'] ?? 0;
    $totalPages = ceil($total / $limit);
    
    // Récupérer les catégories pour les filtres
    $categories = $db->fetchAll("SELECT * FROM categories ORDER BY nom");
    
} catch (Exception $e) {
    $error = $e->getMessage();
    $annonces = [];
    $categories = [];
    $totalPages = 0;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonces - TerangaHomes</title>
    
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
        
        .hero-section {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: white;
            padding: 60px 0;
        }
        
        .search-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin: -30px auto 2rem;
            position: relative;
            z-index: 10;
        }
        
        .card {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .annonce-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }
        
        .annonce-favorite {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .annonce-favorite:hover {
            background: #ff6600;
            color: white;
            transform: scale(1.1);
        }
        
        .annonce-price {
            color: #0066cc;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .annonce-location {
            color: #666666;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background: #0066cc;
            border-color: #0066cc;
            color: white;
        }
        
        .btn-primary:hover {
            background: #004499;
            border-color: #004499;
        }
        
        .footer {
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
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            color: #666;
        }
        
        .contact-item i {
            margin-right: 0.5rem;
            color: #0066cc;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="accueil_bleu_simple.php">
                <i class="fas fa-home me-2"></i>TerangaHomes
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="accueil_bleu_simple.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="annonces_direct_fixed.php">Annonces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search_with_map.php">Recherche</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="connexion_simple.php">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-orange" href="connexion_simple.php">
                            <i class="fas fa-plus"></i> Déposer une annonce
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <h1>Toutes nos annonces</h1>
                        <p class="lead">Découvrez notre sélection complète de biens immobiliers, hôtels et véhicules</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="text-center">
                        <i class="fas fa-search" style="font-size: 150px; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="container">
        <div class="search-section">
            <h3 class="text-center mb-4">Filtrer les annonces</h3>
            <form method="GET" action="annonces_direct_fixed.php" class="row g-3">
                <div class="col-md-3">
                    <select class="form-select" name="type">
                        <option value="">Tous les types</option>
                        <option value="location" <?= ($_GET['type'] ?? '') === 'location' ? 'selected' : '' ?>>🏠 Location</option>
                        <option value="vente" <?= ($_GET['type'] ?? '') === 'vente' ? 'selected' : '' ?>>🏡 Vente</option>
                        <option value="hotel" <?= ($_GET['type'] ?? '') === 'hotel' ? 'selected' : '' ?>>🏨 Hôtel</option>
                        <option value="voiture" <?= ($_GET['type'] ?? '') === 'voiture' ? 'selected' : '' ?>>🚗 Voiture</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="categorie_id">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories ?? [] as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= ($_GET['categorie_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="prix_min" value="<?= htmlspecialchars($_GET['prix_min'] ?? '') ?>" placeholder="Prix min">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="prix_max" value="<?= htmlspecialchars($_GET['prix_max'] ?? '') ?>" placeholder="Prix max">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Results Section -->
    <section class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><?= count($annonces ?? []) ?> annonce<?= count($annonces ?? []) > 1 ? 's' : '' ?> trouvée<?= count($annonces ?? []) > 1 ? 's' : '' ?></h3>
            <div>
                <a href="search_with_map.php" class="btn btn-outline-primary me-2">
                    <i class="fas fa-map me-2"></i>Voir sur la carte
                </a>
            </div>
        </div>
        
        <?php if (empty($annonces)): ?>
            <div class="text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h4>Aucune annonce trouvée</h4>
                <p class="text-muted">Essayez de modifier vos filtres de recherche</p>
                <div class="mt-3">
                    <a href="annonces_direct_fixed.php" class="btn btn-outline-primary me-2">
                        <i class="fas fa-redo me-2"></i>Réinitialiser les filtres
                    </a>
                    <a href="connexion_simple.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Déposer une annonce
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($annonces as $annonce): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card annonce-card">
                        <div class="annonce-badge">
                            <span class="badge bg-primary"><?= ucfirst($annonce['type']) ?></span>
                            <?php if ($annonce['featured']): ?>
                                <span class="badge bg-warning ms-1">Featured</span>
                            <?php endif; ?>
                        </div>
                        <div class="annonce-favorite" onclick="toggleFavorite(this)">
                            <i class="far fa-heart"></i>
                        </div>
                        <?php 
                        $images = json_decode($annonce['images'] ?? '[]', true);
                        $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                        ?>
                        <img src="uploads/images/<?= $firstImage ?>" 
                             class="card-img-top" alt="<?= htmlspecialchars($annonce['titre']) ?>"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($annonce['titre']) ?></h5>
                            <p class="annonce-location">
                                <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($annonce['ville']) ?>
                                <?php if (!empty($annonce['quartier'])): ?>
                                    - <?= htmlspecialchars($annonce['quartier']) ?>
                                <?php endif; ?>
                            </p>
                            
                            <?php if ($annonce['categorie_nom']): ?>
                                <span class="badge bg-secondary small"><?= htmlspecialchars($annonce['categorie_nom']) ?></span>
                            <?php endif; ?>
                            
                            <div class="mt-3">
                                <?php if ($annonce['superficie']): ?>
                                    <span class="text-muted small me-3">
                                        <i class="fas fa-expand me-1"></i><?= $annonce['superficie'] ?>m²
                                    </span>
                                <?php endif; ?>
                                <?php if ($annonce['chambres'] > 0): ?>
                                    <span class="text-muted small me-3">
                                        <i class="fas fa-bed me-1"></i><?= $annonce['chambres'] ?> chambres
                                    </span>
                                <?php endif; ?>
                                <?php if ($annonce['parking']): ?>
                                    <span class="text-muted small">
                                        <i class="fas fa-car me-1"></i>Parking
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="annonce-price">
                                    <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                                    <?php if ($annonce['type'] === 'location'): ?>
                                        <small class="text-muted">/mois</small>
                                    <?php endif; ?>
                                </div>
                                <a href="annonce_detail_maps.php?id=<?= $annonce['id'] ?>" class="btn btn-primary btn-sm">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
                    <h5>Navigation</h5>
                    <ul class="list-unstyled">
                        <li><a href="accueil_bleu_simple.php"><i class="fas fa-chevron-right"></i>Accueil</a></li>
                        <li><a href="annonces_direct_fixed.php"><i class="fas fa-chevron-right"></i>Annonces</a></li>
                        <li><a href="search_with_map.php"><i class="fas fa-chevron-right"></i>Recherche</a></li>
                        <li><a href="connexion_simple.php"><i class="fas fa-chevron-right"></i>Connexion</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5>Contact</h5>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+221 33 123 45 67</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>contact@terangahomes.com</span>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p>&copy; <?= date('Y') ?> TerangaHomes. Tous droits réservés.</p>
                    </div>
                </div>
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
            element.classList.add('active');
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            element.classList.remove('active');
        }
    }
    </script>
</body>
</html>
