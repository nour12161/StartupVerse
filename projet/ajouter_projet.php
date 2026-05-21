<?php
// Inclure le fichier de connexion à la base de données
include_once 'connexion.php';

// Démarrer la session
session_start();

// Vérifier si les données de l'utilisateur sont disponibles dans la session
if (!isset($_SESSION['utilisateur'])) {
    // Rediriger l'utilisateur vers la page de connexion ou afficher un message d'erreur
    echo "Veuillez vous connecter pour ajouter un projet.";
    exit(); // Arrêter l'exécution du script
}

// Récupérer les données de l'utilisateur depuis la session
$utilisateur = $_SESSION['utilisateur'];

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $titre = $_POST['titre'];
    $descriptions = $_POST['descriptions'];
    $actionsAV = $_POST['actionsAV'];
    $actionsV = $_POST['actionsV'];
    $prix = $_POST['prix'];

    // Récupérer l'ID du startuper à partir des données de l'utilisateur dans la session
    $idS = $utilisateur['id_startuper'];

    // Effectuer les validations nécessaires sur les données saisies

    // Les données sont valides, effectuer l'insertion dans la base de données
    $requete = $dbh->prepare("INSERT INTO projet (titre, descriptions, nombre_actions_a_vendre, nombrre_actions_vendues, prix_action, id_startuper) 
    VALUES (:titre, :descriptions, :actionsAV, :actionsV, :prix, :idS)");
    $requete->bindParam(':titre', $titre);
    $requete->bindParam(':descriptions', $descriptions);
    $requete->bindParam(':actionsAV', $actionsAV);
    $requete->bindParam(':actionsV', $actionsV);
    $requete->bindParam(':prix', $prix);
    $requete->bindParam(':idS', $idS);

    if ($requete->execute()) {
        // Insertion réussie
        $_SESSION['succesMessage'] = "Votre projet a été ajouté avec succès !";
        header("Location: accueilS.php");
    } else {
        // Erreur lors de l'insertion
        echo "Une erreur s'est produite. Veuillez réessayer.";
    }
}
?>

