<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivio Armadio - Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/css/main.css">
    <link rel="stylesheet" href="../styles/css/components.css">
    <link rel="stylesheet" href="../styles/css/armadio.css">
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<div class="wardrobe-container">
    <header class="wardrobe-header">
        <h1 class="archive-title">Il mio Armadio</h1>
        <p class="archive-subtitle">Archivio personale &mdash; <?php echo count($miei_look); ?> outfit salvati</p>
    </header>

    <div class="wardrobe-grid">
        <?php if (empty($miei_look)): ?>
            <div class="empty-wardrobe">
                <p>Il tuo armadio è ancora vuoto.</p>
                <a href="carica_look.php" style="color: var(--accent-pop);">Carica il tuo primo look ora</a>
            </div>
        <?php else: ?>
            <?php foreach ($miei_look as $look):
                // Step in più: contiamo i capi per questo outfit specifico
                // Assicurati che getDettagliOutfit sia nel tuo Model
                $n_capi = getDettagliOutfit($conn, $look['id']);
                ?>
                <article class="outfit-card">
                    <div class="outfit-badge"><?php echo $n_capi; ?> Capi</div>

                    <div class="outfit-img-container">
                        <img src="../uploads/<?php echo htmlspecialchars($look['immagine']); ?>" class="outfit-img" alt="Mio Outfit">
                        <div class="outfit-hover-overlay">
                            <a href="dettaglio_outfit.php?id=<?php echo $look['id']; ?>" class="btn-details">Dettagli</a>
                        </div>
                    </div>

                    <div class="outfit-details">
                        <span class="outfit-date"><?php echo date("d M Y", strtotime($look['timestamp'])); ?></span>
                        <p class="outfit-desc"><?php echo htmlspecialchars($look['descrizione']); ?></p>
                        <div class="outfit-footer">
                            <a href="dettaglio_outfit.php?id=<?php echo $look['id']; ?>" class="view-more">Vedi capi utilizzati →</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>