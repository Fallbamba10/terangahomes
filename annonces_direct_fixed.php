<?php
// Page pour déposer une annonce
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
session_start();

// S'assurer que la session est configurée correctement
ini_set('session.cookie_domain', '');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

// Rediriger si non connecté
error_log("Page load - Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));
error_log("Page load - Session status: " . session_status());
error_log("Page load - Session ID: " . session_id());

if (!isset($_SESSION['user_id'])) {
    error_log("User not connected, redirecting to login");
    header('Location: login.php');
    exit;
}

// Langues supportées
$supported_langs = [
    'fr' => 'Français',
    'en' => 'English',
    'es' => 'Español',
    'ar' => 'العربية',
    'zh' => '中文',
    'pt' => 'Português'
];

// Langue actuelle
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'fr';
$_SESSION['lang'] = $lang;

// Traductions
$translations = [
    'fr' => [
        'site_title' => 'TerangaHomes - Déposer une annonce',
        'list_property' => 'Déposer une annonce',
        'property_title' => 'Titre de l\'annonce',
        'property_description' => 'Description',
        'property_type' => 'Type de bien',
        'property_price' => 'Prix',
        'property_city' => 'Ville',
        'property_category' => 'Catégorie',
        'submit' => 'Déposer l\'annonce',
        'cancel' => 'Annuler',
        'back' => 'Retour',
        'required_fields' => 'Tous les champs obligatoires doivent être remplis',
        'success' => 'Annonce déposée avec succès !',
        'error' => 'Erreur lors du dépôt de l\'annonce'
    ],
    'en' => [
        'site_title' => 'TerangaHomes - List Property',
        'list_property' => 'List Property',
        'property_title' => 'Property Title',
        'property_description' => 'Description',
        'property_type' => 'Property Type',
        'property_price' => 'Price',
        'property_city' => 'City',
        'property_category' => 'Category',
        'submit' => 'Submit Listing',
        'cancel' => 'Cancel',
        'back' => 'Back',
        'required_fields' => 'All required fields must be filled',
        'success' => 'Property listed successfully!',
        'error' => 'Error listing property'
    ]
];

$t = $translations[$lang];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Vérifier la session avant traitement
    error_log("POST request - Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));
    error_log("POST request - Session status: " . session_status());
    
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        $error = "Vous devez être connecté pour déposer une annonce";
        error_log("Session perdue - Redirection vers login");
    } else {
        require_once 'config/config.php';
        require_once 'core/Database.php';
        
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $type = $_POST['type'] ?? '';
        $price = $_POST['price'] ?? '';
        $city = $_POST['city'] ?? '';
        $category = $_POST['category'] ?? '';
        
        // Debug: Afficher les données reçues
        error_log("POST data: " . print_r($_POST, true));
        error_log("FILES data: " . print_r($_FILES, true));
        
        // Gestion des images
        $images = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $uploadDir = 'uploads/annonces/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['images']['error'][$key] === 0) {
                    $fileName = 'annonce_' . time() . '_' . $key . '.' . pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                    $filePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($tmp_name, $filePath)) {
                        $images[] = $filePath;
                        error_log("Image uploaded: " . $filePath);
                    } else {
                        error_log("Failed to upload image: " . $tmp_name);
                    }
                }
            }
        }
        
        if (empty($title) || empty($description) || empty($type) || empty($price) || empty($city)) {
            $error = $t['required_fields'];
        } else {
        $db = Database::getInstance();
        
        try {
            $sql = "INSERT INTO annonces (titre, description, type, prix, ville, quartier, images, user_id, statut, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
            
            $params = [$title, $description, $type, $price, $city, $category, json_encode($images), $_SESSION['user_id']];
            
            // Debug: Afficher les paramètres
            error_log("Annonce params: " . print_r($params, true));
            error_log("Images uploaded: " . count($images));
            
            $result = $db->execute($sql, $params);
            
            if ($result) {
                $success = $t['success'] . " (Images: " . count($images) . " uploadées)";
                
                // Rediriger après succès
                header('Location: user_dashboard.php?success=' . urlencode($success));
                exit;
            } else {
                $error = $t['error'] . ': Erreur lors de l\'insertion';
            }
            
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            $error = $t['error'] . ': ' . $e->getMessage();
        }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['site_title'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="assets/css/booking-styles.css"> -->
    <style>
        .list-property-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 40px 20px;
        }
        
        .list-property-card {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .list-property-header {
            background: linear-gradient(135deg, #003580 0%, #001840 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .list-property-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .list-property-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .list-property-body {
            padding: 40px;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section h3 {
            color: #003580;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #003580;
        }
        
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #003580;
            box-shadow: 0 0 0 0.2rem rgba(0, 53, 128, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #003580 0%, #001840 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 53, 128, 0.3);
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        @media (max-width: 768px) {
            .list-property-header {
                padding: 30px 20px;
            }
            
            .list-property-header h1 {
                font-size: 2rem;
            }
            
            .list-property-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="list-property-container">
        <div class="list-property-card">
            <div class="list-property-header">
                <h1><i class="fas fa-plus-circle me-3"></i><?= $t['list_property'] ?></h1>
                <p><?= $lang === 'fr' ? 'Déposez votre annonce immobilier en quelques minutes' : 'List your property in minutes' ?></p>
            </div>
            
            <div class="list-property-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle me-2"></i><?= $success ?>
                    </div>
                <?php endif; ?>
                
                <!-- Debug information (à enlever en production) -->
                <?php if (isset($_GET['debug']) && $_GET['debug'] === '1'): ?>
                    <div class="alert alert-info mb-4">
                        <h6>Debug Information:</h6>
                        <p><strong>Session Status:</strong> <?= session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive' ?></p>
                        <p><strong>User ID:</strong> <?= $_SESSION['user_id'] ?? 'Not set' ?></p>
                        <p><strong>Request Method:</strong> <?= $_SERVER['REQUEST_METHOD'] ?></p>
                        <h6>POST Data:</h6>
                        <pre><?= print_r($_POST, true) ?></pre>
                        <h6>FILES Data:</h6>
                        <pre><?= print_r($_FILES, true) ?></pre>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="annonces_direct_fixed.php" enctype="multipart/form-data">
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle me-2"></i><?= $lang === 'fr' ? 'Informations générales' : 'General Information' ?></h3>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label"><?= $t['property_title'] ?> *</label>
                                <input type="text" class="form-control" id="title" name="title" required 
                                       placeholder="<?= $lang === 'fr' ? 'Ex: Appartement 3 pièces à Dakar' : 'Ex: 3-bedroom apartment in Dakar' ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label"><?= $t['property_type'] ?> *</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value=""><?= $lang === 'fr' ? 'Sélectionner...' : 'Select...' ?></option>
                                    <option value="location"><?= $lang === 'fr' ? 'Location' : 'For Rent' ?></option>
                                    <option value="vente"><?= $lang === 'fr' ? 'Vente' : 'For Sale' ?></option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label"><?= $t['property_description'] ?> *</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required
                                      placeholder="<?= $lang === 'fr' ? 'Décrivez votre bien en détail...' : 'Describe your property in detail...' ?>"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3><i class="fas fa-dollar-sign me-2"></i><?= $lang === 'fr' ? 'Prix et localisation' : 'Price and Location' ?></h3>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label"><?= $t['property_price'] ?> (FCFA) *</label>
                                <input type="number" class="form-control" id="price" name="price" required 
                                       placeholder="<?= $lang === 'fr' ? 'Ex: 150000' : 'Ex: 150000' ?>">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label"><?= $t['property_city'] ?> *</label>
                                <select class="form-select" id="city" name="city" required>
                                    <option value=""><?= $lang === 'fr' ? 'Sélectionner...' : 'Select...' ?></option>
                                    <option value="Dakar">Dakar</option>
                                    <option value="Saly">Saly</option>
                                    <option value="Saint-Louis">Saint-Louis</option>
                                    <option value="Mbour">Mbour</option>
                                    <option value="Touba">Touba</option>
                                    <option value="Kaolack">Kaolack</option>
                                    <option value="Thiès">Thiès</option>
                                    <option value="Ziguinchor">Ziguinchor</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="category" class="form-label"><?= $t['property_category'] ?></label>
                                <select class="form-select" id="category" name="category">
                                    <option value=""><?= $lang === 'fr' ? 'Sélectionner...' : 'Select...' ?></option>
                                    <option value="appartement"><?= $lang === 'fr' ? 'Appartement' : 'Apartment' ?></option>
                                    <option value="villa"><?= $lang === 'fr' ? 'Villa' : 'Villa' ?></option>
                                    <option value="studio"><?= $lang === 'fr' ? 'Studio' : 'Studio' ?></option>
                                    <option value="maison"><?= $lang === 'fr' ? 'Maison' : 'House' ?></option>
                                    <option value="terrain"><?= $lang === 'fr' ? 'Terrain' : 'Land' ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Images -->
                    <div class="form-section">
                        <h3><i class="fas fa-images me-2"></i><?= $lang === 'fr' ? 'Images de l\'annonce' : 'Property Images' ?></h3>
                        
                        <div class="mb-3">
                            <label for="images" class="form-label"><?= $lang === 'fr' ? 'Choisir des images' : 'Select Images' ?></label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                            <small class="text-muted"><?= $lang === 'fr' ? 'Vous pouvez sélectionner plusieurs images (JPG, PNG, GIF, WebP - Max 5MB par image)' : 'You can select multiple images (JPG, PNG, GIF, WebP - Max 5MB per image)' ?></small>
                        </div>
                        
                        <div class="mb-3">
                            <div id="image-preview" class="row"></div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between gap-3">
                        <a href="accueil_booking_fixed.php" class="btn btn-cancel flex-fill">
                            <i class="fas fa-arrow-left me-2"></i><?= $t['cancel'] ?>
                        </a>
                        <button type="submit" class="btn btn-submit flex-fill">
                            <i class="fas fa-check me-2"></i><?= $t['submit'] ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Prévisualisation des images
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    const files = Array.from(e.target.files);
    
    files.forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-3';
                
                const card = document.createElement('div');
                card.className = 'card';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'card-img-top';
                img.style.height = '150px';
                img.style.objectFit = 'cover';
                img.alt = `Image ${index + 1}`;
                
                const cardBody = document.createElement('div');
                cardBody.className = 'card-body p-2';
                
                const fileName = document.createElement('small');
                fileName.className = 'text-muted d-block text-truncate';
                fileName.textContent = file.name;
                
                const fileSize = document.createElement('small');
                fileSize.className = 'text-muted';
                fileSize.textContent = ` (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                
                cardBody.appendChild(fileName);
                cardBody.appendChild(fileSize);
                
                card.appendChild(img);
                card.appendChild(cardBody);
                col.appendChild(card);
                preview.appendChild(col);
            };
            
            reader.readAsDataURL(file);
        }
    });
});
</script>
</body>
</html>

<?php
} catch (Exception $e) {
    echo "<h1>Erreur détectée</h1>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Fichier:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Ligne:</strong> " . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
