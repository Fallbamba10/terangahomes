<?php
// Point d'entrée principal de l'application
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/src/config/config.php';

// Démarrer la session
session_start();

// Router simple
$request = $_SERVER['REQUEST_URI'];
$request = str_replace('/terangaHomes', '', $request);

// Rediriger vers l'accueil principal
header('Location: accueil_booking_fixed.php');
exit;
?>
