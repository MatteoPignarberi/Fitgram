<?php
session_start();

// Recupero i dati inviati dal form
$username = $_POST["username"];
$password = $_POST["password"];
$nome = $_POST["nome"];
$cognome = $_POST["cognome"];
$email = $_POST["email"];
$dataRegistrazione = date('Y-m-d H:i:s');

// Connessione al database
$conn = mysqli_connect("localhost", "fitgram", "", "my_fitgram");

// 1. Controllo se l'username è già presente nel database
$check_sql = "SELECT * FROM utenti WHERE userName=?";
$check_stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($check_stmt, "s", $username);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);


if (mysqli_num_rows($check_result) > 0) {
    // Se c'è già una riga, l'username è occupato
    echo "<h4>Questo username è già in uso. <a href='registrazione.php'>Torna indietro e riprova</a>.</h4>";
} else {
    // 2. Se non esiste, procedo con l'inserimento
    $sql = "INSERT INTO utenti (nome,cognome,username, pass,email,dataRegistrazione) VALUES (?, ?, ?, ?,?,NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss",$nome, $cognome, $username, $password,$email,$dataRegistrazione );

    if (mysqli_stmt_execute($stmt)) {
        echo "<h4>Registrazione completata con successo! <a href='login.php'>Clicca qui per fare il login</a>.</h4>";
    } else {
        echo "<h4>Errore durante la registrazione. Riprova.</h4>";
    }
}

mysqli_close($conn);
?>
