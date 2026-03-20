<?php
// 1. Avvia la sessione per recuperare i dati del login
session_start();

// 2. Controllo di sicurezza: se l'utente non è loggato, lo caccio fuori e lo rimando al login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Assicurati che il nome del file HTML sia corretto
    exit();
}

// 3. Connessione al database per recuperare "tutti i tuoi dati"
$conn = mysqli_connect("localhost", "root", "", "my_fitgram");

// Cerco tutti i dati dell'utente loggato
$username_loggato = $_SESSION['username'];
$sql = "SELECT * FROM Utenti WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username_loggato);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$dati_utente = mysqli_fetch_assoc($result);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Il mio Account - My Fitgram</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fafafa; padding: 20px; }
        .profilo { background: white; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .logout-btn { color: red; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="profilo">
    <h1>Bentornato, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h1>

    <p>Questo è il tuo account reale. Solo tu puoi vedere questa pagina.</p>

    <h3>I tuoi dati salvati:</h3>
    <ul>
        <li><strong>Username:</strong> <?php echo htmlspecialchars($dati_utente['username']); ?></li>
    </ul>

    <br>
    <a href="logout.php" class="logout-btn">Esci dall'account</a>
</div>

</body>
</html>