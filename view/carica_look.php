<?php
session_start();
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../styles/css/carica_look.css">
</head>
<body>

<div class="upload-container">
    <h2>Carica il tuo Look</h2>

    <?php if (isset($_SESSION['msg_look'])): ?>
        <?php echo $_SESSION['msg_look']; unset($_SESSION['msg_look']); ?>
    <?php endif; ?>

    <form action="../controller/upload_look_controller.php" method="POST" enctype="multipart/form-data">
        <label>Seleziona una foto:</label>
        <input type="file" name="immagine" required>

        <label>Descrizione:</label>
        <textarea name="descrizione" placeholder="Es: Outfit perfetto per la palestra..."></textarea>

        <label>Tag (separati da virgola):</label>
        <input type="text" name="tags" placeholder="es. #casual, #estate">

        <label>Link di acquisto (max 10):</label>
        <div id="link-container">
            <div class="link-input-group">
                <input type="url" name="link_acquisto[]" placeholder="https://www.esempio.com/prodotto">
                <button type="button" class="btn-action add-link-btn" id="addLinkBtn">+</button>
            </div>
        </div>

        <input type="submit" value="Pubblica Look">
    </form>
    <a href="../index.php" class="back-link">← Annulla e torna alla Home</a>
</div>

<script>
    const maxLinks = 10;
    let linkCount = 1;
    const container = document.getElementById("link-container");
    const addBtn = document.getElementById("addLinkBtn");

    addBtn.addEventListener("click", function() {
        if (linkCount < maxLinks) {
            linkCount++;
            const newGroup = document.createElement("div");
            newGroup.className = "link-input-group";
            newGroup.innerHTML = `
            <input type="url" name="link_acquisto[]" placeholder="https://www.esempio.com/prodotto">
            <button type="button" class="btn-action remove-link-btn">-</button>
        `;
            container.appendChild(newGroup);
            newGroup.querySelector('.remove-link-btn').onclick = function() {
                newGroup.remove();
                linkCount--;
                addBtn.style.display = "flex";
            };
            if (linkCount === maxLinks) addBtn.style.display = "none";
        }
    });
</script>
</body>
</html>