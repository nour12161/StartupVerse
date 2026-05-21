<?php

$host = "localhost";   
$dbname = "pweb";
$username = "root"; 
$password = ""; 
try {
    
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

} catch (PDOException $e) {
    
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>