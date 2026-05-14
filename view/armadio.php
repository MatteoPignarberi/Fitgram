<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Il mio Armadio - Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/css/main.css">
    <link rel="stylesheet" href="../styles/css/components.css">
    <link rel="stylesheet" href="../styles/css/armadio.css">
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<header class="wardrobe-header">
    <h1>Il mio Archivio</h1>
    <div class="stats-archive"><?php echo count($miei_look); ?> Outfit salvati</div>
</header>

<main class="wardrobe-container">
    <?php if (empty($miei_look)): ?>
        <div class="empty-state">
            <p>Il tuo armadio è ancora vuoto.</p>
            <a href="carica_look.php" class="text-link" style="color: var(--accent-pop);">Carica il tuo primo look ora →</a>
        </div>
    <?php else: ?>
        <div class="archive-grid">
            <?php foreach ($miei_look as $look):
                // Recuperiamo il numero di capi dal Model
                $n_capi = getDettagliOutfit($conn, $look['id']);
                ?>
                <article class="archive-card">
                    <div class="outfit-badge"><?php echo $n_capi; ?> Capi</div>

                    <div class="card-img-container">
                        <img src="../uploads/<?php echo htmlspecialchars($look['immagine']); ?>" alt="Outfit Fitgram">
                        <div class="card-overlay">
                            <a href="dettaglio_outfit.php?id=<?php echo $look['id']; ?>" class="btn-dettaglio">Vedi dettagli</a>
                        </div>
                    </div>

                    <div class="archive-info">
                        <span class="archive-date"><?php echo date("d.m.Y", strtotime($look['timestamp'])); ?></span>
                        <p class="archive-desc"><?php echo htmlspecialchars($look['descrizione']); ?></p>
                        <div class="archive-footer">
                            <a href="dettaglio_outfit.php?id=<?php echo $look['id']; ?>" class="link-capi">Scopri i capi →</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>