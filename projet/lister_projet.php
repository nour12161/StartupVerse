<?php
// Inclure le fichier de connexion à la base de données
include_once 'connexion.php';

session_start();

// Vérifier si l'utilisateur est connecté en tant que startuper et récupérer son ID
if (isset($_SESSION['utilisateur'])) {
    $utilisateur = $_SESSION['utilisateur'];
    $id_startuper = $utilisateur['id_startuper'];

    // Écrire une requête SQL pour récupérer les projets du startuper
    $requete = $dbh->prepare("SELECT * FROM projet WHERE id_startuper = :id_startuper");
    $requete->bindParam(':id_startuper', $id_startuper);
    $requete->execute();

    // Vérifier s'il y a des projets associés à ce startuper
    if ($requete->rowCount() > 0) {
        // Afficher les projets
        echo "<h2>Mes Projets</h2>";
        echo "<ul>";
        while ($projet = $requete->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>";
            echo "<strong>Titre:</strong> {$projet['titre']}<br>";
            echo "<strong>Description:</strong> {$projet['description']}<br>";
            echo "<strong>Nombre d'actions à vendre:</strong> {$projet['nombre_actions_a_vendre']}<br>";
            echo "<strong>Nombre d'actions vendues:</strong> {$projet['nombrre_actions_vendues']}<br>";
            echo "<strong>Prix par action:</strong> {$projet['prix_action']}<br>";
            // Ajoutez d'autres détails du projet selon votre besoin
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "Vous n'avez déposé aucun projet pour le moment.";
    }
} else {
    echo "Veuillez vous connecter pour accéder à cette page.";
}
?>
