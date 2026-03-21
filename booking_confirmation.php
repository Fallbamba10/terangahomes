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
        'site_title' => 'TerangaHomes - Confirmation de réservation',
        'booking_confirmed' => 'Réservation confirmée !',
        'thank_you' => 'Merci pour votre réservation',
        'booking_reference' => 'Référence de réservation',
        'booking_details' => 'Détails de la réservation',
        'service_type' => 'Type de service',
        'pickup_location' => 'Lieu de prise en charge',
        'dropoff_location' => 'Lieu de destination',
        'pickup_date' => 'Date de prise en charge',
        'pickup_time' => 'Heure de prise en charge',
        'passengers' => 'Nombre de passagers',
        'total_price' => 'Prix total',
        'payment_status' => 'Statut de paiement',
        'payment_pending' => 'Paiement en attente',
        'payment_completed' => 'Paiement complété',
        'next_steps' => 'Prochaines étapes',
        'confirmation_email' => 'Email de confirmation envoyé',
        'driver_contact' => 'Contact du chauffeur',
        'cancellation_policy' => 'Politique d\'annulation',
        'contact_support' => 'Contacter le support',
        'back_to_home' => 'Retour à l\'accueil',
        'view_my_bookings' => 'Voir mes réservations',
        'car_rental' => 'Location Voiture',
        'airport_transfer' => 'Taxi Aéroport',
        'properties' => 'Propriétés',
        'login' => 'Se connecter',
        'register' => "S'inscrire",
        'help' => 'Aide',
        'currency' => 'Devise',
        'language' => 'Langue',
        '24_support' => 'Support 24/7',
        'free_cancellation' => 'Annulation gratuite jusqu\'à 24h avant',
        'professional_driver' => 'Chauffeur professionnel',
        'insurance_included' => 'Assurance incluse',
        'instant_confirmation' => 'Confirmation instantanée'
    ],
    'en' => [
        'site_title' => 'TerangaHomes - Booking Confirmation',
        'booking_confirmed' => 'Booking Confirmed!',
        'thank_you' => 'Thank you for your booking',
        'booking_reference' => 'Booking Reference',
        'booking_details' => 'Booking Details',
        'service_type' => 'Service Type',
        'pickup_location' => 'Pickup Location',
        'dropoff_location' => 'Drop-off Location',
        'pickup_date' => 'Pickup Date',
        'pickup_time' => 'Pickup Time',
        'passengers' => 'Number of Passengers',
        'total_price' => 'Total Price',
        'payment_status' => 'Payment Status',
        'payment_pending' => 'Payment Pending',
        'payment_completed' => 'Payment Completed',
        'next_steps' => 'Next Steps',
        'confirmation_email' => 'Confirmation email sent',
        'driver_contact' => 'Driver Contact',
        'cancellation_policy' => 'Cancellation Policy',
        'contact_support' => 'Contact Support',
        'back_to_home' => 'Back to Home',
        'view_my_bookings' => 'View My Bookings',
        'car_rental' => 'Car Rental',
        'airport_transfer' => 'Airport Taxi',
        'properties' => 'Properties',
        'login' => 'Sign in',
        'register' => 'Register',
        'help' => 'Help',
        'currency' => 'Currency',
        'language' => 'Language',
        '24_support' => '24/7 Support',
        'free_cancellation' => 'Free cancellation up to 24h before',
        'professional_driver' => 'Professional Driver',
        'insurance_included' => 'Insurance Included',
        'instant_confirmation' => 'Instant Confirmation'
    ]
];

$t = $translations[$lang];

// Récupérer les données de réservation
$booking_type = $_GET['type'] ?? 'car';
$service_id = $_GET['id'] ?? 1;

// Données simulées pour démonstration
$booking_data = [
    'reference' => 'TH' . strtoupper(uniqid()),
    'type' => $booking_type,
    'service_id' => $service_id,
    'pickup_location' => $_GET['pickup'] ?? 'Aéroport International de Dakar',
    'dropoff_location' => $_GET['dropoff'] ?? 'Dakar, Plateau',
    'pickup_date' => $_GET['pickup_date'] ?? date('Y-m-d', strtotime('+2 days')),
    'pickup_time' => $_GET['pickup_time'] ?? '10:00',
    'passengers' => $_GET['passengers'] ?? 2,
    'price' => $booking_type === 'car' ? 25000 : 15000,
    'status' => 'confirmed',
    'created_at' => date('Y-m-d H:i:s')
];

// Détails du service
if ($booking_type === 'car') {
    $service_details = [
        'name' => 'Toyota Camry',
        'type' => 'Berline',
        'features' => ['Climatisation', 'GPS', 'Bluetooth', 'USB'],
        'image' => 'https://images.unsplash.com/photo-1549394010-8946b1efad3a?w=400&h=250&fit=crop'
    ];
} else {
    $service_details = [
        'name' => 'Taxi Aéroport Standard',
        'type' => 'Transfert Privé',
        'features' => ['Chauffeur professionnel', 'Climatisation', 'GPS tracking', 'Assurance'],
        'image' => 'https://images.unsplash.com/photo-1556987582-3c022d4d4f5?w=400&h=250&fit=crop'
    ];
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
            --warning-yellow: #ffc107;
            --danger-red: #dc3545;
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
            max-width: 800px;
            margin: 40px auto;
            padding: 0 32px;
        }
        
        /* Success Message */
        .success-banner {
            background: linear-gradient(135deg, var(--success-green) 0%, #20c933 100%);
            color: white;
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            margin-bottom: 32px;
            box-shadow: 0 8px 32px rgba(40,167,69,0.2);
        }
        
        .success-icon {
            font-size: 4rem;
            margin-bottom: 16px;
            animation: checkmark 0.6s ease-in-out;
        }
        
        @keyframes checkmark {
            0% { transform: scale(0) rotate(45deg); }
            50% { transform: scale(1.2) rotate(45deg); }
            100% { transform: scale(1) rotate(0deg); }
        }
        
        .success-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .success-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* Booking Card */
        .booking-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            margin-bottom: 32px;
        }
        
        .booking-header {
            background: var(--booking-blue);
            color: white;
            padding: 24px;
            position: relative;
        }
        
        .booking-reference {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .booking-date {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .booking-status {
            position: absolute;
            top: 24px;
            right: 24px;
            background: var(--success-green);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .booking-content {
            padding: 32px;
        }
        
        .service-info {
            display: flex;
            gap: 24px;
            margin-bottom: 32px;
            align-items: center;
        }
        
        .service-image {
            width: 120px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .service-details {
            flex: 1;
        }
        
        .service-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--booking-dark);
            margin-bottom: 4px;
        }
        
        .service-type {
            font-size: 0.9rem;
            color: var(--booking-gray);
            margin-bottom: 8px;
        }
        
        .service-features {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .feature-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            background: var(--booking-light-gray);
            border-radius: 12px;
            font-size: 0.8rem;
            color: var(--booking-gray);
        }
        
        .feature-tag i {
            color: var(--booking-blue);
            font-size: 0.8rem;
        }
        
        /* Booking Details */
        .booking-details-section {
            margin-bottom: 32px;
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--booking-dark);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .section-title i {
            color: var(--booking-blue);
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .detail-label {
            font-size: 0.9rem;
            color: var(--booking-gray);
            font-weight: 500;
        }
        
        .detail-value {
            font-size: 1rem;
            color: var(--booking-dark);
            font-weight: 600;
        }
        
        /* Price Section */
        .price-section {
            background: var(--booking-light-gray);
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 32px;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .price-row:last-child {
            margin-bottom: 0;
            padding-top: 16px;
            border-top: 2px solid var(--booking-border);
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--booking-blue);
        }
        
        .price-label {
            color: var(--booking-gray);
        }
        
        .price-value {
            color: var(--booking-dark);
            font-weight: 600;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 16px;
            margin-bottom: 32px;
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
            color: var(--booking-blue);
            border: 2px solid var(--booking-blue);
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
            background: var(--booking-blue);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,53,128,0.3);
        }
        
        /* Info Cards */
        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid var(--booking-border);
        }
        
        .info-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .info-card-icon {
            width: 48px;
            height: 48px;
            background: var(--booking-light-gray);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--booking-blue);
            font-size: 1.2rem;
        }
        
        .info-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--booking-dark);
        }
        
        .info-card-content {
            color: var(--booking-gray);
            line-height: 1.5;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 0 16px;
                margin: 20px auto;
            }
            
            .success-banner {
                padding: 24px;
            }
            
            .success-title {
                font-size: 1.5rem;
            }
            
            .service-info {
                flex-direction: column;
                text-align: center;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .info-cards {
                grid-template-columns: 1fr;
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
        <!-- Success Banner -->
        <div class="success-banner">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title"><?= $t['booking_confirmed'] ?></h1>
            <p class="success-subtitle"><?= $t['thank_you'] ?></p>
        </div>

        <!-- Booking Card -->
        <div class="booking-card">
            <div class="booking-header">
                <div>
                    <div class="booking-reference"><?= $t['booking_reference'] ?>: <?= $booking_data['reference'] ?></div>
                    <div class="booking-date"><?= $booking_data['created_at'] ?></div>
                </div>
                <div class="booking-status"><?= $t['payment_completed'] ?></div>
            </div>
            
            <div class="booking-content">
                <!-- Service Info -->
                <div class="service-info">
                    <div class="service-image">
                        <img src="<?= $service_details['image'] ?>" alt="<?= $service_details['name'] ?>">
                    </div>
                    <div class="service-details">
                        <h3 class="service-name"><?= $service_details['name'] ?></h3>
                        <p class="service-type"><?= $service_details['type'] ?></p>
                        <div class="service-features">
                            <?php foreach ($service_details['features'] as $feature): ?>
                                <span class="feature-tag">
                                    <i class="fas fa-check"></i>
                                    <?= $feature ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="booking-details-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        <?= $t['booking_details'] ?>
                    </h2>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label"><?= $t['service_type'] ?></span>
                            <span class="detail-value"><?= $booking_type === 'car' ? $t['car_rental'] : $t['airport_transfer'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><?= $t['pickup_location'] ?></span>
                            <span class="detail-value"><?= $booking_data['pickup_location'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><?= $t['dropoff_location'] ?></span>
                            <span class="detail-value"><?= $booking_data['dropoff_location'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><?= $t['pickup_date'] ?></span>
                            <span class="detail-value"><?= date('d/m/Y', strtotime($booking_data['pickup_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><?= $t['pickup_time'] ?></span>
                            <span class="detail-value"><?= $booking_data['pickup_time'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><?= $t['passengers'] ?></span>
                            <span class="detail-value"><?= $booking_data['passengers'] ?></span>
                        </div>
                    </div>
                </div>

                <!-- Price Section -->
                <div class="price-section">
                    <div class="price-row">
                        <span class="price-label"><?= $t['service_type'] ?></span>
                        <span class="price-value"><?= number_format($booking_data['price'], 0, ',', ' ') ?> FCFA</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label"><?= $t['insurance_included'] ?></span>
                        <span class="price-value" style="color: var(--success-green);">Inclus</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label"><?= $t['total_price'] ?></span>
                        <span class="price-value"><?= number_format($booking_data['price'], 0, ',', ' ') ?> FCFA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="accueil_booking_fixed.php" class="btn-primary">
                <i class="fas fa-home"></i> <?= $t['back_to_home'] ?>
            </a>
            <a href="#" class="btn-secondary">
                <i class="fas fa-list"></i> <?= $t['view_my_bookings'] ?>
            </a>
        </div>

        <!-- Info Cards -->
        <div class="info-cards">
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="info-card-title"><?= $t['confirmation_email'] ?></h3>
                </div>
                <p class="info-card-content">
                    <?= $lang === 'fr' ? 'Un email de confirmation a été envoyé à votre adresse avec tous les détails de votre réservation.' : 'A confirmation email has been sent to your address with all booking details.' ?>
                </p>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3 class="info-card-title"><?= $t['professional_driver'] ?></h3>
                </div>
                <p class="info-card-content">
                    <?= $lang === 'fr' ? 'Votre chauffeur vous contactera 24h avant votre prise en charge pour confirmer les détails.' : 'Your driver will contact you 24h before pickup to confirm details.' ?>
                </p>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h3 class="info-card-title"><?= $t['cancellation_policy'] ?></h3>
                </div>
                <p class="info-card-content">
                    <?= $t['free_cancellation'] ?>
                </p>
            </div>
        </div>

        <!-- Contact Support -->
        <div style="text-align: center; padding: 32px;">
            <p style="color: var(--booking-gray); margin-bottom: 16px;">
                <?= $lang === 'fr' ? 'Besoin d\'aide ?' : 'Need help?' ?>
            </p>
            <a href="#" class="btn-primary" style="max-width: 300px; margin: 0 auto;">
                <i class="fas fa-headset"></i> <?= $t['contact_support'] ?>
            </a>
        </div>
    </main>
</body>
</html>
