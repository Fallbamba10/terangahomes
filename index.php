<?php
// Rediriger vers l'accueil Booking International et détruire les sessions

session_start();
session_destroy();

header('Location: accueil_booking_international.php');
exit;
?>
