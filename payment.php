<?php
session_start();

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
        'site_title' => 'TerangaHomes - Paiement Sécurisé',
        'secure_payment' => 'Paiement Sécurisé',
        'payment_summary' => 'Récapitulatif du paiement',
        'booking_reference' => 'Référence de réservation',
        'service_details' => 'Détails du service',
        'pickup_location' => 'Lieu de prise en charge',
        'dropoff_location' => 'Lieu de destination',
        'pickup_date' => 'Date de prise en charge',
        'pickup_time' => 'Heure de prise en charge',
        'passengers' => 'Nombre de passagers',
        'price_details' => 'Détails du prix',
        'service_price' => 'Prix du service',
        'insurance' => 'Assurance',
        'processing_fee' => 'Frais de traitement',
        'total_amount' => 'Montant total',
        'payment_method' => 'Méthode de paiement',
        'card_payment' => 'Paiement par carte',
        'mobile_money' => 'Mobile Money',
        'bank_transfer' => 'Virement bancaire',
        'card_number' => 'Numéro de carte',
        'expiry_date' => 'Date d\'expiration',
        'cvv' => 'CVV',
        'cardholder_name' => 'Nom du titulaire',
        'phone_number' => 'Numéro de téléphone',
        'orange_money' => 'Orange Money',
        'wave_money' => 'Wave',
        'complete_payment' => 'Compléter le paiement',
        'cancel_payment' => 'Annuler le paiement',
        'secure_ssl' => 'Paiement sécurisé par SSL',
        'accepted_cards' => 'Cartes acceptées',
        'refund_policy' => 'Politique de remboursement',
        'terms_conditions' => 'Conditions générales',
        'payment_successful' => 'Paiement réussi !',
        'payment_failed' => 'Paiement échoué',
        'redirecting' => 'Redirection en cours...',
        'car_rental' => 'Location Voiture',
        'airport_transfer' => 'Taxi Aéroport',
        'properties' => 'Propriétés',
        'back_to_booking' => 'Retour à la réservation'
    ],
    'en' => [
        'site_title' => 'TerangaHomes - Secure Payment',
        'secure_payment' => 'Secure Payment',
        'payment_summary' => 'Payment Summary',
        'booking_reference' => 'Booking Reference',
        'service_details' => 'Service Details',
        'pickup_location' => 'Pickup Location',
        'dropoff_location' => 'Drop-off Location',
        'pickup_date' => 'Pickup Date',
        'pickup_time' => 'Pickup Time',
        'passengers' => 'Number of Passengers',
        'price_details' => 'Price Details',
        'service_price' => 'Service Price',
        'insurance' => 'Insurance',
        'processing_fee' => 'Processing Fee',
        'total_amount' => 'Total Amount',
        'payment_method' => 'Payment Method',
        'card_payment' => 'Card Payment',
        'mobile_money' => 'Mobile Money',
        'bank_transfer' => 'Bank Transfer',
        'card_number' => 'Card Number',
        'expiry_date' => 'Expiry Date',
        'cvv' => 'CVV',
        'cardholder_name' => 'Cardholder Name',
        'phone_number' => 'Phone Number',
        'orange_money' => 'Orange Money',
        'wave_money' => 'Wave',
        'complete_payment' => 'Complete Payment',
        'cancel_payment' => 'Cancel Payment',
        'secure_ssl' => 'Secured by SSL Payment',
        'accepted_cards' => 'Accepted Cards',
        'refund_policy' => 'Refund Policy',
        'terms_conditions' => 'Terms & Conditions',
        'payment_successful' => 'Payment Successful!',
        'payment_failed' => 'Payment Failed',
        'redirecting' => 'Redirecting...',
        'car_rental' => 'Car Rental',
        'airport_transfer' => 'Airport Taxi',
        'properties' => 'Properties',
        'back_to_booking' => 'Back to Booking'
    ]
];

$t = $translations[$lang];

// Récupérer les données de réservation
$booking_type = $_GET['type'] ?? 'car';
$service_id = $_GET['id'] ?? 1;

// Données simulées pour démonstration
$booking_data = [
    'reference' => $_GET['ref'] ?? 'TH' . strtoupper(uniqid()),
    'type' => $booking_type,
    'service_id' => $service_id,
    'pickup_location' => $_GET['pickup'] ?? 'Aéroport International de Dakar',
    'dropoff_location' => $_GET['dropoff'] ?? 'Dakar, Plateau',
    'pickup_date' => $_GET['pickup_date'] ?? date('Y-m-d', strtotime('+2 days')),
    'pickup_time' => $_GET['pickup_time'] ?? '10:00',
    'passengers' => $_GET['passengers'] ?? 2,
    'service_price' => $booking_type === 'car' ? 25000 : 15000,
    'insurance' => 2000,
    'processing_fee' => 500,
    'total_price' => 0
];

// Calculer le prix total
$booking_data['total_price'] = $booking_data['service_price'] + $booking_data['insurance'] + $booking_data['processing_fee'];

// Détails du service
if ($booking_type === 'car') {
    $service_details = [
        'name' => 'Toyota Camry',
        'type' => 'Berline',
        'image' => 'https://images.unsplash.com/photo-1549394010-8946b1efad3a?w=400&h=250&fit=crop'
    ];
} else {
    $service_details = [
        'name' => 'Taxi Aéroport Standard',
        'type' => 'Transfert Privé',
        'image' => 'https://images.unsplash.com/photo-1556987582-3c022d4d4f5?w=400&h=250&fit=crop'
    ];
}

// Traitement du paiement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? 'card';
    
    // Simuler le traitement du paiement
    $payment_success = true; // Simuler succès
    
    if ($payment_success) {
        // Rediriger vers la page de succès
        header('Location: payment_success.php?ref=' . $booking_data['reference'] . '&type=' . $booking_type);
        exit;
    } else {
        // Rediriger vers la page d'échec
        header('Location: payment_failed.php?ref=' . $booking_data['reference'] . '&type=' . $booking_type);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['site_title'] ?></title>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --booking-blue: #003580;
            --booking-light-blue: #0071c2;
            --booking-dark: #2d3748;
            --booking-gray: #4a5568;
            --booking-light-gray: #f7fafc;
            --booking-border: #e2e8f0;
            --booking-orange: #febb02;
            --booking-shadow: rgba(0,0,0,0.1);
            --success-green: #28a745;
            --danger-red: #dc3545;
            --warning-yellow: #ffc107;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--booking-light-gray);
            color: var(--booking-dark);
            line-height: 1.6;
        }
        
        /* Header */
        .booking-header {
            background: white;
            border-bottom: 1px solid var(--booking-border);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        
        .header-container {
            margin: 0 auto;
            padding: 0 32px;
        }
        
        .header-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            height: 64px;
            max-width: 1180px;
            margin: 0 auto;
        }
        
        .logo-booking {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--booking-blue);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: color 0.2s ease;
            white-space: nowrap;
        }
        
        .logo-booking:hover {
            color: var(--booking-light-blue);
        }
        
        .logo-booking i {
            margin-right: 8px;
            font-size: 1.4rem;
        }
        
        .nav-center {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: nowrap;
        }
        
        .nav-item-booking {
            color: var(--booking-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            padding: 6px 10px;
            border-radius: 6px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .nav-item-booking:hover {
            background: var(--booking-light-gray);
            color: var(--booking-blue);
        }
        
        /* Main Content */
        .main-content {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 32px;
        }
        
        /* Payment Header */
        .payment-header {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }
        
        .payment-icon {
            width: 80px;
            height: 80px;
            background: var(--booking-light-gray);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--booking-blue);
            font-size: 2rem;
        }
        
        .payment-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--booking-dark);
            margin-bottom: 8px;
        }
        
        .payment-subtitle {
            font-size: 1rem;
            color: var(--booking-gray);
            margin-bottom: 16px;
        }
        
        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--success-green);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .security-badge i {
            font-size: 1rem;
        }
        
        /* Payment Summary */
        .payment-summary {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--booking-dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .section-title i {
            color: var(--booking-blue);
        }
        
        .booking-info {
            display: flex;
            gap: 20px;
            margin-bottom: 24px;
            padding: 20px;
            background: var(--booking-light-gray);
            border-radius: 12px;
        }
        
        .booking-image {
            width: 100px;
            height: 70px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .booking-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .booking-details {
            flex: 1;
        }
        
        .booking-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--booking-dark);
            margin-bottom: 4px;
        }
        
        .booking-type {
            font-size: 0.9rem;
            color: var(--booking-gray);
            margin-bottom: 8px;
        }
        
        .booking-reference {
            font-size: 0.9rem;
            color: var(--booking-blue);
            font-weight: 600;
        }
        
        /* Price Breakdown */
        .price-breakdown {
            margin-bottom: 24px;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--booking-border);
        }
        
        .price-row:last-child {
            border-bottom: none;
            padding-top: 16px;
            margin-top: 8px;
            border-top: 2px solid var(--booking-border);
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--booking-blue);
        }
        
        .price-label {
            color: var(--booking-gray);
            font-weight: 500;
        }
        
        .price-value {
            color: var(--booking-dark);
            font-weight: 600;
        }
        
        /* Payment Methods */
        .payment-methods {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .method-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--booking-border);
        }
        
        .method-tab {
            padding: 12px 24px;
            background: transparent;
            border: none;
            color: var(--booking-gray);
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }
        
        .method-tab.active {
            color: var(--booking-blue);
            border-bottom-color: var(--booking-blue);
            background: var(--booking-light-gray);
        }
        
        .method-tab:hover {
            color: var(--booking-blue);
            background: var(--booking-light-gray);
        }
        
        .method-content {
            display: none;
        }
        
        .method-content.active {
            display: block;
        }
        
        /* Card Payment Form */
        .card-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--booking-dark);
            font-size: 0.9rem;
        }
        
        .form-input {
            padding: 12px 16px;
            border: 2px solid var(--booking-border);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--booking-blue);
            box-shadow: 0 0 0 3px rgba(0,53,128,0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 16px;
        }
        
        /* Mobile Money Form */
        .mobile-form {
            text-align: center;
            padding: 20px;
        }
        
        .mobile-options {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .mobile-option {
            text-align: center;
            padding: 20px;
            border: 2px solid var(--booking-border);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 120px;
        }
        
        .mobile-option:hover {
            border-color: var(--booking-blue);
            background: var(--booking-light-gray);
        }
        
        .mobile-option.selected {
            border-color: var(--booking-blue);
            background: var(--booking-light-blue);
            color: white;
        }
        
        .mobile-icon {
            font-size: 2rem;
            margin-bottom: 8px;
        }
        
        .mobile-name {
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .mobile-number {
            font-size: 0.9rem;
            color: var(--booking-gray);
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 16px;
            margin-top: 32px;
        }
        
        .btn-primary {
            flex: 1;
            background: var(--booking-blue);
            color: white;
            border: none;
            padding: 16px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            text-align: center;
        }
        
        .btn-primary:hover {
            background: var(--booking-light-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,53,128,0.3);
        }
        
        .btn-secondary {
            flex: 1;
            background: white;
            color: var(--booking-gray);
            border: 2px solid var(--booking-border);
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            text-align: center;
        }
        
        .btn-secondary:hover {
            border-color: var(--booking-gray);
            color: var(--booking-dark);
        }
        
        /* Accepted Cards */
        .accepted-cards {
            text-align: center;
            margin-top: 24px;
            padding: 20px;
            background: var(--booking-light-gray);
            border-radius: 12px;
        }
        
        .cards-title {
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--booking-gray);
        }
        
        .cards-list {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .card-icon {
            width: 50px;
            height: 32px;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--booking-gray);
            border: 1px solid var(--booking-border);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 0 16px;
                margin: 20px auto;
            }
            
            .payment-header {
                padding: 24px;
            }
            
            .payment-title {
                font-size: 1.5rem;
            }
            
            .booking-info {
                flex-direction: column;
                text-align: center;
            }
            
            .card-form {
                grid-template-columns: 1fr;
            }
            
            .mobile-options {
                flex-direction: column;
                align-items: center;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .method-tabs {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="booking-header">
        <div class="header-container">
            <div class="header-main">
                <a href="accueil_booking_fixed.php" class="logo-booking">
                    <i class="fas fa-home"></i>
                    TerangaHomes
                </a>
                
                <nav class="nav-center">
                    <a href="annonces_direct_fixed.php" class="nav-item-booking"><?= $t['properties'] ?></a>
                    <a href="car_rental.php" class="nav-item-booking">
                        <i class="fas fa-car" style="margin-right: 4px;"></i>
                        <?= $t['car_rental'] ?>
                    </a>
                    <a href="airport_transfer.php" class="nav-item-booking">
                        <i class="fas fa-plane" style="margin-right: 4px;"></i>
                        <?= $t['airport_transfer'] ?>
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Payment Header -->
        <div class="payment-header">
            <div class="payment-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h1 class="payment-title"><?= $t['secure_payment'] ?></h1>
            <p class="payment-subtitle"><?= $t['payment_summary'] ?></p>
            <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                <?= $t['secure_ssl'] ?>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="payment-summary">
            <h2 class="section-title">
                <i class="fas fa-receipt"></i>
                <?= $t['payment_summary'] ?>
            </h2>
            
            <div class="booking-info">
                <div class="booking-image">
                    <img src="<?= $service_details['image'] ?>" alt="<?= $service_details['name'] ?>">
                </div>
                <div class="booking-details">
                    <h3 class="booking-name"><?= $service_details['name'] ?></h3>
                    <p class="booking-type"><?= $service_details['type'] ?></p>
                    <p class="booking-reference"><?= $t['booking_reference'] ?>: <?= $booking_data['reference'] ?></p>
                </div>
            </div>
            
            <div class="price-breakdown">
                <div class="price-row">
                    <span class="price-label"><?= $t['service_price'] ?></span>
                    <span class="price-value"><?= number_format($booking_data['service_price'], 0, ',', ' ') ?> FCFA</span>
                </div>
                <div class="price-row">
                    <span class="price-label"><?= $t['insurance'] ?></span>
                    <span class="price-value"><?= number_format($booking_data['insurance'], 0, ',', ' ') ?> FCFA</span>
                </div>
                <div class="price-row">
                    <span class="price-label"><?= $t['processing_fee'] ?></span>
                    <span class="price-value"><?= number_format($booking_data['processing_fee'], 0, ',', ' ') ?> FCFA</span>
                </div>
                <div class="price-row">
                    <span class="price-label"><?= $t['total_amount'] ?></span>
                    <span class="price-value"><?= number_format($booking_data['total_price'], 0, ',', ' ') ?> FCFA</span>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="payment-methods">
            <h2 class="section-title">
                <i class="fas fa-credit-card"></i>
                <?= $t['payment_method'] ?>
            </h2>
            
            <div class="method-tabs">
                <button class="method-tab active" onclick="showMethod('card')">
                    <i class="fas fa-credit-card"></i> <?= $t['card_payment'] ?>
                </button>
                <button class="method-tab" onclick="showMethod('mobile')">
                    <i class="fas fa-mobile-alt"></i> <?= $t['mobile_money'] ?>
                </button>
                <button class="method-tab" onclick="showMethod('bank')">
                    <i class="fas fa-university"></i> <?= $t['bank_transfer'] ?>
                </button>
            </div>
            
            <!-- Card Payment -->
            <div id="card-method" class="method-content active">
                <form method="POST" class="card-form">
                    <input type="hidden" name="payment_method" value="card">
                    
                    <div class="form-group">
                        <label class="form-label"><?= $t['cardholder_name'] ?></label>
                        <input type="text" name="cardholder_name" class="form-input" placeholder="John Doe" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><?= $t['card_number'] ?></label>
                        <input type="text" name="card_number" class="form-input" placeholder="1234 5678 9012 3456" maxlength="19" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><?= $t['expiry_date'] ?></label>
                            <input type="text" name="expiry_date" class="form-input" placeholder="MM/YY" maxlength="5" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?= $t['cvv'] ?></label>
                            <input type="text" name="cvv" class="form-input" placeholder="123" maxlength="4" required>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Mobile Money -->
            <div id="mobile-method" class="method-content">
                <div class="mobile-form">
                    <div class="mobile-options">
                        <div class="mobile-option" onclick="selectMobile('orange')">
                            <div class="mobile-icon" style="color: #ff6600;">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="mobile-name"><?= $t['orange_money'] ?></div>
                            <div class="mobile-number">#144#</div>
                        </div>
                        
                        <div class="mobile-option" onclick="selectMobile('wave')">
                            <div class="mobile-icon" style="color: #00d4aa;">
                                <i class="fas fa-wave-square"></i>
                            </div>
                            <div class="mobile-name"><?= $t['wave_money'] ?></div>
                            <div class="mobile-number">#301#</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><?= $t['phone_number'] ?></label>
                        <input type="tel" name="phone_number" class="form-input" placeholder="+221 77 123 45 67" style="max-width: 300px; margin: 0 auto;">
                    </div>
                </div>
            </div>
            
            <!-- Bank Transfer -->
            <div id="bank-method" class="method-content">
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-university" style="font-size: 3rem; color: var(--booking-gray); margin-bottom: 16px;"></i>
                    <h3 style="margin-bottom: 8px;"><?= $t['bank_transfer'] ?></h3>
                    <p style="color: var(--booking-gray); max-width: 400px; margin: 0 auto;">
                        <?= $lang === 'fr' ? 'Veuillez effectuer un virement bancaire vers notre compte et nous enverrons la confirmation dans les 24 heures.' : 'Please make a bank transfer to our account and we will send confirmation within 24 hours.' ?>
                    </p>
                    <div style="margin-top: 20px; padding: 20px; background: var(--booking-light-gray); border-radius: 8px; text-align: left;">
                        <p><strong><?= $lang === 'fr' ? 'Banque:' : 'Bank:' ?></strong> BCI</p>
                        <p><strong><?= $lang === 'fr' ? 'Compte:' : 'Account:' ?></strong> 001234567890</p>
                        <p><strong><?= $lang === 'fr' ? 'Référence:' : 'Reference:' ?></strong> <?= $booking_data['reference'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="booking_confirmation.php?ref=<?= $booking_data['reference'] ?>&type=<?= $booking_type ?>" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> <?= $t['back_to_booking'] ?>
            </a>
            <button type="submit" form="card-method" class="btn-primary">
                <i class="fas fa-lock"></i> <?= $t['complete_payment'] ?>
            </button>
        </div>

        <!-- Accepted Cards -->
        <div class="accepted-cards">
            <h3 class="cards-title"><?= $t['accepted_cards'] ?></h3>
            <div class="cards-list">
                <div class="card-icon">
                    <i class="fab fa-cc-visa"></i>
                </div>
                <div class="card-icon">
                    <i class="fab fa-cc-mastercard"></i>
                </div>
                <div class="card-icon">
                    <i class="fab fa-cc-amex"></i>
                </div>
                <div class="card-icon">
                    <i class="fab fa-cc-discover"></i>
                </div>
            </div>
        </div>
    </main>

    <script>
        function showMethod(method) {
            // Hide all method contents
            document.querySelectorAll('.method-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.method-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected method
            document.getElementById(method + '-method').classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }
        
        function selectMobile(provider) {
            // Remove selected class from all options
            document.querySelectorAll('.mobile-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.target.closest('.mobile-option').classList.add('selected');
        }
        
        // Format card number
        document.querySelector('input[name="card_number"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
        
        // Format expiry date
        document.querySelector('input[name="expiry_date"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });
        
        // Only allow numbers for CVV
        document.querySelector('input[name="cvv"]').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    </script>
</body>
</html>
