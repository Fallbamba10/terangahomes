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
        'site_title' => 'TerangaHomes - Location Voiture au Sénégal',
        'tagline' => 'Louez votre voiture idéale au Sénégal',
        'search_placeholder' => 'Marque, modèle, ville...',
        'pickup_location' => 'Lieu de prise en charge',
        'dropoff_location' => 'Lieu de restitution',
        'pickup_date' => 'Date de prise en charge',
        'dropoff_date' => 'Date de restitution',
        'pickup_time' => 'Heure de prise en charge',
        'dropoff_time' => 'Heure de restitution',
        'search_btn' => 'Rechercher',
        'car_rental' => 'Location Voiture',
        'airport_transfer' => 'Taxi Aéroport',
        'properties' => 'Propriétés',
        'login' => 'Se connecter',
        'register' => "S'inscrire",
        'help' => 'Aide',
        'currency' => 'Devise',
        'language' => 'Langue',
        'available_cars' => 'Voitures disponibles',
        'daily_price' => 'Prix/jour',
        'weekly_price' => 'Prix/semaine',
        'monthly_price' => 'Prix/mois',
        'automatic' => 'Automatique',
        'manual' => 'Manuelle',
        'fuel_type' => 'Type de carburant',
        'seats' => 'Places',
        'luggage' => 'Bagages',
        'air_conditioning' => 'Climatisation',
        'gps' => 'GPS',
        'bluetooth' => 'Bluetooth',
        'usb_charging' => 'Charge USB',
        'child_seat' => 'Siège enfant',
        'insurance_included' => 'Assurance incluse',
        'unlimited_mileage' => 'Kilométrage illimité',
        'free_cancellation' => 'Annulation gratuite',
        'book_now' => 'Réserver maintenant',
        'view_details' => 'Voir détails',
        'filter_by_type' => 'Filtrer par type',
        'filter_by_price' => 'Filtrer par prix',
        'economy' => 'Économique',
        'compact' => 'Compacte',
        'midsize' => 'Berline',
        'suv' => 'SUV',
        'luxury' => 'Luxe',
        'van' => 'Utilitaire',
        'pickup_truck' => 'Pickup',
        'all_types' => 'Tous les types',
        'price_low_to_high' => 'Prix croissant',
        'price_high_to_low' => 'Prix décroissant',
        'newest_first' => 'Plus récents',
        'most_popular' => 'Plus populaires',
        'no_cars_found' => 'Aucune voiture trouvée',
        'try_different_filters' => 'Essayez des filtres différents',
        '24_support' => 'Support 24/7',
        'best_price_guaranteed' => 'Meilleur prix garanti',
        'free_modification' => 'Modification gratuite'
    ],
    'en' => [
        'site_title' => 'TerangaHomes - Car Rental in Senegal',
        'tagline' => 'Rent your ideal car in Senegal',
        'search_placeholder' => 'Brand, model, city...',
        'pickup_location' => 'Pickup Location',
        'dropoff_location' => 'Drop-off Location',
        'pickup_date' => 'Pickup Date',
        'dropoff_date' => 'Drop-off Date',
        'pickup_time' => 'Pickup Time',
        'dropoff_time' => 'Drop-off Time',
        'search_btn' => 'Search',
        'car_rental' => 'Car Rental',
        'airport_transfer' => 'Airport Taxi',
        'properties' => 'Properties',
        'login' => 'Sign in',
        'register' => 'Register',
        'help' => 'Help',
        'currency' => 'Currency',
        'language' => 'Language',
        'available_cars' => 'Available Cars',
        'daily_price' => 'Daily Price',
        'weekly_price' => 'Weekly Price',
        'monthly_price' => 'Monthly Price',
        'automatic' => 'Automatic',
        'manual' => 'Manual',
        'fuel_type' => 'Fuel Type',
        'seats' => 'Seats',
        'luggage' => 'Luggage',
        'air_conditioning' => 'Air Conditioning',
        'gps' => 'GPS',
        'bluetooth' => 'Bluetooth',
        'usb_charging' => 'USB Charging',
        'child_seat' => 'Child Seat',
        'insurance_included' => 'Insurance Included',
        'unlimited_mileage' => 'Unlimited Mileage',
        'free_cancellation' => 'Free Cancellation',
        'book_now' => 'Book Now',
        'view_details' => 'View Details',
        'filter_by_type' => 'Filter by Type',
        'filter_by_price' => 'Filter by Price',
        'economy' => 'Economy',
        'compact' => 'Compact',
        'midsize' => 'Midsize',
        'suv' => 'SUV',
        'luxury' => 'Luxury',
        'van' => 'Van',
        'pickup_truck' => 'Pickup Truck',
        'all_types' => 'All Types',
        'price_low_to_high' => 'Price Low to High',
        'price_high_to_low' => 'Price High to Low',
        'newest_first' => 'Newest First',
        'most_popular' => 'Most Popular',
        'no_cars_found' => 'No cars found',
        'try_different_filters' => 'Try different filters',
        '24_support' => '24/7 Support',
        'best_price_guaranteed' => 'Best Price Guaranteed',
        'free_modification' => 'Free Modification'
    ]
];

$t = $translations[$lang];

// Voitures disponibles (données simulées pour démonstration)
$available_cars = [
    [
        'id' => 1,
        'brand' => 'Toyota',
        'model' => 'Yaris',
        'type' => 'economy',
        'year' => 2023,
        'transmission' => 'manual',
        'fuel' => 'essence',
        'seats' => 5,
        'luggage' => 2,
        'daily_price' => 15000,
        'weekly_price' => 90000,
        'monthly_price' => 350000,
        'image' => 'https://images.unsplash.com/photo-1550355247-6a8a531a1c6c?w=400&h=250&fit=crop',
        'features' => ['air_conditioning', 'bluetooth', 'usb_charging'],
        'available' => true,
        'rating' => 4.6,
        'reviews' => 124
    ],
    [
        'id' => 2,
        'brand' => 'Hyundai',
        'model' => 'Accent',
        'type' => 'compact',
        'year' => 2023,
        'transmission' => 'automatic',
        'fuel' => 'essence',
        'seats' => 5,
        'luggage' => 3,
        'daily_price' => 18000,
        'weekly_price' => 108000,
        'monthly_price' => 420000,
        'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=400&h=250&fit=crop',
        'features' => ['air_conditioning', 'gps', 'bluetooth', 'usb_charging'],
        'available' => true,
        'rating' => 4.7,
        'reviews' => 89
    ],
    [
        'id' => 3,
        'brand' => 'Toyota',
        'model' => 'Camry',
        'type' => 'midsize',
        'year' => 2023,
        'transmission' => 'automatic',
        'fuel' => 'hybride',
        'seats' => 5,
        'luggage' => 4,
        'daily_price' => 25000,
        'weekly_price' => 150000,
        'monthly_price' => 580000,
        'image' => 'https://images.unsplash.com/photo-1549394010-8946b1efad3a?w=400&h=250&fit=crop',
        'features' => ['air_conditioning', 'gps', 'bluetooth', 'usb_charging', 'child_seat'],
        'available' => true,
        'rating' => 4.8,
        'reviews' => 156
    ],
    [
        'id' => 4,
        'brand' => 'Nissan',
        'model' => 'X-Trail',
        'type' => 'suv',
        'year' => 2023,
        'transmission' => 'automatic',
        'fuel' => 'essence',
        'seats' => 7,
        'luggage' => 5,
        'daily_price' => 35000,
        'weekly_price' => 210000,
        'monthly_price' => 820000,
        'image' => 'https://images.unsplash.com/photo-1542362567-b07e54358753?w=400&h=250&fit=crop',
        'features' => ['air_conditioning', 'gps', 'bluetooth', 'usb_charging', 'child_seat'],
        'available' => true,
        'rating' => 4.7,
        'reviews' => 203
    ],
    [
        'id' => 5,
        'brand' => 'Mercedes',
        'model' => 'E-Class',
        'type' => 'luxury',
        'year' => 2023,
        'transmission' => 'automatic',
        'fuel' => 'essence',
        'seats' => 5,
        'luggage' => 4,
        'daily_price' => 65000,
        'weekly_price' => 390000,
        'monthly_price' => 1500000,
        'image' => 'https://images.unsplash.com/photo-1555212697-194d092e3b8f?w=400&h=250&fit=crop',
        'features' => ['air_conditioning', 'gps', 'bluetooth', 'usb_charging', 'child_seat'],
        'available' => true,
        'rating' => 4.9,
        'reviews' => 67
    ],
    [
        'id' => 6,
        'brand' => 'Ford',
        'model' => 'Transit',
        'type' => 'van',
        'year' => 2023,
        'transmission' => 'manual',
        'fuel' => 'diesel',
        'seats' => 9,
        'luggage' => 8,
        'daily_price' => 45000,
        'weekly_price' => 270000,
        'monthly_price' => 1050000,
        'image' => 'https://images.unsplash.com/photo-1580273981733-7dc2b1b2dc0c?w=400&h=250&fit=crop',
        'features' => ['air_conditioning', 'gps', 'bluetooth', 'usb_charging'],
        'available' => true,
        'rating' => 4.5,
        'reviews' => 94
    ]
];

// Filtrage
$type_filter = $_GET['type'] ?? 'all';
$price_filter = $_GET['price'] ?? 'default';

if ($type_filter !== 'all') {
    $available_cars = array_filter($available_cars, function($car) use ($type_filter) {
        return $car['type'] === $type_filter;
    });
}

// Tri
switch ($price_filter) {
    case 'low_high':
        usort($available_cars, function($a, $b) {
            return $a['daily_price'] - $b['daily_price'];
        });
        break;
    case 'high_low':
        usort($available_cars, function($a, $b) {
            return $b['daily_price'] - $a['daily_price'];
        });
        break;
    case 'popular':
        usort($available_cars, function($a, $b) {
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
        
        /* Cars Section */
        .cars-section {
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
        
        .cars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
            margin: 0 auto;
            padding: 0 32px;
            max-width: 1180px;
        }
        
        .car-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid var(--booking-border);
        }
        
        .car-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border-color: var(--booking-blue);
        }
        
        .car-image {
            height: 200px;
            position: relative;
            overflow: hidden;
        }
        
        .car-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .car-badge {
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
        
        .car-content {
            padding: 20px;
        }
        
        .car-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
        }
        
        .car-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--booking-dark);
            margin-bottom: 4px;
        }
        
        .car-subtitle {
            font-size: 14px;
            color: var(--booking-gray);
            margin-bottom: 8px;
        }
        
        .car-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
        }
        
        .car-rating i {
            color: #ffc107;
        }
        
        .car-specs {
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
        
        .car-features {
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
        
        .car-pricing {
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
        
        .car-actions {
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
            
            .cars-grid {
                grid-template-columns: 1fr;
                padding: 0 16px;
            }
            
            .car-pricing {
                flex-direction: column;
                gap: 12px;
            }
            
            .car-actions {
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
                    <a href="car_rental.php" class="nav-item-booking active">
                        <i class="fas fa-car" style="margin-right: 4px;"></i>
                        <?= $t['car_rental'] ?>
                    </a>
                    <a href="airport_transfer.php" class="nav-item-booking">
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
            <p class="hero-subtitle"><?= count($available_cars) ?>+ <?= $t['available_cars'] ?> • <?= $t['best_price_guaranteed'] ?></p>
            
            </div>
    </section>

    <!-- Search Box -->
    <section style="margin: -40px auto 80px; position: relative; z-index: 10;">
        <div class="search-box-booking">
                <form class="search-form-booking" method="GET">
                    <div class="form-group-booking">
                        <label class="form-label-booking"><?= $t['pickup_location'] ?></label>
                        <input type="text" name="pickup" class="form-control-booking" placeholder="Dakar, Aéroport, Hôtel..." required>
                    </div>
                    
                    <div class="form-group-booking">
                        <label class="form-label-booking"><?= $t['dropoff_location'] ?></label>
                        <input type="text" name="dropoff" class="form-control-booking" placeholder="Dakar, Aéroport, Hôtel...">
                    </div>
                    
                    <div class="form-group-booking">
                        <label class="form-label-booking"><?= $t['pickup_date'] ?></label>
                        <input type="date" name="pickup_date" class="form-control-booking" required>
                    </div>
                    
                    <div class="form-group-booking">
                        <label class="form-label-booking"><?= $t['dropoff_date'] ?></label>
                        <input type="date" name="dropoff_date" class="form-control-booking" required>
                    </div>
                    
                    <div class="form-group-booking">
                        <label class="form-label-booking"><?= $t['pickup_time'] ?></label>
                        <input type="time" name="pickup_time" class="form-control-booking" value="10:00">
                    </div>
                    
                    <button type="submit" class="btn-search-booking">
                        <i class="fas fa-search"></i> <?= $t['search_btn'] ?>
                    </button>
                </form>
            </div>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Section Propriétaire : Déposer une voiture -->
        <section class="owner-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 0; margin: 40px auto; max-width: 900px; border-radius: 15px;">
            <div class="container">
                <h2 class="text-center mb-4">
                    <i class="fas fa-car me-2"></i><?= $lang === 'fr' ? 'Déposer votre voiture' : 'List Your Car' ?>
                </h2>
                <p class="text-center mb-4">
                    <?= $lang === 'fr' ? 'Vous avez une voiture à louer ? Déposez votre annonce ici !' : 'Have a car to rent? List your ad here!' ?>
                </p>
                
                <div class="text-center">
                    <a href="add_car.php" class="btn btn-light btn-lg">
                        <i class="fas fa-plus-circle me-2"></i><?= $lang === 'fr' ? 'Ajouter ma voiture' : 'Add My Car' ?>
                    </a>
                </div>
                
                <?php
                // Récupérer les voitures du propriétaire
                require_once 'config/config.php';
                require_once 'core/Database.php';
                $db = Database::getInstance();
                $ownerCars = $db->fetchAll("SELECT * FROM cars WHERE owner_id = ? ORDER BY created_at DESC", [$_SESSION['user_id']]);
                
                if (!empty($ownerCars)): ?>
                    <div class="mt-5">
                        <h3 class="text-center mb-4">
                            <i class="fas fa-list me-2"></i><?= $lang === 'fr' ? 'Mes voitures déposées' : 'My Listed Cars' ?>
                        </h3>
                        
                        <div class="row">
                            <?php foreach ($ownerCars as $car): ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100" style="border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                                        <img src="<?= $car['image'] ?? 'https://via.placeholder.com/400x250' ?>" class="card-img-top" alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h5>
                                            <p class="card-text">
                                                <strong><?= $lang === 'fr' ? 'Année' : 'Year' ?>:</strong> <?= $car['year'] ?><br>
                                                <strong><?= $lang === 'fr' ? 'Prix/jour' : 'Daily Price' ?>:</strong> <?= number_format($car['daily_price'], 0, '.', ' ') ?> FCFA
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge <?= $car['available'] ? 'bg-success' : 'bg-secondary' ?>">
                                                    <?= $car['available'] ? ($lang === 'fr' ? 'Disponible' : 'Available') : ($lang === 'fr' ? 'Indisponible' : 'Unavailable') ?>
                                                </span>
                                                <div>
                                                    <a href="edit_car.php?id=<?= $car['id'] ?>" class="btn btn-sm btn-outline-primary me-2">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="delete_car.php?id=<?= $car['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= $lang === 'fr' ? 'Êtes-vous sûr de vouloir supprimer cette voiture ?' : 'Are you sure you want to delete this car?' ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- Filters Section -->
    <section class="filters-section">
        <div class="filters-container">
            <div class="filter-group">
                <span class="filter-label"><?= $t['filter_by_type'] ?>:</span>
                <select name="type" class="filter-select" onchange="window.location.href='?type=' + this.value">
                    <option value="all"><?= $t['all_types'] ?></option>
                    <option value="economy" <?= $type_filter === 'economy' ? 'selected' : '' ?>><?= $t['economy'] ?></option>
                    <option value="compact" <?= $type_filter === 'compact' ? 'selected' : '' ?>><?= $t['compact'] ?></option>
                    <option value="midsize" <?= $type_filter === 'midsize' ? 'selected' : '' ?>><?= $t['midsize'] ?></option>
                    <option value="suv" <?= $type_filter === 'suv' ? 'selected' : '' ?>><?= $t['suv'] ?></option>
                    <option value="luxury" <?= $type_filter === 'luxury' ? 'selected' : '' ?>><?= $t['luxury'] ?></option>
                    <option value="van" <?= $type_filter === 'van' ? 'selected' : '' ?>><?= $t['van'] ?></option>
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

    <!-- Cars Section -->
    <section class="cars-section">
        <div class="section-header">
            <h2 class="section-title"><?= $t['available_cars'] ?></h2>
            <p class="section-subtitle"><?= $t['best_price_guaranteed'] ?> • <?= $t['free_cancellation'] ?> • <?= $t['24_support'] ?></p>
        </div>
        
        <?php if (empty($available_cars)): ?>
            <div style="text-align: center; padding: 60px 32px; color: var(--booking-gray);">
                <i class="fas fa-car" style="font-size: 3rem; margin-bottom: 16px; opacity: 0.5;"></i>
                <h3 style="margin-bottom: 8px;"><?= $t['no_cars_found'] ?></h3>
                <p><?= $t['try_different_filters'] ?></p>
            </div>
        <?php else: ?>
            <div class="cars-grid">
                <?php foreach ($available_cars as $car): ?>
                    <div class="car-card">
                        <div class="car-image">
                            <img src="<?= $car['image'] ?>" alt="<?= $car['brand'] ?> <?= $car['model'] ?>">
                            <span class="car-badge"><?= $t[$car['type']] ?></span>
                        </div>
                        
                        <div class="car-content">
                            <div class="car-header">
                                <div>
                                    <h3 class="car-title"><?= $car['brand'] ?> <?= $car['model'] ?></h3>
                                    <p class="car-subtitle"><?= $car['year'] ?> • <?= $t[$car['transmission']] ?> • <?= $t[$car['fuel']] ?></p>
                                </div>
                                <div class="car-rating">
                                    <i class="fas fa-star"></i>
                                    <span><?= $car['rating'] ?></span>
                                    <span style="color: var(--booking-gray);">(<?= $car['reviews'] ?>)</span>
                                </div>
                            </div>
                            
                            <div class="car-specs">
                                <div class="spec-item">
                                    <i class="fas fa-users"></i>
                                    <span><?= $car['seats'] ?> <?= $t['seats'] ?></span>
                                </div>
                                <div class="spec-item">
                                    <i class="fas fa-suitcase"></i>
                                    <span><?= $car['luggage'] ?> <?= $t['luggage'] ?></span>
                                </div>
                            </div>
                            
                            <div class="car-features">
                                <?php foreach ($car['features'] as $feature): ?>
                                    <span class="feature-tag">
                                        <i class="fas fa-<?= $feature === 'air_conditioning' ? 'snowflake' : ($feature === 'gps' ? 'map-marker-alt' : ($feature === 'bluetooth' ? 'bluetooth' : 'usb')) ?>"></i>
                                        <?= $t[$feature] ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="car-pricing">
                                <div class="price-info">
                                    <span class="price-main"><?= number_format($car['daily_price'], 0, ',', ' ') ?> FCFA</span>
                                    <span class="price-period"><?= $t['daily_price'] ?></span>
                                    <span class="price-secondary"><?= number_format($car['weekly_price'], 0, ',', ' ') ?> FCFA/<?= $t['weekly_price'] ?></span>
                                </div>
                            </div>
                            
                            <div class="car-actions">
                                <a href="#" class="btn-book" onclick="bookCar(<?= $car['id'] ?>)">
                                    <i class="fas fa-calendar-check"></i> <?= $t['book_now'] ?>
                                </a>
                                <a href="#" class="btn-details" onclick="viewDetails(<?= $car['id'] ?>)">
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
                    <?= $lang === 'fr' ? 'Votre plateforme de confiance pour la location de voitures au Sénégal.' : 'Your trusted platform for car rental in Senegal.' ?>
                </p>
                <div style="display: flex; gap: 16px; margin-top: 20px;">
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h4><?= $t['car_rental'] ?></h4>
                <ul class="footer-links">
                    <li><a href="#"><?= $lang === 'fr' ? 'Toutes les voitures' : 'All cars' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Voitures économiques' : 'Economy cars' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Voitures de luxe' : 'Luxury cars' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Utilitaires' : 'Vans' ?></a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4><?= $lang === 'fr' ? 'Services' : 'Services' ?></h4>
                <ul class="footer-links">
                    <li><a href="accueil_booking_fixed.php"><?= $t['properties'] ?></a></li>
                    <li><a href="#"><?= $t['airport_transfer'] ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Assistance 24/7' : '24/7 Support' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Assurance' : 'Insurance' ?></a></li>
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
        function bookCar(carId) {
            if (!<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>) {
                if (confirm('<?= $lang === 'fr' ? 'Vous devez être connecté pour réserver. Voulez-vous vous connecter maintenant ?' : 'You must be logged in to book. Do you want to login now?' ?>')) {
                    window.location.href = 'login.php?redirect=book_car&car_id=' + carId;
                }
                return;
            }
            
            // Rediriger vers la page de réservation
            window.location.href = 'book_car.php?id=' + carId;
        }
        
        function viewDetails(carId) {
            // Ouvrir modal ou rediriger vers la page de détails
            alert('<?= $lang === 'fr' ? 'Détails de la voiture #' : 'Car details for #' ?>' + carId);
        }
        
        // Validation des dates
        document.addEventListener('DOMContentLoaded', function() {
            const pickupDate = document.querySelector('input[name="pickup_date"]');
            const dropoffDate = document.querySelector('input[name="dropoff_date"]');
            
            // Définir la date minimale à aujourd'hui
            const today = new Date().toISOString().split('T')[0];
            pickupDate.min = today;
            dropoffDate.min = today;
            
            pickupDate.addEventListener('change', function() {
                dropoffDate.min = this.value;
                if (dropoffDate.value && dropoffDate.value < this.value) {
                    dropoffDate.value = this.value;
                }
            });
        });
    </script>
</body>
</html>
