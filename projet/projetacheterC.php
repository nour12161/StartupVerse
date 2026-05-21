<?php
// Inclure le fichier de connexion à la base de données
include_once 'connexion.php';

// Fonction pour lister les projets achetés par le capital risque
function listerProjetsAchetes($id_capital_risque) {
    global $dbh;

    try {
        // Sélectionner les projets achetés par le capital risque
        $requete = $dbh->prepare("SELECT projet.id_projet, projet.titre, capital_risque_projet.nombre_action_achetees, projet.prix_action * capital_risque_projet.nombre_action_achetees AS investissement_total FROM projet INNER JOIN capital_risque_projet ON projet.id_projet = capital_risque_projet.id_projet WHERE capital_risque_projet.id_capital_risque = :id_capital_risque");
        $requete->bindParam(':id_capital_risque', $id_capital_risque);
        $requete->execute();

        // Vérifier s'il y a des projets achetés
        if ($requete->rowCount() > 0) {
            // Afficher les résultats
            echo "<h3>Liste des projets achetés :</h3>";
            echo "<table border='1' id='projetDispo'>";
            echo "<tr><th>Nom du projet</th><th>Nombre d'actions achetées</th><th>Investissement total</th></tr>";

            while ($row = $requete->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['titre'] . "</td>";
                echo "<td>" . $row['nombre_action_achetees'] . "</td>";
                echo "<td>" . $row['investissement_total'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            // Si aucun projet acheté n'est trouvé, afficher un message approprié
            echo "<p>Aucun projet acheté pour le moment.</p>";
        }
    } catch (PDOException $e) {
        // En cas d'erreur, afficher un message d'erreur
        echo "Erreur lors de la récupération des projets : " . $e->getMessage();
    }
}

// Vérifier d'abord si la session est démarrée et si l'utilisateur est connecté
session_start();
if (isset($_SESSION['utilisateur'])) {
    $capital = $_SESSION['utilisateur'];
    listerProjetsAchetes($capital['id_capital_risque']);
} else {
    echo "Veuillez vous connecter en tant que capital-risque pour accéder à cette page.";
}
?>
