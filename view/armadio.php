<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Il mio Armadio - Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@1,400&display=swap" rel="stylesheet">

    <style>
        /* CSS INTERNO PER ESSERE SICURI CHE FUNZIONI */
        body { background-color: #f8f5f0; margin: 0; font-family: 'Montserrat', sans-serif; }

        .wardrobe-header {
            text-align: center;
            padding: 40px 20px;
            background-color: #ffffff;
            border-bottom: 1px solid #efd3d2;
        }

        /* FORZIAMO L'ICONA A 80PX */
        .wardrobe-header img {
            width: 80px !important;
            height: auto !important;
            display: block;
            margin: 0 auto 15px auto;
        }

        .wardrobe-header h1 {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 2.2rem;
            margin: 10px 0;
            color: #3d3d3d;
        }

        .wardrobe-header p {
            font-size: 0.7rem;
            letter-spacing: 2px;
            color: #b8807d;
            text-transform: uppercase;
            margin: 0;
        }

        .wardrobe-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .archive-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .archive-card {
            background: #fff;
            border: 1px solid #eee;
            transition: 0.3s;
        }

        .archive-card img {
            width: 100%;
            aspect-ratio: 3/4;
            object-fit: cover;
            display: block;
        }

        .archive-info { padding: 15px; }
        .archive-info p { margin: 0; font-size: 0.9rem; }
        .archive-info span { font-size: 0.7rem; color: #999; }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<header class="wardrobe-header">
    <img src="../resources/Images/Armadio.png" alt="Icona">
    <h1>Il mio Archivio</h1>
    <p><?php echo count($miei_look); ?> LOOK CARICATI</p>
</header>

<main class="wardrobe-container">
    <div class="archive-grid">
        <?php if (empty($miei_look)): ?>
            <p style="text-align: center; grid-column: 1/-1;">L'armadio è vuoto.</p>
        <?php else: ?>
            <?php foreach ($miei_look as $look): ?>
                <div class="archive-card">
                    <img src="../uploads/<?php echo htmlspecialchars($look['immagine']); ?>">
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