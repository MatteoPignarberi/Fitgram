<?php
/**
 * Crea un nuovo look nella tabella Outfit
 */
function createLook($conn, $descrizione, $nomeFile, $username, $tags, $link_acquisto) {
    $sql = "INSERT INTO Outfit (descrizione, immagine, username, tags, link_acquisto) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssss", $descrizione, $nomeFile, $username, $tags, $link_acquisto);
        if (mysqli_stmt_execute($stmt)) {
            $last_id = mysqli_insert_id($conn); // Recuperiamo l'ID appena creato
            mysqli_stmt_close($stmt);
            return $last_id;
        }
    }
    return false;
}

/**
 * Collega l'outfit all'utente nella tabella pivot
 */
function collegaOutfitUtente($conn, $idUtente, $idOutfit) {
    $sql = "INSERT INTO OutfitUtenti (idUtente, idOutfit) VALUES (?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $idUtente, $idOutfit);
        $esito = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $esito;
    }
    return false;
}

/**
 * Recupera i look per l'armadio
 */
function getArmadioUtente($conn, $idUtente) {
    $outfits = [];
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
?>