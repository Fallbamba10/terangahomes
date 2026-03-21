<?php
// Rediriger vers l'accueil Seloger + Booking et détruire les sessions

session_start();
session_destroy();

header('Location: accueil_seloger_booking.php');
exit;
?>
