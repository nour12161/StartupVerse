<?php
// Inclure le fichier de connexion à la base de données
include_once 'connexion.php';

// Variable pour stocker les résultats de la recherche
$resultats = [];

// Vérifier si des données ont été soumises via la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer le mot-clé de recherche depuis le formulaire
    $recherche = $_POST['recherche'];

    // Requête SQL pour rechercher les projets contenant le mot-clé dans la description ou le titre
    $requete = $dbh->prepare("SELECT * FROM projet WHERE descriptions LIKE :recherche OR titre LIKE :recherche");
    $requete->bindValue(':recherche', '%' . $recherche . '%', PDO::PARAM_STR);
    $requete->execute();

    // Stocker les résultats de la recherche dans un tableau
    $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Contenu de la page -->
<div id="ListeProjetsFinancer" class="fonction-container">
    <!-- Affichage des résultats de la recherche -->
    <?php if (!empty($resultats)): ?>
        <h2>Résultats de la recherche :</h2>
        <ul>
            <?php foreach ($resultats as $projet): ?>
                <li><?php echo $projet['titre']; ?> - <?php echo $projet['descriptions']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Formulaire de recherche -->
    <?php if (empty($resultats)): ?>
        <div class="search">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="recherche">Rechercher :</label>
                <input type="text" name="recherche" id="recherche" required>
                <button type="submit">Rechercher</button>
            </form>
        </div>
    <?php endif; ?>
</div>


