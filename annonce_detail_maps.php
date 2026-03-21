<?php
// Page de détail d'annonce avec Google Maps intégré

require_once 'config/config.php';
require_once 'core/Database.php';

$annonceId = $_GET['id'] ?? null;

if (!$annonceId) {
    header('Location: annonces_direct.php');
    exit;
}

$db = Database::getInstance();

try {
    $annonce = $db->fetch("SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
            u.email as proprietaire_email, u.telephone as proprietaire_telephone,
            c.nom as categorie_nom 
            FROM annonces a 
            LEFT JOIN users u ON a.user_id = u.id 
            LEFT JOIN categories c ON a.categorie_id = c.id 
            WHERE a.id = ? AND a.statut = 'active'", [$annonceId]);
    
    if (!$annonce) {
        header('Location: annonces_direct.php');
        exit;
    }
    
    // Incrémenter les vues
    $db->query("UPDATE annonces SET views_count = views_count + 1 WHERE id = ?", [$annonceId]);
    
    // Récupérer les annonces similaires
    $similar = $db->fetchAll("SELECT * FROM annonces 
            WHERE type = ? AND ville = ? AND id != ? AND statut = 'active' 
            ORDER BY created_at DESC LIMIT 4", 
            [$annonce['type'], $annonce['ville'], $annonceId]);
    
} catch (Exception $e) {
    $error = $e->getMessage();
    $annonce = null;
    $similar = [];
}

// Coordonnées par défaut pour Dakar (si aucune coordonnée dans l'annonce)
$defaultLat = $annonce['latitude'] ?? 14.6928;
$defaultLng = $annonce['longitude'] ?? -17.4467;
$adresse = $annonce['adresse'] ?? $annonce['ville'] . ', Sénégal';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($annonce['titre']) ?> - TerangaHomes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Google Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
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
        
        .breadcrumb {
            background: transparent;
            padding: 1rem 0;
        }
        
        .annonce-gallery {
            position: relative;
        }
        
        .main-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 12px;
        }
        
        .thumbnail-gallery {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            overflow-x: auto;
        }
        
        .thumbnail-item {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .thumbnail-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid transparent;
        }
        
        .thumbnail-item.active img {
            border-color: #0066cc;
        }
        
        .price-section {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
        }
        
        .price {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .characteristics {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .characteristic-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .characteristic-item i {
            color: #0066cc;
            margin-right: 1rem;
            font-size: 1.2rem;
        }
        
        .contact-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
        
        .btn-orange {
            background: #ff6600;
            border-color: #ff6600;
            color: white;
        }
        
        .btn-orange:hover {
            background: #e55a00;
            border-color: #e55a00;
        }
        
        .similar-annonces {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Map Styles */
        #map {
            height: 400px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        
        .map-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 2rem;
        }
        
        .map-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .leaflet-container {
            font-family: 'Inter', sans-serif;
        }
        
        .custom-popup {
            font-family: 'Inter', sans-serif;
        }
        
        .footer {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            padding: 60px 0 30px;
            margin-top: 60px;
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
                        <a class="nav-link" href="annonces_direct.php">Annonces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search_direct.php">Recherche</a>
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

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="accueil_bleu_simple.php">Accueil</a></li>
            <li class="breadcrumb-item"><a href="annonces_direct.php">Annonces</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($annonce['titre']) ?></li>
        </ol>
    </nav>

    <!-- Annonce Detail Section -->
    <section class="container py-4">
        <div class="row">
            <!-- Images Gallery -->
            <div class="col-lg-8">
                <div class="annonce-gallery">
                    <?php 
                    $images = json_decode($annonce['images'] ?? '[]', true);
                    if (!empty($images)):
                    ?>
                    <div id="mainImage" class="main-image mb-3">
                        <img src="uploads/images/<?= $images[0] ?>" 
                             class="img-fluid rounded" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                    </div>
                    <div class="thumbnail-gallery d-flex gap-2 overflow-auto">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="thumbnail-item <?= $index === 0 ? 'active' : '' ?>" 
                                 onclick="changeMainImage('<?= $image ?>', this)">
                                <img src="uploads/images/<?= $image ?>" 
                                     class="img-thumbnail" alt="Miniature <?= $index + 1 ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                        <div class="main-image mb-3">
                            <img src="assets/images/default-property.jpg" 
                                 class="img-fluid rounded" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Description -->
                <div class="description-section mt-4">
                    <h3>Description</h3>
                    <div class="description-content">
                        <?= nl2br(htmlspecialchars($annonce['description'])) ?>
                    </div>
                </div>
                
                <!-- Map Section -->
                <div class="map-section">
                    <h4><i class="fas fa-map-marked-alt me-2"></i>Localisation</h4>
                    <div class="map-info">
                        <strong>Adresse :</strong> <?= htmlspecialchars($adresse) ?><br>
                        <strong>Ville :</strong> <?= htmlspecialchars($annonce['ville']) ?>
                        <?php if (!empty($annonce['quartier'])): ?>
                            <br><strong>Quartier :</strong> <?= htmlspecialchars($annonce['quartier']) ?>
                        <?php endif; ?>
                    </div>
                    <div id="map"></div>
                    <div class="mt-3">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $defaultLat ?>,<?= $defaultLng ?>" 
                           target="_blank" class="btn btn-primary">
                            <i class="fas fa-directions me-2"></i>Itinéraire
                        </a>
                        <button onclick="shareLocation()" class="btn btn-outline-primary ms-2">
                            <i class="fas fa-share-alt me-2"></i>Partager la localisation
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Price Section -->
                <div class="price-section mb-4">
                    <div class="price">
                        <?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA
                        <?php if ($annonce['type'] === 'location'): ?>
                            <small class="d-block">/mois</small>
                        <?php endif; ?>
                    </div>
                    <div class="text-white">
                        <span class="badge bg-light text-dark"><?= ucfirst($annonce['type']) ?></span>
                        <?php if ($annonce['categorie_nom']): ?>
                            <span class="badge bg-light text-dark ms-2"><?= htmlspecialchars($annonce['categorie_nom']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Characteristics -->
                <div class="characteristics mb-4">
                    <h4 class="mb-3">Caractéristiques</h4>
                    <?php if ($annonce['superficie']): ?>
                        <div class="characteristic-item">
                            <i class="fas fa-expand"></i>
                            <span><?= $annonce['superficie'] ?> m²</span>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['chambres'] > 0): ?>
                        <div class="characteristic-item">
                            <i class="fas fa-bed"></i>
                            <span><?= $annonce['chambres'] ?> chambre<?= $annonce['chambres'] > 1 ? 's' : '' ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['salles_bain'] > 0): ?>
                        <div class="characteristic-item">
                            <i class="fas fa-bath"></i>
                            <span><?= $annonce['salles_bain'] ?> salle<?= $annonce['salles_bain'] > 1 ? 's' : '' ?> de bain</span>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['parking']): ?>
                        <div class="characteristic-item">
                            <i class="fas fa-car"></i>
                            <span>Parking</span>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['meuble']): ?>
                        <div class="characteristic-item">
                            <i class="fas fa-couch"></i>
                            <span>Meublé</span>
                        </div>
                    <?php endif; ?>
                    <?php if ($annonce['climatisation']): ?>
                        <div class="characteristic-item">
                            <i class="fas fa-snowflake"></i>
                            <span>Climatisation</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Contact Section -->
                <div class="contact-section mb-4">
                    <h4 class="mb-3">Contact propriétaire</h4>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0"><?= htmlspecialchars($annonce['proprietaire_prenom'] . ' ' . $annonce['proprietaire_nom']) ?></h6>
                            <small class="text-muted">Propriétaire</small>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="contact_proprietaire.php?annonce_id=<?= $annonceId ?>" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>Contacter le propriétaire
                        </a>
                        <a href="tel:<?= htmlspecialchars($annonce['proprietaire_telephone']) ?>" class="btn btn-outline-primary">
                            <i class="fas fa-phone me-2"></i><?= htmlspecialchars($annonce['proprietaire_telephone']) ?>
                        </a>
                        <a href="annonces_direct.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour aux annonces
                        </a>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-danger" onclick="toggleFavorite(this)">
                        <i class="far fa-heart me-2"></i>Ajouter aux favoris
                    </button>
                    <button class="btn btn-outline-primary" onclick="shareAnnonce()">
                        <i class="fas fa-share-alt me-2"></i>Partager l'annonce
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Similar Annonces -->
        <?php if (!empty($similar)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <div class="similar-annonces">
                    <h4 class="mb-4">Annonces similaires</h4>
                    <div class="row">
                        <?php foreach ($similar as $similarAnnonce): ?>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card h-100">
                                <?php 
                                $images = json_decode($similarAnnonce['images'] ?? '[]', true);
                                $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                                ?>
                                <img src="uploads/images/<?= $firstImage ?>" 
                                     class="card-img-top" alt="<?= htmlspecialchars($similarAnnonce['titre']) ?>"
                                     style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($similarAnnonce['titre']) ?></h6>
                                    <p class="text-primary fw-bold">
                                        <?= number_format($similarAnnonce['prix'], 0, '.', ' ') ?> FCFA
                                    </p>
                                    <a href="annonce_detail_maps.php?id=<?= $similarAnnonce['id'] ?>" class="btn btn-primary btn-sm">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
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
    
    <!-- Leaflet JS (OpenStreetMap - gratuit) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
    // Initialiser la carte
    let map;
    let marker;
    
    function initMap() {
        // Créer la carte centrée sur l'annonce
        map = L.map('map').setView([<?= $defaultLat ?>, <?= $defaultLng ?>], 15);
        
        // Ajouter la couche de tiles OpenStreetMap (gratuit)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Créer un marqueur personnalisé
        const customIcon = L.divIcon({
            html: '<div style="background: #0066cc; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"><i class="fas fa-home"></i></div>',
            iconSize: [30, 30],
            className: 'custom-marker'
        });
        
        // Ajouter le marqueur
        marker = L.marker([<?= $defaultLat ?>, <?= $defaultLng ?>], {icon: customIcon}).addTo(map);
        
        // Créer le contenu du popup
        const popupContent = `
            <div class="custom-popup">
                <h6><?= htmlspecialchars($annonce['titre']) ?></h6>
                <p class="mb-1"><strong><?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA</strong></p>
                <p class="mb-0 small"><?= htmlspecialchars($adresse) ?></p>
            </div>
        `;
        
        marker.bindPopup(popupContent).openPopup();
        
        // Ajouter un cercle pour montrer la zone approximative
        L.circle([<?= $defaultLat ?>, <?= $defaultLng ?>], {
            color: '#0066cc',
            fillColor: '#0066cc',
            fillOpacity: 0.1,
            radius: 500 // 500 mètres
        }).addTo(map);
    }
    
    // Initialiser la carte quand la page est chargée
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });
    
    function changeMainImage(image, element) {
        document.querySelector('#mainImage img').src = 'uploads/images/' + image;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail-item').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');
    }
    
    function toggleFavorite(element) {
        const icon = element.querySelector('i');
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            element.classList.remove('btn-outline-danger');
            element.classList.add('btn-danger');
            element.innerHTML = '<i class="fas fa-heart me-2"></i>Retirer des favoris';
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            element.classList.remove('btn-danger');
            element.classList.add('btn-outline-danger');
            element.innerHTML = '<i class="far fa-heart me-2"></i>Ajouter aux favoris';
        }
    }
    
    function shareAnnonce() {
        if (navigator.share) {
            navigator.share({
                title: '<?= htmlspecialchars($annonce['titre']) ?>',
                text: '<?= htmlspecialchars(substr($annonce['description'], 0, 100)) ?>...',
                url: window.location.href
            });
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Lien copié dans le presse-papiers !');
        }
    }
    
    function shareLocation() {
        const locationUrl = `https://www.google.com/maps?q=<?= $defaultLat ?>,<?= $defaultLng ?>`;
        navigator.clipboard.writeText(locationUrl);
        alert('Localisation copiée dans le presse-papiers !');
    }
    </script>
</body>
</html>
