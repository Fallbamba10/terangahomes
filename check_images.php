<?php
// Script pour vérifier les images dans la base de données
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

echo "=== VÉRIFICATION DES IMAGES DANS LA BASE ===\n\n";

try {
    $annonces = $db->fetchAll("SELECT id, titre, images FROM annonces WHERE images IS NOT NULL AND images != '[]' LIMIT 5");
    
    foreach ($annonces as $annonce) {
        echo "Annonce ID: " . $annonce['id'] . "\n";
        echo "Titre: " . $annonce['titre'] . "\n";
        echo "Images (brut): " . $annonce['images'] . "\n";
        
        $images = json_decode($annonce['images'], true);
        echo "Images (décodé): ";
        print_r($images);
        
        if (!empty($images)) {
            echo "Première image: " . $images[0] . "\n";
            echo "Chemin complet: " . APP_URL . "/uploads/annonces/" . $images[0] . "\n";
            echo "Fichier existe: " . (file_exists(UPLOAD_PATH . "annonces/" . $images[0]) ? "OUI" : "NON") . "\n";
        }
        echo str_repeat("-", 50) . "\n";
    }
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
}

echo "\n=== VÉRIFICATION DES FICHIERS UPLOADÉS ===\n";
if (is_dir(UPLOAD_PATH . 'annonces/')) {
    $files = scandir(UPLOAD_PATH . 'annonces/');
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "Fichier: $file\n";
        }
    }
} else {
    echo "Dossier uploads/annonces/ n'existe pas\n";
}
?>
