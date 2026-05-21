<?php
// Inclure le fichier de connexion à la base de données
include_once 'connexion.php';

// Initialiser les variables pour stocker les messages d'erreur et de succès
$erreur = '';
$succesMessage = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id_projet = $_POST['id_projet'];
    $titre = $_POST['titre'];
    $descriptions = $_POST['descriptions'];
    $actionsAV = $_POST['actionsAV'];
    $actionsV = $_POST['actionsV'];
    $prix = $_POST['prix'];

    // Effectuer les validations nécessaires sur les données saisies (à implémenter si nécessaire)

    // Mettre à jour les informations du projet dans la base de données
    $requete = $dbh->prepare("UPDATE projet SET titre = :titre, descriptions = :descriptions, nombre_actions_a_vendre = :actionsAV, nombrre_actions_vendues = :actionsV, prix_action = :prix WHERE id_projet = :id_projet");
    $requete->bindParam(':id_projet', $id_projet);
    $requete->bindParam(':titre', $titre);
    $requete->bindParam(':descriptions', $descriptions);
    $requete->bindParam(':actionsAV', $actionsAV);
    $requete->bindParam(':actionsV', $actionsV);
    $requete->bindParam(':prix', $prix);

    if ($requete->execute()) {
        // Mise à jour réussie
        $succesMessage = "Les informations du projet ont été mises à jour avec succès.";
        // Redirection vers la page d'accueil après la mise à jour
        header("Location: accueilS.php");
        exit(); // Arrêter l'exécution du script après la redirection
    } else {
        // Erreur lors de la mise à jour
        $erreur = "Une erreur s'est produite lors de la mise à jour du projet. Veuillez réessayer.";
    }
}

// Récupérer les informations du projet à modifier depuis la base de données
$id_projet = $_GET['id_projet'];
$requete_projet = $dbh->prepare("SELECT * FROM projet WHERE id_projet = :id_projet");
$requete_projet->bindParam(':id_projet', $id_projet);
$requete_projet->execute();
$projet = $requete_projet->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Projet</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-image: url(images/backEditer.avif);
    background-repeat: no-repeat;
    background-size: cover;
}

        h2 {
            color: #0070c9;
            text-align: center;
            margin-top: 30px;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.8); /* Ajoutez une couleur de fond semi-transparente pour améliorer la lisibilité */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            
        }

        form label {
            display: block;
            margin-bottom: 5px;
        }

        form input[type="text"],
        form input[type="number"],
        form textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form button[type="submit"] {
            background-color: #0070c9;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button[type="submit"]:hover {
            background-color: #005ea6;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }
        </style>
</head>
<body>
    <h2>Modifier Projet</h2>
    <form action="" method="post"> <!-- L'action est vide pour envoyer le formulaire vers la même page -->
        <!-- Champs du formulaire pré-remplis avec les données du projet -->
        <input type="hidden" name="id_projet" value="<?php echo $projet['id_projet']; ?>">
        <label for="titre">Titre :</label>
        <input name="titre" id="titre" type="text" required value="<?php echo $projet['titre']; ?>">
        <label for="descriptions">Description :</label>
        <textarea name="descriptions" id="descriptions" rows="4" required><?php echo $projet['descriptions']; ?></textarea>
        <label for="actionsAV">Nombre d'Actions à vendre :</label>
        <input name="actionsAV" id="actionsAV" type="number" required value="<?php echo $projet['nombre_actions_a_vendre']; ?>">
        <label for="actionsV">Nombre d'Actions vendues :</label>
        <input name="actionsV" id="actionsV" type="number" required value="<?php echo $projet['nombrre_actions_vendues']; ?>">
        <label for="prix">Prix par action :</label>
        <input name="prix" id="prix" type="number" required value="<?php echo $projet['prix_action']; ?>">
        <button type="submit">Enregistrer les modifications</button>
    </form>
    <?php
    // Afficher les messages d'erreur ou de succès
    if (!empty($erreur)) {
        echo "<p style='color: red;'>$erreur</p>";
    } elseif (!empty($succesMessage)) {
        echo "<p style='color: green;'>$succesMessage</p>";
    }
    ?>
</body>
</html>
