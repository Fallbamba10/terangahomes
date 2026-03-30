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
        'site_title' => 'TerangaHomes - Taxi Aéroport au Sénégal',
        'tagline' => 'Réservez votre taxi aéroport au Sénégal',
        'search_placeholder' => 'Destination, hôtel, adresse...',
        'pickup_location' => 'Lieu de prise en charge',
        'dropoff_location' => 'Lieu de destination',
        'pickup_date' => 'Date de prise en charge',
        'pickup_time' => 'Heure de prise en charge',
        'passengers' => 'Nombre de passagers',
        'luggage' => 'Bagages',
        'search_btn' => 'Rechercher',
        'car_rental' => 'Location Voiture',
        'airport_transfer' => 'Taxi Aéroport',
        'properties' => 'Propriétés',
        'login' => 'Se connecter',
        'register' => "S'inscrire",
        'help' => 'Aide',
        'currency' => 'Devise',
        'language' => 'Langue',
        'available_services' => 'Services disponibles',
        'instant_booking' => 'Réservation instantanée',
        'fixed_price' => 'Prix fixe',
        'professional_drivers' => 'Chauffeurs professionnels',
        '24_7_service' => 'Service 24/7',
        'book_now' => 'Réserver maintenant',
        'view_details' => 'Voir détails',
        'filter_by_service' => 'Filtrer par service',
        'filter_by_price' => 'Filtrer par prix',
        'all_services' => 'Tous les services',
        'airport_taxi' => 'Taxi Aéroport',
        'private_transfer' => 'Transfert Privé',
        'shuttle_service' => 'Service Navette',
        'luxury_transfer' => 'Transfert Luxe',
        'price_low_to_high' => 'Prix croissant',
        'price_high_to_low' => 'Prix décroissant',
        'most_popular' => 'Plus populaires',
        'no_services_found' => 'Aucun service trouvé',
        'try_different_filters' => 'Essayez des filtres différents',
        '24_support' => 'Support 24/7',
        'best_price_guaranteed' => 'Meilleur prix garanti',
        'free_cancellation' => 'Annulation gratuite',
        'flight_tracking' => 'Suivi de vol',
        'meet_greet_service' => 'Service Accueil',
        'child_seat_available' => 'Siège enfant disponible'
    ],
    'en' => [
        'site_title' => 'TerangaHomes - Airport Taxi in Senegal',
        'tagline' => 'Book your airport taxi in Senegal',
        'search_placeholder' => 'Destination, hotel, address...',
        'pickup_location' => 'Pickup Location',
        'dropoff_location' => 'Drop-off Location',
        'pickup_date' => 'Pickup Date',
        'pickup_time' => 'Pickup Time',
        'passengers' => 'Number of Passengers',
        'luggage' => 'Luggage',
        'search_btn' => 'Search',
        'car_rental' => 'Car Rental',
        'airport_transfer' => 'Airport Taxi',
        'properties' => 'Properties',
        'login' => 'Sign in',
        'register' => 'Register',
        'help' => 'Help',
        'currency' => 'Currency',
        'language' => 'Language',
        'available_services' => 'Available Services',
        'instant_booking' => 'Instant Booking',
        'fixed_price' => 'Fixed Price',
        'professional_drivers' => 'Professional Drivers',
        '24_7_service' => '24/7 Service',
        'book_now' => 'Book Now',
        'view_details' => 'View Details',
        'filter_by_service' => 'Filter by Service',
        'filter_by_price' => 'Filter by Price',
        'all_services' => 'All Services',
        'airport_taxi' => 'Airport Taxi',
        'private_transfer' => 'Private Transfer',
        'shuttle_service' => 'Shuttle Service',
        'luxury_transfer' => 'Luxury Transfer',
        'price_low_to_high' => 'Price Low to High',
        'price_high_to_low' => 'Price High to Low',
        'most_popular' => 'Most Popular',
        'no_services_found' => 'No services found',
        'try_different_filters' => 'Try different filters',
        '24_support' => '24/7 Support',
        'best_price_guaranteed' => 'Best Price Guaranteed',
        'free_cancellation' => 'Free Cancellation',
        'flight_tracking' => 'Flight Tracking',
        'meet_greet_service' => 'Meet & Greet Service',
        'child_seat_available' => 'Child Seat Available'
    ]
];

$t = $translations[$lang];

// Services disponibles (données simulées pour démonstration)
$available_services = [
    [
        'id' => 1,
        'type' => 'airport_taxi',
        'name' => 'Taxi Aéroport Standard',
        'description' => 'Service de taxi standard entre l\'aéroport et votre destination',
        'pickup_airport' => 'Aéroport International de Dakar (DKR)',
        'dropoff_locations' => ['Dakar', 'Saly', 'Mbour', 'Saint-Louis'],
        'fixed_price' => 15000,
        'capacity' => '1-4 passagers',
        'luggage' => '2-4 bagages',
        'duration' => '30-90 min selon trafic',
        'features' => ['climatisation', 'gps_tracking', 'professional_driver'],
        'available' => true,
        'rating' => 4.7,
        'reviews' => 234,
        'image' => 'https://images.unsplash.com/photo-1556987582-3c022d4d4f5?w=400&h=250&fit=crop'
    ],
    [
        'id' => 2,
        'type' => 'private_transfer',
        'name' => 'Transfert Privé Premium',
        'description' => 'Véhicule privé avec chauffeur personnel',
        'pickup_airport' => 'Tous aéroports sénégalais',
        'dropoff_locations' => ['Dakar', 'Thiès', 'Kaolack', 'Ziguinchor'],
        'fixed_price' => 35000,
        'capacity' => '1-8 passagers',
        'luggage' => 'Jusqu\'à 8 bagages',
        'duration' => 'Service direct, pas d\'attente',
        'features' => ['vehicule_luxe', 'wifi', 'bouteilles_eau', 'charge_usb'],
        'available' => true,
        'rating' => 4.9,
        'reviews' => 189,
        'image' => 'https://images.unsplash.com/photo-1550355247-6a8a531a1c6c?w=400&h=250&fit=crop'
    ],
    [
        'id' => 3,
        'type' => 'shuttle_service',
        'name' => 'Service Navette Partagée',
        'description' => 'Navette partagée économique pour groupes et familles',
        'pickup_airport' => 'Aéroport International de Dakar (DKR)',
        'dropoff_locations' => ['Dakar Centre', 'Plateau', 'Almadies', 'Ouakam'],
        'fixed_price' => 8000,
        'capacity' => '1-12 passagers',
        'luggage' => '1-2 bagages par personne',
        'duration' => '45-90 min avec arrêts multiples',
        'features' => ['economique', 'climatisation', 'assurance_incluse'],
        'available' => true,
        'rating' => 4.5,
        'reviews' => 156,
        'image' => 'https://images.unsplash.com/photo-1542312567-b07e54358753?w=400&h=250&fit=crop'
    ],
    [
        'id' => 4,
        'type' => 'luxury_transfer',
        'name' => 'Transfert Luxe VIP',
        'description' => 'Service premium avec véhicules haut de gamme',
        'pickup_airport' => 'Tous aéroports internationaux',
        'dropoff_locations' => ['Hôtels 5 étoiles', 'Résidences VIP', 'Centres d\'affaires'],
        'fixed_price' => 75000,
        'capacity' => '1-4 passagers',
        'luggage' => 'Bagages illimités',
        'duration' => 'Service prioritaire, attente zéro',
        'features' => ['vehicule_luxe', 'champagne', 'wifi_premium', 'chauffeur_vip'],
        'available' => true,
        'rating' => 4.9,
        'reviews' => 98,
        'image' => 'https://images.unsplash.com/photo-1555212697-194d092e3b8f?w=400&h=250&fit=crop'
    ],
    [
        'id' => 5,
        'type' => 'airport_taxi',
        'name' => 'Taxi Aéroport Nuit',
        'description' => 'Service de taxi disponible 24/7 pour vols de nuit',
        'pickup_airport' => 'Aéroport International de Dakar (DKR)',
        'dropoff_locations' => ['Toutes destinations', 'Service disponible 24/7'],
        'fixed_price' => 20000,
        'capacity' => '1-4 passagers',
        'luggage' => '2-4 bagages',
        'duration' => 'Service rapide de nuit',
        'features' => ['disponibilite_24_7', 'eclairage_securite', 'gps_tracking'],
        'available' => true,
        'rating' => 4.6,
        'reviews' => 87,
        'image' => 'https://images.unsplash.com/photo-1571019672146-4350658f003f?w=400&h=250&fit=crop'
    ],
    [
        'id' => 6,
        'type' => 'private_transfer',
        'name' => 'Transfert Familial',
        'description' => 'Service familial avec sièges enfants et équipements',
        'pickup_airport' => 'Tous aéroports sénégalais',
        'dropoff_locations' => ['Écoles', 'Hôpitaux', 'Centres commerciaux', 'Domiciles'],
        'fixed_price' => 25000,
        'capacity' => '1-6 passagers',
        'luggage' => 'Jusqu\'à 6 bagages + équipements enfants',
        'duration' => 'Service adapté aux familles',
        'features' => ['sieges_enfants', 'portable_divertissement', 'snacks', 'flexibilite'],
        'available' => true,
        'rating' => 4.8,
        'reviews' => 145,
        'image' => 'https://images.unsplash.com/photo-1549394010-8946b1efad3a?w=400&h=250&fit=crop'
    ]
];

// Filtrage
$type_filter = $_GET['type'] ?? 'all';
$price_filter = $_GET['price'] ?? 'default';

if ($type_filter !== 'all') {
    $available_services = array_filter($available_services, function($service) use ($type_filter) {
        return $service['type'] === $type_filter;
    });
}

// Tri
switch ($price_filter) {
    case 'low_high':
        usort($available_services, function($a, $b) {
            return $a['fixed_price'] - $b['fixed_price'];
        });
        break;
    case 'high_low':
        usort($available_services, function($a, $b) {
            return $b['fixed_price'] - $a['fixed_price'];
        });
        break;
    case 'popular':
        usort($available_services, function($a, $b) {
            return $b['reviews'] - $a['reviews'];
        });
        break;
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
        
        /* Header Booking Style */
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
        
        .nav-item-booking.active {
            background: var(--booking-light-blue);
            color: white;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .language-currency-selector {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .selector-dropdown {
            padding: 6px 10px;
            border: 1px solid var(--booking-border);
            border-radius: 6px;
            background: white;
            font-size: 12px;
            cursor: pointer;
        }
        
        .header-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
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
        
        .header-btn-primary {
            background: var(--booking-blue);
            color: white;
            border-color: var(--booking-blue);
        }
        
        /* Hero Section */
        .hero-booking {
            background: linear-gradient(135deg, var(--booking-blue) 0%, var(--booking-light-blue) 50%, #004a99 100%);
            padding: 40px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-booking::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="booking-pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="0" cy="0" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="40" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23booking-pattern)"/></svg>');
            opacity: 0.3;
        }
        
        .hero-content {
            margin: 0 auto;
            padding: 0 32px;
            text-align: center;
            color: white;
            max-width: 1180px;
            position: relative;
            z-index: 1;
        }
        
        .hero-title {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
            margin-bottom: 24px;
            opacity: 0.95;
        }
        
        /* Search Box - Booking Style */
        .search-box-booking {
            background: white;
            border-radius: 8px;
            padding: 12px;
            box-shadow: var(--booking-shadow);
            max-width: 900px;
            margin: 0 auto;
            border: 2px solid var(--booking-blue);
            position: relative;
        }
        
        .search-box-booking::before {
            display: none;
        }
        
        .search-box-booking::after {
            display: none;
        }
        
        .search-form-booking {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
            gap: 8px;
            align-items: end;
        }
        
        .form-group-booking {
            display: flex;
            flex-direction: column;
        }
        
        .form-label-booking {
            font-size: 10px;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--booking-dark);
        }
        
        .form-control-booking {
            padding: 8px 12px;
            border: 1px solid var(--booking-border);
            border-radius: 4px;
            font-size: 13px;
            transition: all 0.2s ease;
        }
        
        .form-control-booking:focus {
            outline: none;
            border-color: var(--booking-blue);
            box-shadow: 0 0 0 2px rgba(0,53,128,0.1);
        }
        
        .btn-search-booking {
            background: var(--booking-blue);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            align-self: flex-end;
            height: fit-content;
        }
        
        .btn-search-booking:hover {
            background: var(--booking-light-blue);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,53,128,0.3);
        }
        
        .btn-search-booking:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(0,53,128,0.2);
        }
        
        /* Filters Section */
        .filters-section {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid var(--booking-border);
        }
        
        .filters-container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .filter-label {
            font-weight: 500;
            font-size: 14px;
            color: var(--booking-gray);
        }
        
        .filter-select {
            padding: 6px 12px;
            border: 1px solid var(--booking-border);
            border-radius: 6px;
            background: white;
            font-size: 14px;
            cursor: pointer;
            min-width: 120px;
        }
        
        /* Services Section */
        .services-section {
            padding: 30px 0;
            background: white;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 0 32px;
        }
        
        .section-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--booking-dark);
        }
        
        .section-subtitle {
            font-size: 1rem;
            color: var(--booking-gray);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.5;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 24px;
            margin: 0 auto;
            padding: 0 32px;
            max-width: 1180px;
        }
        
        .service-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid var(--booking-border);
        }
        
        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border-color: var(--booking-blue);
        }
        
        .service-image {
            height: 180px;
            position: relative;
            overflow: hidden;
        }
        
        .service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .service-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: var(--booking-orange);
            color: var(--booking-dark);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .service-content {
            padding: 20px;
        }
        
        .service-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
        }
        
        .service-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--booking-dark);
            margin-bottom: 4px;
        }
        
        .service-subtitle {
            font-size: 14px;
            color: var(--booking-gray);
            margin-bottom: 8px;
        }
        
        .service-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
        }
        
        .service-rating i {
            color: #ffc107;
        }
        
        .service-description {
            font-size: 14px;
            color: var(--booking-gray);
            line-height: 1.5;
            margin-bottom: 16px;
        }
        
        .service-specs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 16px;
        }
        
        .spec-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--booking-gray);
        }
        
        .spec-item i {
            color: var(--booking-blue);
            font-size: 14px;
        }
        
        .service-features {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }
        
        .feature-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            background: var(--booking-light-gray);
            border-radius: 12px;
            font-size: 12px;
            color: var(--booking-gray);
        }
        
        .feature-tag i {
            color: var(--booking-blue);
            font-size: 12px;
        }
        
        .service-pricing {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .price-info {
            display: flex;
            flex-direction: column;
        }
        
        .price-main {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--booking-blue);
        }
        
        .price-period {
            font-size: 13px;
            color: var(--booking-gray);
        }
        
        .price-secondary {
            font-size: 13px;
            color: var(--booking-gray);
        }
        
        .service-actions {
            display: flex;
            gap: 8px;
        }
        
        .btn-book {
            flex: 1;
            padding: 10px 16px;
            background: var(--booking-blue);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            text-align: center;
        }
        
        .btn-book:hover {
            background: var(--booking-light-blue);
            transform: translateY(-1px);
        }
        
        .btn-details {
            padding: 10px 16px;
            background: white;
            color: var(--booking-blue);
            border: 1px solid var(--booking-blue);
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            text-align: center;
        }
        
        .btn-details:hover {
            background: var(--booking-blue);
            color: white;
        }
        
        /* Footer */
        .footer-booking {
            background: var(--booking-dark);
            color: white;
            padding: 40px 0 20px;
        }
        
        .footer-content {
            margin: 0 auto;
            padding: 0 32px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            max-width: 1180px;
        }
        
        .footer-section h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s ease;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 30px;
            padding-top: 20px;
            text-align: center;
            font-size: 14px;
            color: rgba(255,255,255,0.6);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-main {
                padding: 12px 0;
            }
            
            .nav-center {
                display: none;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .search-form-booking {
                grid-template-columns: 1fr;
            }
            
            .filters-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
                padding: 0 16px;
            }
            
            .service-pricing {
                flex-direction: column;
                gap: 12px;
            }
            
            .service-actions {
                flex-direction: column;
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
                    <a href="airport_transfer.php" class="nav-item-booking active">
                        <i class="fas fa-plane" style="margin-right: 4px;"></i>
                        <?= $t['airport_transfer'] ?>
                    </a>
                </nav>
                
                <div class="header-right">
                    <div class="language-currency-selector">
                        <form method="POST" style="display: inline;">
                            <select name="lang" class="selector-dropdown" onchange="this.form.submit()">
                                <?php foreach ($supported_langs as $code => $name): ?>
                                    <option value="<?= $code ?>" <?= $lang === $code ? 'selected' : '' ?>>
                                        <?= match($code) {
                                            'fr' => '🇸🇳',
                                            'en' => '🇬🇧',
                                            'es' => '🇪🇸',
                                            'ar' => '🇸🇦',
                                            'zh' => '🇨🇳',
                                            'pt' => '🇵🇹',
                                            default => '🌐'
                                        } ?> <?= $name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    
                    <div class="header-buttons">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="dashboard.php" class="header-btn">
                                <i class="fas fa-user"></i>
                            </a>
                            <a href="logout.php" class="header-btn">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        <?php else: ?>
                            <a href="connexion_simple.php" class="header-btn"><?= $t['login'] ?></a>
                            <a href="connexion_simple.php" class="header-btn header-btn-primary"><?= $t['register'] ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-booking">
        <div class="hero-content">
            <h1 class="hero-title"><?= $t['tagline'] ?></h1>
            <p class="hero-subtitle"><?= count($available_services) ?>+ <?= $t['available_services'] ?> • <?= $t['best_price_guaranteed'] ?></p>
            
            </div>
    </section>

    <!-- Search Box -->
    <section style="margin: -40px auto 80px; position: relative; z-index: 10;">
        <div class="search-box-booking">
                <form class="search-form-booking" method="GET">
                    <div class="form-group-booking">
                        <label class="form-label-booking"><?= $t['pickup_location'] ?></label>
                        <input type="text" name="pickup" class="form-control-booking" placeholder="Aéroport, Hôtel, Adresse..." required>
                    </div>
                    
                    <div class="form-group-booking">
                        <label class="form-label-booking"><?= $t['dropoff_location'] ?></label>
                        <input type="text" name="dropoff" class="form-control-booking" placeholder="Dakar, Saly, Mbour...">
                    </div>
                    
                    <div class="form-group-booking">
                        <label class="form-label-booking"><?= $t['pickup_date'] ?></label>
                        <input type="date" name="pickup_date" class="form-control-booking" required>
                    </div>
                    
                    <div class="form-group-booking">
                        <label class="form-label-booking"><?= $t['pickup_time'] ?></label>
                        <input type="time" name="pickup_time" class="form-control-booking" value="10:00">
                    </div>
                    
                    <div class="form-group-booking">
                        <label class="form-label-booking"><?= $t['passengers'] ?></label>
                        <select name="passengers" class="form-control-booking">
                            <option value="1">1 passager</option>
                            <option value="2">2 passagers</option>
                            <option value="3">3 passagers</option>
                            <option value="4">4 passagers</option>
                            <option value="5">5 passagers</option>
                            <option value="6">6 passagers</option>
                            <option value="7">7 passagers</option>
                            <option value="8">8+ passagers</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-search-booking">
                        <i class="fas fa-search"></i> <?= $t['search_btn'] ?>
                    </button>
                </form>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section">
        <div class="filters-container">
            <div class="filter-group">
                <span class="filter-label"><?= $t['filter_by_service'] ?>:</span>
                <select name="type" class="filter-select" onchange="window.location.href='?type=' + this.value">
                    <option value="all"><?= $t['all_services'] ?></option>
                    <option value="airport_taxi" <?= $type_filter === 'airport_taxi' ? 'selected' : '' ?>><?= $t['airport_taxi'] ?></option>
                    <option value="private_transfer" <?= $type_filter === 'private_transfer' ? 'selected' : '' ?>><?= $t['private_transfer'] ?></option>
                    <option value="shuttle_service" <?= $type_filter === 'shuttle_service' ? 'selected' : '' ?>><?= $t['shuttle_service'] ?></option>
                    <option value="luxury_transfer" <?= $type_filter === 'luxury_transfer' ? 'selected' : '' ?>><?= $t['luxury_transfer'] ?></option>
                </select>
            </div>
            
            <div class="filter-group">
                <span class="filter-label"><?= $t['filter_by_price'] ?>:</span>
                <select name="price" class="filter-select" onchange="window.location.href='?price=' + this.value">
                    <option value="default"><?= $t['most_popular'] ?></option>
                    <option value="low_high" <?= $price_filter === 'low_high' ? 'selected' : '' ?>><?= $t['price_low_to_high'] ?></option>
                    <option value="high_low" <?= $price_filter === 'high_low' ? 'selected' : '' ?>><?= $t['price_high_to_low'] ?></option>
                </select>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="section-header">
            <h2 class="section-title"><?= $t['available_services'] ?></h2>
            <p class="section-subtitle"><?= $t['professional_drivers'] ?> • <?= $t['24_7_service'] ?> • <?= $t['best_price_guaranteed'] ?></p>
        </div>
        
        <?php if (empty($available_services)): ?>
            <div style="text-align: center; padding: 60px 32px; color: var(--booking-gray);">
                <i class="fas fa-plane" style="font-size: 3rem; margin-bottom: 16px; opacity: 0.5;"></i>
                <h3 style="margin-bottom: 8px;"><?= $t['no_services_found'] ?></h3>
                <p><?= $t['try_different_filters'] ?></p>
            </div>
        <?php else: ?>
            <div class="services-grid">
                <?php foreach ($available_services as $service): ?>
                    <div class="service-card">
                        <div class="service-image">
                            <img src="<?= $service['image'] ?>" alt="<?= $service['name'] ?>">
                            <span class="service-badge"><?= $t[$service['type']] ?></span>
                        </div>
                        
                        <div class="service-content">
                            <div class="service-header">
                                <div>
                                    <h3 class="service-title"><?= $service['name'] ?></h3>
                                    <p class="service-subtitle"><?= $service['pickup_airport'] ?> • <?= $service['duration'] ?></p>
                                </div>
                                <div class="service-rating">
                                    <i class="fas fa-star"></i>
                                    <span><?= $service['rating'] ?></span>
                                    <span style="color: var(--booking-gray);">(<?= $service['reviews'] ?>)</span>
                                </div>
                            </div>
                            
                            <p class="service-description"><?= $service['description'] ?></p>
                            
                            <div class="service-specs">
                                <div class="spec-item">
                                    <i class="fas fa-users"></i>
                                    <span><?= $service['capacity'] ?></span>
                                </div>
                                <div class="spec-item">
                                    <i class="fas fa-suitcase"></i>
                                    <span><?= $service['luggage'] ?></span>
                                </div>
                            </div>
                            
                            <div class="service-features">
                                <?php foreach ($service['features'] as $feature): ?>
                                    <span class="feature-tag">
                                        <?php
                                        $iconClass = 'wifi';
                                        if ($feature === 'climatisation') $iconClass = 'snowflake';
                                        elseif ($feature === 'gps_tracking') $iconClass = 'map-marker-alt';
                                        elseif ($feature === 'vehicule_luxe') $iconClass = 'gem';
                                        elseif ($feature === 'bouteilles_eau') $iconClass = 'tint';
                                        elseif ($feature === 'charge_usb') $iconClass = 'usb';
                                        elseif ($feature === 'sieges_enfants') $iconClass = 'child';
                                        elseif ($feature === 'portable_divertissement') $iconClass = 'gamepad';
                                        elseif ($feature === 'snacks') $iconClass = 'cookie';
                                        elseif ($feature === 'flexibilite') $iconClass = 'clock';
                                        elseif ($feature === 'disponibilite_24_7') $iconClass = 'shield-alt';
                                        elseif ($feature === 'eclairage_securite') $iconClass = 'lightbulb';
                                        elseif ($feature === 'champagne') $iconClass = 'glass-cheers';
                                        ?>
                                        <i class="fas fa-<?= $iconClass ?>"></i>
                                        <?= $t[$feature] ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="service-pricing">
                                <div class="price-info">
                                    <span class="price-main"><?= number_format($service['fixed_price'], 0, ',', ' ') ?> FCFA</span>
                                    <span class="price-period"><?= $t['fixed_price'] ?></span>
                                </div>
                            </div>
                            
                            <div class="service-actions">
                                <a href="#" class="btn-book" onclick="bookService(<?= $service['id'] ?>)">
                                    <i class="fas fa-calendar-check"></i> <?= $t['book_now'] ?>
                                </a>
                                <a href="#" class="btn-details" onclick="viewDetails(<?= $service['id'] ?>)">
                                    <?= $t['view_details'] ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <footer class="footer-booking">
        <div class="footer-content">
            <div class="footer-section">
                <h4>TerangaHomes</h4>
                <p style="line-height: 1.6; opacity: 0.9; margin-bottom: 20px;">
                    <?= $lang === 'fr' ? 'Votre plateforme de confiance pour les services de transport aéroport au Sénégal.' : 'Your trusted platform for airport transport services in Senegal.' ?>
                </p>
                <div style="display: flex; gap: 16px; margin-top: 20px;">
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h4><?= $t['airport_transfer'] ?></h4>
                <ul class="footer-links">
                    <li><a href="#"><?= $lang === 'fr' ? 'Tous les services' : 'All services' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Taxi standard' : 'Standard taxi' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Transferts privés' : 'Private transfers' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Service VIP' : 'VIP service' ?></a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4><?= $lang === 'fr' ? 'Services' : 'Services' ?></h4>
                <ul class="footer-links">
                    <li><a href="accueil_booking_fixed.php"><?= $t['properties'] ?></a></li>
                    <li><a href="car_rental.php"><?= $t['car_rental'] ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Assistance 24/7' : '24/7 Support' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Suivi des vols' : 'Flight tracking' ?></a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4><?= $lang === 'fr' ? 'Contact' : 'Contact' ?></h4>
                <ul class="footer-links">
                    <li><i class="fas fa-phone" style="margin-right: 8px;"></i>+221 78 600 00 28</li>
                    <li><i class="fas fa-envelope" style="margin-right: 8px;"></i>contact@terangahomes.com</li>
                    <li><i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i>Dakar, Sénégal</li>
                    <li><i class="fas fa-clock" style="margin-right: 8px;"></i>24/7 <?= $t['24_support'] ?></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> TerangaHomes. <?= $lang === 'fr' ? 'Tous droits réservés.' : 'All rights reserved.' ?></p>
        </div>
    </footer>

    <script>
        // Fonctions de réservation
        function bookService(serviceId) {
            if (!<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>) {
                if (confirm('<?= $lang === 'fr' ? 'Vous devez être connecté pour réserver. Voulez-vous vous connecter maintenant ?' : 'You must be logged in to book. Do you want to login now?' ?>')) {
                    window.location.href = 'connexion_simple.php';
                }
                return;
            }
            
            // Rediriger vers la page de réservation
            window.location.href = `booking_confirmation.php?type=airport&id=${serviceId}`;
        }
        
        function viewDetails(serviceId) {
            // Ouvrir modal ou rediriger vers la page de détails
            window.location.href = `service_details.php?id=${serviceId}`;
        }
        
        // Validation des dates
        document.addEventListener('DOMContentLoaded', function() {
            const pickupDate = document.querySelector('input[name="pickup_date"]');
            const pickupTime = document.querySelector('input[name="pickup_time"]');
            
            // Définir la date minimale à aujourd'hui
            const today = new Date().toISOString().split('T')[0];
            pickupDate.min = today;
            
            // Définir l'heure minimale à l'heure actuelle
            const now = new Date();
            const minTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            pickupTime.min = minTime;
        });
    </script>
</body>
</html>
