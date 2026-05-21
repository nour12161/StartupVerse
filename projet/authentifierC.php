<?php
include_once 'connexion.php';

// Fonction pour vérifier les identifiants du Capital Risque
function verifierIdentifiantsCapital($pseudo, $pass) {
    global $dbh;

    $requete = $dbh->prepare("SELECT * FROM capital_risque WHERE pseudo = :pseudo AND pwrd = :pass");
    $requete->bindParam(':pseudo', $pseudo);
    $requete->bindParam(':pass', $pass);
    $requete->execute();
    $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

    return $utilisateur;
}

// Vérifier si des données ont été soumises via la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $pseudo = $_POST['pseudo'];
    $pass = $_POST['pass'];

    // Vérifier les identifiants du Capital Risque
    $utilisateur = verifierIdentifiantsCapital($pseudo, $pass);

    // Vérifier si l'utilisateur existe
    if ($utilisateur) {
        session_start();
        $_SESSION['utilisateur'] = $utilisateur;
        header("location: accueilC.php");
        exit;
    } else {
        echo "Pseudo ou mot de passe incorrect.";
    }
}
?>

