<?php
session_start();

// Diciamo al browser che questa pagina restituirà SOLO dati JSON
header('Content-Type: application/json');

// Recupero i dati inviati dal form
$username = $_POST["username"];
$password = $_POST["password"];
$nome = $_POST["nome"];
$cognome = $_POST["cognome"];
$email = $_POST["email"];

// Connessione al database (LOCALE = root)
$conn = mysqli_connect("localhost", "fitgram", "", "my_fitgram");

// Se la connessione fallisce, restituiamo l'errore in JSON
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Errore di connessione al database."]);
    exit();
}

// 1. Controllo se l'username è già presente
$check_sql = "SELECT * FROM Utenti WHERE username=?";
$check_stmt = mysqli_prepare($conn, $check_sql);

if (!$check_stmt) {
    echo json_encode(["status" => "error", "message" => "Errore nella query SELECT. Controlla phpMyAdmin."]);
    exit();
}

mysqli_stmt_bind_param($check_stmt, "s", $username);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

// Se l'utente esiste già
if (mysqli_num_rows($check_result) > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Questo username è già in uso. Scegline un altro!"
    ]);
    exit(); // Blocca l'esecuzione qui

} else {
    // 2. Inserimento (se l'utente NON esiste)
    $sql = "INSERT INTO Utenti (nome, cognome, username, pass, email, dataRegistrazione) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);

    // Se la query fallisce
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Errore nella query INSERT. Controlla le colonne."]);
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssss", $nome, $cognome, $username, $password, $email);

    // Eseguo l'inserimento
    if (mysqli_stmt_execute($stmt)) {
        // SUCCESSO: Utente salvato
        echo json_encode([
            "status" => "success",
            "message" => "Registrazione completata con successo! Reindirizzamento in corso..."
        ]);
        exit();
    } else {
        // ERRORE DATABASE DURANTE IL SALVATAGGIO
        echo json_encode([
            "status" => "error",
            "message" => "Errore durante il salvataggio dei dati."
        ]);
        exit();
    }
} // <-- Qui si chiude correttamente l'else!
mysqli_close($conn);
?>
<html>
    <body>
        <script>
        document.getElementById('form-registrazione').addEventListener('submit', function(e) {
            // 1. BLOCCO IL COMPORTAMENTO DI DEFAULT (il ricaricamento della pagina)
            e.preventDefault();

            // 2. Raccolgo tutti i dati scritti nei campi del form
            const formData = new FormData(this);
            const divMessaggio = document.getElementById('messaggio-esito');

            // Mostro un messaggio di caricamento (opzionale ma fa molto "pro")
            divMessaggio.innerHTML = "<span style='color: gray;'>Verifica in corso...</span>";

            // 3. Spedisco i dati in background al file PHP usando fetch
            fetch('salvaUtente.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Trasformo la risposta da PHP in un oggetto leggibile
            .then(data => {
                // 4. Analizzo la risposta di PHP e modifico l'HTML di conseguenza
                if (data.status === 'error') {
                    // Se c'è un errore (es. username usato), lo mostro in rosso
                    divMessaggio.innerHTML = `
                        <div style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px;">
                            <strong>Errore:</strong> ${data.message}
                        </div>`;
                }
                else if (data.status === 'success') {
                    // Se va bene, mostro il successo in verde
                    divMessaggio.innerHTML = `
                        <div style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 5px;">
                            <strong>Evviva!</strong> ${data.message}
                        </div>`;

                    // Opzionale: Reindirizzo l'utente alla pagina di login dopo 2 secondi
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                }
            })
            .catch(error => {
                // Se si rompe la connessione o il PHP restituisce un errore fatale
                console.error('Errore di rete o JSON non valido:', error);
                divMessaggio.innerHTML = "<div style='color: red;'>Errore di comunicazione col server.</div>";
            });
        });
        </script>
    <body>
<html>


