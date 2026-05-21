<?php
// Inclure le fichier de connexion à la base de données
include_once 'connexion.php';

// Initialiser les variables pour stocker les messages d'erreur et de succès
$erreur = '';
$succesMessage = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $pseudo = $_POST['pseudo']; // Nouveau champ pour le pseudo
    $CIN = $_POST['CIN']; // Nouveau champ pour le CIN
    $tmp_name = $_FILES["photo"]["tmp_name"];


    $dbh = new PDO("mysql:host=localhost;dbname=pweb", "root", "");

    // Vérifier si le fichier photo a été correctement téléchargé
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
        $img_data = file_get_contents($tmp_name); // Lire le contenu binaire de l'image
    } else {
        // Gérer le cas où aucun fichier photo n'a été téléchargé
        echo "Erreur : Aucune photo sélectionnée.";
        exit;
    }
    
    // Vérifier si l'utilisateur est connecté en tant que startuper et récupérer son ID
    session_start();

    // Vérifier si les données de l'utilisateur sont disponibles dans la session
    if (!isset($_SESSION['utilisateur'])) {
        // Rediriger l'utilisateur vers la page de connexion ou afficher un message d'erreur
        echo "Veuillez vous connecter pour ajouter un projet.";
        exit(); // Arrêter l'exécution du script
    }

    // Récupérer les données de l'utilisateur depuis la session
    $utilisateur = $_SESSION['utilisateur'];
    $idS = $utilisateur['id_startuper'];

    // Vérifier si un nouveau fichier de photo a été téléchargé
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
        // Lire le contenu binaire du fichier
        $tmp_name = $_FILES["photo"]["tmp_name"];
        $img_data = file_get_contents($tmp_name);

        // Mettre à jour la photo de profil dans la base de données
        $requete = $dbh->prepare("UPDATE startupeur SET photo = :photo WHERE id_startuper = :idS");
        $requete->bindParam(':photo', $img_data, PDO::PARAM_LOB);
        $requete->bindParam(':idS', $idS);

        if ($requete->execute()) {
            // Succès de la mise à jour de la photo de profil
            $succesMessage = "Photo de profil mise à jour avec succès.";
        } else {
            // Erreur lors de la mise à jour de la photo de profil
            $erreur = "Erreur lors de la mise à jour de la photo de profil. Veuillez réessayer.";
            // Afficher les informations sur l'erreur SQL
            print_r($requete->errorInfo());
        }
    }

    // Effectuer les validations nécessaires sur les données saisies
    
    // Les données sont valides, effectuer la mise à jour du profil dans la base de données
    $requete = $dbh->prepare("UPDATE startupeur SET nom = :nom, prenom = :prenom, email = :email, pseudo = :pseudo, CIN = :CIN WHERE id_startuper = :idS");
    $requete->bindParam(':nom', $nom);
    $requete->bindParam(':prenom', $prenom);
    $requete->bindParam(':email', $email);
    $requete->bindParam(':pseudo', $pseudo); // Liaison pour le pseudo
    $requete->bindParam(':CIN', $CIN); // Liaison pour le CIN
    $requete->bindParam(':idS', $idS);

    if ($requete->execute()) {
        // Mise à jour réussie, récupérer les informations mises à jour de l'utilisateur depuis la base de données
        $requete = $dbh->prepare("SELECT * FROM startupeur WHERE id_startuper = :idS");
        $requete->bindParam(':idS', $idS);
        $requete->execute();
        $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

        // Mettre à jour les données dans la session
        $_SESSION['utilisateur'] = $utilisateur;

        // Rediriger l'utilisateur vers la page d'accueil avec les nouvelles informations
        header("Location: accueilS.php");
        exit();
    } else {
        // Erreur lors de la mise à jour
        echo "Une erreur s'est produite. Veuillez réessayer.";
        // Afficher les informations sur l'erreur SQL
        print_r($requete->errorInfo());
    }
}

// Redirection vers la page d'accueil avec les messages d'erreur ou de succès
exit();
?>
