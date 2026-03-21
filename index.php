<?php
// Rediriger vers l'accueil bleu simple et détruire les sessions

session_start();
session_destroy();

header('Location: accueil_bleu_simple.php');
exit;
?>
