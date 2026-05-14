<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Il mio Armadio - Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/css/main.css">
    <link rel="stylesheet" href="../styles/css/armadio.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<header class="wardrobe-header">
    <img src="../resources/Images/Armadio.png" alt="Icona Armadio">
    <h1>Il mio Archivio</h1>
    <p><?php echo count($miei_look); ?> LOOK CARICATI</p>
</header>

<main class="wardrobe-container">
    <div class="archive-grid">
        <?php if (empty($miei_look)): ?>
            <div class="empty-state-msg">L'armadio è attualmente vuoto. Inizia a caricare i tuoi look!</div>
        <?php else: ?>
            <?php foreach ($miei_look as $look): ?>
                <div class="archive-card">
                    <img src="../uploads/<?php echo htmlspecialchars($look['immagine']); ?>" alt="Outfit">
                    <div class="archive-info">
                        <p><?php echo htmlspecialchars($look['descrizione']); ?></p>
                        <span><?php echo date("d/m/Y", strtotime($look['timestamp'])); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>

</body>
</html>