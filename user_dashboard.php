<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
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
        'site_title' => 'TerangaHomes - Mon Tableau de Bord',
        'welcome_back' => 'Bienvenue de retour',
        'dashboard' => 'Tableau de Bord',
        'my_bookings' => 'Mes Réservations',
        'my_profile' => 'Mon Profil',
        'settings' => 'Paramètres',
        'logout' => 'Déconnexion',
        'upcoming_bookings' => 'Réservations à Venir',
        'past_bookings' => 'Réservations Passées',
        'booking_reference' => 'Référence',
        'service_type' => 'Type de Service',
        'pickup_location' => 'Lieu de Prise en Charge',
        'dropoff_location' => 'Lieu de Destination',
        'date' => 'Date',
        'time' => 'Heure',
        'amount' => 'Montant',
        'status' => 'Statut',
        'confirmed' => 'Confirmé',
        'completed' => 'Terminé',
        'cancelled' => 'Annulé',
        'pending' => 'En Attente',
        'view_details' => 'Voir Détails',
        'manage_booking' => 'Gérer la Réservation',
        'download_invoice' => 'Télécharger Facture',
        'total_spent' => 'Total Dépensé',
        'total_bookings' => 'Total Réservations',
        'recent_activity' => 'Activité Récente',
        'quick_actions' => 'Actions Rapides',
        'book_new_service' => 'Réserver un Nouveau Service',
        'contact_support' => 'Contacter le Support',
        'edit_profile' => 'Modifier le Profil',
        'change_password' => 'Changer le Mot de Passe',
        'notification_settings' => 'Paramètres de Notification',
        'privacy_settings' => 'Paramètres de Confidentialité',
        'car_rental' => 'Location Voiture',
        'airport_transfer' => 'Taxi Aéroport',
        'properties' => 'Propriétés',
        'no_bookings' => 'Aucune réservation trouvée',
        'all_bookings' => 'Toutes les réservations',
        'upcoming' => 'À Venir',
        'past' => 'Passées',
        'cancelled_bookings' => 'Annulées',
        'booking_history' => 'Historique des Réservations',
        'account_info' => 'Informations du Compte',
        'personal_info' => 'Informations Personnelles',
        'contact_info' => 'Informations de Contact',
        'security_settings' => 'Paramètres de Sécurité',
        '24_support' => 'Support 24/7',
        'help_center' => 'Centre d\'Aide'
    ],
    'en' => [
        'site_title' => 'TerangaHomes - My Dashboard',
        'welcome_back' => 'Welcome Back',
        'dashboard' => 'Dashboard',
        'my_bookings' => 'My Bookings',
        'my_profile' => 'My Profile',
        'settings' => 'Settings',
        'logout' => 'Logout',
        'upcoming_bookings' => 'Upcoming Bookings',
        'past_bookings' => 'Past Bookings',
        'booking_reference' => 'Reference',
        'service_type' => 'Service Type',
        'pickup_location' => 'Pickup Location',
        'dropoff_location' => 'Drop-off Location',
        'date' => 'Date',
        'time' => 'Time',
        'amount' => 'Amount',
        'status' => 'Status',
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'pending' => 'Pending',
        'view_details' => 'View Details',
        'manage_booking' => 'Manage Booking',
        'download_invoice' => 'Download Invoice',
        'total_spent' => 'Total Spent',
        'total_bookings' => 'Total Bookings',
        'recent_activity' => 'Recent Activity',
        'quick_actions' => 'Quick Actions',
        'book_new_service' => 'Book New Service',
        'contact_support' => 'Contact Support',
        'edit_profile' => 'Edit Profile',
        'change_password' => 'Change Password',
        'notification_settings' => 'Notification Settings',
        'privacy_settings' => 'Privacy Settings',
        'car_rental' => 'Car Rental',
        'airport_transfer' => 'Airport Taxi',
        'properties' => 'Properties',
        'no_bookings' => 'No bookings found',
        'all_bookings' => 'All Bookings',
        'upcoming' => 'Upcoming',
        'past' => 'Past',
        'cancelled_bookings' => 'Cancelled',
        'booking_history' => 'Booking History',
        'account_info' => 'Account Information',
        'personal_info' => 'Personal Information',
        'contact_info' => 'Contact Information',
        'security_settings' => 'Security Settings',
        '24_support' => '24/7 Support',
        'help_center' => 'Help Center'
    ]
];

$t = $translations[$lang];

// Données utilisateur simulées
$user_data = [
    'name' => 'Moussa Fall',
    'email' => 'moussa.fall@terangahomes.com',
    'phone' => '+221 77 123 45 67',
    'join_date' => '2024-01-15',
    'avatar' => 'https://images.unsplash.com/photo-1472099645785-a5a4b427679a?w=150&h=150&fit=crop&crop=faces',
    'member_since' => 'Member since January 2024'
];

// Réservations simulées
$user_bookings = [
    [
        'id' => 1,
        'reference' => 'TH2024ABC123',
        'type' => 'car_rental',
        'service' => 'Toyota Camry',
        'pickup' => 'Aéroport International de Dakar',
        'dropoff' => 'Dakar, Plateau',
        'date' => '2024-03-25',
        'time' => '10:00',
        'amount' => 25000,
        'status' => 'confirmed',
        'image' => 'https://images.unsplash.com/photo-1549394010-8946b1efad3a?w=400&h=250&fit=crop'
    ],
    [
        'id' => 2,
        'reference' => 'TH2024DEF456',
        'type' => 'airport_transfer',
        'service' => 'Taxi Aéroport Standard',
        'pickup' => 'Aéroport International de Dakar',
        'dropoff' => 'Saly',
        'date' => '2024-03-20',
        'time' => '14:30',
        'amount' => 15000,
        'status' => 'completed',
        'image' => 'https://images.unsplash.com/photo-1556987582-3c022d4d4f5?w=400&h=250&fit=crop'
    ],
    [
        'id' => 3,
        'reference' => 'TH2024GHI789',
        'type' => 'car_rental',
        'service' => 'Nissan X-Trail',
        'pickup' => 'Dakar, Almadies',
        'dropoff' => 'Mbour',
        'date' => '2024-03-15',
        'time' => '09:00',
        'amount' => 35000,
        'status' => 'cancelled',
        'image' => 'https://images.unsplash.com/photo-1542362567-b07e54358753?w=400&h=250&fit=crop'
    ]
];

// Filtrer les réservations
$booking_filter = $_GET['filter'] ?? 'all';
$filtered_bookings = $user_bookings;

if ($booking_filter !== 'all') {
    $filtered_bookings = array_filter($user_bookings, function($booking) use ($booking_filter) {
        if ($booking_filter === 'upcoming') {
            return in_array($booking['status'], ['confirmed', 'pending']);
        } elseif ($booking_filter === 'past') {
            return $booking['status'] === 'completed';
        } elseif ($booking_filter === 'cancelled') {
            return $booking['status'] === 'cancelled';
        }
        return true;
    });
}

// Calculer les statistiques
$total_bookings = count($user_bookings);
$total_spent = array_sum(array_column($user_bookings, 'amount'));
$upcoming_bookings = count(array_filter($user_bookings, function($booking) {
    return in_array($booking['status'], ['confirmed', 'pending']);
}));
$completed_bookings = count(array_filter($user_bookings, function($booking) {
    return $booking['status'] === 'completed';
}));
$cancelled_bookings = count(array_filter($user_bookings, function($booking) {
    return $booking['status'] === 'cancelled';
}));
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
        .dashboard-header {
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
        
        .nav-item-booking.active {
            background: var(--booking-light-blue);
            color: white;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--booking-blue);
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: var(--booking-dark);
        }
        
        .user-email {
            font-size: 12px;
            color: var(--booking-gray);
        }
        
        .user-actions {
            display: flex;
            gap: 8px;
        }
        
        .header-btn {
            padding: 6px 12px;
            border: 1px solid var(--booking-border);
            border-radius: 6px;
            background: white;
            color: var(--booking-dark);
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .header-btn:hover {
            border-color: var(--booking-blue);
            color: var(--booking-blue);
        }
        
        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px;
        }
        
        /* Welcome Section */
        .welcome-section {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        
        .welcome-header {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 24px;
        }
        
        .welcome-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--booking-blue);
        }
        
        .welcome-info {
            flex: 1;
        }
        
        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--booking-dark);
            margin-bottom: 4px;
        }
        
        .welcome-subtitle {
            font-size: 1rem;
            color: var(--booking-gray);
            margin-bottom: 8px;
        }
        
        .welcome-stats {
            display: flex;
            gap: 32px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--booking-blue);
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--booking-gray);
        }
        
        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 32px;
            margin-bottom: 32px;
        }
        
        /* Bookings Section */
        .bookings-section {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--booking-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .section-title i {
            color: var(--booking-blue);
        }
        
        .filter-tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid var(--booking-border);
        }
        
        .filter-tab {
            padding: 12px 20px;
            background: transparent;
            border: none;
            color: var(--booking-gray);
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }
        
        .filter-tab.active {
            color: var(--booking-blue);
            border-bottom-color: var(--booking-blue);
            background: var(--booking-light-gray);
        }
        
        .filter-tab:hover {
            color: var(--booking-blue);
            background: var(--booking-light-gray);
        }
        
        /* Booking Cards */
        .bookings-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .booking-card {
            display: flex;
            gap: 16px;
            padding: 20px;
            background: var(--booking-light-gray);
            border-radius: 12px;
            border: 1px solid var(--booking-border);
            transition: all 0.2s ease;
        }
        
        .booking-card:hover {
            border-color: var(--booking-blue);
            box-shadow: 0 2px 8px rgba(0,53,128,0.1);
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
        
        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 8px;
        }
        
        .booking-service {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--booking-dark);
            margin-bottom: 4px;
        }
        
        .booking-reference {
            font-size: 0.9rem;
            color: var(--booking-blue);
            font-weight: 600;
        }
        
        .booking-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 12px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
            color: var(--booking-gray);
        }
        
        .info-item i {
            color: var(--booking-blue);
            font-size: 0.9rem;
        }
        
        .booking-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .booking-amount {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--booking-blue);
        }
        
        .booking-status {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-confirmed {
            background: var(--success-green);
            color: white;
        }
        
        .status-completed {
            background: var(--booking-light-blue);
            color: white;
        }
        
        .status-cancelled {
            background: var(--danger-red);
            color: white;
        }
        
        .status-pending {
            background: var(--warning-yellow);
            color: var(--booking-dark);
        }
        
        .booking-actions {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            padding: 6px 12px;
            border: 1px solid var(--booking-border);
            border-radius: 6px;
            background: white;
            color: var(--booking-blue);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            background: var(--booking-blue);
            color: white;
        }
        
        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        
        .quick-actions-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        
        .quick-actions-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--booking-dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .quick-actions-title i {
            color: var(--booking-blue);
        }
        
        .action-buttons-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .action-button {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: white;
            border: 2px solid var(--booking-border);
            border-radius: 12px;
            text-decoration: none;
            color: var(--booking-dark);
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .action-button:hover {
            border-color: var(--booking-blue);
            color: var(--booking-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,53,128,0.1);
        }
        
        .action-button i {
            font-size: 1.2rem;
            color: var(--booking-blue);
        }
        
        .action-button-text {
            flex: 1;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--booking-gray);
        }
        
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        
        .empty-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--booking-dark);
        }
        
        .empty-text {
            font-size: 1rem;
            margin-bottom: 24px;
        }
        
        .empty-action {
            padding: 12px 24px;
            background: var(--booking-blue);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .empty-action:hover {
            background: var(--booking-light-blue);
            transform: translateY(-2px);
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }
            
            .welcome-header {
                flex-direction: column;
                text-align: center;
            }
            
            .welcome-stats {
                justify-content: center;
            }
            
            .filter-tabs {
                flex-wrap: wrap;
            }
            
            .booking-card {
                flex-direction: column;
            }
            
            .booking-info {
                grid-template-columns: 1fr;
            }
            
            .booking-footer {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }
            
            .booking-actions {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="dashboard-header">
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
                
                <div class="header-right">
                    <div class="user-menu">
                        <img src="<?= $user_data['avatar'] ?>" alt="User Avatar" class="user-avatar">
                        <div class="user-info">
                            <div class="user-name"><?= $user_data['name'] ?></div>
                            <div class="user-email"><?= $user_data['email'] ?></div>
                        </div>
                    </div>
                    <div class="user-actions">
                        <a href="user_dashboard.php" class="header-btn">
                            <i class="fas fa-th-large"></i>
                        </a>
                        <a href="#" class="header-btn">
                            <i class="fas fa-bell"></i>
                        </a>
                        <a href="connexion_simple.php?logout=1" class="header-btn">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-header">
                <img src="<?= $user_data['avatar'] ?>" alt="User Avatar" class="welcome-avatar">
                <div class="welcome-info">
                    <h1 class="welcome-title"><?= $t['welcome_back'] ?>, <?= $user_data['name'] ?>!</h1>
                    <p class="welcome-subtitle"><?= $user_data['member_since'] ?></p>
                    <div class="welcome-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?= $total_bookings ?></div>
                            <div class="stat-label"><?= $t['total_bookings'] ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= number_format($total_spent, 0, ',', ' ') ?> FCFA</div>
                            <div class="stat-label"><?= $t['total_spent'] ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= $upcoming_bookings ?></div>
                            <div class="stat-label"><?= $t['upcoming'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Bookings Section -->
            <div class="bookings-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-calendar-alt"></i>
                        <?= $t['my_bookings'] ?>
                    </h2>
                    <div class="filter-tabs">
                        <button class="filter-tab <?= $booking_filter === 'all' ? 'active' : '' ?>" onclick="window.location.href='?filter=all'">
                            <?= $t['all_bookings'] ?> (<?= $total_bookings ?>)
                        </button>
                        <button class="filter-tab <?= $booking_filter === 'upcoming' ? 'active' : '' ?>" onclick="window.location.href='?filter=upcoming'">
                            <?= $t['upcoming'] ?> (<?= $upcoming_bookings ?>)
                        </button>
                        <button class="filter-tab <?= $booking_filter === 'past' ? 'active' : '' ?>" onclick="window.location.href='?filter=past'">
                            <?= $t['past'] ?> (<?= $completed_bookings ?>)
                        </button>
                        <button class="filter-tab <?= $booking_filter === 'cancelled' ? 'active' : '' ?>" onclick="window.location.href='?filter=cancelled'">
                            <?= $t['cancelled_bookings'] ?> (<?= $cancelled_bookings ?>)
                        </button>
                    </div>
                </div>
                
                <?php if (empty($filtered_bookings)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h3 class="empty-title"><?= $t['no_bookings'] ?></h3>
                        <p class="empty-text"><?= $lang === 'fr' ? 'Vous n\'avez aucune réservation dans cette catégorie.' : 'You have no bookings in this category.' ?></p>
                        <a href="car_rental.php" class="empty-action">
                            <i class="fas fa-plus"></i> <?= $t['book_new_service'] ?>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="bookings-list">
                        <?php foreach ($filtered_bookings as $booking): ?>
                            <div class="booking-card">
                                <div class="booking-image">
                                    <img src="<?= $booking['image'] ?>" alt="<?= $booking['service'] ?>">
                                </div>
                                <div class="booking-details">
                                    <div class="booking-header">
                                        <div>
                                            <h3 class="booking-service"><?= $booking['service'] ?></h3>
                                            <p class="booking-reference"><?= $t['booking_reference'] ?>: <?= $booking['reference'] ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="booking-info">
                                        <div class="info-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?= $t['pickup_location'] ?>: <?= $booking['pickup'] ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-flag-checkered"></i>
                                            <span><?= $t['dropoff_location'] ?>: <?= $booking['dropoff'] ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-calendar"></i>
                                            <span><?= $t['date'] ?>: <?= date('d/m/Y', strtotime($booking['date'])) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?= $t['time'] ?>: <?= $booking['time'] ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="booking-footer">
                                        <div class="booking-amount"><?= number_format($booking['amount'], 0, ',', ' ') ?> FCFA</div>
                                        <div class="booking-status status-<?= $booking['status'] ?>">
                                            <?= $t[$booking['status']] ?>
                                        </div>
                                    </div>
                                    
                                    <div class="booking-actions">
                                        <a href="#" class="action-btn">
                                            <i class="fas fa-eye"></i> <?= $t['view_details'] ?>
                                        </a>
                                        <a href="#" class="action-btn">
                                            <i class="fas fa-download"></i> <?= $t['download_invoice'] ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Quick Actions -->
                <div class="quick-actions-card">
                    <h3 class="quick-actions-title">
                        <i class="fas fa-bolt"></i>
                        <?= $t['quick_actions'] ?>
                    </h3>
                    <div class="action-buttons-list">
                        <a href="car_rental.php" class="action-button">
                            <i class="fas fa-car"></i>
                            <span class="action-button-text"><?= $t['car_rental'] ?></span>
                        </a>
                        <a href="airport_transfer.php" class="action-button">
                            <i class="fas fa-plane"></i>
                            <span class="action-button-text"><?= $t['airport_transfer'] ?></span>
                        </a>
                        <a href="annonces_direct_fixed.php" class="action-button">
                            <i class="fas fa-home"></i>
                            <span class="action-button-text"><?= $t['properties'] ?></span>
                        </a>
                    </div>
                </div>
                
                <!-- Account Settings -->
                <div class="quick-actions-card">
                    <h3 class="quick-actions-title">
                        <i class="fas fa-cog"></i>
                        <?= $t['settings'] ?>
                    </h3>
                    <div class="action-buttons-list">
                        <a href="#" class="action-button">
                            <i class="fas fa-user"></i>
                            <span class="action-button-text"><?= $t['edit_profile'] ?></span>
                        </a>
                        <a href="#" class="action-button">
                            <i class="fas fa-lock"></i>
                            <span class="action-button-text"><?= $t['change_password'] ?></span>
                        </a>
                        <a href="#" class="action-button">
                            <i class="fas fa-bell"></i>
                            <span class="action-button-text"><?= $t['notification_settings'] ?></span>
                        </a>
                    </div>
                </div>
                
                <!-- Support -->
                <div class="quick-actions-card">
                    <h3 class="quick-actions-title">
                        <i class="fas fa-headset"></i>
                        <?= $t['help_center'] ?>
                    </h3>
                    <div class="action-buttons-list">
                        <a href="#" class="action-button">
                            <i class="fas fa-phone"></i>
                            <span class="action-button-text"><?= $t['contact_support'] ?></span>
                        </a>
                        <a href="#" class="action-button">
                            <i class="fas fa-question-circle"></i>
                            <span class="action-button-text"><?= $t['help_center'] ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
