<?php
// Démarrez la session
session_start();

// Détruisez toutes les données de session
session_destroy();

// Redirigez l'utilisateur vers la page de connexion
header("Location: login.php");
exit; // Assurez-vous d'arrêter l'exécution du script après la redirection
?>
