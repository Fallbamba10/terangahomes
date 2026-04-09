<?php
// Script pour ajouter les require_once manquants dans les contrôleurs

$controllers = glob('src/controllers/*.php');

foreach ($controllers as $controller) {
    $content = file_get_contents($controller);
    
    // Vérifier si le fichier a déjà des require_once
    if (strpos($content, 'require_once') === false) {
        // Ajouter les require_once au début
        $className = basename($controller, '.php');
        $modelPath = "../models/" . str_replace('Controller', '', $className) . ".php";
        
        $newContent = "<?php\nrequire_once '../core/Controller.php';\n";
        
        // Ajouter le modèle s'il existe
        if (file_exists($modelPath)) {
            $newContent .= "require_once '$modelPath';\n";
        }
        
        $newContent .= "\n" . substr($content, 6); // Enlever <?php
        
        file_put_contents($controller, $newContent);
        echo "Require_once ajoutés dans: $controller\n";
    }
}

echo "Correction des contrôleurs terminée.\n";
?>
