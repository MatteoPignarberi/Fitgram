<?php
session_start();

// 1. PROTEZIONE: Se l'utente non è loggato, lo rispediamo al login!
if (!isset($_SESSION['username'])) {
    header("Location: Admin/login.php");
    exit();
}

$messaggio = "";

// 2. GESTIONE DELL'UPLOAD (Si attiva solo quando l'utente preme "Pubblica")
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $descrizione = $_POST['descrizione'] ?? '';
    $username = $_SESSION['username'];

    // Controlliamo se c'è un file e se non ci sono stati errori durante l'invio
    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {

        $fileTmpPath = $_FILES['immagine']['tmp_name'];
        $fileName = $_FILES['immagine']['name'];

        // Estraiamo l'estensione del file (es: jpg, png)
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Consentiamo solo immagini
        $estensioni_permesse = array('jpg', 'jpeg', 'png', 'gif', 'webp');

        if (in_array($fileExtension, $estensioni_permesse)) {

            // Generiamo un nome UNICO per il file (evita che due foto chiamate "foto.jpg" si sovrascrivano)
            $nuovoNomeFile = md5(time() . $fileName) . 'Fitgram' . $fileExtension;

            // Cartella dove salveremo le foto
            $cartellaUpload = './uploads/';

            // Se la cartella "uploads" non esiste, la creiamo in automatico!
            if (!is_dir($cartellaUpload)) {
                mkdir($cartellaUpload, 0777, true);
            }

            $percorso_destinazione = $cartellaUpload . $nuovoNomeFile;

            // Spostiamo il file dalla memoria temporanea alla cartella finale
            if(move_uploaded_file($fileTmpPath, $percorso_destinazione)) {

                // 3. SALVIAMO NEL DATABASE
                $conn = mysqli_connect("localhost", "root", "", "my_fitgram");

                if ($conn) {
                    // Inseriamo la descrizione, il NOME del file e l'autore nella tabella Outfit
                    $sql = "INSERT INTO Outfit (descrizione, immagine, username) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $sql);

                    mysqli_stmt_bind_param($stmt, "sss", $descrizione, $nuovoNomeFile, $username);

                    if (mysqli_stmt_execute($stmt)) {
                        $messaggio = "<div class='msg success'>Look pubblicato con successo! <a href='../index.php'>Torna alla Home</a></div>";
                    } else {
                        $messaggio = "<div class='msg error'>Errore nel salvataggio sul database.</div>";
                    }
                    mysqli_close($conn);
                } else {
                    $messaggio = "<div class='msg error'>Errore di connessione al database.</div>";
                }

            } else {
                $messaggio = "<div class='msg error'>Errore nello spostamento del file. Riprova.</div>";
            }
        } else {
            $messaggio = "<div class='msg error'>Formato non valido! Carica solo JPG, PNG, GIF o WEBP.</div>";
        }
    } else {
        $messaggio = "<div class='msg error'>Nessun file selezionato o errore nel caricamento.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carica Look - Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f5f0;
            color: #3d3d3d;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .upload-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            width: 100%;
            max-width: 400px;
            border: 1px solid #efd3d2;
        }
        h2 { margin-top: 0; font-weight: 500; text-align: center; margin-bottom: 30px; }

        /* Stile per i messaggi di errore/successo */
        .msg { padding: 15px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; text-align: center; }
        .msg.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .msg.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .msg a { color: inherit; font-weight: bold; }

        label { display: block; margin-bottom: 8px; font-size: 0.85rem; font-weight: 600; }

        input[type="file"], textarea {
            width: 100%; padding: 12px; margin-bottom: 20px;
            border: 1px solid #efd3d2; border-radius: 6px; box-sizing: border-box;
            background-color: #fafafa; font-family: inherit;
        }
        textarea { resize: vertical; min-height: 80px; }

        input[type="submit"] {
            background-color: #b8807d; color: white; border: none; padding: 15px;
            width: 100%; border-radius: 30px; cursor: pointer; font-size: 1rem;
            font-weight: 500; transition: background 0.3s;
        }
        input[type="submit"]:hover { background-color: #3d3d3d; }

        .back-link { display: block; text-align: center; margin-top: 20px; color: #888; text-decoration: none; font-size: 0.85rem; }
        .back-link:hover { color: #3d3d3d; }
    </style>
</head>
<body>

<div class="upload-container">
    <h2>Carica il tuo Look</h2>

    <?php echo $messaggio; ?>

    <form action="carica_look.php" method="POST" enctype="multipart/form-data">

        <label for="immagine">Seleziona una foto (JPG, PNG)</label>
        <input type="file" name="immagine" id="immagine" accept="image/*" required>

        <label for="descrizione">Aggiungi una descrizione (opzionale)</label>
        <textarea name="descrizione" id="descrizione" placeholder="Es: Outfit perfetto per la palestra..."></textarea>

        <input type="submit" value="Pubblica Look">
    </form>

    <a href="../index.php" class="back-link">← Annulla e torna alla Home</a>
</div>

</body>
</html>
