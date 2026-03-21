<?php
// Page d'accueil inspirée de Booking.com - Version Internationale

session_start();

// Forcer le mode visiteur - détruire toute session existante
if (isset($_SESSION['user_id'])) {
    session_destroy();
    session_start();
}

// Détecter la langue de l'utilisateur
$default_lang = 'fr';
$supported_langs = ['fr', 'en', 'es', 'ar', 'zh', 'pt'];

// Détecter la langue depuis le navigateur ou la session
if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $supported_langs)) {
    $lang = $_SESSION['lang'];
} else {
    $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'fr', 0, 2);
    $lang = in_array($browser_lang, $supported_langs) ? $browser_lang : $default_lang;
    $_SESSION['lang'] = $lang;
}

// Détecter la devise
$default_currency = 'XOF';
$supported_currencies = ['XOF', 'EUR', 'USD', 'GBP', 'CAD', 'AUD', 'JPY', 'CNY'];

if (isset($_SESSION['currency']) && in_array($_SESSION['currency'], $supported_currencies)) {
    $currency = $_SESSION['currency'];
} else {
    // Détecter depuis le pays de l'utilisateur
    $user_country = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? 'SN'; // Cloudflare ou défaut Sénégal
    $currency = match($user_country) {
        'FR', 'DE', 'IT', 'ES', 'BE' => 'EUR',
        'US', 'CA', 'AU' => 'USD',
        'GB' => 'GBP',
        'JP' => 'JPY',
        'CN' => 'CNY',
        default => 'XOF'
    };
    $_SESSION['currency'] = $currency;
}

// Traductions
$translations = [
    'fr' => [
        'site_title' => 'TerangaHomes - Votre plateforme immobilière au Sénégal',
        'tagline' => 'Réservez votre logement idéal au Sénégal',
        'search_placeholder' => 'Destination, ville, quartier...',
        'check_in' => 'Arrivée',
        'check_out' => 'Départ',
        'guests' => 'Voyageurs',
        'search_btn' => 'Rechercher',
        'properties' => 'Propriétés',
        'experiences' => 'Expériences',
        'restaurants' => 'Restaurants',
        'login' => 'Se connecter',
        'register' => "S'inscrire",
        'list_property' => 'Déposer une annonce',
        'help' => 'Aide',
        'currency' => 'Devise',
        'language' => 'Langue',
        'popular_destinations' => 'Destinations populaires',
        'featured_properties' => 'Propriétés en vedette',
        'why_choose_us' => 'Pourquoi nous choisir',
        'instant_booking' => 'Réservation instantanée',
        'best_prices' => 'Meilleurs prix',
        '24_support' => 'Support 24/7',
        'secure_payment' => 'Paiement sécurisé',
        'book_now' => 'Réserver maintenant',
        'view_details' => 'Voir détails',
        'per_night' => 'par nuit',
        'per_month' => 'par mois',
        'reviews' => 'avis',
        'location' => 'Localisation',
        'amenities' => 'Équipements',
        'host' => 'Hôte',
        'contact_host' => 'Contacter l\'hôte'
    ],
    'en' => [
        'site_title' => 'TerangaHomes - Your Real Estate Platform in Senegal',
        'tagline' => 'Book your ideal accommodation in Senegal',
        'search_placeholder' => 'Destination, city, neighborhood...',
        'check_in' => 'Check-in',
        'check_out' => 'Check-out',
        'guests' => 'Guests',
        'search_btn' => 'Search',
        'properties' => 'Properties',
        'experiences' => 'Experiences',
        'restaurants' => 'Restaurants',
        'login' => 'Sign in',
        'register' => 'Register',
        'list_property' => 'List your property',
        'help' => 'Help',
        'currency' => 'Currency',
        'language' => 'Language',
        'popular_destinations' => 'Popular Destinations',
        'featured_properties' => 'Featured Properties',
        'why_choose_us' => 'Why Choose Us',
        'instant_booking' => 'Instant Booking',
        'best_prices' => 'Best Prices',
        '24_support' => '24/7 Support',
        'secure_payment' => 'Secure Payment',
        'book_now' => 'Book Now',
        'view_details' => 'View Details',
        'per_night' => 'per night',
        'per_month' => 'per month',
        'reviews' => 'reviews',
        'location' => 'Location',
        'amenities' => 'Amenities',
        'host' => 'Host',
        'contact_host' => 'Contact Host'
    ],
    'es' => [
        'site_title' => 'TerangaHomes - Tu Plataforma Inmobiliaria en Senegal',
        'tagline' => 'Reserva tu alojamiento ideal en Senegal',
        'search_placeholder' => 'Destino, ciudad, barrio...',
        'check_in' => 'Entrada',
        'check_out' => 'Salida',
        'guests' => 'Huéspedes',
        'search_btn' => 'Buscar',
        'properties' => 'Propiedades',
        'experiences' => 'Experiencias',
        'restaurants' => 'Restaurantes',
        'login' => 'Iniciar sesión',
        'register' => 'Registrarse',
        'list_property' => 'Publicar propiedad',
        'help' => 'Ayuda',
        'currency' => 'Moneda',
        'language' => 'Idioma',
        'popular_destinations' => 'Destinos populares',
        'featured_properties' => 'Propiedades destacadas',
        'why_choose_us' => '¿Por qué elegirnos?',
        'instant_booking' => 'Reserva instantánea',
        'best_prices' => 'Mejores precios',
        '24_support' => 'Soporte 24/7',
        'secure_payment' => 'Pago seguro',
        'book_now' => 'Reservar ahora',
        'view_details' => 'Ver detalles',
        'per_night' => 'por noche',
        'per_month' => 'por mes',
        'reviews' => 'reseñas',
        'location' => 'Ubicación',
        'amenities' => 'Comodidades',
        'host' => 'Anfitrión',
        'contact_host' => 'Contactar anfitrión'
    ],
    'ar' => [
        'site_title' => 'تيرانجا هومز - منصتك العقارية في السنغال',
        'tagline' => 'احجز إقامتك المثالية في السنغال',
        'search_placeholder' => 'الوجهة، المدينة، الحي...',
        'check_in' => 'تسجيل الوصول',
        'check_out' => 'تسجيل المغادرة',
        'guests' => 'الضيوف',
        'search_btn' => 'بحث',
        'properties' => 'العقارات',
        'experiences' => 'التجارب',
        'restaurants' => 'المطاعم',
        'login' => 'تسجيل الدخول',
        'register' => 'التسجيل',
        'list_property' => 'إدراج العقار',
        'help' => 'مساعدة',
        'currency' => 'العملة',
        'language' => 'اللغة',
        'popular_destinations' => 'الوجهات الشائعة',
        'featured_properties' => 'العقارات المميزة',
        'why_choose_us' => 'لماذا تختارنا',
        'instant_booking' => 'حجز فوري',
        'best_prices' => 'أفضل الأسعار',
        '24_support' => 'دعم على مدار الساعة',
        'secure_payment' => 'دفع آمن',
        'book_now' => 'احجز الآن',
        'view_details' => 'عرض التفاصيل',
        'per_night' => 'لكل ليلة',
        'per_month' => 'لكل شهر',
        'reviews' => 'التقييمات',
        'location' => 'الموقع',
        'amenities' => 'المرافق',
        'host' => 'المضيف',
        'contact_host' => 'تواصل مع المضيف'
    ],
    'zh' => [
        'site_title' => 'TerangaHomes - 塞内加尔房地产平台',
        'tagline' => '预订您在塞内加尔的理想住宿',
        'search_placeholder' => '目的地，城市，社区...',
        'check_in' => '入住',
        'check_out' => '退房',
        'guests' => '客人',
        'search_btn' => '搜索',
        'properties' => '房产',
        'experiences' => '体验',
        'restaurants' => '餐厅',
        'login' => '登录',
        'register' => '注册',
        'list_property' => '发布房产',
        'help' => '帮助',
        'currency' => '货币',
        'language' => '语言',
        'popular_destinations' => '热门目的地',
        'featured_properties' => '特色房产',
        'why_choose_us' => '为什么选择我们',
        'instant_booking' => '即时预订',
        'best_prices' => '最佳价格',
        '24_support' => '24/7支持',
        'secure_payment' => '安全支付',
        'book_now' => '立即预订',
        'view_details' => '查看详情',
        'per_night' => '每晚',
        'per_month' => '每月',
        'reviews' => '评论',
        'location' => '位置',
        'amenities' => '设施',
        'host' => '房东',
        'contact_host' => '联系房东'
    ],
    'pt' => [
        'site_title' => 'TerangaHomes - Sua Plataforma Imobiliária no Senegal',
        'tagline' => 'Reserve sua acomodação ideal no Senegal',
        'search_placeholder' => 'Destino, cidade, bairro...',
        'check_in' => 'Check-in',
        'check_out' => 'Check-out',
        'guests' => 'Hóspedes',
        'search_btn' => 'Pesquisar',
        'properties' => 'Propriedades',
        'experiences' => 'Experiências',
        'restaurants' => 'Restaurantes',
        'login' => 'Entrar',
        'register' => 'Registrar',
        'list_property' => 'Anunciar propriedade',
        'help' => 'Ajuda',
        'currency' => 'Moeda',
        'language' => 'Idioma',
        'popular_destinations' => 'Destinos Populares',
        'featured_properties' => 'Propriedades em Destaque',
        'why_choose_us' => 'Por que nos escolher',
        'instant_booking' => 'Reserva Imediata',
        'best_prices' => 'Melhores Preços',
        '24_support' => 'Suporte 24/7',
        'secure_payment' => 'Pagamento Seguro',
        'book_now' => 'Reservar Agora',
        'view_details' => 'Ver Detalhes',
        'per_night' => 'por noite',
        'per_month' => 'por mês',
        'reviews' => 'avaliações',
        'location' => 'Localização',
        'amenities' => 'Comodidades',
        'host' => 'Anfitrião',
        'contact_host' => 'Contatar Anfitrião'
    ]
];

// Taux de conversion (simulés - en pratique, utiliser une API)
$exchange_rates = [
    'XOF' => 1,        // Base
    'EUR' => 0.0015,   // 1 XOF = 0.0015 EUR
    'USD' => 0.0016,   // 1 XOF = 0.0016 USD
    'GBP' => 0.0013,   // 1 XOF = 0.0013 GBP
    'CAD' => 0.0022,   // 1 XOF = 0.0022 CAD
    'AUD' => 0.0024,   // 1 XOF = 0.0024 AUD
    'JPY' => 0.24,     // 1 XOF = 0.24 JPY
    'CNY' => 0.011     // 1 XOF = 0.011 CNY
];

// Symboles de devise
$currency_symbols = [
    'XOF' => 'FCFA',
    'EUR' => '€',
    'USD' => '$',
    'GBP' => '£',
    'CAD' => 'C$',
    'AUD' => 'A$',
    'JPY' => '¥',
    'CNY' => '¥'
];

// Fonction de conversion
function convert_price($price_xof, $to_currency, $rates) {
    return $price_xof * $rates[$to_currency];
}

// Fonction de formatage
function format_price($price, $currency, $symbol, $lang) {
    $formatted = match($currency) {
        'XOF', 'EUR' => number_format($price, 0, '.', ' ') . ' ' . $symbol,
        'USD', 'GBP', 'CAD', 'AUD' => $symbol . number_format($price, 0, '.', ','),
        'JPY', 'CNY' => $symbol . number_format($price, 0, '.', ','),
        default => $symbol . number_format($price, 0, '.', ',')
    };
    return $formatted;
}

// Obtenir les traductions
$t = $translations[$lang];

require_once 'config/config.php';
require_once 'core/Database.php';

// Récupérer les données
$db = Database::getInstance();
try {
    $annonces = $db->fetchAll("SELECT a.*, u.nom as proprietaire_nom, u.prenom as proprietaire_prenom 
            FROM annonces a 
            LEFT JOIN users u ON a.user_id = u.id 
            WHERE a.statut = 'active' 
            ORDER BY a.created_at DESC LIMIT 12");
    
    $stats = [
        'total_annonces' => $db->fetch("SELECT COUNT(*) as total FROM annonces WHERE statut = 'active'")['total'] ?? 0,
        'total_users' => $db->fetch("SELECT COUNT(*) as total FROM users WHERE is_active = 1")['total'] ?? 0,
    ];
    
} catch (Exception $e) {
    $annonces = [];
    $stats = ['total_annonces' => 0, 'total_users' => 0];
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['site_title'] ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --booking-blue: #003580;
            --booking-light-blue: #0071c2;
            --booking-orange: #febb02;
            --booking-dark: #222222;
            --booking-gray: #717171;
            --booking-light-gray: #f7f7f7;
            --booking-border: #dddddd;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--booking-dark);
            background: white;
        }
        
        /* Header Booking Style */
        .booking-header {
            background: white;
            border-bottom: 1px solid var(--booking-border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .header-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
        }
        
        .logo-booking {
            font-size: 2rem;
            font-weight: 700;
            color: var(--booking-blue);
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logo-booking i {
            margin-right: 8px;
        }
        
        .nav-center {
            display: flex;
            gap: 32px;
            align-items: center;
        }
        
        .nav-item-booking {
            color: var(--booking-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
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
            padding: 8px 12px;
            border: 1px solid var(--booking-border);
            border-radius: 8px;
            background: white;
            color: var(--booking-dark);
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .selector-dropdown:hover {
            border-color: var(--booking-blue);
        }
        
        .btn-booking {
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-outline-booking {
            background: white;
            color: var(--booking-blue);
            border: 1px solid var(--booking-blue);
        }
        
        .btn-outline-booking:hover {
            background: var(--booking-light-gray);
        }
        
        .btn-primary-booking {
            background: var(--booking-blue);
            color: white;
        }
        
        .btn-primary-booking:hover {
            background: var(--booking-light-blue);
        }
        
        /* Hero Section */
        .hero-booking {
            background: linear-gradient(135deg, var(--booking-blue) 0%, var(--booking-light-blue) 100%);
            padding: 80px 0;
            position: relative;
        }
        
        .hero-content {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
            color: white;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.95;
        }
        
        /* Search Box - Booking Style */
        .search-box-booking {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .search-tabs {
            display: flex;
            gap: 24px;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--booking-light-gray);
        }
        
        .search-tab {
            padding: 12px 16px;
            background: transparent;
            border: none;
            color: var(--booking-gray);
            font-weight: 500;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }
        
        .search-tab.active {
            color: var(--booking-blue);
            font-weight: 600;
        }
        
        .search-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--booking-blue);
        }
        
        .search-form-booking {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
            gap: 16px;
            align-items: end;
        }
        
        .form-group-booking {
            display: flex;
            flex-direction: column;
        }
        
        .form-label-booking {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--booking-dark);
        }
        
        .form-control-booking {
            padding: 12px 16px;
            border: 1px solid var(--booking-border);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .form-control-booking:focus {
            outline: none;
            border-color: var(--booking-blue);
            box-shadow: 0 0 0 2px rgba(0,53,128,0.1);
        }
        
        .btn-search-booking {
            background: var(--booking-orange);
            color: var(--booking-dark);
            border: none;
            border-radius: 8px;
            padding: 14px 24px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .btn-search-booking:hover {
            background: #f3c432;
            transform: translateY(-1px);
        }
        
        /* Destinations Section */
        .destinations-section {
            padding: 80px 0;
            background: var(--booking-light-gray);
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 48px;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--booking-dark);
        }
        
        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .destination-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            height: 300px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .destination-card:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .destination-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .destination-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            color: white;
            padding: 20px;
        }
        
        .destination-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .destination-count {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Properties Section */
        .properties-section {
            padding: 80px 0;
        }
        
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 32px;
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .property-card-booking {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .property-card-booking:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transform: translateY(-4px);
        }
        
        .property-image-booking {
            height: 240px;
            position: relative;
            overflow: hidden;
        }
        
        .property-image-booking img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .property-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: var(--booking-orange);
            color: var(--booking-dark);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .property-favorite {
            position: absolute;
            top: 12px;
            right: 12px;
            background: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .property-favorite:hover {
            background: var(--booking-orange);
            color: white;
        }
        
        .property-content-booking {
            padding: 20px;
        }
        
        .property-title-booking {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--booking-dark);
            line-height: 1.4;
        }
        
        .property-location-booking {
            color: var(--booking-gray);
            font-size: 14px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .property-price-booking {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--booking-dark);
            margin-bottom: 12px;
        }
        
        .property-rating {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }
        
        .stars {
            color: var(--booking-orange);
        }
        
        .rating-number {
            font-weight: 600;
            color: var(--booking-dark);
        }
        
        .property-footer-booking {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid var(--booking-border);
        }
        
        .property-host {
            color: var(--booking-gray);
            font-size: 14px;
        }
        
        .btn-book-booking {
            background: var(--booking-blue);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-book-booking:hover {
            background: var(--booking-light-blue);
        }
        
        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: var(--booking-light-gray);
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }
        
        .feature-item {
            text-align: center;
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--booking-blue);
            margin-bottom: 20px;
        }
        
        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--booking-dark);
        }
        
        .feature-description {
            color: var(--booking-gray);
            line-height: 1.6;
        }
        
        /* Footer */
        .footer-booking {
            background: var(--booking-dark);
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-content {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 40px;
        }
        
        .footer-brand-booking {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 16px;
        }
        
        .footer-description {
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .footer-title {
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
            margin-top: 40px;
            padding-top: 30px;
            text-align: center;
            opacity: 0.8;
            font-size: 14px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-center {
                display: none;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .search-form-booking {
                grid-template-columns: 1fr;
            }
            
            .properties-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Header Booking Style -->
    <header class="booking-header">
        <div class="header-container">
            <div class="header-main">
                <a href="accueil_booking_international.php" class="logo-booking">
                    <i class="fas fa-home"></i>
                    TerangaHomes
                </a>
                
                <nav class="nav-center">
                    <a href="#" class="nav-item-booking active"><?= $t['properties'] ?></a>
                    <a href="#" class="nav-item-booking"><?= $t['experiences'] ?></a>
                    <a href="#" class="nav-item-booking"><?= $t['restaurants'] ?></a>
                </nav>
                
                <div class="header-right">
                    <div class="language-currency-selector">
                        <form method="POST" style="display: inline;">
                            <select name="lang" class="selector-dropdown" onchange="this.form.submit()">
                                <option value="fr" <?= $lang === 'fr' ? 'selected' : '' ?>>🇫🇷 FR</option>
                                <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>🇬🇧 EN</option>
                                <option value="es" <?= $lang === 'es' ? 'selected' : '' ?>>🇪🇸 ES</option>
                                <option value="ar" <?= $lang === 'ar' ? 'selected' : '' ?>>🇸🇦 AR</option>
                                <option value="zh" <?= $lang === 'zh' ? 'selected' : '' ?>>🇨🇳 ZH</option>
                                <option value="pt" <?= $lang === 'pt' ? 'selected' : '' ?>>🇵🇹 PT</option>
                            </select>
                            <select name="currency" class="selector-dropdown" onchange="this.form.submit()">
                                <option value="XOF" <?= $currency === 'XOF' ? 'selected' : '' ?>>FCFA</option>
                                <option value="EUR" <?= $currency === 'EUR' ? 'selected' : '' ?>>€ EUR</option>
                                <option value="USD" <?= $currency === 'USD' ? 'selected' : '' ?>>$ USD</option>
                                <option value="GBP" <?= $currency === 'GBP' ? 'selected' : '' ?>>£ GBP</option>
                                <option value="CAD" <?= $currency === 'CAD' ? 'selected' : '' ?>>C$ CAD</option>
                                <option value="AUD" <?= $currency === 'AUD' ? 'selected' : '' ?>>A$ AUD</option>
                                <option value="JPY" <?= $currency === 'JPY' ? 'selected' : '' ?>>¥ JPY</option>
                                <option value="CNY" <?= $currency === 'CNY' ? 'selected' : '' ?>>¥ CNY</option>
                            </select>
                        </form>
                    </div>
                    
                    <a href="connexion_simple.php" class="btn-booking btn-outline-booking">
                        <i class="fas fa-user me-2"></i><?= $t['login'] ?>
                    </a>
                    <a href="connexion_simple.php" class="btn-booking btn-primary-booking">
                        <i class="fas fa-plus me-2"></i><?= $t['list_property'] ?>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-booking">
        <div class="hero-content">
            <h1 class="hero-title"><?= $t['tagline'] ?></h1>
            <p class="hero-subtitle"><?= $stats['total_annonces'] ?>+ <?= strtolower($t['properties']) ?> • <?= $stats['total_users'] ?>+ <?= strtolower($t['host']) ?>s</p>
        </div>
    </section>

    <!-- Search Box -->
    <section style="margin: -40px auto 80px; position: relative; z-index: 10;">
        <div class="search-box-booking">
            <div class="search-tabs">
                <button class="search-tab active" data-type="all"><?= $t['properties'] ?></button>
                <button class="search-tab" data-type="location">🏠 <?= $t['properties'] ?></button>
                <button class="search-tab" data-type="hotel">🏨 Hôtels</button>
                <button class="search-tab" data-type="car">🚗 Voitures</button>
            </div>
            
            <form class="search-form-booking" method="GET" action="search_with_map.php">
                <div class="form-group-booking">
                    <label class="form-label-booking"><?= $t['search_placeholder'] ?></label>
                    <input type="text" class="form-control-booking" name="ville" placeholder="Dakar, Saly, Saint-Louis...">
                </div>
                
                <div class="form-group-booking">
                    <label class="form-label-booking"><?= $t['check_in'] ?></label>
                    <input type="date" class="form-control-booking" name="check_in">
                </div>
                
                <div class="form-group-booking">
                    <label class="form-label-booking"><?= $t['check_out'] ?></label>
                    <input type="date" class="form-control-booking" name="check_out">
                </div>
                
                <div class="form-group-booking">
                    <label class="form-label-booking"><?= $t['guests'] ?></label>
                    <select class="form-control-booking" name="guests">
                        <option value="1">1 <?= $lang === 'fr' ? 'voyageur' : 'guest' ?></option>
                        <option value="2">2 <?= $lang === 'fr' ? 'voyageurs' : 'guests' ?></option>
                        <option value="3">3 <?= $lang === 'fr' ? 'voyageurs' : 'guests' ?></option>
                        <option value="4">4+ <?= $lang === 'fr' ? 'voyageurs' : 'guests' ?></option>
                    </select>
                </div>
                
                <div class="form-group-booking">
                    <label class="form-label-booking"><?= $t['currency'] ?></label>
                    <select class="form-control-booking" name="currency_display">
                        <option value="<?= $currency ?>"><?= $currency_symbols[$currency] ?></option>
                    </select>
                </div>
                
                <button type="submit" class="btn-search-booking">
                    <i class="fas fa-search me-2"></i><?= $t['search_btn'] ?>
                </button>
            </form>
        </div>
    </section>

    <!-- Destinations Section -->
    <section class="destinations-section">
        <div class="section-header">
            <h2 class="section-title"><?= $t['popular_destinations'] ?></h2>
        </div>
        
        <div class="destinations-grid">
            <div class="destination-card" onclick="window.location.href='search_with_map.php?ville=Dakar'">
                <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400&h=300&fit=crop" alt="Dakar" class="destination-image">
                <div class="destination-overlay">
                    <h3 class="destination-name">Dakar</h3>
                    <p class="destination-count">250+ <?= strtolower($t['properties']) ?></p>
                </div>
            </div>
            
            <div class="destination-card" onclick="window.location.href='search_with_map.php?ville=Saly'">
                <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=300&fit=crop" alt="Saly" class="destination-image">
                <div class="destination-overlay">
                    <h3 class="destination-name">Saly</h3>
                    <p class="destination-count">180+ <?= strtolower($t['properties']) ?></p>
                </div>
            </div>
            
            <div class="destination-card" onclick="window.location.href='search_with_map.php?ville=Saint-Louis'">
                <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400&h=300&fit=crop" alt="Saint-Louis" class="destination-image">
                <div class="destination-overlay">
                    <h3 class="destination-name">Saint-Louis</h3>
                    <p class="destination-count">120+ <?= strtolower($t['properties']) ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Properties Section -->
    <section class="properties-section">
        <div class="section-header">
            <h2 class="section-title"><?= $t['featured_properties'] ?></h2>
        </div>
        
        <div class="properties-grid">
            <?php foreach ($annonces as $annonce): ?>
            <div class="property-card-booking" onclick="window.location.href='annonce_detail_maps.php?id=<?= $annonce['id'] ?>'">
                <div class="property-image-booking">
                    <?php 
                    $images = json_decode($annonce['images'] ?? '[]', true);
                    $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                    ?>
                    <img src="uploads/images/<?= $firstImage ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                    <div class="property-badge"><?= ucfirst($annonce['type']) ?></div>
                    <div class="property-favorite" onclick="event.stopPropagation(); toggleFavorite(this)">
                        <i class="far fa-heart"></i>
                    </div>
                </div>
                <div class="property-content-booking">
                    <h3 class="property-title-booking"><?= htmlspecialchars($annonce['titre']) ?></h3>
                    <div class="property-location-booking">
                        <i class="fas fa-map-marker-alt"></i>
                        <?= htmlspecialchars($annonce['ville']) ?>
                    </div>
                    
                    <div class="property-price-booking">
                        <?php 
                        $converted_price = convert_price($annonce['prix'], $currency, $exchange_rates);
                        echo format_price($converted_price, $currency, $currency_symbols[$currency], $lang);
                        ?>
                        <?php if ($annonce['type'] === 'location'): ?>
                            <small style="font-weight: 400; color: var(--booking-gray);">/ <?= $t['per_night'] ?></small>
                        <?php endif; ?>
                    </div>
                    
                    <div class="property-rating">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="rating-number">4.8 (24 <?= $t['reviews'] ?>)</span>
                    </div>
                    
                    <div class="property-footer-booking">
                        <span class="property-host">
                            <?= htmlspecialchars($annonce['proprietaire_prenom']) ?>
                        </span>
                        <button class="btn-book-booking" onclick="event.stopPropagation()">
                            <?= $t['book_now'] ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="section-header">
            <h2 class="section-title"><?= $t['why_choose_us'] ?></h2>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 class="feature-title"><?= $t['instant_booking'] ?></h3>
                <p class="feature-description"><?= $lang === 'fr' ? 'Réservez instantanément sans attendre' : 'Book instantly without waiting' ?></p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <h3 class="feature-title"><?= $t['best_prices'] ?></h3>
                <p class="feature-description"><?= $lang === 'fr' ? 'Tarifs compétitifs et transparents' : 'Competitive and transparent prices' ?></p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="feature-title"><?= $t['24_support'] ?></h3>
                <p class="feature-description"><?= $lang === 'fr' ? 'Support client disponible 24/7' : 'Customer support available 24/7' ?></p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title"><?= $t['secure_payment'] ?></h3>
                <p class="feature-description"><?= $lang === 'fr' ? 'Paiements sécurisés et protégés' : 'Secure and protected payments' ?></p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-booking">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-brand-booking">TerangaHomes</div>
                <p class="footer-description">
                    <?= $lang === 'fr' ? 'Votre plateforme de confiance pour la location, la vente et la réservation d\'hébergements au Sénégal.' : 'Your trusted platform for renting, buying and booking accommodations in Senegal.' ?>
                </p>
                <div style="display: flex; gap: 16px; margin-top: 20px;">
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h4 class="footer-title"><?= $t['properties'] ?></h4>
                <ul class="footer-links">
                    <li><a href="annonces_direct_fixed.php"><?= $lang === 'fr' ? 'Toutes les annonces' : 'All listings' ?></a></li>
                    <li><a href="search_with_map.php"><?= $t['search_btn'] ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Dernières annonces' : 'Latest listings' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Annonces populaires' : 'Popular listings' ?></a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4 class="footer-title"><?= $t['experiences'] ?></h4>
                <ul class="footer-links">
                    <li><a href="#"><?= $lang === 'fr' ? 'Tours guidés' : 'Guided tours' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Activités locales' : 'Local activities' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Événements' : 'Events' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Guide de voyage' : 'Travel guide' ?></a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4 class="footer-title"><?= $t['help'] ?></h4>
                <ul class="footer-links">
                    <li><a href="connexion_simple.php"><?= $t['login'] ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Centre d\'aide' : 'Help center' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'FAQ' : 'FAQ' ?></a></li>
                    <li><a href="#"><?= $lang === 'fr' ? 'Contact' : 'Contact' ?></a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4 class="footer-title"><?= $t['contact_host'] ?></h4>
                <ul class="footer-links">
                    <li><i class="fas fa-phone me-2"></i>+221 33 123 45 67</li>
                    <li><i class="fas fa-envelope me-2"></i>contact@terangahomes.com</li>
                    <li><i class="fas fa-map-marker-alt me-2"></i>Dakar, Sénégal</li>
                    <li><i class="fas fa-clock me-2"></i>24/7 <?= $t['24_support'] ?></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> TerangaHomes. <?= $lang === 'fr' ? 'Tous droits réservés.' : 'All rights reserved.' ?></p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Search tabs functionality
    document.querySelectorAll('.search-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.search-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const type = this.dataset.type;
            const typeSelect = document.querySelector('select[name="type"]');
            if (typeSelect) {
                typeSelect.value = type === 'all' ? '' : type;
            }
        });
    });
    
    function toggleFavorite(element) {
        const icon = element.querySelector('i');
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            element.style.background = 'var(--booking-orange)';
            element.style.color = 'var(--booking-dark)';
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            element.style.background = 'white';
            element.style.color = '#333';
        }
    }
    
    // Set minimum date for check-in (today)
    const today = new Date().toISOString().split('T')[0];
    const checkInInput = document.querySelector('input[name="check_in"]');
    const checkOutInput = document.querySelector('input[name="check_out"]');
    
    if (checkInInput) {
        checkInInput.min = today;
    }
    
    if (checkOutInput) {
        checkOutInput.min = today;
    }
    
    // Update check-out min date when check-in changes
    if (checkInInput) {
        checkInInput.addEventListener('change', function() {
            if (checkOutInput) {
                checkOutInput.min = this.value;
                if (checkOutInput.value && checkOutInput.value < this.value) {
                    checkOutInput.value = this.value;
                }
            }
        });
    }
    </script>
</body>
</html>
