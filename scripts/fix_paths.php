<?php
// Script pour corriger les chemins des require_once après réorganisation

$files = glob('*.php');
$corrections = [
    "require_once 'config/" => "require_once 'src/config/",
    "require_once 'core/" => "require_once 'src/core/",
    "require_once 'models/" => "require_once 'src/models/",
    "require_once 'controllers/" => "require_once 'src/controllers/",
    "require_once 'views/" => "require_once 'src/views/'",
    "require_once '../config/" => "require_once '../src/config/",
    "require_once '../core/" => "require_once '../src/core/",
    "require_once '../models/" => "require_once '../src/models/'",
    "require_once '../controllers/" => "require_once '../src/controllers/'",
    "require_once '../views/" => "require_once '../src/views/'",
];

foreach ($files as $file) {
    if (in_array($file, ['fix_paths.php', 'index.php'])) continue;
    
    $content = file_get_contents($file);
    $original = $content;
    
    foreach ($corrections as $from => $to) {
        $content = str_replace($from, $to, $content);
    }
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Chemin corrigé dans: $file\n";
    }
}

echo "Correction des chemins terminée.\n";
?>
