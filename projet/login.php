<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Startuper</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="background-image">
    <div class="container">
        <div class="login-container" id="LoginStartuper">
            <h2>Login Startuper</h2>
            <?php
            
            include_once 'connexion.php';

            
            function verifierIdentifiants($pseudo, $pwrd) {
                global $dbh;

                
                $requete = $dbh->prepare("SELECT * FROM startupeur WHERE pseudo = :pseudo");
                $requete->bindParam(':pseudo', $pseudo);
                $requete->execute();
                $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

                
                if ($utilisateur && password_verify($pwrd, $utilisateur['pwrd'])) {
                    return true; 
                } else {
                    return false; 
                }
            }

           
            $erreur = '';

          
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                if (isset($_POST['pseudoA']) && isset($_POST['mdpA'])) {
                    
                    $pseudo = $_POST['pseudoA'];
                    $mdp = $_POST['mdpA'];

                   
                    if (verifierIdentifiants($pseudo, $mdp)) {
                       
                        $succesMessage = "Connexion réussie !";

                        header("location: accueilS.php");

                    } else {
                        $erreur = "L'utilisateur n'existe pas. Veuillez réessayer.";
                    }
                } else {
                    $erreur = "";
                }
            }
            ?>
            
            <?php if (!empty($erreur)) { ?>
                <p style="color: red;"><?php echo htmlspecialchars($erreur); ?></p>
            <?php } elseif (!empty($succesMessage)) { ?>
                <p style="color: green;"><?php echo htmlspecialchars($succesMessage); ?></p>
            <?php } ?>
            
            <form action="authentifierS.php" method="post"> 
                <label for="pseudo">Pseudo :</label>
                <input name="pseudo" id="pseudo" type="text" required>
                <label for="pass">Mot de passe :</label>
                <input name="pass" id="pass" type="password" required>
                <button type="submit">Se connecter en tant que Startuper</button>
            </form>
            <a href="projet.html">Inscrivez vous!</a>
        </div>
        <div class="login-container" id="LoginCapitalRisque">
            <h2>Login Capital Risque</h2>
            <?php
            include_once 'connexion.php';

            function verifierIdentifiantsCapital($pseudo, $pwrd) {
                global $dbh;

                $requete = $dbh->prepare("SELECT * FROM capital_risque WHERE pseudo = :pseudo");
                $requete->bindParam(':pseudo', $pseudo);
                $requete->execute();
                $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

                if ($utilisateur && password_verify($pwrd, $utilisateur['pwrd'])) {
                    return true; 
                } else {
                    return false; 
                }
            }

            $erreur = '';
            $succesMessage = '';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['pseudo']) && isset($_POST['pass'])) {
                    $pseudo = $_POST['pseudo'];
                    $mdp = $_POST['pass'];

                    if (verifierIdentifiantsCapital($pseudo, $mdp)) {
                        $succesMessage = "Connexion réussie !";
                        session_start();
                        $_SESSION['capital_risque'] = $pseudo; // Stocker le pseudo du Capital Risque dans la session
                        header("location: accueilC.php");
                        exit(); // Arrête l'exécution du script après la redirection
                    } else {
                        $erreur = "Pseudo ou mot de passe incorrect.";
                    }
                } else {
                    $erreur = "Veuillez remplir tous les champs.";
                }
            }
            ?>
            
            <?php if (!empty($erreur)) { ?>
                <p style="color: red;"><?php echo htmlspecialchars($erreur); ?></p>
            <?php } elseif (!empty($succesMessage)) { ?>
                <p style="color: green;"><?php echo htmlspecialchars($succesMessage); ?></p>
            <?php } ?>
            
            <form action="authentifierC.php" method="post"> <!-- Modifier l'action pour envoyer les données à authentifierC.php -->
                <label for="pseudo">Pseudo :</label>
                <input name="pseudo" id="pseudo" type="text" required>
                <label for="pass">Mot de passe :</label>
                <input name="pass" id="pass" type="password" required>
                <button type="submit" name="submit_capital">Se connecter en tant que Capital Risque</button>
            </form>
            <a href="projet.html">Inscrivez-vous !</a>
        </div>
    </div>
</div>
</body>
</html>



