<?php
// Inclure le fichier de connexion à la base de données
include_once 'connexion.php';

// Vérifier si la session est démarrée
session_start();

if (isset($_SESSION['utilisateur']) && isset($_SESSION['utilisateur']['id_capital_risque'])) {
    // Récupérer les données du capital-risque depuis la session
    $capital_risque = $_SESSION['utilisateur'];

    // Récupérer l'ID du capital-risque depuis les données de l'utilisateur
    $id_capital_risque = $capital_risque['id_capital_risque'];

    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer l'ID du projet et le nombre d'actions à acheter depuis le formulaire
        $id_projet = $_POST['id_projet'];
        $nombre_actions = $_POST['nombre_actions'];

        // Effectuer l'achat d'actions dans la base de données
        try {
            // Commencer une transaction
            $dbh->beginTransaction();

            // Insérer les détails de l'achat dans la table capital_risque_projet
            $requete = $dbh->prepare("INSERT INTO capital_risque_projet (id_projet, id_capital_risque, nombre_action_achetees) VALUES (:id_projet, :id_capital_risque, :nombre_actions)");
            $requete->bindParam(':id_projet', $id_projet);
            $requete->bindParam(':id_capital_risque', $id_capital_risque);
            $requete->bindParam(':nombre_actions', $nombre_actions);
            $requete->execute();

            // Mettre à jour le nombre d'actions vendues dans la table projet
            $requete_update = $dbh->prepare("UPDATE projet SET nombrre_actions_vendues = nombrre_actions_vendues + :nombre_actions WHERE id_projet = :id_projet");
            $requete_update->bindParam(':nombre_actions', $nombre_actions);
            $requete_update->bindParam(':id_projet', $id_projet);
            $requete_update->execute();

            // Valider la transaction
            $dbh->commit();

            // Rediriger l'utilisateur vers une page de confirmation ou afficher un message de succès
            header("Location: accueilC.php");
            exit();
        } catch (PDOException $e) {
            // En cas d'erreur, annuler la transaction et afficher un message d'erreur
            $dbh->rollBack();
            echo "Erreur lors de l'achat d'actions : " . $e->getMessage();
        }
    } else {
        // Si le formulaire n'a pas été soumis, rediriger l'utilisateur vers une page d'erreur ou afficher un message d'erreur
        echo "Erreur : Le formulaire n'a pas été soumis.";
    }
} else {
    // Si les informations du capital-risque ne sont pas présentes dans la session, afficher un message d'erreur
    echo "Veuillez vous connecter en tant que capital-risque pour acheter des actions.";
}


