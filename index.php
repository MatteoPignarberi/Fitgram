<?php
session_start();

// Se l'utente è loggato, lo spostiamo in dashboard dove il "+" funziona per caricare
if (isset($_SESSION['user_id'])) {
    header("Location: Admin/dashboard.php");
    exit();
}

require_once 'config/connessione.php';

// Inizializzazione variabili per l'index
$utenti_suggeriti = [];

if ($conn) {
    // Query per gli utenti suggeriti (funzionalità originale)
    $sql_sugg = "SELECT id, username, nome FROM Utenti ORDER BY RAND() LIMIT 5";
    $res_sugg = mysqli_query($conn, $sql_sugg);
    if ($res_sugg) {
        while ($row = mysqli_fetch_assoc($res_sugg)) {
            $utenti_suggeriti[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitgram - Esplora</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/css/main.css">
    <link rel="stylesheet" href="styles/css/components.css">
    <link rel="stylesheet" href="styles/css/feed.css">
    <script src="js/homepage.js" defer></script>
</head>
<body>

<?php require_once 'includes/header.php'; ?>

<main class="lookbook-container">
    <div class="style-navigation">
        <button class="style-pill active">Tutti i look</button>
        <button class="style-pill">Streetwear</button>
        <button class="style-pill">Sartoriale</button>
        <button class="style-pill">Minimal</button>
        <button class="style-pill">Sportivo</button>
    </div>

    <div class="content-layout">
        <div class="main-feed">
            <div class="gallery-grid">
                <?php
                if ($conn) {
                    $sql_outfits = "SELECT * FROM Outfit ORDER BY timestamp DESC LIMIT 20";
                    $result_outfits = mysqli_query($conn, $sql_outfits);

                    if ($result_outfits && mysqli_num_rows($result_outfits) > 0) {
                        while ($outfit = mysqli_fetch_assoc($result_outfits)) {
                            $idO = $outfit['id'];
                            // Conteggio Like statico per l'index
                            $q_l = mysqli_query($conn, "SELECT COUNT(*) as tot FROM Likes WHERE idOutfit = $idO");
                            $n_likes = mysqli_fetch_assoc($q_l)['tot'];
                            ?>
                            <article class="look-card" style="position: relative;">
                                <img src="uploads/<?php echo htmlspecialchars($outfit['immagine']); ?>" alt="Look" style="width: 100%; height: 100%; object-fit: cover;">
                                <div class="look-overlay">
                                    <div class="overlay-user">
                                        <div class="overlay-mini-avatar">👤</div>
                                        @<?php echo htmlspecialchars($outfit['username']); ?>
                                    </div>
                                </div>
                                <div style="position:absolute; bottom:10px; right:10px; background:white; padding:5px 12px; border-radius:20px; font-size:14px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
                                    <span style="color:#ccc;">🤍</span> <b><?php echo $n_likes; ?></b>
                                </div>
                            </article>
                            <?php
                        }
                    } else {
                        echo "<p style='text-align:center; grid-column: 1/-1;'>Nessun look disponibile.</p>";
                    }
                }
                ?>
            </div>
        </div>

        <aside class="suggested-sidebar">
            <section class="suggested-section">
                <h3>Suggeriti per te</h3>
                <div class="suggested-list">
                    <?php foreach($utenti_suggeriti as $u): ?>
                        <div class="suggested-card">
                            <div class="suggested-avatar">👤</div>
                            <div class="suggested-info">
                                <strong><?php echo htmlspecialchars($u['nome'] ?? $u['username']); ?></strong>
                                <span>@<?php echo htmlspecialchars($u['username']); ?></span>
                            </div>
                            <a href="view/login.php" class="follow-btn-index" onclick="alert('Accedi per seguire gli utenti!');">Segui</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </aside>
    </div>
</main>
<?php require_once 'includes/footer.php'; ?>

</body>
</html>