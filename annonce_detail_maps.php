<?php
// Page de détail d'annonce
session_start();

// Configuration agressive de la session
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

// Forcer la régénération de l'ID de session si nécessaire
if (!isset($_SESSION['initialized'])) {
    session_regenerate_id(false);
    $_SESSION['initialized'] = true;
}

require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'models/Annonce.php';

// Récupérer l'ID de l'annonce
$annonceId = $_GET['id'] ?? null;

if (!$annonceId) {
    header('Location: accueil_booking_fixed.php');
    exit;
}

// Récupérer l'annonce
$annonceModel = new Annonce();
$annonce = $annonceModel->findById($annonceId);

if (!$annonce) {
    header('Location: accueil_booking_fixed.php');
    exit;
}

// Récupérer les informations du propriétaire
$db = Database::getInstance();
$owner = $db->fetch("SELECT id, nom, email, telephone FROM users WHERE id = ?", [$annonce['user_id']]);

// Récupérer les images
$images = json_decode($annonce['images'] ?? '[]', true) ?: [];

// Langues supportées
$supported_langs = [
    'fr' => 'Français',
    'en' => 'English',
    'es' => 'Español',
    'ar' => 'Arial',
    'zh' => 'Chinese',
    'pt' => 'Português'
];

$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'fr';
$_SESSION['lang'] = $lang;

// Traductions
$translations = [
    'fr' => [
        'back_to_home' => 'Retour à l\'accueil',
        'property_details' => 'Détails de l\'annonce',
        'price' => 'Prix',
        'location' => 'Localisation',
        'description' => 'Description',
        'features' => 'Caractéristiques',
        'contact_owner' => 'Contacter le propriétaire',
        'gallery' => 'Galerie d\'images',
        'no_images' => 'Aucune image disponible',
        'bedrooms' => 'Chambres',
        'bathrooms' => 'Salles de bain',
        'surface' => 'Superficie',
        'furnished' => 'Meublé',
        'air_conditioning' => 'Climatisation',
        'wifi' => 'WiFi',
        'parking' => 'Parking',
        'type' => 'Type',
        'city' => 'Ville',
        'neighborhood' => 'Quartier',
        'contact_whatsapp' => 'Contacter via WhatsApp',
        'owner_info' => 'Informations du propriétaire',
        'whatsapp_message' => 'Bonjour, je suis intéressé(e) par votre annonce'
    ],
    'en' => [
        'back_to_home' => 'Back to Home',
        'property_details' => 'Property Details',
        'price' => 'Price',
        'location' => 'Location',
        'description' => 'Description',
        'features' => 'Features',
        'contact_owner' => 'Contact Owner',
        'gallery' => 'Image Gallery',
        'no_images' => 'No images available',
        'bedrooms' => 'Bedrooms',
        'bathrooms' => 'Bathrooms',
        'surface' => 'Surface',
        'furnished' => 'Furnished',
        'air_conditioning' => 'Air Conditioning',
        'wifi' => 'WiFi',
        'parking' => 'Parking',
        'type' => 'Type',
        'city' => 'City',
        'neighborhood' => 'Neighborhood',
        'contact_whatsapp' => 'Contact via WhatsApp',
        'owner_info' => 'Owner Information',
        'whatsapp_message' => 'Hello, I am interested in your property listing'
    ]
];

$t = $translations[$lang];
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($annonce['titre']) ?> - TerangaHomes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/booking-styles.css">
    <style>
        .property-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .property-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .property-gallery {
            margin-bottom: 30px;
        }
        
        .gallery-main {
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        
        .gallery-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .gallery-thumbnails {
            display: flex;
            gap: 10px;
            overflow-x: auto;
        }
        
        .gallery-thumbnails img {
            width: 100px;
            height: 75px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .gallery-thumbnails img:hover {
            transform: scale(1.1);
        }
        
        .property-info {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .feature-badge {
            display: inline-block;
            padding: 8px 15px;
            margin: 5px;
            background: #f8f9fa;
            border-radius: 20px;
            font-size: 14px;
        }
        
        .feature-badge.active {
            background: #28a745;
            color: white;
        }
        
        .contact-section {
            background: #28a745;
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
            text-align: center;
        }
        
        .alert-warning {
            background: rgba(255, 193, 7, 0.9);
            border: none;
            color: #333;
        }
        
        .price-tag {
            font-size: 2rem;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="property-detail-container">
        <!-- Header -->
        <div class="property-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2"><?= htmlspecialchars($annonce['titre']) ?></h1>
                    <p class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <?= htmlspecialchars($annonce['ville']) ?>
                        <?php if (!empty($annonce['quartier'])): ?>
                            - <?= htmlspecialchars($annonce['quartier']) ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="text-end">
                    <div class="price-tag"><?= number_format($annonce['prix'], 0, '.', ' ') ?> FCFA</div>
                    <small><?= ucfirst($annonce['type']) ?></small>
                </div>
            </div>
        </div>
        
        <!-- Gallery -->
        <div class="property-gallery">
            <h3 class="mb-3"><i class="fas fa-images me-2"></i><?= $t['gallery'] ?></h3>
            <?php if (!empty($images)): ?>
                <div class="gallery-main">
                    <img id="mainImage" src="<?= $images[0] ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                </div>
                <div class="gallery-thumbnails">
                    <?php foreach ($images as $index => $image): ?>
                        <img src="<?= $image ?>" alt="Image <?= $index + 1 ?>" onclick="changeMainImage('<?= $image ?>')">
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i><?= $t['no_images'] ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Property Info -->
        <div class="property-info">
            <h3 class="mb-4"><i class="fas fa-info-circle me-2"></i><?= $t['property_details'] ?></h3>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5><?= $t['description'] ?></h5>
                    <p><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>
                </div>
                <div class="col-md-6">
                    <h5><?= $t['features'] ?></h5>
                    <div class="mb-3">
                        <span class="feature-badge <?= $annonce['chambres'] ? 'active' : '' ?>">
                            <i class="fas fa-bed me-1"></i><?= $t['bedrooms'] ?>: <?= $annonce['chambres'] ?: 'N/A' ?>
                        </span>
                        <span class="feature-badge <?= $annonce['salles_bain'] ? 'active' : '' ?>">
                            <i class="fas fa-bath me-1"></i><?= $t['bathrooms'] ?>: <?= $annonce['salles_bain'] ?: 'N/A' ?>
                        </span>
                        <span class="feature-badge <?= $annonce['superficie'] ? 'active' : '' ?>">
                            <i class="fas fa-ruler-combined me-1"></i><?= $t['surface'] ?>: <?= $annonce['superficie'] ?: 'N/A' ?> m²
                        </span>
                    </div>
                    <div>
                        <span class="feature-badge <?= $annonce['meuble'] ? 'active' : '' ?>">
                            <i class="fas fa-couch me-1"></i><?= $t['furnished'] ?>
                        </span>
                        <span class="feature-badge <?= $annonce['climatisation'] ? 'active' : '' ?>">
                            <i class="fas fa-snowflake me-1"></i><?= $t['air_conditioning'] ?>
                        </span>
                        <span class="feature-badge <?= $annonce['wifi'] ? 'active' : '' ?>">
                            <i class="fas fa-wifi me-1"></i>WiFi
                        </span>
                        <span class="feature-badge <?= $annonce['parking'] ? 'active' : '' ?>">
                            <i class="fas fa-car me-1"></i><?= $t['parking'] ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Section -->
        <div class="contact-section">
            <h3><i class="fas fa-phone me-2"></i><?= $t['contact_owner'] ?></h3>
            
            <?php if ($owner && !empty($owner['telephone'])): ?>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5><i class="fas fa-user me-2"></i><?= $t['owner_info'] ?></h5>
                        <p><strong>Nom :</strong> <?= htmlspecialchars($owner['nom']) ?></p>
                        <p><strong>Téléphone :</strong> <?= htmlspecialchars($owner['telephone']) ?></p>
                    </div>
                </div>
                
                <div class="text-center">
                    <p class="mb-3">Cliquez sur le bouton ci-dessous pour contacter directement le propriétaire via WhatsApp</p>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $owner['telephone']) ?>?text=<?= urlencode($t['whatsapp_message'] . ' : ' . $annonce['titre'] . ' - ' . $annonce['ville']) ?>" 
                       target="_blank" 
                       class="btn btn-light btn-lg">
                        <i class="fab fa-whatsapp me-2"></i><?= $t['contact_whatsapp'] ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Les informations de contact du propriétaire ne sont pas disponibles pour le moment.
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="accueil_booking_fixed.php" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i><?= $t['back_to_home'] ?>
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeMainImage(src) {
            document.getElementById('mainImage').src = src;
        }
    </script>
</body>
</html>
