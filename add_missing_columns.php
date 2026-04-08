<?php
// Script pour ajouter les colonnes manquantes
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

echo "Ajout des colonnes manquantes...\n";

try {
    // Ajouter la colonne etage
    $sql1 = "ALTER TABLE annonces ADD COLUMN etage INT DEFAULT 0 AFTER superficie";
    $db->execute($sql1);
    echo "Colonne etage ajoutée avec succès\n";
} catch (Exception $e) {
    echo "Erreur ajout etage: " . $e->getMessage() . "\n";
}

try {
    // Ajouter la colonne terrasse
    $sql2 = "ALTER TABLE annonces ADD COLUMN terrasse BOOLEAN DEFAULT FALSE AFTER piscine";
    $db->execute($sql2);
    echo "Colonne terrasse ajoutée avec succès\n";
} catch (Exception $e) {
    echo "Erreur ajout terrasse: " . $e->getMessage() . "\n";
}

try {
    // Ajouter la colonne jardin
    $sql3 = "ALTER TABLE annonces ADD COLUMN jardin BOOLEAN DEFAULT FALSE AFTER terrasse";
    $db->execute($sql3);
    echo "Colonne jardin ajoutée avec succès\n";
} catch (Exception $e) {
    echo "Erreur ajout jardin: " . $e->getMessage() . "\n";
}

try {
    // Ajouter la colonne garage
    $sql4 = "ALTER TABLE annonces ADD COLUMN garage BOOLEAN DEFAULT FALSE AFTER jardin";
    $db->execute($sql4);
    echo "Colonne garage ajoutée avec succès\n";
} catch (Exception $e) {
    echo "Erreur ajout garage: " . $e->getMessage() . "\n";
}

try {
    // Ajouter la colonne duree_minimale
    $sql5 = "ALTER TABLE annonces ADD COLUMN duree_minimale INT NULL AFTER garage";
    $db->execute($sql5);
    echo "Colonne duree_minimale ajoutée avec succès\n";
} catch (Exception $e) {
    echo "Erreur ajout duree_minimale: " . $e->getMessage() . "\n";
}

try {
    // Ajouter la colonne type_location
    $sql6 = "ALTER TABLE annonces ADD COLUMN type_location ENUM('jour', 'semaine', 'mois', 'annee') NULL AFTER duree_minimale";
    $db->execute($sql6);
    echo "Colonne type_location ajoutée avec succès\n";
} catch (Exception $e) {
    echo "Erreur ajout type_location: " . $e->getMessage() . "\n";
}

try {
    // Ajouter la colonne services_complementaires
    $sql7 = "ALTER TABLE annonces ADD COLUMN services_complementaires JSON NULL AFTER type_location";
    $db->execute($sql7);
    echo "Colonne services_complementaires ajoutée avec succès\n";
} catch (Exception $e) {
    echo "Erreur ajout services_complementaires: " . $e->getMessage() . "\n";
}

echo "Terminé !\n";
?>
