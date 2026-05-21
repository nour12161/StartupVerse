<?php
session_start();



// Vérifier si la session utilisateur existe et si elle contient une photo
if (isset($_SESSION['utilisateur']) && isset($_SESSION['utilisateur']['photo'])) {
    // Récupérer les données de la photo depuis la session
    $photoData = $_SESSION['utilisateur']['photo'];
    // Encoder les données de la photo en base64
    $imgSrc = 'data:image/jpeg;base64,' . base64_encode($photoData);
    // Afficher l'image
    echo '<img id ="photo_profile" src="' . $imgSrc . '" alt="Photo de profil">';
} else {
    // Si la photo n'existe pas, afficher un message ou une image par défaut
    echo '<p>Aucune photo de profil trouvée.</p>';
}




// Vérifier si la variable de session utilisateur est définie
if (isset($_SESSION['utilisateur'])) {
    // Récupérer les données de l'utilisateur depuis la session
    $utilisateur = $_SESSION['utilisateur'];
    // Récupérer les informations spécifiques de l'utilisateur
    $nom = isset($utilisateur['nom']) ? $utilisateur['nom'] : '';
    $prenom = isset($utilisateur['prenom']) ? $utilisateur['prenom'] : '';
    $email = isset($utilisateur['email']) ? $utilisateur['email'] : '';
    $pseudo = isset($utilisateur['pseudo']) ? $utilisateur['pseudo'] : '';
    $CIN = isset($utilisateur['CIN']) ? $utilisateur['CIN'] : '';
} else {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}
echo '<div class="UserName">';
echo '<p>Welcome to STARTUPVERSE  ' . $prenom .'! </p>';
echo '</div>';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Projet</title>
    <link rel="stylesheet" href="accueilSS.css"> 
    <style>
        /* Pour masquer toutes les divs initialement */
        .fonction-container {
            display: none;
        }
    </style>
</head>
<body>
<button class="button" id="ajouterProjetBtn" onclick="afficherDiv('AjouterProjet')">Ajouter un Projet</button>
<button class="button" id="modifierProfilBtn" onclick="afficherDiv('ModifierProfil')">Modifier Profil Startuper</button>
<button class="button" id="mesProjetsBtn" onclick="afficherDiv('MesProjets')">Mes Projets</button>
<button class="button" id="supprimerMesProjetsBtn" onclick="afficherDiv('SupprimerMesProjets')">Supprimer Mes Projets</button>



<div id="AjouterProjet" class="fonction-container">
        <h2>Ajouter un Projet</h2>
        <?php if (!empty($erreur)) { ?>
            <p style="color: red;"><?php echo htmlspecialchars($erreur); ?></p>
        <?php } elseif (!empty($succesMessage)) { ?>
            <p style="color: green;"><?php echo htmlspecialchars($succesMessage); ?></p>
        <?php } ?>
        <form action="ajouter_projet.php" method="post">
            <label for="titre">Titre :</label>
            <input name="titre" id="titre" type="text" required>
            <label for="descriptions">Description :</label>
            <textarea name="descriptions" id="descriptions" rows="4" required></textarea>
            <label for="actionsAV">Nombre d'Actions à vendre :</label>
            <input name="actionsAV" id="actionsAV" type="number" required>
            <label for="actionsV">Nombre d'Actions vendues :</label>
            <input name="actionsV" id="actionsV" type="number" required>
            <label for="prix">Prix_Action</label>
            <input name="prix" id="prix" type="number" required>
            <button type="submit">Ajouter Projet</button>
        </form>
        
    </div>

    

 
  <div id="ModifierProfil" class="fonction-container">
        <h2>Modifier Profil Startuper</h2>
        <form action="editer_profilS.php" method="post" enctype="multipart/form-data">
            <label for="nom">Nom :</label>
            <input name="nom" id="nom" type="text" required value="<?php echo htmlspecialchars($nom); ?>">
            <label for="prenom">Prénom :</label>
            <input name="prenom" id="prenom" type="text" required value="<?php echo htmlspecialchars($prenom); ?>">
            <label for="email">Email :</label>
            <input name="email" id="email" type="email" required value="<?php echo htmlspecialchars($email); ?>">
            <label for="pseudo">Pseudo :</label> <!-- Nouveau champ pour le pseudo -->
            <input name="pseudo" id="pseudo" type="text" required value="<?php echo htmlspecialchars($pseudo); ?>">
            <label for="CIN">CIN :</label> <!-- Nouveau champ pour le CIN -->
            <input name="CIN" id="CIN" type="text" required value="<?php echo htmlspecialchars($CIN); ?>">
            <label for="photo">Photo de profil :</label> <!-- Champ pour la photo -->
            <input type="file" id="photo" name="photo">
            <button type="submit">Modifier Profil</button>
        </form>
    </div>

    <div id="MesProjets" class="fonction-container">
    <?php
    // Include le fichier de connexion à la base de données (s'il n'est pas déjà inclus)
    include_once 'connexion.php';
    
    // Récupérer les projets du startuper depuis la base de données
    if (isset($_SESSION['utilisateur'])) {
        $utilisateur = $_SESSION['utilisateur'];
        $id_startuper = $utilisateur['id_startuper'];
        $requete_projet = $dbh->prepare("SELECT * FROM projet WHERE id_startuper = :id_startuper");
        $requete_projet->bindParam(':id_startuper', $id_startuper);
        $requete_projet->execute();
        
        // Afficher les projets
        if ($requete_projet->rowCount() > 0) {
            echo "<h2>Mes Projets</h2>";
            echo "<table>";
            echo "<tr><th>Titre</th><th>Description</th><th>Actions à vendre</th><th>Actions vendues</th><th>Prix par action</th><th>Actions restantes</th><th>Montant collecté</th></tr>";
            while ($projet = $requete_projet->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$projet['titre']}</td>";
                echo "<td>{$projet['descriptions']}</td>";
                echo "<td>{$projet['nombre_actions_a_vendre']}</td>";
                echo "<td>{$projet['nombrre_actions_vendues']}</td>";
                echo "<td>{$projet['prix_action']}</td>";
                echo "<td>" . ($projet['nombre_actions_a_vendre'] - $projet['nombrre_actions_vendues']) . "</td>";
                echo "<td>" . ($projet['nombrre_actions_vendues'] * $projet['prix_action']) . "</td>";

                echo "<td><button><a href='editer_projetS.php?id_projet={$projet['id_projet']}' target='_blank'>Editer le projet</a></button></td>";
                echo "</tr>";
                echo "<tr><td colspan='8'><hr></td></tr>"; // Ajout de la ligne de séparation
            }
            echo "</table>";
        } else {
            echo "<p>Vous n'avez déposé aucun projet.</p>";
        }
    } else {
        echo "<p>Veuillez vous connecter pour voir vos projets.</p>";
    }
    ?>
</div>

<div id="SupprimerMesProjets" class="fonction-container">
    <?php
    // Vérifier si les données de l'utilisateur sont disponibles dans la session
    if (isset($_SESSION['utilisateur'])) {
        $utilisateur = $_SESSION['utilisateur'];
        $id_startuper = $utilisateur['id_startuper'];
        $requete_projet = $dbh->prepare("SELECT * FROM projet WHERE id_startuper = :id_startuper");
        $requete_projet->bindParam(':id_startuper', $id_startuper);
        $requete_projet->execute();
        
        // Afficher les projets
        if ($requete_projet->rowCount() > 0) {
            echo "<h2>Surrpimer les projets</h2>";
            echo "<table>";
            echo "<tr><th>Titre</th><th>Description</th><th>Actions à vendre</th><th>Actions vendues</th><th>Prix par action</th><th>Supprimer</th></tr>";
            while ($projet = $requete_projet->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$projet['titre']}</td>";
                echo "<td>{$projet['descriptions']}</td>";
                echo "<td>{$projet['nombre_actions_a_vendre']}</td>";
                echo "<td>{$projet['nombrre_actions_vendues']}</td>";
                echo "<td>{$projet['prix_action']}</td>";
                
                // Vérifier si le projet n'a pas encore vendu d'actions
                if ($projet['nombrre_actions_vendues'] == 0) {
                    echo "<td><form action='supprimer_projet.php' method='post'><input type='hidden' name='id_projet' value='{$projet['id_projet']}'><button type='submit'>Supprimer ce projet</button></form></td>";
                } else {
                    // Si des actions ont été vendues, afficher un message indiquant que le projet ne peut pas être supprimé
                    echo "<td>Impossible de supprimer le projet!</td>";
                }
                
                echo "</tr>";
                echo "<tr><td colspan='6'><hr></td></tr>"; // Ajout de la ligne de séparation
            }
            echo "</table>";
        } else {
            echo "<p>Vous n'avez déposé aucun projet.</p>";
        }
    } else {
        echo "<p>Veuillez vous connecter pour voir vos projets.</p>";
    }
    ?>
</div>

<script>
        // Fonction pour afficher la div correspondante et masquer les autres
        function afficherDiv(id) {
            // Masquer toutes les divs
            var divs = document.getElementsByClassName('fonction-container');
            for (var i = 0; i < divs.length; i++) {
                divs[i].style.display = 'none';
            }
            // Afficher la div correspondante
            document.getElementById(id).style.display = 'block';
        }

    </script>

<div class="logout-container">
    <form action="logoutC.php" method="post">
        <button class="button" type="submit">Déconnexion</button>
    </form>
</div>


</body>
</html>
