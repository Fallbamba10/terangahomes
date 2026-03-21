<?php
// Page d'accueil Booking International - Version corrigée

session_start();

// Forcer le mode visiteur - détruire toute session existante
if (isset($_SESSION['user_id'])) {
    session_destroy();
    session_start();
}

// Détecter la langue de l'utilisateur
$default_lang = 'fr';
$supported_langs = [
    'fr' => 'Français', 'en' => 'English', 'es' => 'Español', 'ar' => 'العربية', 
    'zh' => '中文', 'pt' => 'Português', 'de' => 'Deutsch', 'it' => 'Italiano',
    'nl' => 'Nederlands', 'ru' => 'Русский', 'ja' => '日本語', 'ko' => '한국어',
    'hi' => 'हिन्दी', 'tr' => 'Türkçe', 'pl' => 'Polski', 'sv' => 'Svenska',
    'no' => 'Norsk', 'da' => 'Dansk', 'fi' => 'Suomi', 'el' => 'Ελληνικά',
    'he' => 'עברית', 'th' => 'ไทย', 'vi' => 'Tiếng Việt', 'id' => 'Bahasa Indonesia',
    'ms' => 'Bahasa Melayu', 'cs' => 'Čeština', 'hu' => 'Magyar', 'ro' => 'Română',
    'bg' => 'Български', 'hr' => 'Hrvatski', 'sr' => 'Српски', 'sk' => 'Slovenčina',
    'et' => 'Eesti', 'lv' => 'Latviešu', 'lt' => 'Lietuvių', 'uk' => 'Українська',
    'be' => 'Беларуская', 'ka' => 'ქართული', 'am' => 'አማርኛ', 'sw' => 'Kiswahili',
    'zu' => 'isiZulu', 'af' => 'Afrikaans', 'is' => 'Íslenska', 'mt' => 'Malti',
    'cy' => 'Cymraeg', 'ga' => 'Gaeilge', 'gd' => 'Gàidhlig', 'eu' => 'Euskara',
    'ca' => 'Català', 'gl' => 'Galego', 'ast' => 'Asturianu', 'lb' => 'Lëtzebuergesch'
];

// Détecter la langue depuis le navigateur ou la session
if (isset($_SESSION['lang']) && array_key_exists($_SESSION['lang'], $supported_langs)) {
    $lang = $_SESSION['lang'];
} else {
    $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'fr', 0, 2);
    $lang = array_key_exists($browser_lang, $supported_langs) ? $browser_lang : $default_lang;
    $_SESSION['lang'] = $lang;
}

// Détecter la devise
$default_currency = 'XOF';
$supported_currencies = [
    'XOF' => ['name' => 'FCFA', 'countries' => ['SN', 'ML', 'BF', 'NE', 'CI', 'TG', 'BJ', 'GW', 'MR']],
    'EUR' => ['name' => 'Euro', 'countries' => ['FR', 'DE', 'IT', 'ES', 'BE', 'NL', 'AT', 'PT', 'IE', 'FI', 'GR', 'LU', 'CY', 'MT', 'SI', 'SK', 'EE', 'LV', 'LT']],
    'USD' => ['name' => 'Dollar', 'countries' => ['US', 'CA', 'AU', 'NZ', 'SG', 'HK', 'TW', 'KR', 'PH', 'MY', 'TH', 'VN', 'ID', 'IN', 'PK', 'BD', 'LK', 'NP', 'BT', 'MV']],
    'GBP' => ['name' => 'Pound', 'countries' => ['GB', 'JE', 'GG', 'IM']],
    'CAD' => ['name' => 'Dollar CAD', 'countries' => ['CA']],
    'AUD' => ['name' => 'Dollar AUD', 'countries' => ['AU', 'NZ', 'FJ', 'PG', 'SB', 'VU', 'NC', 'PF', 'WS', 'TO', 'KI', 'TV', 'NR', 'PW', 'FM', 'MH']],
    'JPY' => ['name' => 'Yen', 'countries' => ['JP']],
    'CNY' => ['name' => 'Yuan', 'countries' => ['CN']],
    'INR' => ['name' => 'Rupee', 'countries' => ['IN']],
    'BRL' => ['name' => 'Real', 'countries' => ['BR']],
    'MXN' => ['name' => 'Peso', 'countries' => ['MX']],
    'ARS' => ['name' => 'Peso', 'countries' => ['AR']],
    'CLP' => ['name' => 'Peso', 'countries' => ['CL']],
    'COP' => ['name' => 'Peso', 'countries' => ['CO']],
    'PEN' => ['name' => 'Sol', 'countries' => ['PE']],
    'UYU' => ['name' => 'Peso', 'countries' => ['UY']],
    'PYG' => ['name' => 'Guarani', 'countries' => ['PY']],
    'BOB' => ['name' => 'Boliviano', 'countries' => ['BO']],
    'VES' => ['name' => 'Bolívar', 'countries' => ['VE']],
    'CRC' => ['name' => 'Colón', 'countries' => ['CR']],
    'GTQ' => ['name' => 'Quetzal', 'countries' => ['GT']],
    'HNL' => ['name' => 'Lempira', 'countries' => ['HN']],
    'NIO' => ['name' => 'Córdoba', 'countries' => ['NI']],
    'PAB' => ['name' => 'Balboa', 'countries' => ['PA']],
    'DOP' => ['name' => 'Peso', 'countries' => ['DO']],
    'JMD' => ['name' => 'Dollar', 'countries' => ['JM']],
    'TTD' => ['name' => 'Dollar', 'countries' => ['TT']],
    'BBD' => ['name' => 'Dollar', 'countries' => ['BB']],
    'XCD' => ['name' => 'Dollar', 'countries' => ['AG', 'DM', 'GD', 'KN', 'LC', 'VC']],
    'BSD' => ['name' => 'Dollar', 'countries' => ['BS']],
    'KYD' => ['name' => 'Dollar', 'countries' => ['KY']],
    'BMD' => ['name' => 'Dollar', 'countries' => ['BM']],
    'ANG' => ['name' => 'Guilder', 'countries' => ['CW', 'SX', 'BQ']],
    'AWG' => ['name' => 'Florin', 'countries' => ['AW']],
    'SRD' => ['name' => 'Dollar', 'countries' => ['SR']],
    'GYD' => ['name' => 'Dollar', 'countries' => ['GY']],
    'TWD' => ['name' => 'Dollar', 'countries' => ['TW']],
    'HKD' => ['name' => 'Dollar', 'countries' => ['HK']],
    'SGD' => ['name' => 'Dollar', 'countries' => ['SG']],
    'MYR' => ['name' => 'Ringgit', 'countries' => ['MY']],
    'THB' => ['name' => 'Baht', 'countries' => ['TH']],
    'VND' => ['name' => 'Dong', 'countries' => ['VN']],
    'IDR' => ['name' => 'Rupiah', 'countries' => ['ID']],
    'PHP' => ['name' => 'Peso', 'countries' => ['PH']],
    'LKR' => ['name' => 'Rupee', 'countries' => ['LK']],
    'PKR' => ['name' => 'Rupee', 'countries' => ['PK']],
    'BDT' => ['name' => 'Taka', 'countries' => ['BD']],
    'NPR' => ['name' => 'Rupee', 'countries' => ['NP']],
    'BTN' => ['name' => 'Ngultrum', 'countries' => ['BT']],
    'MVR' => ['name' => 'Rufiyaa', 'countries' => ['MV']],
    'KRW' => ['name' => 'Won', 'countries' => ['KR']],
    'MNT' => ['name' => 'Tugrik', 'countries' => ['MN']],
    'KZT' => ['name' => 'Tenge', 'countries' => ['KZ']],
    'KGS' => ['name' => 'Som', 'countries' => ['KG']],
    'UZS' => ['name' => 'Som', 'countries' => ['UZ']],
    'TJS' => ['name' => 'Somoni', 'countries' => ['TJ']],
    'AFN' => ['name' => 'Afghani', 'countries' => ['AF']],
    'IRR' => ['name' => 'Rial', 'countries' => ['IR']],
    'IQD' => ['name' => 'Dinar', 'countries' => ['IQ']],
    'SAR' => ['name' => 'Riyal', 'countries' => ['SA']],
    'KWD' => ['name' => 'Dinar', 'countries' => ['KW']],
    'BHD' => ['name' => 'Dinar', 'countries' => ['BH']],
    'QAR' => ['name' => 'Riyal', 'countries' => ['QA']],
    'AED' => ['name' => 'Dirham', 'countries' => ['AE']],
    'OMR' => ['name' => 'Rial', 'countries' => ['OM']],
    'JOD' => ['name' => 'Dinar', 'countries' => ['JO']],
    'LBP' => ['name' => 'Pound', 'countries' => ['LB']],
    'SYP' => ['name' => 'Pound', 'countries' => ['SY']],
    'EGP' => ['name' => 'Pound', 'countries' => ['EG']],
    'SDD' => ['name' => 'Dinar', 'countries' => ['SD']],
    'LYD' => ['name' => 'Dinar', 'countries' => ['LY']],
    'TND' => ['name' => 'Dinar', 'countries' => ['TN']],
    'DZD' => ['name' => 'Dinar', 'countries' => ['DZ']],
    'MAD' => ['name' => 'Dirham', 'countries' => ['MA']],
    'ZAR' => ['name' => 'Rand', 'countries' => ['ZA', 'LS', 'SZ']],
    'BWP' => ['name' => 'Pula', 'countries' => ['BW']],
    'NAD' => ['name' => 'Dollar', 'countries' => ['NA']],
    'SZL' => ['name' => 'Lilangeni', 'countries' => ['SZ']],
    'AOA' => ['name' => 'Kwanza', 'countries' => ['AO']],
    'XAF' => ['name' => 'CFA', 'countries' => ['CM', 'CF', 'TD', 'GQ', 'GA']],
    'XPF' => ['name' => 'CFP', 'countries' => ['NC', 'PF', 'WF']],
    'SCR' => ['name' => 'Rupee', 'countries' => ['SC']],
    'MUR' => ['name' => 'Rupee', 'countries' => ['MU']],
    'KES' => ['name' => 'Shilling', 'countries' => ['KE']],
    'UGX' => ['name' => 'Shilling', 'countries' => ['UG']],
    'TZS' => ['name' => 'Shilling', 'countries' => ['TZ']],
    'RWF' => ['name' => 'Franc', 'countries' => ['RW']],
    'BIF' => ['name' => 'Franc', 'countries' => ['BI']],
    'DJF' => ['name' => 'Franc', 'countries' => ['DJ']],
    'ERN' => ['name' => 'Nakfa', 'countries' => ['ER']],
    'ETB' => ['name' => 'Birr', 'countries' => ['ET']],
    'SOS' => ['name' => 'Shilling', 'countries' => ['SO']],
    'GMD' => ['name' => 'Dalasi', 'countries' => ['GM']],
    'GNF' => ['name' => 'Franc', 'countries' => ['GN']],
    'LRD' => ['name' => 'Dollar', 'countries' => ['LR']],
    'SLL' => ['name' => 'Leone', 'countries' => ['SL']],
    'CVE' => ['name' => 'Escudo', 'countries' => ['CV']],
    'STN' => ['name' => 'Dobra', 'countries' => ['ST']],
    'GHS' => ['name' => 'Cedi', 'countries' => ['GH']],
    'NGN' => ['name' => 'Naira', 'countries' => ['NG']],
    'XOF' => ['name' => 'FCFA', 'countries' => ['SN', 'ML', 'BF', 'NE', 'CI', 'TG', 'BJ', 'GW', 'MR']],
    'ZMW' => ['name' => 'Kwacha', 'countries' => ['ZM']],
    'MWK' => ['name' => 'Kwacha', 'countries' => ['MW']],
    'BZD' => ['name' => 'Dollar', 'countries' => ['BZ']],
    'GTQ' => ['name' => 'Quetzal', 'countries' => ['GT']],
    'HNL' => ['name' => 'Lempira', 'countries' => ['HN']],
    'NIO' => ['name' => 'Córdoba', 'countries' => ['NI']],
    'CRC' => ['name' => 'Colón', 'countries' => ['CR']],
    'PAB' => ['name' => 'Balboa', 'countries' => ['PA']],
    'DOP' => ['name' => 'Peso', 'countries' => ['DO']],
    'HTG' => ['name' => 'Gourde', 'countries' => ['HT']],
    'XCD' => ['name' => 'Dollar', 'countries' => ['AG', 'DM', 'GD', 'KN', 'LC', 'VC']],
    'JMD' => ['name' => 'Dollar', 'countries' => ['JM']],
    'TTD' => ['name' => 'Dollar', 'countries' => ['TT']],
    'BBD' => ['name' => 'Dollar', 'countries' => ['BB']],
    'KYD' => ['name' => 'Dollar', 'countries' => ['KY']],
    'BMD' => ['name' => 'Dollar', 'countries' => ['BM']],
    'ANG' => ['name' => 'Guilder', 'countries' => ['CW', 'SX', 'BQ']],
    'AWG' => ['name' => 'Florin', 'countries' => ['AW']],
    'SRD' => ['name' => 'Dollar', 'countries' => ['SR']],
    'GYD' => ['name' => 'Dollar', 'countries' => ['GY']],
    'FKP' => ['name' => 'Pound', 'countries' => ['FK']],
    'GIP' => ['name' => 'Pound', 'countries' => ['GI']],
    'SHP' => ['name' => 'Pound', 'countries' => ['SH']],
    'SBD' => ['name' => 'Dollar', 'countries' => ['SB']],
    'VUV' => ['name' => 'Vatu', 'countries' => ['VU']],
    'WST' => ['name' => 'Tala', 'countries' => ['WS']],
    'TOP' => ['name' => 'Paʻanga', 'countries' => ['TO']],
    'KID' => ['name' => 'Dollar', 'countries' => ['KI']],
    'TVD' => ['name' => 'Dollar', 'countries' => ['TV']],
    'NRD' => ['name' => 'Dollar', 'countries' => ['NR']],
    'PWD' => ['name' => 'Dollar', 'countries' => ['PW']],
    'FMD' => ['name' => 'Dollar', 'countries' => ['FM']],
    'MHD' => ['name' => 'Dollar', 'countries' => ['MH']],
    'AUD' => ['name' => 'Dollar', 'countries' => ['AU', 'NR', 'TV', 'KI', 'PW', 'FM', 'MH', 'FJ', 'PG', 'SB', 'VU', 'NC', 'PF', 'WS', 'TO']],
    'NZD' => ['name' => 'Dollar', 'countries' => ['NZ', 'CK', 'NU', 'TK']],
    'FJD' => ['name' => 'Dollar', 'countries' => ['FJ']],
    'PGK' => ['name' => 'Kina', 'countries' => ['PG']],
    'SBD' => ['name' => 'Dollar', 'countries' => ['SB']],
    'VUV' => ['name' => 'Vatu', 'countries' => ['VU']],
    'WST' => ['name' => 'Tala', 'countries' => ['WS']],
    'TOP' => ['name' => 'Paʻanga', 'countries' => ['TO']],
    'KID' => ['name' => 'Dollar', 'countries' => ['KI']],
    'TVD' => ['name' => 'Dollar', 'countries' => ['TV']],
    'NRD' => ['name' => 'Dollar', 'countries' => ['NR']],
    'PWD' => ['name' => 'Dollar', 'countries' => ['PW']],
    'FMD' => ['name' => 'Dollar', 'countries' => ['FM']],
    'MHD' => ['name' => 'Dollar', 'countries' => ['MH']],
    'NCL' => ['name' => 'Franc', 'countries' => ['NC']],
    'PYF' => ['name' => 'Franc', 'countries' => ['PF']],
    'WLF' => ['name' => 'Franc', 'countries' => ['WF']],
    'CUC' => ['name' => 'Peso', 'countries' => ['CU']],
    'CUP' => ['name' => 'Peso', 'countries' => ['CU']],
    'USD' => ['name' => 'Dollar', 'countries' => ['US', 'EC', 'SV', 'PA', 'PW', 'FM', 'MH', 'MP', 'PR', 'TL', 'UM', 'VI', 'VG', 'VI']],
    'EUR' => ['name' => 'Euro', 'countries' => ['AD', 'AT', 'AX', 'BE', 'BL', 'HR', 'CY', 'CZ', 'EE', 'FI', 'FR', 'GF', 'DE', 'GR', 'GP', 'GG', 'HU', 'IE', 'IT', 'JE', 'LV', 'LI', 'LT', 'LU', 'MT', 'MQ', 'YT', 'MC', 'ME', 'NL', 'NC', 'MK', 'NO', 'PF', 'PL', 'PT', 'RE', 'SM', 'RS', 'SK', 'SI', 'SJ', 'ES', 'SE', 'CH', 'TR', 'VA', 'WF']],
    'GBP' => ['name' => 'Pound', 'countries' => ['GB', 'GG', 'IM', 'JE']],
    'SEK' => ['name' => 'Krona', 'countries' => ['SE']],
    'NOK' => ['name' => 'Krone', 'countries' => ['NO', 'SJ', 'BV']],
    'DKK' => ['name' => 'Krone', 'countries' => ['DK', 'FO', 'GL']],
    'ISK' => ['name' => 'Krona', 'countries' => ['IS']],
    'CHF' => ['name' => 'Franc', 'countries' => ['CH', 'LI']],
    'RUB' => ['name' => 'Ruble', 'countries' => ['RU', 'BY']],
    'BYN' => ['name' => 'Ruble', 'countries' => ['BY']],
    'UAH' => ['name' => 'Hryvnia', 'countries' => ['UA']],
    'MDL' => ['name' => 'Leu', 'countries' => ['MD']],
    'RON' => ['name' => 'Leu', 'countries' => ['RO']],
    'BGN' => ['name' => 'Lev', 'countries' => ['BG']],
    'HRK' => ['name' => 'Kuna', 'countries' => ['HR']],
    'CZK' => ['name' => 'Koruna', 'countries' => ['CZ']],
    'HUF' => ['name' => 'Forint', 'countries' => ['HU']],
    'PLN' => ['name' => 'Zloty', 'countries' => ['PL']],
    'RSD' => ['name' => 'Dinar', 'countries' => ['RS']],
    'MKD' => ['name' => 'Denar', 'countries' => ['MK']],
    'ALL' => ['name' => 'Lek', 'countries' => ['AL']],
    'BAM' => ['name' => 'Mark', 'countries' => ['BA']],
    'EUR' => ['name' => 'Euro', 'countries' => ['ME']],
    'GEL' => ['name' => 'Lari', 'countries' => ['GE']],
    'AMD' => ['name' => 'Dram', 'countries' => ['AM']],
    'AZN' => ['name' => 'Manat', 'countries' => ['AZ']],
    'TRY' => ['name' => 'Lira', 'countries' => ['TR']],
    'CYP' => ['name' => 'Pound', 'countries' => ['CY']],
    'EUR' => ['name' => 'Euro', 'countries' => ['CY']]
];

if (isset($_SESSION['currency']) && array_key_exists($_SESSION['currency'], $supported_currencies)) {
    $currency = $_SESSION['currency'];
} else {
    // Détecter depuis le pays de l'utilisateur
    $user_country = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? 'SN'; // Cloudflare ou défaut Sénégal
    
    // Trouver la devise pour ce pays
    $currency = $default_currency;
    foreach ($supported_currencies as $code => $data) {
        if (in_array($user_country, $data['countries'])) {
            $currency = $code;
            break;
        }
    }
    $_SESSION['currency'] = $currency;
}

// Traitement du changement de langue/devise
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['lang'])) {
        $new_lang = $_POST['lang'];
        if (array_key_exists($new_lang, $supported_langs)) {
            $_SESSION['lang'] = $new_lang;
            $lang = $new_lang;
        }
    }
    
    if (isset($_POST['currency'])) {
        $new_currency = $_POST['currency'];
        if (array_key_exists($new_currency, $supported_currencies)) {
            $_SESSION['currency'] = $new_currency;
            $currency = $new_currency;
        }
    }
    
    // Rediriger pour éviter la repost du formulaire
    header('Location: accueil_booking_fixed.php');
    exit;
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
    'CNY' => 0.011,    // 1 XOF = 0.011 CNY
    'INR' => 0.13,     // 1 XOF = 0.13 INR
    'BRL' => 0.008,    // 1 XOF = 0.008 BRL
    'MXN' => 0.032,    // 1 XOF = 0.032 MXN
    'ARS' => 0.14,     // 1 XOF = 0.14 ARS
    'CLP' => 1.3,      // 1 XOF = 1.3 CLP
    'COP' => 5.8,      // 1 XOF = 5.8 COP
    'PEN' => 0.006,    // 1 XOF = 0.006 PEN
    'UYU' => 0.064,    // 1 XOF = 0.064 UYU
    'PYG' => 11.5,     // 1 XOF = 11.5 PYG
    'BOB' => 0.011,    // 1 XOF = 0.011 BOB
    'VES' => 0.0004,   // 1 XOF = 0.0004 VES
    'CRC' => 2.7,      // 1 XOF = 2.7 CRC
    'GTQ' => 0.012,    // 1 XOF = 0.012 GTQ
    'HNL' => 0.041,    // 1 XOF = 0.041 HNL
    'NIO' => 0.046,    // 1 XOF = 0.046 NIO
    'PAB' => 0.0016,   // 1 XOF = 0.0016 PAB
    'DOP' => 0.091,    // 1 XOF = 0.091 DOP
    'JMD' => 0.24,     // 1 XOF = 0.24 JMD
    'TTD' => 0.011,    // 1 XOF = 0.011 TTD
    'BBD' => 0.0032,   // 1 XOF = 0.0032 BBD
    'XCD' => 0.0043,   // 1 XOF = 0.0043 XCD
    'BSD' => 0.0016,   // 1 XOF = 0.0016 BSD
    'KYD' => 0.0013,   // 1 XOF = 0.0013 KYD
    'BMD' => 0.0016,   // 1 XOF = 0.0016 BMD
    'ANG' => 0.0029,   // 1 XOF = 0.0029 ANG
    'AWG' => 0.0029,   // 1 XOF = 0.0029 AWG
    'SRD' => 0.12,     // 1 XOF = 0.12 SRD
    'GYD' => 0.33,     // 1 XOF = 0.33 GYD
    'TWD' => 0.051,    // 1 XOF = 0.051 TWD
    'HKD' => 0.012,    // 1 XOF = 0.012 HKD
    'SGD' => 0.0022,   // 1 XOF = 0.0022 SGD
    'MYR' => 0.0075,   // 1 XOF = 0.0075 MYR
    'THB' => 0.058,    // 1 XOF = 0.058 THB
    'VND' => 38,       // 1 XOF = 38 VND
    'IDR' => 25,       // 1 XOF = 25 IDR
    'PHP' => 0.091,    // 1 XOF = 0.091 PHP
    'LKR' => 0.59,     // 1 XOF = 0.59 LKR
    'PKR' => 0.44,     // 1 XOF = 0.44 PKR
    'BDT' => 0.19,     // 1 XOF = 0.19 BDT
    'NPR' => 0.24,     // 1 XOF = 0.24 NPR
    'BTN' => 0.12,     // 1 XOF = 0.12 BTN
    'MVR' => 0.025,    // 1 XOF = 0.025 MVR
    'KRW' => 2.1,      // 1 XOF = 2.1 KRW
    'MNT' => 5.8,      // 1 XOF = 5.8 MNT
    'KZT' => 0.75,     // 1 XOF = 0.75 KZT
    'KGS' => 0.14,     // 1 XOF = 0.14 KGS
    'UZS' => 13,       // 1 XOF = 13 UZS
    'TJS' => 0.015,    // 1 XOF = 0.015 TJS
    'AFN' => 0.12,     // 1 XOF = 0.12 AFN
    'IRR' => 67,       // 1 XOF = 67 IRR
    'IQD' => 2.1,      // 1 XOF = 2.1 IQD
    'SAR' => 0.006,    // 1 XOF = 0.006 SAR
    'KWD' => 0.0005,   // 1 XOF = 0.0005 KWD
    'BHD' => 0.0006,   // 1 XOF = 0.0006 BHD
    'QAR' => 0.0058,   // 1 XOF = 0.0058 QAR
    'AED' => 0.0059,   // 1 XOF = 0.0059 AED
    'OMR' => 0.0006,   // 1 XOF = 0.0006 OMR
    'JOD' => 0.0011,   // 1 XOF = 0.0011 JOD
    'LBP' => 24,       // 1 XOF = 24 LBP
    'SYP' => 2.1,      // 1 XOF = 2.1 SYP
    'EGP' => 0.05,     // 1 XOF = 0.05 EGP
    'SDD' => 0.09,     // 1 XOF = 0.09 SDD
    'LYD' => 0.0008,   // 1 XOF = 0.0008 LYD
    'TND' => 0.005,    // 1 XOF = 0.005 TND
    'DZD' => 0.21,     // 1 XOF = 0.21 DZD
    'MAD' => 0.016,    // 1 XOF = 0.016 MAD
    'ZAR' => 0.03,     // 1 XOF = 0.03 ZAR
    'BWP' => 0.021,    // 1 XOF = 0.021 BWP
    'NAD' => 0.03,     // 1 XOF = 0.03 NAD
    'SZL' => 0.03,     // 1 XOF = 0.03 SZL
    'AOA' => 0.012,    // 1 XOF = 0.012 AOA
    'XAF' => 0.0015,   // 1 XOF = 0.0015 XAF
    'XPF' => 0.18,     // 1 XOF = 0.18 XPF
    'SCR' => 0.022,    // 1 XOF = 0.022 SCR
    'MUR' => 0.073,    // 1 XOF = 0.073 MUR
    'KES' => 0.19,     // 1 XOF = 0.19 KES
    'UGX' => 6.1,      // 1 XOF = 6.1 UGX
    'TZS' => 3.7,      // 1 XOF = 3.7 TZS
    'RWF' => 1.6,      // 1 XOF = 1.6 RWF
    'BIF' => 3.0,      // 1 XOF = 3.0 BIF
    'DJF' => 0.29,     // 1 XOF = 0.29 DJF
    'ERN' => 0.024,    // 1 XOF = 0.024 ERN
    'ETB' => 0.09,     // 1 XOF = 0.09 ETB
    'SOS' => 0.09,     // 1 XOF = 0.09 SOS
    'GMD' => 0.12,     // 1 XOF = 0.12 GMD
    'GNF' => 8.7,      // 1 XOF = 8.7 GNF
    'LRD' => 0.27,     // 1 XOF = 0.27 LRD
    'SLL' => 0.19,     // 1 XOF = 0.19 SLL
    'CVE' => 0.018,    // 1 XOF = 0.018 CVE
    'STN' => 0.043,    // 1 XOF = 0.043 STN
    'GHS' => 0.019,    // 1 XOF = 0.019 GHS
    'NGN' => 0.73,     // 1 XOF = 0.73 NGN
    'ZMW' => 0.03,     // 1 XOF = 0.03 ZMW
    'MWK' => 2.5,      // 1 XOF = 2.5 MWK
    'BZD' => 0.0032,   // 1 XOF = 0.0032 BZD
    'HTG' => 0.13,     // 1 XOF = 0.13 HTG
    'FKP' => 0.0013,   // 1 XOF = 0.0013 FKP
    'GIP' => 0.0013,   // 1 XOF = 0.0013 GIP
    'SHP' => 0.0013,   // 1 XOF = 0.0013 SHP
    'PGK' => 0.0055,   // 1 XOF = 0.0055 PGK
    'WST' => 0.0045,   // 1 XOF = 0.0045 WST
    'TOP' => 0.0038,   // 1 XOF = 0.0038 TOP
    'KID' => 0.0013,   // 1 XOF = 0.0013 KID
    'TVD' => 0.0021,   // 1 XOF = 0.0021 TVD
    'NRD' => 0.0021,   // 1 XOF = 0.0021 NRD
    'FJD' => 0.0035,   // 1 XOF = 0.0035 FJD
    'NZD' => 0.0026,   // 1 XOF = 0.0026 NZD
    'SEK' => 0.017,    // 1 XOF = 0.017 SEK
    'NOK' => 0.017,    // 1 XOF = 0.017 NOK
    'DKK' => 0.012,    // 1 XOF = 0.012 DKK
    'ISK' => 0.22,     // 1 XOF = 0.22 ISK
    'CHF' => 0.0015,   // 1 XOF = 0.0015 CHF
    'RUB' => 0.14,     // 1 XOF = 0.14 RUB
    'BYN' => 0.005,    // 1 XOF = 0.005 BYN
    'UAH' => 0.062,    // 1 XOF = 0.062 UAH
    'MDL' => 0.028,    // 1 XOF = 0.028 MDL
    'RON' => 0.0075,   // 1 XOF = 0.0075 RON
    'BGN' => 0.0029,   // 1 XOF = 0.0029 BGN
    'HRK' => 0.011,    // 1 XOF = 0.011 HRK
    'CZK' => 0.037,    // 1 XOF = 0.037 CZK
    'HUF' => 0.58,     // 1 XOF = 0.58 HUF
    'PLN' => 0.0066,   // 1 XOF = 0.0066 PLN
    'RSD' => 0.015,    // 1 XOF = 0.015 RSD
    'MKD' => 0.026,    // 1 XOF = 0.026 MKD
    'ALL' => 0.16,     // 1 XOF = 0.16 ALL
    'BAM' => 0.0029,   // 1 XOF = 0.0029 BAM
    'GEL' => 0.0043,   // 1 XOF = 0.0043 GEL
    'AMD' => 0.63,     // 1 XOF = 0.63 AMD
    'AZN' => 0.0027,   // 1 XOF = 0.0027 AZN
    'TRY' => 0.053,    // 1 XOF = 0.053 TRY
    'CUC' => 0.0016,   // 1 XOF = 0.0016 CUC
    'CUP' => 0.0016    // 1 XOF = 0.0016 CUP
];

// Symboles de devise
$currency_symbols = [
    'XOF' => 'FCFA', 'EUR' => '€', 'USD' => '$', 'GBP' => '£', 'CAD' => 'C$', 'AUD' => 'A$', 'JPY' => '¥', 'CNY' => '¥',
    'INR' => '₹', 'BRL' => 'R$', 'MXN' => '$', 'ARS' => '$', 'CLP' => '$', 'COP' => '$', 'PEN' => 'S/', 'UYU' => '$',
    'PYG' => '₲', 'BOB' => 'Bs', 'VES' => 'Bs', 'CRC' => '₡', 'GTQ' => 'Q', 'HNL' => 'L', 'NIO' => 'C$', 'PAB' => 'B/',
    'DOP' => 'RD$', 'JMD' => 'J$', 'TTD' => 'TT$', 'BBD' => 'Bds$', 'XCD' => 'EC$', 'BSD' => 'B$', 'KYD' => 'CI$',
    'BMD' => 'BD$', 'ANG' => 'ƒ', 'AWG' => 'ƒ', 'SRD' => '$', 'GYD' => 'G$', 'TWD' => 'NT$', 'HKD' => 'HK$',
    'SGD' => 'S$', 'MYR' => 'RM', 'THB' => '฿', 'VND' => '₫', 'IDR' => 'Rp', 'PHP' => '₱', 'LKR' => 'Rs',
    'PKR' => 'Rs', 'BDT' => '৳', 'NPR' => 'Rs', 'BTN' => 'Nu', 'MVR' => 'Rf', 'KRW' => '₩', 'MNT' => '₮',
    'KZT' => '₸', 'KGS' => 'с', 'UZS' => 'so\'m', 'TJS' => 'SM', 'AFN' => '؋', 'IRR' => '﷼', 'IQD' => 'ع.د',
    'SAR' => '﷼', 'KWD' => 'د.ك', 'BHD' => 'د.ب', 'QAR' => '﷼', 'AED' => 'د.إ', 'OMR' => 'ر.ع', 'JOD' => 'د.ا',
    'LBP' => 'ل.ل', 'SYP' => '£', 'EGP' => 'ج.م', 'SDD' => 'ج.س', 'LYD' => 'د.ل', 'TND' => 'د.ت', 'DZD' => 'د.ج',
    'MAD' => 'د.م', 'ZAR' => 'R', 'BWP' => 'P', 'NAD' => 'N$', 'SZL' => 'E', 'AOA' => 'Kz', 'XAF' => 'FCFA',
    'XPF' => '₣', 'SCR' => '₨', 'MUR' => '₨', 'KES' => 'KSh', 'UGX' => 'USh', 'TZS' => 'TSh', 'RWF' => 'R₣',
    'BIF' => 'FBu', 'DJF' => 'Fdj', 'ERN' => 'Nfk', 'ETB' => 'Br', 'SOS' => 'Sh.so', 'GMD' => 'D', 'GNF' => 'FG',
    'LRD' => 'L$', 'SLL' => 'Le', 'CVE' => '$', 'STN' => 'Db', 'GHS' => 'GH₵', 'NGN' => '₦', 'ZMW' => 'ZK',
    'MWK' => 'MK', 'BZD' => 'BZ$', 'HTG' => 'G', 'FKP' => '£', 'GIP' => '£', 'SHP' => '£', 'PGK' => 'K',
    'WST' => 'WS$', 'TOP' => 'T$', 'KID' => '$', 'TVD' => '$', 'NRD' => '$', 'FJD' => 'FJ$', 'NZD' => 'NZ$',
    'SEK' => 'kr', 'NOK' => 'kr', 'DKK' => 'kr', 'ISK' => 'kr', 'CHF' => 'CHF', 'RUB' => '₽', 'BYN' => 'Br',
    'UAH' => '₴', 'MDL' => 'L', 'RON' => 'lei', 'BGN' => 'лв', 'HRK' => 'kn', 'CZK' => 'Kč', 'HUF' => 'Ft',
    'PLN' => 'zł', 'RSD' => 'дин', 'MKD' => 'ден', 'ALL' => 'L', 'BAM' => 'KM', 'GEL' => '₾', 'AMD' => '֏',
    'AZN' => '₼', 'TRY' => '₺', 'CUC' => 'C$', 'CUP' => '₱'
];

// Fonction de conversion
function convert_price($price_xof, $to_currency, $rates) {
    return $price_xof * $rates[$to_currency];
}

// Fonction de formatage
function format_price($price, $currency, $symbol, $lang) {
    $formatted = match($currency) {
        'XOF' => number_format($price, 0, '.', ' ') . ' ' . $symbol,
        'EUR' => number_format($price, 0, '.', ' ') . ' ' . $symbol,
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
        
        /* Container widths - Booking.com style */
        .booking-container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 32px;
        }
        
        .booking-container-narrow {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 32px;
        }
        
        .booking-container-wide {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 32px;
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
            padding: 12px 0;
            max-width: 1180px;
            margin: 0 auto;
        }
        
        .logo-booking {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--booking-blue);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: color 0.2s ease;
        }
        
        .logo-booking:hover {
            color: var(--booking-light-blue);
        }
        
        .logo-booking i {
            margin-right: 8px;
            font-size: 1.5rem;
        }
        
        .nav-center {
            display: flex;
            gap: 24px;
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
            position: relative;
        }
        
        .nav-item-booking:hover {
            background: var(--booking-light-gray);
            color: var(--booking-blue);
        }
        
        .nav-item-booking.active {
            background: var(--booking-light-blue);
            color: white;
        }
        
        .nav-item-booking.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: white;
            border-radius: 2px;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .language-currency-selector {
            display: flex;
            gap: 8px;
            align-items: center;
            padding: 0 8px;
            border-right: 1px solid var(--booking-border);
        }
        
        .selector-dropdown {
            padding: 6px 10px;
            border: 1px solid var(--booking-border);
            border-radius: 6px;
            background: white;
            color: var(--booking-dark);
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 80px;
        }
        
        .selector-dropdown:hover {
            border-color: var(--booking-blue);
            box-shadow: 0 2px 4px rgba(0,53,128,0.1);
        }
        
        .selector-dropdown:focus {
            outline: none;
            border-color: var(--booking-blue);
            box-shadow: 0 0 0 2px rgba(0,53,128,0.1);
        }
        
        .btn-booking {
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-outline-booking {
            background: white;
            color: var(--booking-blue);
            border: 1px solid var(--booking-blue);
        }
        
        .btn-outline-booking:hover {
            background: var(--booking-light-gray);
            border-color: var(--booking-light-blue);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,53,128,0.15);
        }
        
        .btn-primary-booking {
            background: var(--booking-blue);
            color: white;
        }
        
        .btn-primary-booking:hover {
            background: var(--booking-light-blue);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,53,128,0.15);
        }
        
        .btn-booking i {
            font-size: 12px;
        }
        
        /* Mobile menu toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--booking-dark);
            cursor: pointer;
            padding: 8px;
        }
        
        /* Responsive navbar */
        @media (max-width: 768px) {
            .nav-center {
                display: none;
            }
            
            .header-right {
                gap: 8px;
            }
            
            .language-currency-selector {
                padding: 0 4px;
            }
            
            .selector-dropdown {
                min-width: 60px;
                padding: 4px 6px;
                font-size: 12px;
            }
            
            .btn-booking {
                padding: 6px 10px;
                font-size: 12px;
            }
            
            .btn-booking i {
                font-size: 11px;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
            
            .logo-booking {
                font-size: 1.5rem;
            }
            
            .logo-booking i {
                font-size: 1.2rem;
            }
        }
        
        @media (max-width: 1024px) {
            .nav-center {
                gap: 16px;
            }
            
            .header-right {
                gap: 8px;
            }
            
            .language-currency-selector {
                padding: 0 4px;
            }
            
            .btn-booking {
                padding: 6px 12px;
                font-size: 12px;
            }
        }
        
        /* Hero Section */
        .hero-booking {
            background: linear-gradient(135deg, var(--booking-blue) 0%, var(--booking-light-blue) 100%);
            padding: 80px 0;
            position: relative;
        }
        
        .hero-content {
            margin: 0 auto;
            padding: 0 32px;
            text-align: center;
            color: white;
            max-width: 1180px;
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
            padding: 0 32px;
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
            margin: 0 auto;
            padding: 0 32px;
            max-width: 1180px;
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
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin: 0 auto;
            padding: 0 32px;
            max-width: 1180px;
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
            height: 180px;
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
            padding: 16px;
        }
        
        .property-title-booking {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--booking-dark);
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            /* Fallback for other browsers */
            max-height: 2.6em;
            position: relative;
        }
        
        .property-title-booking::after {
            content: '';
            position: absolute;
            right: 0;
            bottom: 0;
            width: 20px;
            height: 1.3em;
            background: linear-gradient(transparent, white);
        }
        
        .property-location-booking {
            color: var(--booking-gray);
            font-size: 13px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .property-price-booking {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--booking-dark);
            margin-bottom: 8px;
        }
        
        .property-rating {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 12px;
        }
        
        .stars {
            color: var(--booking-orange);
            font-size: 12px;
        }
        
        .rating-number {
            font-weight: 600;
            color: var(--booking-dark);
            font-size: 13px;
        }
        
        .property-footer-booking {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid var(--booking-border);
        }
        
        .property-host {
            color: var(--booking-gray);
            font-size: 12px;
        }
        
        .btn-book-booking {
            background: var(--booking-blue);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
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
            margin: 0 auto;
            padding: 0 32px;
            max-width: 1000px;
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
            margin: 0 auto;
            padding: 0 32px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 40px;
            max-width: 1180px;
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
        
        /* Property Slider */
        .property-slider {
            position: relative;
            height: 100%;
            overflow: hidden;
        }
        
        .slider-container {
            position: relative;
            height: 100%;
        }
        
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        
        .slide.active {
            opacity: 1;
        }
        
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .slider-prev,
        .slider-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
            font-size: 14px;
        }
        
        .slider-prev {
            left: 12px;
        }
        
        .slider-next {
            right: 12px;
        }
        
        .slider-prev:hover,
        .slider-next:hover {
            background: rgba(0, 0, 0, 0.7);
            transform: translateY(-50%) scale(1.1);
        }
        
        .photo-counter {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
            z-index: 10;
        }
        
        .photo-counter i {
            font-size: 10px;
        }
        
        /* Auto-play indicator */
        .slider-container::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            z-index: 5;
        }
        
        .slider-container::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: var(--booking-orange);
            width: 0;
            z-index: 6;
            animation: progress 5s linear infinite;
        }
        
        @keyframes progress {
            from { width: 0; }
            to { width: 100%; }
        }
        
        /* Pause auto-play on hover */
        .property-slider:hover .slider-container::after {
            animation-play-state: paused;
        }
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
                <a href="accueil_booking_fixed.php" class="logo-booking">
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
                                <?php foreach ($supported_langs as $code => $name): ?>
                                    <option value="<?= $code ?>" <?= $lang === $code ? 'selected' : '' ?>>
                                        <?= match($code) {
                                            'fr' => '🇫🇷',
                                            'en' => '🇬🇧',
                                            'es' => '🇪🇸',
                                            'ar' => '🇸🇦',
                                            'zh' => '🇨🇳',
                                            'pt' => '🇵🇹',
                                            'de' => '��',
                                            'it' => '🇮🇹',
                                            'nl' => '🇳🇱',
                                            'ru' => '🇷🇺',
                                            'ja' => '🇯🇵',
                                            'ko' => '��',
                                            'hi' => '🇮🇳',
                                            'tr' => '🇹🇷',
                                            'pl' => '🇵🇱',
                                            'sv' => '🇸🇪',
                                            'no' => '🇳🇴',
                                            'da' => '🇩🇰',
                                            'fi' => '🇫🇮',
                                            'el' => '🇬🇷',
                                            'he' => '🇮🇱',
                                            'th' => '🇹🇭',
                                            'vi' => '��',
                                            'id' => '🇮🇩',
                                            'ms' => '🇲🇾',
                                            'cs' => '🇨🇿',
                                            'hu' => '🇭🇺',
                                            'ro' => '🇷🇴',
                                            'bg' => '🇧🇬',
                                            'hr' => '🇭🇷',
                                            'sr' => '🇷🇸',
                                            'sk' => '🇸🇰',
                                            'et' => '🇪🇪',
                                            'lv' => '🇱🇻',
                                            'lt' => '🇱🇹',
                                            'uk' => '🇺🇦',
                                            'be' => '🇧🇾',
                                            'ka' => '🇬🇪',
                                            'am' => '🇪🇹',
                                            'sw' => '🇰🇪',
                                            'zu' => '🇿🇦',
                                            'af' => '🇿🇦',
                                            'is' => '🇮🇸',
                                            'mt' => '🇲🇹',
                                            'cy' => '🏴󠁧󠁢󠁷󠁬󠁳󠁿',
                                            'ga' => '🇮🇪',
                                            'gd' => '🏴󠁧󠁢󠁳󠁣󠁴󠁿',
                                            'eu' => '🏴󠁥󠁳󠁰󠁶󠁿',
                                            'ca' => '🏴󠁥󠁳󠁣󠁴󠁿',
                                            'gl' => '🏴󠁥󠁳󠁣󠁴󠁿',
                                            'ast' => '🏴󠁥󠁳󠁣󠁴󠁿',
                                            'lb' => '🇱🇺',
                                            default => '🌍'
                                        } ?> <?= strtoupper($code) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select name="currency" class="selector-dropdown" onchange="this.form.submit()">
                                <?php foreach ($supported_currencies as $code => $data): ?>
                                    <option value="<?= $code ?>" <?= $currency === $code ? 'selected' : '' ?>>
                                        <?= $currency_symbols[$code] ?> <?= $data['name'] ?>
                                    </option>
                                <?php endforeach; ?>
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
                    $imageCount = count($images);
                    ?>
                    
                    <!-- Image Slider -->
                    <div class="property-slider" data-property-id="<?= $annonce['id'] ?>">
                        <div class="slider-container">
                            <?php foreach ($images as $index => $image): ?>
                                <div class="slide <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="uploads/images/<?= $image ?>" alt="<?= htmlspecialchars($annonce['titre']) ?> - Photo <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Navigation buttons -->
                        <?php if ($imageCount > 1): ?>
                            <button class="slider-prev" onclick="event.stopPropagation(); previousSlide(<?= $annonce['id'] ?>)">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="slider-next" onclick="event.stopPropagation(); nextSlide(<?= $annonce['id'] ?>)">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        <?php endif; ?>
                        
                        <!-- Photo counter -->
                        <?php if ($imageCount > 1): ?>
                            <div class="photo-counter">
                                <i class="fas fa-camera"></i>
                                <span><?= $imageCount ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
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
                        <button class="btn-book-booking" onclick="event.stopPropagation(); showBookingModal(<?= $annonce['id'] ?>, '<?= htmlspecialchars($annonce['titre']) ?>', <?= convert_price($annonce['prix'], $currency, $exchange_rates) ?>, '<?= $currency ?>', '<?= $currency_symbols[$currency] ?>')">
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

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">
                        <i class="fas fa-calendar-check me-2"></i>
                        <span id="bookingTitle"><?= $lang === 'fr' ? 'Réserver' : 'Book' ?></span>: <span id="propertyTitle"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bookingForm">
                        <input type="hidden" id="propertyId" name="property_id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="checkInDate" class="form-label"><?= $t['check_in'] ?></label>
                                    <input type="date" class="form-control" id="checkInDate" name="check_in" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="checkOutDate" class="form-label"><?= $t['check_out'] ?></label>
                                    <input type="date" class="form-control" id="checkOutDate" name="check_out" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="guestCount" class="form-label"><?= $t['guests'] ?></label>
                                    <select class="form-control" id="guestCount" name="guests" required>
                                        <option value="1">1 <?= $lang === 'fr' ? 'voyageur' : 'guest' ?></option>
                                        <option value="2">2 <?= $lang === 'fr' ? 'voyageurs' : 'guests' ?></option>
                                        <option value="3">3 <?= $lang === 'fr' ? 'voyageurs' : 'guests' ?></option>
                                        <option value="4">4+ <?= $lang === 'fr' ? 'voyageurs' : 'guests' ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bookingCurrency" class="form-label"><?= $t['currency'] ?></label>
                                    <select class="form-control" id="bookingCurrency" name="currency_display" readonly>
                                        <option value="<?= $currency ?>"><?= $currency_symbols[$currency] ?> <?= $supported_currencies[$currency]['name'] ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="guestName" class="form-label"><?= $lang === 'fr' ? 'Nom complet' : 'Full Name' ?></label>
                            <input type="text" class="form-control" id="guestName" name="guest_name" required 
                                   placeholder="<?= $lang === 'fr' ? 'Entrez votre nom complet' : 'Enter your full name' ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="guestEmail" class="form-label"><?= $lang === 'fr' ? 'Email' : 'Email' ?></label>
                            <input type="email" class="form-control" id="guestEmail" name="guest_email" required 
                                   placeholder="<?= $lang === 'fr' ? 'Entrez votre email' : 'Enter your email' ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="guestPhone" class="form-label"><?= $lang === 'fr' ? 'Téléphone' : 'Phone' ?></label>
                            <input type="tel" class="form-control" id="guestPhone" name="guest_phone" required 
                                   placeholder="<?= $lang === 'fr' ? 'Entrez votre numéro de téléphone' : 'Enter your phone number' ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="specialRequests" class="form-label"><?= $lang === 'fr' ? 'Demandes spéciales (optionnel)' : 'Special Requests (Optional)' ?></label>
                            <textarea class="form-control" id="specialRequests" name="special_requests" rows="3" 
                                      placeholder="<?= $lang === 'fr' ? 'Demandes spéciales, allergies, préférences...' : 'Special requests, allergies, preferences...' ?>"></textarea>
                        </div>
                        
                        <!-- Price Summary -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-calculator me-2"></i><?= $lang === 'fr' ? 'Résumé des prix' : 'Price Summary' ?></h6>
                            <div class="d-flex justify-content-between">
                                <span><?= $lang === 'fr' ? 'Prix par nuit' : 'Price per night' ?>:</span>
                                <span id="pricePerNight"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><?= $lang === 'fr' ? 'Nombre de nuits' : 'Number of nights' ?>:</span>
                                <span id="numberOfNights">0</span>
                                <small class="text-muted ms-2">(<?= $lang === 'fr' ? 'Arrivée le 15, Départ le 16 = 1 nuit' : 'Check-in 15th, Check-out 16th = 1 night' ?>)</small>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span><?= $lang === 'fr' ? 'Total' : 'Total' ?>:</span>
                                <span id="totalPrice"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <?= $lang === 'fr' ? 'Annuler' : 'Cancel' ?>
                    </button>
                    <button type="button" class="btn btn-primary" onclick="submitBooking()">
                        <i class="fas fa-check me-2"></i><?= $lang === 'fr' ? 'Confirmer la réservation' : 'Confirm Booking' ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= $lang === 'fr' ? 'Réservation confirmée !' : 'Booking Confirmed!' ?>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3"><?= $lang === 'fr' ? 'Votre réservation a été confirmée' : 'Your booking has been confirmed' ?></h4>
                        <p class="text-muted"><?= $lang === 'fr' ? 'Vous recevrez un email de confirmation dans quelques instants.' : 'You will receive a confirmation email shortly.' ?></p>
                        <div class="alert alert-light">
                            <strong><?= $lang === 'fr' ? 'Numéro de réservation' : 'Booking Reference' ?>:</strong> 
                            <span id="bookingReference"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                        <?= $lang === 'fr' ? 'OK' : 'OK' ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

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
    
    // Booking Modal Functions
    let currentProperty = {
        id: null,
        title: '',
        price: 0,
        currency: '',
        symbol: ''
    };
    
    function showBookingModal(propertyId, propertyTitle, price, currency, symbol) {
        currentProperty = {
            id: propertyId,
            title: propertyTitle,
            price: price,
            currency: currency,
            symbol: symbol
        };
        
        // Set property info
        document.getElementById('propertyId').value = propertyId;
        document.getElementById('propertyTitle').textContent = propertyTitle;
        document.getElementById('pricePerNight').textContent = formatPriceDisplay(price, symbol);
        
        // Reset form
        document.getElementById('bookingForm').reset();
        document.getElementById('numberOfNights').textContent = '0';
        document.getElementById('totalPrice').textContent = formatPriceDisplay(0, symbol);
        
        // Set minimum dates
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('checkInDate').min = today;
        document.getElementById('checkOutDate').min = today;
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
        modal.show();
    }
    
    function formatPriceDisplay(price, symbol) {
        // Format based on currency - éviter double affichage pour FCFA
        if (currentProperty.currency === 'XOF') {
            return number_format(price, 0, '.', ' ') + ' ' + 'FCFA';
        } else if (currentProperty.currency === 'EUR') {
            return number_format(price, 0, '.', ' ') + ' ' + '€';
        } else {
            return symbol + number_format(price, 0, '.', ',');
        }
    }
    
    function number_format(number, decimals, dec_point, thousands_sep) {
        // Simple number formatting
        const parts = parseFloat(number).toFixed(decimals).split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
        return parts.join(dec_point);
    }
    
    // Calculate total price when dates change
    document.getElementById('checkInDate')?.addEventListener('change', calculateTotal);
    document.getElementById('checkOutDate')?.addEventListener('change', calculateTotal);
    
    function calculateTotal() {
        const checkIn = document.getElementById('checkInDate').value;
        const checkOut = document.getElementById('checkOutDate').value;
        
        if (checkIn && checkOut) {
            const startDate = new Date(checkIn);
            const endDate = new Date(checkOut);
            
            if (endDate > startDate) {
                // Calcul correct : différence en jours - 1 (arrivée et départ ne comptent pas)
                const timeDiff = endDate.getTime() - startDate.getTime();
                const nights = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                
                const total = nights * currentProperty.price;
                
                document.getElementById('numberOfNights').textContent = nights;
                document.getElementById('totalPrice').textContent = formatPriceDisplay(total, currentProperty.symbol);
            } else {
                document.getElementById('numberOfNights').textContent = '0';
                document.getElementById('totalPrice').textContent = formatPriceDisplay(0, currentProperty.symbol);
            }
        }
    }
    
    function submitBooking() {
        const form = document.getElementById('bookingForm');
        
        // Validate form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Get form data
        const formData = new FormData(form);
        const bookingData = {
            property_id: formData.get('property_id'),
            check_in: formData.get('check_in'),
            check_out: formData.get('check_out'),
            guests: formData.get('guests'),
            currency: formData.get('currency_display'),
            guest_name: formData.get('guest_name'),
            guest_email: formData.get('guest_email'),
            guest_phone: formData.get('guest_phone'),
            special_requests: formData.get('special_requests'),
            total_price: document.getElementById('totalPrice').textContent,
            booking_reference: generateBookingReference()
        };
        
        // Simulate booking submission (in real app, this would be an AJAX call)
        console.log('Booking data:', bookingData);
        
        // Close booking modal
        bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
        
        // Show success modal
        document.getElementById('bookingReference').textContent = bookingData.booking_reference;
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        
        // In a real application, you would send this data to your server
        // fetch('/api/bookings', {
        //     method: 'POST',
        //     headers: { 'Content-Type': 'application/json' },
        //     body: JSON.stringify(bookingData)
        // })
    }
    
    function generateBookingReference() {
        const prefix = 'TH'; // TerangaHomes
        const timestamp = Date.now().toString().slice(-6);
        const random = Math.random().toString(36).substring(2, 6).toUpperCase();
        return `${prefix}${timestamp}${random}`;
    }
    
    // Image Slider Functions
    const sliderIntervals = {};
    
    function startSlider(propertyId) {
        const slider = document.querySelector(`[data-property-id="${propertyId}"]`);
        if (!slider) return;
        
        const slides = slider.querySelectorAll('.slide');
        if (slides.length <= 1) return;
        
        let currentSlide = 0;
        
        // Clear existing interval
        if (sliderIntervals[propertyId]) {
            clearInterval(sliderIntervals[propertyId]);
        }
        
        // Auto-play every 5 seconds
        sliderIntervals[propertyId] = setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 5000);
    }
    
    function stopSlider(propertyId) {
        if (sliderIntervals[propertyId]) {
            clearInterval(sliderIntervals[propertyId]);
            delete sliderIntervals[propertyId];
        }
    }
    
    function nextSlide(propertyId) {
        const slider = document.querySelector(`[data-property-id="${propertyId}"]`);
        if (!slider) return;
        
        const slides = slider.querySelectorAll('.slide');
        const currentActive = slider.querySelector('.slide.active');
        const currentIndex = Array.from(slides).indexOf(currentActive);
        
        currentActive.classList.remove('active');
        const nextIndex = (currentIndex + 1) % slides.length;
        slides[nextIndex].classList.add('active');
        
        // Reset auto-play
        stopSlider(propertyId);
        startSlider(propertyId);
    }
    
    function previousSlide(propertyId) {
        const slider = document.querySelector(`[data-property-id="${propertyId}"]`);
        if (!slider) return;
        
        const slides = slider.querySelectorAll('.slide');
        const currentActive = slider.querySelector('.slide.active');
        const currentIndex = Array.from(slides).indexOf(currentActive);
        
        currentActive.classList.remove('active');
        const prevIndex = (currentIndex - 1 + slides.length) % slides.length;
        slides[prevIndex].classList.add('active');
        
        // Reset auto-play
        stopSlider(propertyId);
        startSlider(propertyId);
    }
    
    // Initialize sliders when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const sliders = document.querySelectorAll('.property-slider');
        sliders.forEach(slider => {
            const propertyId = slider.dataset.propertyId;
            startSlider(propertyId);
            
            // Pause on hover, resume on mouse leave
            slider.addEventListener('mouseenter', () => stopSlider(propertyId));
            slider.addEventListener('mouseleave', () => startSlider(propertyId));
        });
    });
    
    // Clean up intervals when page unloads
    window.addEventListener('beforeunload', function() {
        Object.keys(sliderIntervals).forEach(propertyId => {
            stopSlider(propertyId);
        });
    });
    </script>
</body>
</html>
