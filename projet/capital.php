<?php

include_once 'connexion.php';

$existe = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $nomC = $_POST['nomC'];
    $prenomC = $_POST['prenomC'];
    $cinC = $_POST['cinC'];
    $mailC = $_POST['mailC'];
    $pseudoC = $_POST['pseudoC'];
    $mdpC = $_POST['mdpC'];


    $existe = utilisateurExisteC($mailC);
    if ($existe == false) {


    
    $stmt = $dbh->prepare("INSERT INTO capital_risque (nom, prenom, email, CIN, pseudo, pwrd) 
                           VALUES (:nomC, :prenomC, :mailC, :cinC,:pseudoC, :mdpC)");

   
    $stmt->bindParam(':nomC', $nomC);
    $stmt->bindParam(':prenomC', $prenomC);
    $stmt->bindParam(':cinC', $cinC);
    $stmt->bindParam(':mailC', $mailC);
    $stmt->bindParam(':pseudoC', $pseudoC);
    $stmt->bindParam(':mdpC', $mdpC);

   
    if ($stmt->execute()) {
     
        echo "Inscription réussie !";
    } else {
       
        echo "Erreur lors de l'inscription.";
    }
    
exit;

}else {echo"l'utilisateur existe déja";}
}

function utilisateurExisteC($email) {
 
    $dbh = new PDO("mysql:host=localhost;dbname=pweb", "root", "");

 
    $sql = "SELECT COUNT(*) FROM capital_risque WHERE email = :email";

   
    $stmt = $dbh->prepare($sql);

  
    $stmt->bindParam(':email', $email);

  
    $stmt->execute();

   
    $nombreUtilisateurs = $stmt->fetchColumn();

    return ($nombreUtilisateurs > 0);
}

?>
