<?php
// Page de réservation de voiture
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
    header('Location: login.php?redirect=book_car&car_id=' . ($_GET['id'] ?? ''));
    exit;
}

require_once 'config/config.php';
require_once 'core/Database.php';

// Récupérer les informations de la voiture
$carId = $_GET['id'] ?? null;
if (!$carId) {
    header('Location: car_rental.php');
    exit;
}

$db = Database::getInstance();
$car = $db->fetch("SELECT * FROM cars WHERE id = ? AND available = 1", [$carId]);

if (!$car) {
    header('Location: car_rental.php');
    exit;
}

// Traitement du formulaire de réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup_date = $_POST['pickup_date'] ?? '';
    $dropoff_date = $_POST['dropoff_date'] ?? '';
    $pickup_time = $_POST['pickup_time'] ?? '';
    $dropoff_time = $_POST['dropoff_time'] ?? '';
    $pickup_location = $_POST['pickup_location'] ?? '';
    $dropoff_location = $_POST['dropoff_location'] ?? '';
    $total_price = $_POST['total_price'] ?? 0;
    
    if (empty($pickup_date) || empty($dropoff_date) || empty($pickup_location)) {
        $error = "Veuillez remplir tous les champs obligatoires";
    } else {
        try {
            // Calculer le nombre de jours
            $pickup = new DateTime($pickup_date . ' ' . $pickup_time);
            $dropoff = new DateTime($dropoff_date . ' ' . $dropoff_time);
            $days = $pickup->diff($dropoff)->days;
            
            if ($days <= 0) {
                $error = "La date de retour doit être postérieure à la date de prise en charge";
            } else {
                // Insérer la réservation
                $sql = "INSERT INTO car_bookings (car_id, user_id, pickup_date, dropoff_date, pickup_time, dropoff_time, pickup_location, dropoff_location, total_price, status, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
                
                $params = [
                    $carId, $_SESSION['user_id'], $pickup_date, $dropoff_date,
                    $pickup_time, $dropoff_time, $pickup_location, $dropoff_location, $total_price
                ];
                
                $result = $db->execute($sql, $params);
                
                if ($result) {
                    $success = "Réservation effectuée avec succès ! Le propriétaire va vous contacter.";
                    header('Location: user_dashboard.php?success=' . urlencode($success));
                    exit;
                } else {
                    $error = "Erreur lors de la réservation";
                }
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
    <title>Réservation - <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?> - TerangaHomes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .booking-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .car-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .booking-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .price-display {
            background: #28a745;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
        }
        
        .price-display .price {
            font-size: 2rem;
            font-weight: bold;
        }
        
        .car-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .feature-badge {
            display: inline-block;
            padding: 5px 10px;
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            font-size: 12px;
            margin: 2px;
        }
    </style>
</head>
<body style="background: #f8f9fa;">
    <div class="booking-container">
        <h1 class="text-center mb-4">
            <i class="fas fa-calendar-check me-2"></i>Réserver cette voiture
        </h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
            </div>
        <?php endif; ?>
        
        <!-- Résumé de la voiture -->
        <div class="car-summary">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <img src="<?= $car['image'] ?? 'https://via.placeholder.com/400x250' ?>" 
                         class="car-image" alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>">
                </div>
                <div class="col-md-8">
                    <h2><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h2>
                    <p class="mb-2">
                        <i class="fas fa-calendar me-2"></i>Année <?= $car['year'] ?>
                        <i class="fas fa-cogs ms-3 me-2"></i><?= ucfirst($car['transmission']) ?>
                        <i class="fas fa-gas-pump ms-3 me-2"></i><?= ucfirst($car['fuel']) ?>
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-users me-2"></i><?= $car['seats'] ?> places
                        <i class="fas fa-suitcase ms-3 me-2"></i><?= $car['luggage'] ?> bagages
                    </p>
                    
                    <!-- Équipements -->
                    <div class="mb-3">
                        <?php if ($car['air_conditioning']): ?>
                            <span class="feature-badge"><i class="fas fa-snowflake me-1"></i>Clim</span>
                        <?php endif; ?>
                        <?php if ($car['gps']): ?>
                            <span class="feature-badge"><i class="fas fa-map-marked-alt me-1"></i>GPS</span>
                        <?php endif; ?>
                        <?php if ($car['bluetooth']): ?>
                            <span class="feature-badge"><i class="fab fa-bluetooth me-1"></i>BT</span>
                        <?php endif; ?>
                        <?php if ($car['usb_charging']): ?>
                            <span class="feature-badge"><i class="fas fa-usb me-1"></i>USB</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="price-display">
                        <div class="price"><?= number_format($car['daily_price'], 0, '.', ' ') ?> FCFA</div>
                        <div>par jour</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Formulaire de réservation -->
        <div class="booking-form">
            <h3 class="mb-4">
                <i class="fas fa-edit me-2"></i>Détails de la réservation
            </h3>
            
            <form method="post" id="bookingForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de prise en charge *</label>
                        <input type="date" class="form-control" name="pickup_date" required 
                               min="<?= date('Y-m-d') ?>" onchange="calculatePrice()">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de restitution *</label>
                        <input type="date" class="form-control" name="dropoff_date" required 
                               min="<?= date('Y-m-d') ?>" onchange="calculatePrice()">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Heure de prise en charge *</label>
                        <input type="time" class="form-control" name="pickup_time" value="10:00" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Heure de restitution *</label>
                        <input type="time" class="form-control" name="dropoff_time" value="10:00" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lieu de prise en charge *</label>
                        <input type="text" class="form-control" name="pickup_location" required 
                               placeholder="Ex: Aéroport de Dakar, Hôtel, Adresse...">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lieu de restitution</label>
                        <input type="text" class="form-control" name="dropoff_location" 
                               placeholder="Ex: Aéroport de Dakar, Hôtel, Adresse...">
                    </div>
                </div>
                
                <!-- Calcul du prix -->
                <div class="price-display">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-2">
                                <i class="fas fa-calendar-day me-2"></i>
                                <span id="days">0</span> jours
                            </div>
                            <div>
                                <i class="fas fa-tag me-2"></i>
                                <?= number_format($car['daily_price'], 0, '.', ' ') ?> FCFA / jour
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="price">
                                <span id="totalPrice">0</span> FCFA
                            </div>
                            <small>Total</small>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="total_price" id="totalPriceInput" value="0">
                
                <div class="d-flex justify-content-between gap-3 mt-4">
                    <a href="car_rental.php" class="btn btn-secondary btn-lg flex-fill">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg flex-fill">
                        <i class="fas fa-check-circle me-2"></i>Confirmer la réservation
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const dailyPrice = <?= $car['daily_price'] ?>;
        
        function calculatePrice() {
            const pickupDate = document.querySelector('input[name="pickup_date"]').value;
            const dropoffDate = document.querySelector('input[name="dropoff_date"]').value;
            
            if (pickupDate && dropoffDate) {
                const pickup = new Date(pickupDate);
                const dropoff = new Date(dropoffDate);
                const days = Math.max(1, Math.ceil((dropoff - pickup) / (1000 * 60 * 60 * 24)));
                
                const totalPrice = days * dailyPrice;
                
                document.getElementById('days').textContent = days;
                document.getElementById('totalPrice').textContent = totalPrice.toLocaleString();
                document.getElementById('totalPriceInput').value = totalPrice;
            }
        }
        
        // Mettre à jour la date minimale de restitution
        document.querySelector('input[name="pickup_date"]').addEventListener('change', function() {
            document.querySelector('input[name="dropoff_date"]').min = this.value;
            calculatePrice();
        });
        
        document.querySelector('input[name="dropoff_date"]').addEventListener('change', calculatePrice);
    </script>
</body>
</html>
