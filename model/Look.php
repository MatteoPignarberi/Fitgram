<?php
// model/Look.php

function createLook($conn, $descrizione, $nomeFile, $username, $tags, $link_acquisto) {
    // Query basata sulla tua tabella Outfit
    $sql = "INSERT INTO outfit (descrizione, immagine, username, tags, link_acquisto) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssss", $descrizione, $nomeFile, $username, $tags, $link_acquisto);
        $esito = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $esito;
    }
    return false;
}
?>