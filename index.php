<?php
// Rediriger vers l'accueil Booking Fixed et détruire les sessions

session_start();
session_destroy();

header('Location: accueil_booking_fixed.php');
exit;
?>
