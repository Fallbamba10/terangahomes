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
        'site_title' => 'TerangaHomes - Hébergements et Locations au Sénégal',
        'welcome_message' => 'Trouvez votre chez-vous au Sénégal',
        'search_placeholder' => 'Rechercher une ville, un quartier, une propriété...',
        'check_in' => 'Arrivée',
        'check_out' => 'Départ',
        'guests' => 'Voyageurs',
        'search_button' => 'Rechercher',
        'explore_senegal' => 'Explorez le Sénégal',
        'popular_cities' => 'Villes populaires',
        'featured_properties' => 'Propriétés en vedette',
        'car_rental' => 'Location Voiture',
        'airport_transfer' => 'Taxi Aéroport',
        'properties' => 'Propriétés',
        'experiences' => 'Expériences',
        'restaurants' => 'Restaurants',
        'language' => 'Langue',
        'currency' => 'Devise',
        'login' => 'Se connecter',
        'register' => "S'inscrire",
        'help' => 'Aide',
        'list_property' => 'Mettre sa propriété',
        'dakar' => 'Dakar',
        'saly' => 'Saly',
        'mbour' => 'Mbour',
        'saint_louis' => 'Saint-Louis',
        'cap_skirring' => 'Cap Skirring',
        'gorée_island' => 'Île de Gorée',
        'lompoul_desert' => 'Désert de Lompoul',
        'bandia_reserve' => 'Réserve de Bandia',
        'pink_lake' => 'Lac Rose',
        'joal_fadiouth' => 'Joal-Fadiouth',
        'more_destinations' => 'Plus de destinations',
        'view_property' => 'Voir la propriété',
        'instant_book' => 'Réservation instantanée',
        'superhost' => 'Superhost',
        'new' => 'Nouveau',
        'guest_favorite' => 'Coup de cœur des voyageurs',
        'about_teranga' => 'À propos de TerangaHomes',
        'customer_support' => 'Support client 24/7',
        'safe_booking' => 'Réservation sécurisée',
        'best_prices' => 'Meilleurs prix garantis',
        'follow_us' => 'Suivez-nous',
        'all_rights_reserved' => 'Tous droits réservés',
        'privacy_policy' => 'Politique de confidentialité',
        'terms_conditions' => 'Conditions générales',
        'cookie_policy' => 'Politique de cookies',
        'sitemap' => 'Plan du site'
    ],
    'en' => [
        'site_title' => 'TerangaHomes - Accommodations and Rentals in Senegal',
        'welcome_message' => 'Find your home in Senegal',
        'search_placeholder' => 'Search a city, neighborhood, property...',
        'check_in' => 'Check-in',
        'check_out' => 'Check-out',
        'guests' => 'Guests',
        'search_button' => 'Search',
        'explore_senegal' => 'Explore Senegal',
        'popular_cities' => 'Popular Cities',
        'featured_properties' => 'Featured Properties',
        'car_rental' => 'Car Rental',
        'airport_transfer' => 'Airport Taxi',
        'properties' => 'Properties',
        'experiences' => 'Experiences',
        'restaurants' => 'Restaurants',
        'language' => 'Language',
        'currency' => 'Currency',
        'login' => 'Sign in',
        'register' => 'Register',
        'help' => 'Help',
        'list_property' => 'List your property',
        'dakar' => 'Dakar',
        'saly' => 'Saly',
        'mbour' => 'Mbour',
        'saint_louis' => 'Saint-Louis',
        'cap_skirring' => 'Cap Skirring',
        'gorée_island' => 'Gorée Island',
        'lompoul_desert' => 'Lompoul Desert',
        'bandia_reserve' => 'Bandia Reserve',
        'pink_lake' => 'Pink Lake',
        'joal_fadiouth' => 'Joal-Fadiouth',
        'more_destinations' => 'More destinations',
        'view_property' => 'View property',
        'instant_book' => 'Instant book',
        'superhost' => 'Superhost',
        'new' => 'New',
        'guest_favorite' => 'Guest favorite',
        'about_teranga' => 'About TerangaHomes',
        'customer_support' => '24/7 Customer Support',
        'safe_booking' => 'Safe Booking',
        'best_prices' => 'Best Price Guaranteed',
        'follow_us' => 'Follow us',
        'all_rights_reserved' => 'All rights reserved',
        'privacy_policy' => 'Privacy Policy',
        'terms_conditions' => 'Terms & Conditions',
        'cookie_policy' => 'Cookie Policy',
        'sitemap' => 'Sitemap'
    ]
];

$t = $translations[$lang];

// Données des villes
$cities = [
    [
        'name' => $t['dakar'],
        'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400&h=300&fit=crop',
        'properties' => 156,
        'description' => $lang === 'fr' ? 'Capitale vibrante du Sénégal' : 'Vibrant capital of Senegal'
    ],
    [
        'name' => $t['saly'],
        'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=300&fit=crop',
        'properties' => 89,
        'description' => $lang === 'fr' ? 'Station balnéaire prisée' : 'Popular seaside resort'
    ],
    [
        'name' => $t['mbour'],
        'image' => 'https://images.unsplash.com/photo-1549394010-8946b1efad3a?w=400&h=300&fit=crop',
        'properties' => 67,
        'description' => $lang === 'fr' ? 'Grande ville de pêche' : 'Major fishing city'
    ],
    [
        'name' => $t['saint_louis'],
        'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=300&fit=crop',
        'properties' => 45,
        'description' => $lang === 'fr' ? 'Ville historique coloniale' : 'Historic colonial town'
    ],
    [
        'name' => $t['cap_skirring'],
        'image' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=400&h=300&fit=crop',
        'properties' => 34,
        'description' => $lang === 'fr' ? 'Paradis tropical' : 'Tropical paradise'
    ],
    [
        'name' => $t['gorée_island'],
        'image' => 'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?w=400&h=300&fit=crop',
        'properties' => 23,
        'description' => $lang === 'fr' ? 'Île historique et mémorielle' : 'Historic and memorial island'
    ]
];

// Données des propriétés
$properties = [
    [
        'id' => 1,
        'title' => $lang === 'fr' ? 'Villa de luxe avec piscine à Saly' : 'Luxury villa with pool in Saly',
        'type' => 'Villa',
        'location' => $t['saly'],
        'price' => 85000,
        'image' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=400&h=300&fit=crop',
        'rating' => 4.8,
        'reviews' => 124,
        'guests' => 8,
        'bedrooms' => 4,
        'bathrooms' => 3,
        'badges' => ['instant_book', 'superhost'],
        'description' => $lang === 'fr' ? 'Magnifique villa de 4 chambres avec piscine privée et jardin tropical.' : 'Beautiful 4-bedroom villa with private pool and tropical garden.'
    ],
    [
        'id' => 2,
        'title' => $lang === 'fr' ? 'Appartement moderne à Dakar Plateau' : 'Modern apartment in Dakar Plateau',
        'type' => 'Appartement',
        'location' => $t['dakar'],
        'price' => 45000,
        'image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400&h=300&fit=crop',
        'rating' => 4.9,
        'reviews' => 89,
        'guests' => 4,
        'bedrooms' => 2,
        'bathrooms' => 2,
        'badges' => ['guest_favorite', 'superhost'],
        'description' => $lang === 'fr' ? 'Appartement élégant en plein cœur de Dakar avec vue sur l\'océan.' : 'Elegant apartment in the heart of Dakar with ocean view.'
    ],
    [
        'id' => 3,
        'title' => $lang === 'fr' ? 'Bungalow traditionnel à Cap Skirring' : 'Traditional bungalow in Cap Skirring',
        'type' => 'Bungalow',
        'location' => $t['cap_skirring'],
        'price' => 35000,
        'image' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=400&h=300&fit=crop',
        'rating' => 4.7,
        'reviews' => 67,
        'guests' => 6,
        'bedrooms' => 3,
        'bathrooms' => 2,
        'badges' => ['new'],
        'description' => $lang === 'fr' ? 'Bungalow authentique à quelques pas des plus belles plages.' : 'Authentic bungalow steps away from the most beautiful beaches.'
    ],
    [
        'id' => 4,
        'title' => $lang === 'fr' ? 'Maison d\'hôtes à Saint-Louis' : 'Guest house in Saint-Louis',
        'type' => 'Maison d\'hôtes',
        'location' => $t['saint_louis'],
        'price' => 28000,
        'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=300&fit=crop',
        'rating' => 4.6,
        'reviews' => 45,
        'guests' => 4,
        'bedrooms' => 2,
        'bathrooms' => 1,
        'badges' => ['guest_favorite'],
        'description' => $lang === 'fr' ? 'Charmante maison d\'hôtes dans le quartier historique de Saint-Louis.' : 'Charming guest house in the historic district of Saint-Louis.'
    ]
];
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
        /* ==================== PALETTE TERANGA MODERNE ==================== */
        :root {
            /* Couleurs principales - Teranga Moderne */
            --teranga-gold: #D4AF37;           /* Or sablonneux - boutons, actions */
            --teranga-blue: #1B263B;           /* Bleu nuit - header, footer */
            --teranga-terracotta: #BC6C25;     /* Terracotta - accents, badges */
            --teranga-sand: #F8F7F4;          /* Sable clair - fond principal */
            --teranga-cream: #FAF9F6;          /* Crème - cards */
            --teranga-earth: #8B7355;          /* Terre - textes secondaires */
            --teranga-coral: #E67E50;          /* Corail - hover effects */
            --teranga-sage: #87A96B;           /* Sauge - éléments nature */
            
            /* Dégradés */
            --gradient-primary: linear-gradient(135deg, var(--teranga-gold) 0%, var(--teranga-terracotta) 100%);
            --gradient-secondary: linear-gradient(135deg, var(--teranga-blue) 0%, #2C3E50 100%);
            --gradient-hero: linear-gradient(135deg, var(--teranga-blue) 0%, var(--teranga-gold) 100%);
            
            /* Ombres */
            --shadow-soft: 0 4px 20px rgba(212, 175, 55, 0.15);
            --shadow-medium: 0 8px 30px rgba(27, 38, 59, 0.12);
            --shadow-strong: 0 12px 40px rgba(188, 108, 37, 0.2);
            
            /* Bordures */
            --border-light: rgba(212, 175, 55, 0.2);
            --border-medium: rgba(27, 38, 59, 0.15);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--teranga-sand);
            color: var(--teranga-blue);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* ==================== HEADER ==================== */
        .booking-header {
            background: var(--teranga-blue);
            border-bottom: 1px solid var(--border-medium);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-soft);
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
            color: var(--teranga-gold);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .logo-booking:hover {
            color: var(--teranga-terracotta);
            transform: scale(1.05);
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
            color: var(--teranga-cream);
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            padding: 6px 10px;
            border-radius: 6px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .nav-item-booking:hover {
            background: rgba(212, 175, 55, 0.1);
            color: var(--teranga-gold);
        }
        
        .nav-item-booking.active {
            background: var(--teranga-gold);
            color: var(--teranga-blue);
        }
        
        /* ==================== HERO SECTION ==================== */
        .hero-booking {
            background: var(--gradient-hero);
            padding: 80px 32px 60px;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="hero-pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23hero-pattern)"/></svg>');
            opacity: 0.3;
        }
        
        .hero-content {
            max-width: 1180px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 16px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 40px;
            font-weight: 300;
        }
        
        /* ==================== SEARCH BOX ==================== */
        .search-box-booking {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-strong);
            max-width: 900px;
            margin: 0 auto;
            border: 2px solid var(--teranga-gold);
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 16px;
            align-items: end;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--teranga-blue);
            margin-bottom: 8px;
        }
        
        .form-input {
            padding: 12px 16px;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--teranga-cream);
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--teranga-gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }
        
        .search-button {
            background: var(--gradient-primary);
            color: var(--teranga-blue);
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            height: fit-content;
        }
        
        .search-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        /* ==================== SECTIONS ==================== */
        .section-booking {
            padding: 60px 32px;
            max-width: 1180px;
            margin: 0 auto;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--teranga-blue);
            margin-bottom: 12px;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: var(--teranga-earth);
        }
        
        /* ==================== CITIES GRID ==================== */
        .cities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }
        
        .city-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        
        .city-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
            border: 2px solid var(--teranga-gold);
        }
        
        .city-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .city-content {
            padding: 20px;
        }
        
        .city-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--teranga-blue);
            margin-bottom: 8px;
        }
        
        .city-description {
            color: var(--teranga-earth);
            margin-bottom: 12px;
        }
        
        .city-properties {
            font-size: 0.9rem;
            color: var(--teranga-terracotta);
            font-weight: 600;
        }
        
        /* ==================== PROPERTIES GRID ==================== */
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }
        
        .property-card {
            background: var(--teranga-cream);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .property-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
        }
        
        .property-image-container {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
        }
        
        .property-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .property-card:hover .property-image {
            transform: scale(1.05);
        }
        
        .property-badges {
            position: absolute;
            top: 12px;
            left: 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .property-badge {
            background: var(--teranga-gold);
            color: var(--teranga-blue);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .property-badge.guest_favorite {
            background: var(--teranga-terracotta);
            color: white;
        }
        
        .property-badge.new {
            background: var(--teranga-sage);
            color: white;
        }
        
        .property-content {
            padding: 20px;
        }
        
        .property-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--teranga-blue);
            margin-bottom: 8px;
        }
        
        .property-location {
            color: var(--teranga-earth);
            font-size: 0.9rem;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .property-location i {
            color: var(--teranga-terracotta);
        }
        
        .property-specs {
            display: flex;
            gap: 16px;
            margin-bottom: 12px;
            font-size: 0.9rem;
            color: var(--teranga-earth);
        }
        
        .property-spec {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .property-spec i {
            color: var(--teranga-gold);
        }
        
        .property-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid var(--border-light);
        }
        
        .property-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--teranga-blue);
        }
        
        .property-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.9rem;
            color: var(--teranga-terracotta);
        }
        
        .property-rating i {
            color: var(--teranga-gold);
        }
        
        /* ==================== FOOTER ==================== */
        .booking-footer {
            background: var(--teranga-blue);
            color: var(--teranga-cream);
            padding: 60px 32px 32px;
            margin-top: 80px;
        }
        
        .footer-content {
            max-width: 1180px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 40px;
        }
        
        .footer-section h3 {
            color: var(--teranga-gold);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .footer-section p {
            line-height: 1.6;
            margin-bottom: 16px;
            opacity: 0.9;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: var(--teranga-cream);
            text-decoration: none;
            transition: color 0.3s ease;
            opacity: 0.9;
        }
        
        .footer-links a:hover {
            color: var(--teranga-gold);
            opacity: 1;
        }
        
        .footer-bottom {
            max-width: 1180px;
            margin: 40px auto 0;
            padding-top: 32px;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            opacity: 0.8;
        }
        
        .social-links {
            display: flex;
            gap: 16px;
            margin-top: 16px;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(212, 175, 55, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--teranga-gold);
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--teranga-gold);
            color: var(--teranga-blue);
            transform: scale(1.1);
        }
        
        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .cities-grid {
                grid-template-columns: 1fr;
            }
            
            .properties-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .social-links {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="booking-header">
        <div class="header-container">
            <div class="header-main">
                <a href="#" class="logo-booking">
                    <i class="fas fa-home"></i>
                    TerangaHomes
                </a>
                
                <nav class="nav-center">
                    <a href="#" class="nav-item-booking active"><?= $t['properties'] ?></a>
                    <a href="#" class="nav-item-booking">
                        <i class="fas fa-car" style="margin-right: 4px;"></i>
                        <?= $t['car_rental'] ?>
                    </a>
                    <a href="#" class="nav-item-booking">
                        <i class="fas fa-plane" style="margin-right: 4px;"></i>
                        <?= $t['airport_transfer'] ?>
                    </a>
                    <a href="#" class="nav-item-booking"><?= $t['experiences'] ?></a>
                    <a href="#" class="nav-item-booking"><?= $t['restaurants'] ?></a>
                </nav>
                
                <div class="header-right">
                    <div class="language-currency-selector">
                        <form method="POST" style="display: inline;">
                            <select name="lang" class="selector-dropdown" onchange="this.form.submit()">
                                <?php foreach ($supported_langs as $code => $name): ?>
                                    <option value="<?= $code ?>" <?= $lang === $code ? 'selected' : '' ?>>
                                        <?= match($code) {
                                            'fr' => '🇸🇳 ' . $name,
                                            'en' => '🇬🇧 ' . $name,
                                            'es' => '🇪🇸 ' . $name,
                                            'ar' => '🇸🇦 ' . $name,
                                            'zh' => '🇨🇳 ' . $name,
                                            'pt' => '🇵🇹 ' . $name,
                                            default => $name
                                        } ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    
                    <div class="user-menu">
                        <a href="#" class="nav-item-booking"><?= $t['login'] ?></a>
                        <a href="#" class="nav-item-booking"><?= $t['register'] ?></a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-booking">
        <div class="hero-content">
            <h1 class="hero-title"><?= $t['welcome_message'] ?></h1>
            <p class="hero-subtitle"><?= $lang === 'fr' ? 'Découvrez les plus belles destinations du Sénégal' : 'Discover the most beautiful destinations in Senegal' ?></p>
            
            <!-- Search Box -->
            <div class="search-box-booking">
                <form class="search-form" method="GET">
                    <div class="form-group">
                        <label class="form-label"><?= $t['search_placeholder'] ?></label>
                        <input type="text" name="search" class="form-input" placeholder="<?= $t['search_placeholder'] ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><?= $t['check_in'] ?></label>
                        <input type="date" name="checkin" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><?= $t['check_out'] ?></label>
                        <input type="date" name="checkout" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><?= $t['guests'] ?></label>
                        <select name="guests" class="form-input">
                            <option value="1">1 <?= $lang === 'fr' ? 'voyageur' : 'guest' ?></option>
                            <option value="2">2 <?= $lang === 'fr' ? 'voyageurs' : 'guests' ?></option>
                            <option value="3">3 <?= $lang === 'fr' ? 'voyageurs' : 'guests' ?></option>
                            <option value="4">4 <?= $lang === 'fr' ? 'voyageurs' : 'guests' ?></option>
                            <option value="5+">5+ <?= $lang === 'fr' ? 'voyageurs' : 'guests' ?></option>
                        </select>
                    </div>
                    
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i> <?= $t['search_button'] ?>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Cities Section -->
    <section class="section-booking">
        <div class="section-header">
            <h2 class="section-title"><?= $t['explore_senegal'] ?></h2>
            <p class="section-subtitle"><?= $lang === 'fr' ? 'Des villes magnifiques à découvrir' : 'Beautiful cities to discover' ?></p>
        </div>
        
        <div class="cities-grid">
            <?php foreach ($cities as $city): ?>
                <div class="city-card">
                    <img src="<?= $city['image'] ?>" alt="<?= $city['name'] ?>" class="city-image">
                    <div class="city-content">
                        <h3 class="city-name"><?= $city['name'] ?></h3>
                        <p class="city-description"><?= $city['description'] ?></p>
                        <p class="city-properties"><?= $city['properties'] ?> <?= $lang === 'fr' ? 'propriétés' : 'properties' ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Properties Section -->
    <section class="section-booking">
        <div class="section-header">
            <h2 class="section-title"><?= $t['featured_properties'] ?></h2>
            <p class="section-subtitle"><?= $lang === 'fr' ? 'Nos meilleures sélections pour vous' : 'Our best selections for you' ?></p>
        </div>
        
        <div class="properties-grid">
            <?php foreach ($properties as $property): ?>
                <div class="property-card">
                    <div class="property-image-container">
                        <img src="<?= $property['image'] ?>" alt="<?= $property['title'] ?>" class="property-image">
                        <div class="property-badges">
                            <?php foreach ($property['badges'] as $badge): ?>
                                <span class="property-badge <?= $badge ?>">
                                    <?= $t[$badge] ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="property-content">
                        <h3 class="property-title"><?= $property['title'] ?></h3>
                        <p class="property-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?= $property['location'] ?>
                        </p>
                        <div class="property-specs">
                            <span class="property-spec">
                                <i class="fas fa-users"></i>
                                <?= $property['guests'] ?> <?= $lang === 'fr' ? 'voyageurs' : 'guests' ?>
                            </span>
                            <span class="property-spec">
                                <i class="fas fa-bed"></i>
                                <?= $property['bedrooms'] ?> <?= $lang === 'fr' ? 'chambres' : 'bedrooms' ?>
                            </span>
                            <span class="property-spec">
                                <i class="fas fa-bath"></i>
                                <?= $property['bathrooms'] ?> <?= $lang === 'fr' ? 'salles de bain' : 'bathrooms' ?>
                            </span>
                        </div>
                        <div class="property-footer">
                            <span class="property-price"><?= number_format($property['price'], 0, ',', ' ') ?> FCFA/nuit</span>
                            <span class="property-rating">
                                <i class="fas fa-star"></i>
                                <?= $property['rating'] ?> (<?= $property['reviews'] ?>)
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="booking-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3><?= $t['about_teranga'] ?></h3>
                <p><?= $lang === 'fr' ? 'TerangaHomes est la plateforme n°1 pour les hébergements et locations au Sénégal. Découvrez l\'authentique hospitalité sénégalaise.' : 'TerangaHomes is the #1 platform for accommodations and rentals in Senegal. Discover authentic Senegalese hospitality.' ?></p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3><?= $t['properties'] ?></h3>
                <ul class="footer-links">
                    <li><a href="#"><?= $t['featured_properties'] ?></a></li>
                    <li><a href="#"><?= $t['popular_cities'] ?></a></li>
                    <li><a href="#"><?= $t['list_property'] ?></a></li>
                    <li><a href="#"><?= $t['car_rental'] ?></a></li>
                    <li><a href="#"><?= $t['airport_transfer'] ?></a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3><?= $t['help'] ?></h3>
                <ul class="footer-links">
                    <li><a href="#"><?= $t['customer_support'] ?></a></li>
                    <li><a href="#"><?= $t['safe_booking'] ?></a></li>
                    <li><a href="#"><?= $t['best_prices'] ?></a></li>
                    <li><a href="#"><?= $t['privacy_policy'] ?></a></li>
                    <li><a href="#"><?= $t['terms_conditions'] ?></a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3><?= $t['follow_us'] ?></h3>
                <ul class="footer-links">
                    <li><a href="#"><?= $t['about_teranga'] ?></a></li>
                    <li><a href="#"><?= $t['car_rental'] ?></a></li>
                    <li><a href="#"><?= $t['airport_transfer'] ?></a></li>
                    <li><a href="#"><?= $t['experiences'] ?></a></li>
                    <li><a href="#"><?= $t['restaurants'] ?></a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3><?= $t['language'] ?></h3>
                <ul class="footer-links">
                    <li><a href="?lang=fr">Français</a></li>
                    <li><a href="?lang=en">English</a></li>
                    <li><a href="?lang=es">Español</a></li>
                    <li><a href="?lang=ar">العربية</a></li>
                    <li><a href="?lang=zh">中文</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2024 TerangaHomes. <?= $t['all_rights_reserved'] ?></p>
        </div>
    </footer>
</body>
</html>
