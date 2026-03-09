<?php
session_start();

// Recupero i dati inviati dal form
$username = $_POST["username"];
$password = $_POST["password"];
$nome = $_POST["nome"];
$cognome = $_POST["cognome"];
$email = $_POST["email"];
// ⚠️ NON creare la variabile $dataRegistrazione! Non serve!

// Connessione al database su Altervista
$conn = mysqli_connect("localhost", "fitgram", "", "my_fitgram");

if (!$conn) {
    die("Errore di connessione al database: " . mysqli_connect_error());
}

// 1. Controllo se l'username è già presente (ATTENZIONE: controlla che la tabella si chiami 'utenti' tutto minuscolo)
$check_sql = "SELECT * FROM utenti WHERE username=?";
$check_stmt = mysqli_prepare($conn, $check_sql);

if (!$check_stmt) {
    die("<h4>ERRORE NELLA QUERY SELECT: " . mysqli_error($conn) . " (Controlla il nome della tabella o colonna su phpMyAdmin!)</h4>");
}

mysqli_stmt_bind_param($check_stmt, "s", $username);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);


if (mysqli_num_rows($check_result) > 0) {
    echo "<h4>Questo username è già in uso. <a href='registrazione.php'>Torna indietro e riprova</a>.</h4>";
} else {
    // 2. Inserimento
    // Uso NOW() per la data. I punti interrogativi sono 5.
    $sql = "INSERT INTO utenti (nome, cognome, username, pass, email, dataRegistrazione) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);

    // SE LA QUERY FALLISCE (Es. nomi tabelle/colonne sbagliati), ORA TI STAMPA L'ERRORE VERO!
    if (!$stmt) {
        die("<h4>ERRORE NELLA QUERY INSERT: " . mysqli_error($conn) . " (Controlla che le colonne scritte sopra esistano identiche su Altervista!)</h4>");
    }

    // QUI I PARAMETRI DEVONO ESSERE ESATTAMENTE 5 ("sssss" e le 5 variabili). Niente data!
    mysqli_stmt_bind_param($stmt, "sssss", $nome, $cognome, $username, $password, $email);

    if (mysqli_stmt_execute($stmt)) {
        echo "<h4>Registrazione completata con successo! <a href='login.php'>Clicca qui per fare il login</a>.</h4>";
    } else {
        echo "<h4>Errore durante il salvataggio: " . mysqli_error($conn) . "</h4>";
    }
}

mysqli_close($conn);
?>
