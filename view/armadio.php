<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivio Armadio - Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/css/main.css">
    <link rel="stylesheet" href="../styles/css/components.css">
    <link rel="stylesheet" href="../styles/css/sidebar.css">
    <style>
        .wardrobe-container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .archive-title { font-family: 'Playfair Display', serif; font-size: 2.8rem; text-align: center; margin-bottom: 10px; color: var(--text-main); }
        .archive-subtitle { text-align: center; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; margin-bottom: 50px; }
        .wardrobe-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; }
        .outfit-card { background: var(--pure-white); border-radius: 15px; overflow: hidden; border: 1px solid var(--gray-border); transition: all 0.3s ease; }
        .outfit-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }
        .outfit-img { width: 100%; aspect-ratio: 1/1; object-fit: cover; }
        .outfit-details { padding: 20px; }
        .outfit-date { font-size: 0.7rem; color: var(--text-muted); margin-bottom: 8px; }
        .outfit-desc { font-size: 0.9rem; line-height: 1.4; color: var(--text-main); }
        .empty-wardrobe { text-align: center; padding: 100px 0; grid-column: 1 / -1; }
        .btn-carica { display: inline-block; margin-top: 15px; color: var(--accent-pop); font-weight: 600; text-decoration: none; padding: 10px 20px; border: 1px solid var(--accent-pop); border-radius: 25px; transition: 0.3s; }
        .btn-carica:hover { background-color: var(--accent-pop); color: white; }
    </style>
</head>
<body>

<?php
require_once '../includes/header.php';
// Fondamentale includere la sidebar qui per farla funzionare in questa pagina
require_once '../includes/sidebar.php';
?>

<div class="wardrobe-container">
    <h1 class="archive-title">Il mio Armadio</h1>
    <p class="archive-subtitle">Archivio personale dei tuoi fit</p>

    <div class="wardrobe-grid">
        <?php if (empty($miei_look)): ?>
            <div class="empty-wardrobe">
                <p>Non hai ancora aggiunto look al tuo armadio.</p>
                <a href="carica_look.php" class="btn-carica">Carica il primo look ora</a>
            </div>
        <?php else: ?>
            <?php foreach ($miei_look as $look): ?>
                <article class="outfit-card">
                    <img src="../uploads/<?php echo htmlspecialchars($look['immagine']); ?>" class="outfit-img" alt="Mio Outfit">
                    <div class="outfit-details">
                        <div class="outfit-date"><?php echo date("d M Y", strtotime($look['timestamp'])); ?></div>
                        <p class="outfit-desc"><?php echo htmlspecialchars($look['descrizione'] ?? ''); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileBtn = document.getElementById('profile-toggle-btn');
        const sidebar = document.getElementById('account-sidebar'); // Verifica che questo ID sia uguale nel tuo sidebar.php
        const closeBtn = document.getElementById('close-sidebar');

        if (profileBtn && sidebar) {
            profileBtn.addEventListener('click', function() {
                sidebar.classList.add('active');
            });
        }
        if (closeBtn && sidebar) {
            closeBtn.addEventListener('click', function() {
                sidebar.classList.remove('active');
            });
        }
    });
</script>

</body>
</html>