<?php
session_start();
include_once "../config/connessione.php";

// Protezione: se non c'è l'ID utente in sessione, neghiamo l'operazione
if (!isset($_SESSION['id_utente_loggato'])) {
    header("Location: ../index.php"); // Rimanda alla home pubblica
    exit();
}

if (isset($_GET['idOutfit'])) {
    $idOutfit = $_GET['idOutfit'];
    $idUtente = $_SESSION['id_utente_loggato'];

    // Controllo se il like esiste già (Tabella Likes con colonne idUtente e idOutfit)
    $check = mysqli_query($conn, "SELECT * FROM Likes WHERE idOutfit = '$idOutfit' AND idUtente = '$idUtente'");

    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "DELETE FROM Likes WHERE idOutfit = '$idOutfit' AND idUtente = '$idUtente'");
    } else {
        mysqli_query($conn, "INSERT INTO Likes (idUtente, idOutfit) VALUES ('$idUtente', '$idOutfit')");
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();