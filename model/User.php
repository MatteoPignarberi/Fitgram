<?php
function findUserByUsername($conn, $username) {
    $sql = "SELECT * FROM Utenti WHERE username = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return null;
    }
    mysqli_stmt_bind_param($stmt, "s", $username);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }

    return null;
}
function createUser($conn, $nome, $cognome, $username, $password_hash, $email) {
    $sql = "INSERT INTO Utenti (nome, cognome, username, pass, email, dataRegistrazione) VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return false;
    }

    // Inseriamo i dati ("sssss" = 5 stringhe)
    mysqli_stmt_bind_param($stmt, "sssss", $nome, $cognome, $username, $password_hash, $email);

    // Eseguiamo e restituiamo true se va a buon fine
    return mysqli_stmt_execute($stmt);
}

?>
