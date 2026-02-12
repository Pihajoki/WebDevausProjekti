<?php
include('dbh.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nimi = $_POST["nimi"];

        try {
            require_once "dbh.php";

            $query = "DELETE FROM annokset WHERE nimi = :nimi;";



            $stmt = $pdo->prepare($query);

            $stmt->bindParam(":nimi", $nimi);

            $stmt->execute();

            $pdo = null;
            $stmt = null;

            header("Location: admin.php");


            die();
        }
        catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }

} else {
    header("Location: admin.php");
}