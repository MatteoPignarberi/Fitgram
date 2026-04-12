<?php
function createLook($conn, $descrizione, $nomeImmagine, $username) {
    $sql = "INSERT INTO Outfit (descrizione, immagine, username) VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    // Inseriamo i dati ("sss" = 3 stringhe)
    mysqli_stmt_bind_param($stmt, "sss", $descrizione, $nomeImmagine, $username);

    // Eseguiamo e restituiamo true o false
    return mysqli_stmt_execute($stmt);
}

?>