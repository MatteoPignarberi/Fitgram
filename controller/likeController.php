<?php
session_start();
// Percorso corretto per la connessione (regolalo se necessario)
require_once '../config/connessione.php';

// Verifichiamo che l'utente sia loggato
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['idOutfit'])) {
    $idOutfit = $_GET['idOutfit'];
    $idUtente = $_SESSION['user_id'];

    // Usiamo i nomi colonne del tuo DB: idUtente e idOutfit
    $check_query = "SELECT * FROM Likes WHERE idOutfit = '$idOutfit' AND idUtente = '$idUtente'";
    $check = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check) > 0) {
        // Se c'è già, lo togliamo
        mysqli_query($conn, "DELETE FROM Likes WHERE idOutfit = '$idOutfit' AND idUtente = '$idUtente'");
    } else {
        // Se non c'è, lo aggiungiamo
        mysqli_query($conn, "INSERT INTO Likes (idUtente, idOutfit) VALUES ('$idUtente', '$idOutfit')");
    }
}

// Torna alla pagina precedente (Dashboard)
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: ../Admin/dashboard.php");
}
exit();