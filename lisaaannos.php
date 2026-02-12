<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisää annos</title>
</head>
<body>

<?php 
include('dbh.php'); 
?>

<h2>Lisää annos</h2>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
            
        $nimi = trim($_POST['nimi']);
        $tiedot = trim($_POST['tiedot']);
        $hinta = str_replace(',', '.', $_POST['hinta']);
        $kuva = $_FILES['kuva'];

        // Validointi || throw new Exception siirtää virheen tapahtuessa heti catch osioon
        if (empty($nimi) || empty($tiedot) || empty($hinta) || empty($kuva)) {
            throw new Exception("Mikään kenttä ei saa olla tyhjä.");
        }

        if (!is_numeric($hinta)) {
            throw new Exception("Hinnan täytyy olla numeerinen.");
        }

        $kuva_nimi = time() . '_' . basename($kuva['name']);
        $kuva_polku = 'uploads/' . $kuva_nimi;

        $tyyppi = ['jpg', 'jpeg', 'png', 'gif'];
        $paate = strtolower(pathinfo($kuva['name'], PATHINFO_EXTENSION));

        if (!in_array($paate, $tyyppi)) {
            throw new Exception("Vain jpg, jpeg, png ja gif tiedostot sallittu.");
        }

        if (!move_uploaded_file($kuva['tmp_name'], $kuva_polku)) {
                throw new Exception("Kuvan lataaminen epäonnistui.");
            }

        $sql = "INSERT INTO annokset (nimi, tiedot, hinta, Kuva)
                VALUES (?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([$nimi, $tiedot, $hinta, $kuva_polku]);

        header("Location: admin.php?success=1");
        exit();
    
    } catch (Exception $e) {

        echo "<p style='color:red;'>" . htmlspecialchars($e->getMessage()) . "</p>";

    } catch (PDOException $e) {

        echo "<p style='color:red;'>Tietokantavirhe.</p>";

    }
}

$pdo = null;
?>
</body>
</html>