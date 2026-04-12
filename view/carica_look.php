<?php
session_start();

// PROTEZIONE: Se l'utente non è loggato, via al login!
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carica Look - Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/css/carica_look.css">
</head>
<body>

<div class="upload-container">
    <h2>Carica il tuo Look</h2>

    <?php if (isset($_SESSION['msg_look'])): ?>
        <?php echo $_SESSION['msg_look']; ?>
        <?php unset($_SESSION['msg_look']); // Cancello il messaggio dopo averlo mostrato ?>
    <?php endif; ?>

    <form action="../controller/lookController.php" method="POST" enctype="multipart/form-data">

        <label for="immagine">Seleziona una foto (JPG, PNG, GIF, WEBP)</label>
        <input type="file" name="immagine" id="immagine" accept="image/*" required>

        <label for="descrizione">Aggiungi una descrizione (opzionale)</label>
        <textarea name="descrizione" id="descrizione" placeholder="Es: Outfit perfetto per la palestra..."></textarea>

        <input type="submit" value="Pubblica Look">
    </form>

    <a href="../index.php" class="back-link">← Annulla e torna alla Home</a>
</div>

</body>
</html>