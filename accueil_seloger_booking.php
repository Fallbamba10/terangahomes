<?php
// Page d'accueil inspirée de Seloger.com + Booking.com

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
    
    // Récupérer les villes pour les filtres
    $villes = $db->fetchAll("SELECT DISTINCT ville FROM annonces WHERE ville IS NOT NULL ORDER BY ville LIMIT 10");
    
} catch (Exception $e) {
    $annonces = [];
    $stats = ['total_annonces' => 0, 'total_users' => 0];
    $villes = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerangaHomes - Votre plateforme immobilière au Sénégal</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #0066cc;
            --secondary-blue: #004499;
            --accent-orange: #ff6600;
            --dark-gray: #2c3e50;
            --light-gray: #f8f9fa;
            --border-gray: #dee2e6;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: var(--dark-gray);
        }
        
        /* Header Style Seloger */
        .main-header {
            background: white;
            border-bottom: 1px solid var(--border-gray);
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-gray);
        }
        
        .logo-section {
            display: flex;
            align-items: center;
        }
        
        .logo {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-blue);
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logo i {
            margin-right: 10px;
        }
        
        .header-nav {
            display: flex;
            align-items: center;
            gap: 30px;
        }
        
        .nav-link-custom {
            color: var(--dark-gray);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s ease;
            position: relative;
        }
        
        .nav-link-custom:hover {
            color: var(--primary-blue);
        }
        
        .nav-link-custom.active {
            color: var(--primary-blue);
            font-weight: 600;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .btn-header {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-outline-header {
            background: transparent;
            color: var(--primary-blue);
            border: 1px solid var(--primary-blue);
        }
        
        .btn-outline-header:hover {
            background: var(--primary-blue);
            color: white;
        }
        
        .btn-primary-header {
            background: var(--accent-orange);
            color: white;
        }
        
        .btn-primary-header:hover {
            background: #e55a00;
            transform: translateY(-1px);
        }
        
        /* Hero Section Style Booking */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            padding: 60px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }
        
        .hero-text h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero-text p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.95;
            line-height: 1.6;
        }
        
        .hero-stats {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Search Section - Style Booking */
        .search-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin: -40px auto 60px;
            position: relative;
            z-index: 10;
        }
        
        .search-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .search-tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--light-gray);
        }
        
        .search-tab {
            padding: 12px 20px;
            background: transparent;
            border: none;
            color: var(--dark-gray);
            font-weight: 500;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .search-tab.active {
            color: var(--primary-blue);
            font-weight: 600;
        }
        
        .search-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-blue);
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 15px;
            align-items: end;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--dark-gray);
        }
        
        .form-control-custom {
            padding: 12px 15px;
            border: 1px solid var(--border-gray);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0,102,204,0.1);
        }
        
        .btn-search {
            padding: 12px 30px;
            background: var(--accent-orange);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-search:hover {
            background: #e55a00;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,102,0,0.3);
        }
        
        /* Categories Section */
        .categories-section {
            padding: 80px 0;
            background: var(--light-gray);
        }
        
        .section-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 15px;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .category-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: var(--dark-gray);
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            color: var(--primary-blue);
        }
        
        .category-icon {
            font-size: 3rem;
            color: var(--primary-blue);
            margin-bottom: 20px;
        }
        
        .category-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .category-count {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Properties Section */
        .properties-section {
            padding: 80px 0;
        }
        
        .properties-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
        }
        
        .properties-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-gray);
        }
        
        .view-all-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: gap 0.3s ease;
        }
        
        .view-all-link:hover {
            gap: 10px;
        }
        
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .property-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .property-image {
            height: 220px;
            position: relative;
            overflow: hidden;
        }
        
        .property-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .property-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--primary-blue);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .property-favorite {
            position: absolute;
            top: 15px;
            right: 15px;
            background: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .property-favorite:hover {
            background: var(--accent-orange);
            color: white;
            transform: scale(1.1);
        }
        
        .property-content {
            padding: 20px;
        }
        
        .property-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .property-location {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .property-features {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.85rem;
            color: #666;
        }
        
        .property-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 15px;
        }
        
        .property-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid var(--border-gray);
        }
        
        .property-owner {
            color: #666;
            font-size: 0.85rem;
        }
        
        .btn-property {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-property:hover {
            background: var(--secondary-blue);
            color: white;
        }
        
        /* Footer */
        .footer {
            background: var(--dark-gray);
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-brand {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: white;
        }
        
        .footer-description {
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .footer-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: white;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 30px;
            text-align: center;
            opacity: 0.8;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .hero-text h1 {
                font-size: 2rem;
            }
            
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .properties-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .header-nav {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header Style Seloger -->
    <header class="main-header">
        <div class="header-container">
            <div class="header-top">
                <div class="logo-section">
                    <a href="accueil_seloger_booking.php" class="logo">
                        <i class="fas fa-home"></i>
                        TerangaHomes
                    </a>
                </div>
                
                <nav class="header-nav">
                    <a href="accueil_seloger_booking.php" class="nav-link-custom active">Accueil</a>
                    <a href="annonces_direct_fixed.php" class="nav-link-custom">Annonces</a>
                    <a href="search_with_map.php" class="nav-link-custom">Recherche</a>
                    <a href="#" class="nav-link-custom">Services</a>
                    <a href="#" class="nav-link-custom">Guide</a>
                </nav>
                
                <div class="header-actions">
                    <a href="connexion_simple.php" class="btn-header btn-outline-header">
                        <i class="fas fa-user me-2"></i>Connexion
                    </a>
                    <a href="connexion_simple.php" class="btn-header btn-primary-header">
                        <i class="fas fa-plus me-2"></i>Déposer une annonce
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section Style Booking -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Trouvez votre logement idéal au Sénégal</h1>
                    <p>Plus de <?= $stats['total_annonces'] ?> annonces de location, vente et réservation dans tout le pays</p>
                    
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?= $stats['total_annonces'] ?>+</span>
                            <span class="stat-label">Annonces</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?= $stats['total_users'] ?>+</span>
                            <span class="stat-label">Utilisateurs</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Support</span>
                        </div>
                    </div>
                </div>
                
                <div class="hero-image">
                    <i class="fas fa-building" style="font-size: 300px; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section Style Booking -->
    <section class="search-section">
        <div class="search-container">
            <div class="search-tabs">
                <button class="search-tab active" data-type="all">Tous types</button>
                <button class="search-tab" data-type="location">🏠 Location</button>
                <button class="search-tab" data-type="vente">🏡 Vente</button>
                <button class="search-tab" data-type="hotel">🏨 Hôtel</button>
                <button class="search-tab" data-type="voiture">🚗 Voiture</button>
            </div>
            
            <form class="search-form" method="GET" action="search_with_map.php">
                <div class="form-group">
                    <label class="form-label">Localisation</label>
                    <input type="text" class="form-control-custom" name="ville" placeholder="Ville, quartier, région...">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Type de bien</label>
                    <select class="form-control-custom" name="type">
                        <option value="">Tous types</option>
                        <option value="location">🏠 Location</option>
                        <option value="vente">🏡 Vente</option>
                        <option value="hotel">🏨 Hôtel</option>
                        <option value="voiture">🚗 Voiture</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Prix min</label>
                    <input type="number" class="form-control-custom" name="prix_min" placeholder="FCFA">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Prix max</label>
                    <input type="number" class="form-control-custom" name="prix_max" placeholder="FCFA">
                </div>
                
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i>
                    Rechercher
                </button>
            </form>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">Explorez nos catégories</h2>
                <p class="section-subtitle">Trouvez exactement ce que vous cherchez parmi nos différentes catégories de biens</p>
            </div>
            
            <div class="categories-grid">
                <a href="search_with_map.php?type=location" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="category-title">Locations</h3>
                    <p class="category-count">Appartements, maisons, studios</p>
                </a>
                
                <a href="search_with_map.php?type=vente" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h3 class="category-title">Ventes</h3>
                    <p class="category-count">Biens à vendre</p>
                </a>
                
                <a href="search_with_map.php?type=hotel" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-hotel"></i>
                    </div>
                    <h3 class="category-title">Hôtels</h3>
                    <p class="category-count">Réservations hôtelières</p>
                </a>
                
                <a href="search_with_map.php?type=voiture" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3 class="category-title">Voitures</h3>
                    <p class="category-count">Location de véhicules</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Properties Section -->
    <section class="properties-section">
        <div class="section-container">
            <div class="properties-header">
                <h2 class="properties-title">Dernières annonces</h2>
                <a href="annonces_direct_fixed.php" class="view-all-link">
                    Voir toutes les annonces
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="properties-grid">
                <?php foreach ($annonces as $annonce): ?>
                <div class="property-card" onclick="window.location.href='annonce_detail_maps.php?id=<?= $annonce['id'] ?>'">
                    <div class="property-image">
                        <?php 
                        $images = json_decode($annonce['images'] ?? '[]', true);
                        $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                        ?>
                        <img src="uploads/images/<?= $firstImage ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                        <div class="property-badge"><?= ucfirst($annonce['type']) ?></div>
                        <div class="property-favorite" onclick="event.stopPropagation(); toggleFavorite(this)">
                            <i class="far fa-heart"></i>
                        </div>
                    </div>
                    <div class="property-content">
                        <h3 class="property-title"><?= htmlspecialchars($annonce['titre']) ?></h3>
                        <div class="property-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?= htmlspecialchars($annonce['ville']) ?>
                        </div>
                        
                        <div class="property-features">
                            <?php if ($annonce['superficie']): ?>
                                <span><i class="fas fa-expand me-1"></i><?= $annonce['superficie'] ?>m²</span>
                            <?php endif; ?>
                            <?php if ($annonce['chambres'] > 0): ?>
                                <span><i class="fas fa-bed me-1"></i><?= $annonce['chambres'] ?> chambres</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="property-price">
                            <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                            <?php if ($annonce['type'] === 'location'): ?>
                                <small style="font-weight: 400; color: #666;">/mois</small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="property-footer">
                            <span class="property-owner">
                                <?= htmlspecialchars($annonce['proprietaire_prenom'] . ' ' . $annonce['proprietaire_nom']) ?>
                            </span>
                            <a href="annonce_detail_maps.php?id=<?= $annonce['id'] ?>" class="btn-property" onclick="event.stopPropagation()">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-brand">
                        <i class="fas fa-home me-2"></i>TerangaHomes
                    </div>
                    <p class="footer-description">
                        Votre plateforme de confiance pour la location, la vente et la réservation d'hébergements et véhicules au Sénégal.
                    </p>
                    <div class="footer-social">
                        <a href="#" style="color: white; margin-right: 15px; font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
                        <a href="#" style="color: white; margin-right: 15px; font-size: 1.2rem;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: white; margin-right: 15px; font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-title">Navigation</h4>
                    <ul class="footer-links">
                        <li><a href="accueil_seloger_booking.php">Accueil</a></li>
                        <li><a href="annonces_direct_fixed.php">Annonces</a></li>
                        <li><a href="search_with_map.php">Recherche</a></li>
                        <li><a href="connexion_simple.php">Connexion</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-title">Services</h4>
                    <ul class="footer-links">
                        <li><a href="#">Location</a></li>
                        <li><a href="#">Vente</a></li>
                        <li><a href="#">Réservation</a></li>
                        <li><a href="#">Services Premium</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-title">Contact</h4>
                    <ul class="footer-links">
                        <li><i class="fas fa-phone me-2"></i>+221 33 123 45 67</li>
                        <li><i class="fas fa-envelope me-2"></i>contact@terangahomes.com</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Dakar, Sénégal</li>
                    </ul>
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
    // Search tabs functionality
    document.querySelectorAll('.search-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.search-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Update form type field
            const type = this.dataset.type;
            const typeSelect = document.querySelector('select[name="type"]');
            if (typeSelect) {
                typeSelect.value = type === 'all' ? '' : type;
            }
        });
    });
    
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
