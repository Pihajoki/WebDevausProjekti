<?php
session_start();
include('dbh.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];


        try {
            require_once "dbh.php";

            $query = "SELECT pwd FROM omistaja WHERE username = :username";
            
            $stmt = $pdo->prepare($query);

            $stmt->bindParam(":username", $username);

            $stmt->execute();

            $results = $stmt->fetchColumn();

    
        
        
            if(password_verify($pwd, $results)) {
            $_SESSION["kirjautunut"] = true;
            header("Location: admin.php");
        } else {
            header("Location: adminkirjautuminen.php");
        }

            
            $pdo = null;
            $stmt = null;


        }
        
            catch (PDOException $e) {
                die("Query failed: " . $e->getMessage());
            }
} else {
    header("Location: adminkirjautuminen.php");
}
