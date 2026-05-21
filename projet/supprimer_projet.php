<?php
// Inclure le fichier de connexion à la base de données
include_once 'connexion.php';

// Vérifier si l'ID du projet à supprimer est passé en paramètre dans l'URL
if (isset($_POST['id_projet'])) {
    // Récupérer l'ID du projet à supprimer depuis le formulaire POST
    $id_projet = $_POST['id_projet'];

    // Préparer la requête SQL de suppression
    $requete_suppression = $dbh->prepare("DELETE FROM projet WHERE id_projet = :id_projet");
    $requete_suppression->bindParam(':id_projet', $id_projet);

    // Exécuter la requête de suppression
    if ($requete_suppression->execute()) {
        // Rediriger vers la page d'accueil après la suppression
        header("Location: accueilS.php");
        exit();
    } else {
        // En cas d'erreur lors de la suppression, afficher un message d'erreur
        echo "Une erreur s'est produite lors de la suppression du projet.";
    }
} else {
    // Si l'ID du projet n'est pas passé en paramètre, afficher un message d'erreur
    echo "ID du projet manquant.";
}
?>
