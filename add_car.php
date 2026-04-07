<?php
// Page pour ajouter une voiture à louer
session_start();

// Configuration agressive de la session AVANT de la démarrer
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

session_start();

// Forcer la régénération de l'ID de session si nécessaire
if (!isset($_SESSION['initialized'])) {
    session_regenerate_id(false);
    $_SESSION['initialized'] = true;
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/config.php';
require_once 'core/Database.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $year = $_POST['year'] ?? '';
    $type = $_POST['type'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $fuel = $_POST['fuel'] ?? '';
    $seats = $_POST['seats'] ?? '';
    $luggage = $_POST['luggage'] ?? '';
    $daily_price = $_POST['daily_price'] ?? '';
    $weekly_price = $_POST['weekly_price'] ?? '';
    $monthly_price = $_POST['monthly_price'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // Équipements
    $air_conditioning = isset($_POST['air_conditioning']) ? 1 : 0;
    $gps = isset($_POST['gps']) ? 1 : 0;
    $bluetooth = isset($_POST['bluetooth']) ? 1 : 0;
    $usb_charging = isset($_POST['usb_charging']) ? 1 : 0;
    $child_seat = isset($_POST['child_seat']) ? 1 : 0;
    
    // Gestion de l'image
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = 'uploads/cars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = 'car_' . time() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            $image = $filePath;
        }
    }
    
    if (empty($brand) || empty($model) || empty($year) || empty($daily_price)) {
        $error = "Veuillez remplir tous les champs obligatoires";
    } else {
        $db = Database::getInstance();
        
        try {
            $sql = "INSERT INTO cars (brand, model, year, type, transmission, fuel, seats, luggage, daily_price, weekly_price, monthly_price, description, image, air_conditioning, gps, bluetooth, usb_charging, child_seat, available, owner_id, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $params = [
                $brand, $model, $year, $type, $transmission, $fuel, $seats, $luggage,
                $daily_price, $weekly_price, $monthly_price, $description, $image,
                $air_conditioning, $gps, $bluetooth, $usb_charging, $child_seat,
                1, $_SESSION['user_id']
            ];
            
            $result = $db->execute($sql, $params);
            
            if ($result) {
                $success = "Voiture ajoutée avec succès !";
                header('Location: car_rental.php?success=' . urlencode($success));
                exit;
            } else {
                $error = "Erreur lors de l'ajout de la voiture";
            }
            
        } catch (Exception $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une voiture - TerangaHomes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .add-car-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .form-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .section-title {
            color: #004a99;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .feature-check {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .feature-check input {
            margin-right: 10px;
        }
    </style>
</head>
<body style="background: #f8f9fa;">
    <div class="add-car-container">
        <h1 class="text-center mb-4">
            <i class="fas fa-car me-2"></i>Ajouter une voiture à louer
        </h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
            </div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <!-- Informations principales -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle me-2"></i>Informations principales
                </h3>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Marque *</label>
                        <input type="text" class="form-control" name="brand" required 
                               placeholder="Ex: Toyota, Renault, Peugeot">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Modèle *</label>
                        <input type="text" class="form-control" name="model" required 
                               placeholder="Ex: Yaris, Clio, 208">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Année *</label>
                        <input type="number" class="form-control" name="year" required 
                               placeholder="Ex: 2023" min="2000" max="<?= date('Y') ?>">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Type *</label>
                        <select class="form-select" name="type" required>
                            <option value="">Sélectionner...</option>
                            <option value="economy">Économique</option>
                            <option value="compact">Compact</option>
                            <option value="midsize">Berline</option>
                            <option value="suv">SUV</option>
                            <option value="luxury">Luxe</option>
                            <option value="van">Utilitaire</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Transmission *</label>
                        <select class="form-select" name="transmission" required>
                            <option value="">Sélectionner...</option>
                            <option value="manual">Manuelle</option>
                            <option value="automatic">Automatique</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Carburant *</label>
                        <select class="form-select" name="fuel" required>
                            <option value="">Sélectionner...</option>
                            <option value="essence">Essence</option>
                            <option value="diesel">Diesel</option>
                            <option value="hybrid">Hybride</option>
                            <option value="electric">Électrique</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre de places *</label>
                        <input type="number" class="form-control" name="seats" required 
                               placeholder="Ex: 5" min="1" max="9">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre de bagages *</label>
                        <input type="number" class="form-control" name="luggage" required 
                               placeholder="Ex: 3" min="0" max="8">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="4" 
                              placeholder="Décrivez votre voiture en détail..."></textarea>
                </div>
            </div>
            
            <!-- Prix -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-dollar-sign me-2"></i>Tarifs
                </h3>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Prix par jour (FCFA) *</label>
                        <input type="number" class="form-control" name="daily_price" required 
                               placeholder="Ex: 25000" min="0">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Prix par semaine (FCFA)</label>
                        <input type="number" class="form-control" name="weekly_price" 
                               placeholder="Ex: 150000" min="0">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Prix par mois (FCFA)</label>
                        <input type="number" class="form-control" name="monthly_price" 
                               placeholder="Ex: 500000" min="0">
                    </div>
                </div>
            </div>
            
            <!-- Équipements -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-cogs me-2"></i>Équipements et options
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="feature-check">
                            <input type="checkbox" name="air_conditioning" value="1" id="air_conditioning">
                            <label for="air_conditioning">
                                <i class="fas fa-snowflake me-2"></i>Climatisation
                            </label>
                        </div>
                        
                        <div class="feature-check">
                            <input type="checkbox" name="gps" value="1" id="gps">
                            <label for="gps">
                                <i class="fas fa-map-marked-alt me-2"></i>GPS
                            </label>
                        </div>
                        
                        <div class="feature-check">
                            <input type="checkbox" name="bluetooth" value="1" id="bluetooth">
                            <label for="bluetooth">
                                <i class="fab fa-bluetooth me-2"></i>Bluetooth
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="feature-check">
                            <input type="checkbox" name="usb_charging" value="1" id="usb_charging">
                            <label for="usb_charging">
                                <i class="fas fa-usb me-2"></i>Prise USB
                            </label>
                        </div>
                        
                        <div class="feature-check">
                            <input type="checkbox" name="child_seat" value="1" id="child_seat">
                            <label for="child_seat">
                                <i class="fas fa-baby me-2"></i>Siège enfant
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-image me-2"></i>Photo de la voiture
                </h3>
                
                <div class="mb-3">
                    <label class="form-label">Image principale</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                    <small class="text-muted">Formats acceptés : JPG, PNG, GIF (Max 5MB)</small>
                </div>
            </div>
            
            <!-- Boutons -->
            <div class="d-flex justify-content-between gap-3">
                <a href="car_rental.php" class="btn btn-secondary btn-lg flex-fill">
                    <i class="fas fa-arrow-left me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary btn-lg flex-fill">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter la voiture
                </button>
            </div>
        </form>
    </div>
</body>
</html>
