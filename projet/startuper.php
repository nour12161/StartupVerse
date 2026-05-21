<?php
// Inclure le fichier de connexion à la base de données
include_once 'connexion.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $cin = htmlspecialchars($_POST['cin']);
    $mail = htmlspecialchars($_POST['mail']);
    $nomEps = htmlspecialchars($_POST['eName']);
    $adresseEps = htmlspecialchars($_POST['eAdresse']);
    $regEps = htmlspecialchars($_POST['n_registre']);
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $mdp = $_POST['mdp']; // Assurez-vous de valider et hacher ce mot de passe
    $tmp_name = $_FILES["photo"]["tmp_name"];

    // Rétablir la connexion à la base de données si nécessaire
    $dbh = new PDO("mysql:host=localhost;dbname=pweb", "root", "");

    // Vérifier si le fichier photo a été correctement téléchargé
    if ($_FILES["photo"]["error"] != UPLOAD_ERR_OK) {
        echo "Erreur lors du téléchargement du fichier : " . $_FILES["photo"]["error"];
        exit;
    }
    
        // Gérer le cas où aucun fichier photo n'a été téléchargé
        echo "Erreur : Aucune photo sélectionnée.";
        exit;
    }

    $existe = utilisateurExiste($mail);
    if (!$existe) {
        // Préparation de la requête SQL d'insertion
        $stmt = $dbh->prepare("INSERT INTO startupeur (nom, prenom, CIN, email, nom_entreprise, adresse_entreprise, numero_registre_commerce, pseudo, pwrd) 
                               VALUES (:nom, :prenom, :cin, :mail, :nomEps, :adresseEps, :regEps, :pseudo, :mdp)");

        // Liaison des paramètres
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':cin', $cin);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':nomEps', $nomEps);
        $stmt->bindParam(':adresseEps', $adresseEps);
        $stmt->bindParam(':regEps', $regEps);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':photo', $img_data, PDO::PARAM_LOB); // Utilisation de PDO::PARAM_LOB pour un LONGBLOB
        $stmt->bindParam(':mdp', $mdp); // Assurez-vous de hacher le mot de passe ici

        // Exécution de la requête SQL
        if ($stmt->execute()) {
            // Afficher un message de succès
            echo "Inscription réussie !";
        } else {
            // Afficher un message en cas d'erreur
            echo "Erreur lors de l'inscription.";
            // Vous pouvez également enregistrer l'erreur dans un journal pour examen ultérieur
        }
    } else {
        echo "L'utilisateur existe déjà.";
    }

// Fonction pour vérifier si l'utilisateur existe déjà
function utilisateurExiste($email) {
    // Connexion à la base de données
    global $dbh;

    // Requête SQL pour rechercher l'utilisateur par email
    $sql = "SELECT COUNT(*) FROM startupeur WHERE email = :email";

    // Préparation de la requête
    $stmt = $dbh->prepare($sql);

    // Liaison des paramètres
    $stmt->bindParam(':email', $email);

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $nombreUtilisateurs = $stmt->fetchColumn();

    // Retourner vrai si l'utilisateur existe, faux sinon
    return ($nombreUtilisateurs > 0);
}
?>