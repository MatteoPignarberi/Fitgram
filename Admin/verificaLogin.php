<?php
session_start();
header('Content-Type: application/json');

// Recupero i dati dal form
$username = isset($_POST["username"]) ? $_POST["username"] : '';
$password = isset($_POST["password"]) ? $_POST["password"] : '';

// Connessione locale (ricordati di cambiarla in Altervista quando andrai online!)
$conn = mysqli_connect("localhost", "root", "", "my_fitgram");

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Errore di connessione al database."]);
    exit();
}

// Cerco l'utente nel database
$sql = "SELECT * FROM Utenti WHERE username=?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Errore nella query SELECT."]);
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Se trovo una riga, l'username esiste
if ($row = mysqli_fetch_assoc($result)) {

    // Controllo se la password digitata è uguale a quella nel database
    if ($password === $row['pass']) {

        // Successo! Salvo i dati nella Sessione per ricordarmi che l'utente è loggato
        $_SESSION['username'] = $row['username'];
        $_SESSION['nome'] = $row['nome']; // Se vuoi stampare "Ciao Marco!" nelle altre pagine

        echo json_encode([
            "status" => "success",
            "message" => "Bentornato! Accesso in corso..."
        ]);
        exit();

    } else {
        // La password è sbagliata
        echo json_encode([
            "status" => "error",
            "message" => "Password errata. Riprova."
        ]);
        exit();
    }

} else {
    // L'username non esiste nel database
    echo json_encode([
        "status" => "error",
        "message" => "Questo username non esiste."
    ]);
    exit();
}

mysqli_close($conn);
?>
