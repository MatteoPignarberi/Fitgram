<?php
function createLook($conn, $descrizione, $nomeFile, $username, $tags, $link_acquisto) {
    // Query SQL con i nuovi campi
    $sql = "INSERT INTO outfit (descrizione, immagine, username, tags, link_acquisto) VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // 'sssss' indica che stiamo passando 5 stringhe
        mysqli_stmt_bind_param($stmt, "sssss", $descrizione, $nomeFile, $username, $tags, $link_acquisto);

        $esito = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $esito;
    } else {
        return false;
    }
}