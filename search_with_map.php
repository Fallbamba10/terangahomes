<?php
// Page de recherche avec carte intégrée

require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

try {
    // Récupérer les catégories et villes
    $categories = $db->fetchAll("SELECT * FROM categories ORDER BY nom");
    $villes = $db->fetchAll("SELECT DISTINCT ville FROM annonces WHERE ville IS NOT NULL ORDER BY ville");
    
    // Traitement de la recherche
    $results = [];
    $totalResults = 0;
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
        $query = $_GET['query'] ?? '';
        $type = $_GET['type'] ?? '';
        $categorie_id = $_GET['categorie_id'] ?? '';
        $ville = $_GET['ville'] ?? '';
        $prix_min = $_GET['prix_min'] ?? '';
        $prix_max = $_GET['prix_max'] ?? '';
        
        // Construire la requête
        $where = ["a.statut = 'active'"];
        $params = [];
        
        if (!empty($query)) {
            $where[] = "(a.titre LIKE ? OR a.description LIKE ?)";
            $searchTerm = '%' . $query . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($type)) {
            $where[] = "a.type = ?";
            $params[] = $type;
        }
        
        if (!empty($categorie_id)) {
            $where[] = "a.categorie_id = ?";
            $params[] = $categorie_id;
        }
        
        if (!empty($ville)) {
            $where[] = "a.ville = ?";
            $params[] = $ville;
        }
        
        if (!empty($prix_min)) {
            $where[] = "a.prix >= ?";
            $params[] = $prix_min;
        }
        
        if (!empty($prix_max)) {
            $where[] = "a.prix <= ?";
            $params[] = $prix_max;
        }
        
        $whereClause = "WHERE " . implode(' AND ', $where);
        
        $sql = "SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                c.nom as categorie_nom 
                FROM annonces a
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN categories c ON a.categorie_id = c.id
                $whereClause
                ORDER BY a.created_at DESC";
        
        $results = $db->fetchAll($sql, $params);
        $totalResults = count($results);
    } else {
        // Récupérer toutes les annonces pour la carte
        $results = $db->fetchAll("SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom, 
                c.nom as categorie_nom 
                FROM annonces a
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN categories c ON a.categorie_id = c.id
                WHERE a.statut = 'active'
                ORDER BY a.created_at DESC LIMIT 50");
        
        $totalResults = $db->fetch("SELECT COUNT(*) as total FROM annonces WHERE statut = 'active'")['total'] ?? 0;
    }
    
} catch (Exception $e) {
    $error = $e->getMessage();
    $categories = [];
    $villes = [];
    $results = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche avec carte - TerangaHomes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS -->
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
        
        .search-hero {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: white;
            padding: 60px 0;
        }
        
        .search-container {
            display: flex;
            gap: 2rem;
            height: 600px;
        }
        
        .search-panel {
            flex: 0 0 400px;
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        
        .map-panel {
            flex: 1;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        #map {
            height: 100%;
            width: 100%;
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
        
        .btn-primary {
            background: #0066cc;
            border-color: #0066cc;
            color: white;
        }
        
        .btn-primary:hover {
            background: #004499;
            border-color: #004499;
        }
        
        .annonce-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .annonce-item:hover {
            background: white;
            border-color: #0066cc;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .annonce-item.active {
            background: white;
            border-color: #0066cc;
        }
        
        .annonce-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .annonce-price {
            color: #0066cc;
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .annonce-location {
            color: #666;
            font-size: 0.9rem;
        }
        
        .results-count {
            background: #0066cc;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: inline-block;
        }
        
        .leaflet-container {
            font-family: 'Inter', sans-serif;
        }
        
        .custom-popup {
            font-family: 'Inter', sans-serif;
        }
        
        .map-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: white;
            border-radius: 8px;
            padding: 0.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .map-controls button {
            display: block;
            width: 100%;
            padding: 0.5rem;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 0.25rem;
        }
        
        .map-controls button:hover {
            background: #f0f0f0;
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
                        <a class="nav-link active" href="search_with_map.php">Recherche</a>
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

    <!-- Search Hero -->
    <section class="search-hero">
        <div class="container">
            <h1 class="text-center mb-4">Recherche géolocalisée</h1>
            <p class="text-center">Trouvez votre bien idéal avec notre carte interactive</p>
        </div>
    </section>

    <!-- Search Container -->
    <section class="container py-4">
        <div class="search-container">
            <!-- Search Panel -->
            <div class="search-panel">
                <h4 class="mb-4">Filtres de recherche</h4>
                
                <div class="results-count">
                    <?= $totalResults ?> résultat<?= $totalResults > 1 ? 's' : '' ?>
                </div>
                
                <form method="GET" action="search_with_map.php">
                    <div class="mb-3">
                        <label class="form-label">Recherche</label>
                        <input type="text" class="form-control" name="query" 
                               value="<?= htmlspecialchars($_GET['query'] ?? '') ?>" 
                               placeholder="Titre, description...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="type">
                            <option value="">Tous les types</option>
                            <option value="location" <?= ($_GET['type'] ?? '') === 'location' ? 'selected' : '' ?>>🏠 Location</option>
                            <option value="vente" <?= ($_GET['type'] ?? '') === 'vente' ? 'selected' : '' ?>>🏡 Vente</option>
                            <option value="hotel" <?= ($_GET['type'] ?? '') === 'hotel' ? 'selected' : '' ?>>🏨 Hôtel</option>
                            <option value="voiture" <?= ($_GET['type'] ?? '') === 'voiture' ? 'selected' : '' ?>>🚗 Voiture</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catégorie</label>
                        <select class="form-select" name="categorie_id">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories ?? [] as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= ($_GET['categorie_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ville</label>
                        <select class="form-select" name="ville">
                            <option value="">Toutes les villes</option>
                            <?php foreach ($villes ?? [] as $ville): ?>
                                <option value="<?= $ville['ville'] ?>" <?= ($_GET['ville'] ?? '') === $ville['ville'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ville['ville']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Prix min</label>
                            <input type="number" class="form-control" name="prix_min" 
                                   value="<?= htmlspecialchars($_GET['prix_min'] ?? '') ?>" 
                                   placeholder="FCFA">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Prix max</label>
                            <input type="number" class="form-control" name="prix_max" 
                                   value="<?= htmlspecialchars($_GET['prix_max'] ?? '') ?>" 
                                   placeholder="FCFA">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-search me-2"></i>Rechercher
                    </button>
                    
                    <a href="search_with_map.php" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-2"></i>Réinitialiser
                    </a>
                </form>
                
                <!-- Results List -->
                <div class="mt-4">
                    <h5 class="mb-3">Résultats</h5>
                    <div id="resultsList">
                        <?php foreach ($results as $annonce): ?>
                        <div class="annonce-item" 
                             data-lat="<?= $annonce['latitude'] ?? 14.6928 ?>" 
                             data-lng="<?= $annonce['longitude'] ?? -17.4467 ?>"
                             data-id="<?= $annonce['id'] ?>"
                             data-title="<?= htmlspecialchars($annonce['titre']) ?>"
                             data-price="<?= number_format($annonce['prix'], 0, '.', ' ') ?>"
                             data-location="<?= htmlspecialchars($annonce['ville']) ?>"
                             onclick="focusAnnonce(this)">
                            <div class="annonce-title"><?= htmlspecialchars($annonce['titre']) ?></div>
                            <div class="annonce-price"><?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA</div>
                            <div class="annonce-location">
                                <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($annonce['ville']) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Map Panel -->
            <div class="map-panel">
                <div id="map"></div>
                <div class="map-controls">
                    <button onclick="resetMap()" title="Réinitialiser la carte">
                        <i class="fas fa-home"></i>
                    </button>
                    <button onclick="toggleFullscreen()" title="Plein écran">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
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
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
    let map;
    let markers = [];
    let currentMarker = null;
    
    function initMap() {
        // Initialiser la carte centrée sur Dakar
        map = L.map('map').setView([14.6928, -17.4467], 12);
        
        // Ajouter la couche de tiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Ajouter les marqueurs des annonces
        addMarkers();
    }
    
    function addMarkers() {
        // Supprimer les marqueurs existants
        markers.forEach(marker => map.removeLayer(marker));
        markers = [];
        
        // Ajouter les nouveaux marqueurs
        const annonces = document.querySelectorAll('.annonce-item');
        
        annonces.forEach(annonce => {
            const lat = parseFloat(annonce.dataset.lat);
            const lng = parseFloat(annonce.dataset.lng);
            const title = annonce.dataset.title;
            const price = annonce.dataset.price;
            const location = annonce.dataset.location;
            const id = annonce.dataset.id;
            
            // Créer une icône personnalisée selon le type
            const iconHtml = '<div style="background: #0066cc; color: white; width: 25px; height: 25px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3); font-size: 12px;"><i class="fas fa-home"></i></div>';
            
            const customIcon = L.divIcon({
                html: iconHtml,
                iconSize: [25, 25],
                className: 'custom-marker'
            });
            
            // Créer le marqueur
            const marker = L.marker([lat, lng], {icon: customIcon}).addTo(map);
            
            // Créer le popup
            const popupContent = `
                <div class="custom-popup">
                    <h6>${title}</h6>
                    <p class="mb-1"><strong>${price} FCFA</strong></p>
                    <p class="mb-2 small"><i class="fas fa-map-marker-alt me-1"></i>${location}</p>
                    <a href="annonce_detail_maps.php?id=${id}" class="btn btn-primary btn-sm">Voir détails</a>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            
            // Ajouter un événement click
            marker.on('click', function() {
                highlightAnnonce(id);
            });
            
            markers.push(marker);
        });
        
        // Ajuster la vue pour montrer tous les marqueurs
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
    }
    
    function focusAnnonce(element) {
        const lat = parseFloat(element.dataset.lat);
        const lng = parseFloat(element.dataset.lng);
        
        // Centrer la carte sur l'annonce
        map.setView([lat, lng], 15);
        
        // Ouvrir le popup du marqueur correspondant
        markers.forEach(marker => {
            const markerLat = marker.getLatLng().lat;
            const markerLng = marker.getLatLng().lng;
            
            if (Math.abs(markerLat - lat) < 0.0001 && Math.abs(markerLng - lng) < 0.0001) {
                marker.openPopup();
            }
        });
        
        // Mettre en évidence l'annonce dans la liste
        highlightAnnonce(element.dataset.id);
    }
    
    function highlightAnnonce(annonceId) {
        // Retirer la classe active de tous les éléments
        document.querySelectorAll('.annonce-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Ajouter la classe active à l'élément correspondant
        const targetElement = document.querySelector(`[data-id="${annonceId}"]`);
        if (targetElement) {
            targetElement.classList.add('active');
            targetElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    function resetMap() {
        map.setView([14.6928, -17.4467], 12);
        
        // Fermer tous les popups
        markers.forEach(marker => {
            marker.closePopup();
        });
    }
    
    function toggleFullscreen() {
        const mapPanel = document.querySelector('.map-panel');
        
        if (!document.fullscreenElement) {
            mapPanel.requestFullscreen().catch(err => {
                console.log(`Error attempting to enable fullscreen: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    }
    
    // Initialiser la carte au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });
    
    // Redimensionner la carte quand la fenêtre change
    window.addEventListener('resize', function() {
        if (map) {
            map.invalidateSize();
        }
    });
    </script>
</body>
</html>
