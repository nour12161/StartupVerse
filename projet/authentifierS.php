
<?php
include_once 'connexion.php';
// Vérifier si des données ont été soumises via la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $pseudo = $_POST['pseudo'];
    $pass = $_POST['pass'];

    // Requête SQL pour vérifier le pseudo et le mot de passe dans la base de données
    $sql = "SELECT * FROM startupeur WHERE pseudo = :pseudo AND pwrd = :pass";

    // Préparation de la requête
    $stmt = $dbh->prepare($sql);

    // Liaison des paramètres
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->bindParam(':pass', $pass);

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($utilisateur) {
    
        echo "startuper connecté.";
        session_start();
$_SESSION['utilisateur'] = $utilisateur;
        header("location: accueilS.php");
        
        exit; 
    } else {
        
        echo "Pseudo ou mot de passe incorrect.";
    }
}
?>


