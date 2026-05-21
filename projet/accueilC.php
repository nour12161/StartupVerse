<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Projets à Financer</title>
    <link rel="stylesheet" href="accueilCC.css"> <!-- Assurez-vous d'avoir un fichier style.css correspondant -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">



    <style>
        /* Pour masquer toutes les divs initialement */
        .fonction-container {
            display: none;
        }
    </style>

</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php
    session_start();

    // Vérifier si les données de l'utilisateur sont disponibles dans la session
    if (!isset($_SESSION['utilisateur'])) {
        // Rediriger l'utilisateur vers la page de connexion ou afficher un message d'erreur
        echo "Veuillez vous connecter en tant que capital-risque pour accéder à cette page.";
        exit(); // Arrêter l'exécution du script
    }

    // Récupérer les données de l'utilisateur depuis la session
    $capital = $_SESSION['utilisateur'];

    $nom = $capital['nom'];
    $prenom = $capital['prenom'];
    $email = $capital['email'];
    $pseudo = $capital['pseudo']; // Nouveau champ pour le pseudo
    $CIN = $capital['CIN']; // Nouveau champ pour le CIN
    echo '<div class="UserName">';
    echo '<p>Welcome to STARTUPVERSE  ' . $prenom .'! </p>';
    echo '</div>';
    
    ?>

<button class="button1" id="ListeProjetsFinancerBtn" onclick="afficherDiv('ListeProjetsFinancer')">Liste des projets à financer</button>
<button class="button2" id="listeProjetsAchetesBtn" onclick="afficherDiv('listeProjetsAchetes')">Liste des projets achetés</button>


    <div id="ListeProjetsFinancer" class="fonction-container">
 

    <!-- Contenu de la page avec les données du capital-risque -->

    <?php

    // Inclure le fichier de connexion à la base de données
    include_once 'connexion.php';
  

    // Variable pour vérifier si une recherche a déjà été effectuée
    $recherche_effectuee = false;

    // Vérifier si une recherche est effectuée
    if (isset($_POST['recherche'])) {
        // Nettoyer la chaîne de recherche
        $recherche = htmlspecialchars($_POST['recherche']);
        
        // Préparer la requête SQL avec une recherche dans la description et le titre du projet
        $requete_projets = $dbh->prepare("SELECT *, (nombre_actions_a_vendre - nombrre_actions_vendues) AS actions_restantes FROM projet WHERE etat_projet = 'en_attente_financement' AND (titre LIKE :recherche OR descriptions LIKE :recherche) AND (nombre_actions_a_vendre - nombrre_actions_vendues) > 0");
        $requete_projets->bindValue(':recherche', '%' . $recherche . '%', PDO::PARAM_STR);
        $requete_projets->execute();
        
        // Afficher les projets à financer
        if ($requete_projets->rowCount() > 0) {
            echo "<h2>Résultats de la recherche :</h2>";
            echo "<table id='projetDispo'>";
            echo "<tr><th>Titre</th><th>Description</th><th>Actions à vendre</th><th>Prix par action</th><th>Actions restantes à vendre</th><th>Acheter</th></tr>";
            while ($projet = $requete_projets->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$projet['titre']}</td>";
                echo "<td>{$projet['descriptions']}</td>";
                echo "<td>{$projet['nombre_actions_a_vendre']}</td>";
                echo "<td>{$projet['prix_action']}</td>";
                echo "<td>{$projet['actions_restantes']}</td>";
                echo "<td> <form action='acheter_actionC.php' method='post'>
                          <input type='hidden' name='id_projet' value='{$projet['id_projet']}'>
                          <input type='number' name='nombre_actions' min='1' max='{$projet['actions_restantes']}' required>
                          <button type='submit'>Acheter</button>
                       </form></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Aucun projet correspondant à votre recherche.</p>";
        }

        // Définir la variable de recherche à true pour indiquer qu'une recherche a été effectuée
        $recherche_effectuee = true;
    }

    // Si aucune recherche n'a été effectuée ou si la recherche n'a pas donné de résultats, afficher tous les projets à financer
    if (!$recherche_effectuee) {
        echo "<h2>Liste des Projets à Financer</h2>";

        // Récupérer tous les projets à financer depuis la base de données
        $requete_projets = $dbh->prepare("SELECT *, (nombre_actions_a_vendre - nombrre_actions_vendues) AS actions_restantes FROM projet WHERE etat_projet = 'en_attente_financement' AND (nombre_actions_a_vendre - nombrre_actions_vendues) > 0");
        $requete_projets->execute();
        
        // Afficher les projets à financer
        if ($requete_projets->rowCount() > 0) {
            
            echo "<table border ='1' id='projetDispo'>";
            echo "<tr><th>Titre</th><th>Description</th><th>Actions à vendre</th><th>Prix par action</th><th>Actions restantes à vendre</th><th>Acheter</th></tr>";
            while ($projet = $requete_projets->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$projet['titre']}</td>";
                echo "<td>{$projet['descriptions']}</td>";
                echo "<td>{$projet['nombre_actions_a_vendre']}</td>";
                echo "<td>{$projet['prix_action']}</td>";
                echo "<td>{$projet['actions_restantes']}</td>";
                echo "<td><form action='acheter_actionC.php' method='post'>
                          <input type='hidden' name='id_projet' value='{$projet['id_projet']}'>
                          <input type='number' name='nombre_actions' min='1' max='{$projet['actions_restantes']}' required>
                          <button type='submit'>Acheter</button>
                       </form></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Aucun projet à financer pour le moment.</p>";
        }
    }
    ?>


    <!-- Formulaire de recherche -->
    <div class="search">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="recherche">Rechercher :</label>
        <input type="text" name="recherche" id="recherche" required>
        <button type="submit">Rechercher</button>
    </form>
    </div>

    <!-- Bouton pour retourner à la liste complète des projets -->
    <?php if ($recherche_effectuee): ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <button type="submit">Retour à la liste complète des projets</button>
    </form>
    <?php endif; ?>
    </div>

    <div id="listeProjetsAchetes" class="fonction-container">
    <!-- Le contenu sera chargé ici dynamiquement -->
</div>

    <!-- Bouton de déconnexion -->
    <div class="logout-container">
    <form action="logoutC.php" method="post">
        <button class="button" type="submit">Déconnexion</button>
    </form>
</div>

    <script>
    // Fonction pour afficher la div correspondante et masquer les autres
    function afficherDiv(id) {
        // Masquer toutes les divs avec la classe 'fonction-container'
        var divs = document.getElementsByClassName('fonction-container');
        for (var i = 0; i < divs.length; i++) {
            divs[i].style.display = 'none';
        }
        // Afficher la div correspondante avec l'id spécifié
        document.getElementById(id).style.display = 'block';
    }


    // Fonction pour charger les projets achetés
function chargerProjetsAchetes() {
    // Effectuez une requête Ajax pour charger les projets achetés dans la balise <div>
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                document.getElementById("listeProjetsAchetes").innerHTML = xhr.responseText;
            } else {
                console.error("Erreur lors du chargement des projets achetés : " + xhr.status);
            }
        }
    };
    xhr.open("GET", "projetacheterC.php", true);
    xhr.send();
}

// Fonction pour afficher la div correspondante et masquer les autres
function afficherDiv(id) {
    // Masquer toutes les divs avec la classe 'fonction-container'
    var divs = document.getElementsByClassName('fonction-container');
    for (var i = 0; i < divs.length; i++) {
        divs[i].style.display = 'none';
    }
    // Afficher la div correspondante avec l'id spécifié
    document.getElementById(id).style.display = 'block';
    
    // Si l'id correspond à 'listeProjetsAchetes', charger les projets achetés
    if (id === 'listeProjetsAchetes') {
        chargerProjetsAchetes();
    }
}
</script>


</body>
</html>



