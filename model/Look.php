<?php
// model/Look.php

function createLook($conn, $descrizione, $nomeFile, $username, $tags, $link_acquisto) {
    // Specifichiamo i nomi delle colonne esattamente come nel tuo screenshot
    $sql = "INSERT INTO outfit (descrizione, immagine, username, tags, link_acquisto) VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Colleghiamo le 5 variabili
        mysqli_stmt_bind_param($stmt, "sssss", $descrizione, $nomeFile, $username, $tags, $link_acquisto);
        $esito = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $esito;
    }
    return false;
}
?>