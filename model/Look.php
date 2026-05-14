<?php
// model/Look.php

function createLook($conn, $descrizione, $nomeFile, $username, $tags, $link_acquisto) {
    // Query basata sulla tua tabella Outfit
    $sql = "INSERT INTO Outfit (descrizione, immagine, username, tags, link_acquisto) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssss", $descrizione, $nomeFile, $username, $tags, $link_acquisto);
        $esito = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $esito;
    }
    return false;
}

// model/Look.php

function getArmadioUtente($conn, $idUtente) {
    $outfits = [];
    // Query con JOIN per prendere i dati dell'outfit collegati all'utente specifico
    $sql = "SELECT O.* FROM Outfit O
            INNER JOIN OutfitUtenti OU ON O.id = OU.idOutfit
            WHERE OU.idUtente = ?
            ORDER BY O.timestamp DESC";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $idUtente);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $outfits[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
    return $outfits;
}