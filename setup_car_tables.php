<?php
// Script pour créer les tables manquantes
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

echo "Création des tables manquantes...\n";

// Créer la table cars
$sqlCars = "CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    type ENUM('economy', 'compact', 'midsize', 'suv', 'luxury', 'van') NOT NULL,
    transmission ENUM('manual', 'automatic') NOT NULL,
    fuel ENUM('essence', 'diesel', 'hybrid', 'electric') NOT NULL,
    seats INT NOT NULL,
    luggage INT NOT NULL,
    daily_price DECIMAL(10,2) NOT NULL,
    weekly_price DECIMAL(10,2),
    monthly_price DECIMAL(10,2),
    description TEXT,
    image VARCHAR(255),
    air_conditioning BOOLEAN DEFAULT FALSE,
    gps BOOLEAN DEFAULT FALSE,
    bluetooth BOOLEAN DEFAULT FALSE,
    usb_charging BOOLEAN DEFAULT FALSE,
    child_seat BOOLEAN DEFAULT FALSE,
    available BOOLEAN DEFAULT TRUE,
    rating DECIMAL(3,2) DEFAULT 0.00,
    reviews INT DEFAULT 0,
    owner_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $db->execute($sqlCars);
    echo "Table 'cars' créée avec succès\n";
} catch (Exception $e) {
    echo "Erreur création table 'cars': " . $e->getMessage() . "\n";
}

// Créer la table car_bookings
$sqlBookings = "CREATE TABLE IF NOT EXISTS car_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    user_id INT NOT NULL,
    pickup_date DATE NOT NULL,
    dropoff_date DATE NOT NULL,
    pickup_time TIME NOT NULL,
    dropoff_time TIME NOT NULL,
    pickup_location VARCHAR(255) NOT NULL,
    dropoff_location VARCHAR(255),
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $db->execute($sqlBookings);
    echo "Table 'car_bookings' créée avec succès\n";
} catch (Exception $e) {
    echo "Erreur création table 'car_bookings': " . $e->getMessage() . "\n";
}

echo "Terminé !\n";
?>
