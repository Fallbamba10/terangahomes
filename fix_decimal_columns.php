<?php
// Script pour autoriser NULL dans les champs décimaux
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

echo "Correction des colonnes décimales...\n";

try {
    // Autoriser NULL pour latitude
    $sql1 = "ALTER TABLE annonces MODIFY latitude DECIMAL(10,8) NULL";
    $db->execute($sql1);
    echo "Colonne latitude modifiée avec succès\n";
} catch (Exception $e) {
    echo "Erreur modification latitude: " . $e->getMessage() . "\n";
}

try {
    // Autoriser NULL pour longitude
    $sql2 = "ALTER TABLE annonces MODIFY longitude DECIMAL(11,8) NULL";
    $db->execute($sql2);
    echo "Colonne longitude modifiée avec succès\n";
} catch (Exception $e) {
    echo "Erreur modification longitude: " . $e->getMessage() . "\n";
}

try {
    // Autoriser NULL pour superficie
    $sql3 = "ALTER TABLE annonces MODIFY superficie DECIMAL(8,2) NULL";
    $db->execute($sql3);
    echo "Colonne superficie modifiée avec succès\n";
} catch (Exception $e) {
    echo "Erreur modification superficie: " . $e->getMessage() . "\n";
}

try {
    // Autoriser NULL pour prix (au cas où)
    $sql4 = "ALTER TABLE annonces MODIFY prix DECIMAL(10,2) NULL";
    $db->execute($sql4);
    echo "Colonne prix modifiée avec succès\n";
} catch (Exception $e) {
    echo "Erreur modification prix: " . $e->getMessage() . "\n";
}

echo "Terminé !\n";
?>
