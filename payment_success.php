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
        'site_title' => 'TerangaHomes - Paiement Réussi',
        'payment_successful' => 'Paiement Réussi !',
        'thank_you' => 'Merci pour votre paiement',
        'booking_confirmed' => 'Votre réservation est confirmée',
        'booking_reference' => 'Référence de réservation',
        'amount_paid' => 'Montant payé',
        'payment_method' => 'Méthode de paiement',
        'transaction_id' => 'ID de transaction',
        'payment_date' => 'Date de paiement',
        'next_steps' => 'Prochaines étapes',
        'confirmation_email' => 'Email de confirmation envoyé',
        'download_receipt' => 'Télécharger le reçu',
        'view_booking' => 'Voir la réservation',
        'back_to_home' => 'Retour à l\'accueil',
        'contact_support' => 'Contacter le support',
        'car_rental' => 'Location Voiture',
        'airport_transfer' => 'Taxi Aéroport',
        'properties' => 'Propriétés',
        'card_payment' => 'Carte bancaire',
        'mobile_money' => 'Mobile Money',
        'bank_transfer' => 'Virement bancaire',
        '24_support' => 'Support 24/7',
        'instant_confirmation' => 'Confirmation instantanée',
        'secure_payment' => 'Paiement sécurisé'
    ],
    'en' => [
        'site_title' => 'TerangaHomes - Payment Successful',
        'payment_successful' => 'Payment Successful!',
        'thank_you' => 'Thank you for your payment',
        'booking_confirmed' => 'Your booking is confirmed',
        'booking_reference' => 'Booking Reference',
        'amount_paid' => 'Amount Paid',
        'payment_method' => 'Payment Method',
        'transaction_id' => 'Transaction ID',
        'payment_date' => 'Payment Date',
        'next_steps' => 'Next Steps',
        'confirmation_email' => 'Confirmation email sent',
        'download_receipt' => 'Download Receipt',
        'view_booking' => 'View Booking',
        'back_to_home' => 'Back to Home',
        'contact_support' => 'Contact Support',
        'car_rental' => 'Car Rental',
        'airport_transfer' => 'Airport Taxi',
        'properties' => 'Properties',
        'card_payment' => 'Credit Card',
        'mobile_money' => 'Mobile Money',
        'bank_transfer' => 'Bank Transfer',
        '24_support' => '24/7 Support',
        'instant_confirmation' => 'Instant Confirmation',
        'secure_payment' => 'Secure Payment'
    ]
];

$t = $translations[$lang];

// Récupérer les données de paiement
$booking_reference = $_GET['ref'] ?? 'TH' . strtoupper(uniqid());
$booking_type = $_GET['type'] ?? 'car';
$transaction_id = 'TXN' . strtoupper(uniqid());
$amount_paid = $_GET['amount'] ?? 27500;
$payment_method = $_GET['method'] ?? 'card';

// Données de réservation
$booking_data = [
    'reference' => $booking_reference,
    'type' => $booking_type,
    'transaction_id' => $transaction_id,
    'amount' => $amount_paid,
    'payment_method' => $payment_method,
    'payment_date' => date('Y-m-d H:i:s'),
    'status' => 'completed'
];

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
            --success-light: #d4edda;
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
        
        /* Success Banner */
        .success-banner {
            background: linear-gradient(135deg, var(--success-green) 0%, #20c933 100%);
            color: white;
            padding: 60px 40px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 40px;
            box-shadow: 0 8px 32px rgba(40,167,69,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .success-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="success-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23success-pattern)"/></svg>');
            opacity: 0.3;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        
        .success-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            animation: checkmark 0.8s ease-in-out;
        }
        
        @keyframes checkmark {
            0% { transform: scale(0) rotate(45deg); opacity: 0; }
            50% { transform: scale(1.2) rotate(45deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        
        .success-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }
        
        .success-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        
        .success-message {
            font-size: 1rem;
            opacity: 0.8;
            position: relative;
            z-index: 1;
        }
        
        /* Payment Card */
        .payment-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin-bottom: 32px;
        }
        
        .payment-header {
            background: var(--booking-blue);
            color: white;
            padding: 32px;
            position: relative;
        }
        
        .payment-amount {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .payment-currency {
            font-size: 1.2rem;
            opacity: 0.8;
        }
        
        .payment-status {
            position: absolute;
            top: 32px;
            right: 32px;
            background: var(--success-light);
            color: var(--success-green);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            border: 2px solid var(--success-green);
        }
        
        .payment-content {
            padding: 32px;
        }
        
        .transaction-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 20px;
            background: var(--booking-light-gray);
            border-radius: 12px;
        }
        
        .detail-icon {
            width: 48px;
            height: 48px;
            background: var(--booking-blue);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 12px;
        }
        
        .detail-label {
            font-size: 0.9rem;
            color: var(--booking-gray);
            font-weight: 500;
            margin-bottom: 4px;
        }
        
        .detail-value {
            font-size: 1.1rem;
            color: var(--booking-dark);
            font-weight: 600;
        }
        
        /* Service Info */
        .service-info {
            display: flex;
            gap: 24px;
            margin-bottom: 32px;
            padding: 24px;
            background: var(--success-light);
            border-radius: 16px;
            border: 2px solid var(--success-green);
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
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--booking-dark);
            margin-bottom: 8px;
        }
        
        .service-type {
            font-size: 1rem;
            color: var(--booking-gray);
            margin-bottom: 8px;
        }
        
        .service-reference {
            font-size: 0.9rem;
            color: var(--booking-blue);
            font-weight: 600;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }
        
        .btn-primary {
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary:hover {
            background: var(--booking-light-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,53,128,0.3);
        }
        
        .btn-secondary {
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-secondary:hover {
            background: var(--booking-blue);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,53,128,0.3);
        }
        
        .btn-success {
            background: var(--success-green);
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40,167,69,0.3);
        }
        
        /* Info Section */
        .info-section {
            text-align: center;
            padding: 32px;
            background: var(--booking-light-gray);
            border-radius: 16px;
            margin-bottom: 32px;
        }
        
        .info-icon {
            font-size: 2.5rem;
            color: var(--booking-blue);
            margin-bottom: 16px;
        }
        
        .info-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--booking-dark);
            margin-bottom: 8px;
        }
        
        .info-text {
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
                padding: 40px 24px;
            }
            
            .success-title {
                font-size: 2rem;
            }
            
            .payment-amount {
                font-size: 2.5rem;
            }
            
            .transaction-details {
                grid-template-columns: 1fr;
            }
            
            .service-info {
                flex-direction: column;
                text-align: center;
            }
            
            .action-buttons {
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
            <h1 class="success-title"><?= $t['payment_successful'] ?></h1>
            <p class="success-subtitle"><?= $t['thank_you'] ?></p>
            <p class="success-message"><?= $t['booking_confirmed'] ?></p>
        </div>

        <!-- Payment Card -->
        <div class="payment-card">
            <div class="payment-header">
                <div>
                    <div class="payment-amount"><?= number_format($booking_data['amount'], 0, ',', ' ') ?></div>
                    <div class="payment-currency">FCFA</div>
                </div>
                <div class="payment-status">
                    <i class="fas fa-check"></i> <?= $t['instant_confirmation'] ?>
                </div>
            </div>
            
            <div class="payment-content">
                <!-- Transaction Details -->
                <div class="transaction-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div>
                            <div class="detail-label"><?= $t['transaction_id'] ?></div>
                            <div class="detail-value"><?= $booking_data['transaction_id'] ?></div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div>
                            <div class="detail-label"><?= $t['payment_method'] ?></div>
                            <div class="detail-value">
                                <?php
                                switch($booking_data['payment_method']) {
                                    case 'card': echo $t['card_payment']; break;
                                    case 'mobile': echo $t['mobile_money']; break;
                                    case 'bank': echo $t['bank_transfer']; break;
                                    default: echo $t['card_payment'];
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div>
                            <div class="detail-label"><?= $t['payment_date'] ?></div>
                            <div class="detail-value"><?= date('d/m/Y H:i', strtotime($booking_data['payment_date'])) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Service Info -->
                <div class="service-info">
                    <div class="service-image">
                        <img src="<?= $service_details['image'] ?>" alt="<?= $service_details['name'] ?>">
                    </div>
                    <div class="service-details">
                        <h3 class="service-name"><?= $service_details['name'] ?></h3>
                        <p class="service-type"><?= $service_details['type'] ?></p>
                        <p class="service-reference"><?= $t['booking_reference'] ?>: <?= $booking_data['reference'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="#" class="btn-success" onclick="downloadReceipt()">
                <i class="fas fa-download"></i>
                <?= $t['download_receipt'] ?>
            </a>
            <a href="booking_confirmation.php?ref=<?= $booking_data['reference'] ?>&type=<?= $booking_type ?>" class="btn-primary">
                <i class="fas fa-eye"></i>
                <?= $t['view_booking'] ?>
            </a>
            <a href="accueil_booking_fixed.php" class="btn-secondary">
                <i class="fas fa-home"></i>
                <?= $t['back_to_home'] ?>
            </a>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <h3 class="info-title"><?= $t['confirmation_email'] ?></h3>
            <p class="info-text">
                <?= $lang === 'fr' ? 'Un email de confirmation avec tous les détails de votre réservation et votre reçu a été envoyé à votre adresse email.' : 'A confirmation email with all booking details and your receipt has been sent to your email address.' ?>
            </p>
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

    <script>
        function downloadReceipt() {
            // Simuler le téléchargement du reçu
            const receiptData = {
                reference: '<?= $booking_data['reference'] ?>',
                transactionId: '<?= $booking_data['transaction_id'] ?>',
                amount: '<?= $booking_data['amount'] ?>',
                currency: 'FCFA',
                paymentMethod: '<?= $booking_data['payment_method'] ?>',
                paymentDate: '<?= $booking_data['payment_date'] ?>',
                service: '<?= $service_details['name'] ?>'
            };
            
            // Créer un fichier JSON pour le reçu
            const dataStr = JSON.stringify(receiptData, null, 2);
            const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
            
            const exportFileDefaultName = `receipt_${receiptData.reference}.json`;
            
            const linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
        }
        
        // Animation de confettis
        function createConfetti() {
            const colors = ['#28a745', '#0071c2', '#febb02', '#dc3545'];
            const confettiCount = 50;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.width = '10px';
                confetti.style.height = '10px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.top = '-10px';
                confetti.style.borderRadius = '50%';
                confetti.style.zIndex = '9999';
                confetti.style.pointerEvents = 'none';
                
                document.body.appendChild(confetti);
                
                // Animation
                const duration = Math.random() * 3 + 2;
                const horizontalMovement = (Math.random() - 0.5) * 200;
                
                confetti.animate([
                    { transform: 'translateY(0) translateX(0) rotate(0deg)', opacity: 1 },
                    { transform: `translateY(100vh) translateX(${horizontalMovement}px) rotate(${Math.random() * 720}deg)`, opacity: 0 }
                ], {
                    duration: duration * 1000,
                    easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
                }).onfinish = () => confetti.remove();
            }
        }
        
        // Lancer les confettis après le chargement de la page
        window.addEventListener('load', () => {
            setTimeout(createConfetti, 500);
        });
    </script>
</body>
</html>
