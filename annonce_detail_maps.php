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

// Traitement du formulaire de contact
$messageSent = false;
$contactError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_owner'])) {
    $message = $_POST['message'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    if (empty($message) || empty($name) || empty($email)) {
        $contactError = "Veuillez remplir tous les champs obligatoires.";
    } else {
        try {
            // Insérer le message dans la table messages
            $sql = "INSERT INTO messages (sender_id, receiver_id, annonce_id, sujet, message, created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
            
            $senderId = $_SESSION['user_id'] ?? null;
            $receiverId = $annonce['user_id'];
            $sujet = "Intérêt pour l'annonce : " . $annonce['titre'];
            
            $db->execute($sql, [$senderId, $receiverId, $annonceId, $sujet, $message]);
            
            $messageSent = true;
        } catch (Exception $e) {
            $contactError = "Erreur lors de l'envoi du message. Veuillez réessayer.";
        }
    }
}

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
        'send_message' => 'Envoyer un message',
        'your_name' => 'Votre nom',
        'your_email' => 'Votre email',
        'your_phone' => 'Votre téléphone',
        'your_message' => 'Votre message',
        'send' => 'Envoyer',
        'message_sent' => 'Message envoyé avec succès !',
        'owner_info' => 'Informations du propriétaire',
        'contact_methods' => 'Méthodes de contact'
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
        'send_message' => 'Send Message',
        'your_name' => 'Your Name',
        'your_email' => 'Your Email',
        'your_phone' => 'Your Phone',
        'your_message' => 'Your Message',
        'send' => 'Send',
        'message_sent' => 'Message sent successfully!',
        'owner_info' => 'Owner Information',
        'contact_methods' => 'Contact Methods'
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
        }
        
        .contact-form .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            color: #333;
        }
        
        .contact-form .form-control:focus {
            background: white;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.5);
        }
        
        .contact-form .form-label {
            color: white;
            font-weight: 500;
        }
        
        .contact-form .form-control::placeholder {
            color: #666;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.9);
            border: none;
            color: white;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.9);
            border: none;
            color: white;
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
            
            <?php if ($messageSent): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i><?= $t['message_sent'] ?>
                </div>
            <?php endif; ?>
            
            <?php if ($contactError): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= $contactError ?>
                </div>
            <?php endif; ?>
            
            <?php if ($owner): ?>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5><i class="fas fa-user me-2"></i><?= $t['owner_info'] ?></h5>
                        <p><strong>Nom :</strong> <?= htmlspecialchars($owner['nom']) ?></p>
                        <?php if ($owner['telephone']): ?>
                            <p><strong>Téléphone :</strong> <?= htmlspecialchars($owner['telephone']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-envelope me-2"></i><?= $t['contact_methods'] ?></h5>
                        <p>Vous pouvez contacter le propriétaire via ce formulaire.</p>
                    </div>
                </div>
            <?php endif; ?>
            
            <form method="post" class="contact-form">
                <input type="hidden" name="contact_owner" value="1">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label"><?= $t['your_name'] ?> *</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label"><?= $t['your_email'] ?> *</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="phone" class="form-label"><?= $t['your_phone'] ?></label>
                    <input type="tel" class="form-control" id="phone" name="phone" 
                           placeholder="<?= $t['your_phone'] ?>">
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label"><?= $t['your_message'] ?> *</label>
                    <textarea class="form-control" id="message" name="message" rows="4" required 
                              placeholder="Bonjour, je suis intéressé(e) par cette annonce. Pouvez-vous me donner plus d'informations ?"></textarea>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-light btn-lg">
                        <i class="fas fa-paper-plane me-2"></i><?= $t['send'] ?>
                    </button>
                </div>
            </form>
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
