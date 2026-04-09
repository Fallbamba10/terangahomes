<?php
define('APP_NAME', 'TerangaHomes');
define('APP_URL', 'http://localhost/terangaHomes');
define('ROOT_PATH', dirname(__DIR__, 2) . '/terangaHomes');

// Configuration base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'terangahomes');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuration upload
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Configuration Google Maps (à compléter avec votre clé API)
define('GOOGLE_MAPS_API_KEY', '');

// Session
define('SESSION_LIFETIME', 3600); // 1 heure

// Pagination
define('ITEMS_PER_PAGE', 12);

// Modes
define('DEBUG', true);
define('ENVIRONMENT', 'development');
