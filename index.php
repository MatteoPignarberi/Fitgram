<?php
session_start();

// 1. SE L'UTENTE È GIÀ LOGGATO, LO MANDIAMO ALLA DASHBOARD
if (isset($_SESSION['user_id'])) {
    header("Location: Admin/dashboard.php");
    exit();
}

// 2. VARIABILI PER "OSPITE"
$is_logged = false;
$nome = "Ospite";
$username_mostrato = "Accedi";
$bio = "Benvenuto su Fitgram. Effettua il login per vedere il tuo profilo, caricare look e seguire altri creator.";
$utenti_suggeriti = [];

// 3. RICHIAMIAMO LA CONNESSIONE AL DB
require_once 'config/connessione.php';

// 4. RECUPERO UTENTI DA SEGUIRE (Suggeriti)
if ($conn) {
    $sql_utenti = "SELECT id, username, nome FROM Utenti ORDER BY RAND() LIMIT 5";
    $result_utenti = mysqli_query($conn, $sql_utenti);
    if ($result_utenti) {
        while ($row = mysqli_fetch_assoc($result_utenti)) {
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
    <style>
        /* Stile per il contatore Like (Ospite) */
        .look-stats {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.85);
            padding: 5px 10px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            z-index: 2;
        }
    </style>
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
        <button class="style-pill">Accessori</button>
    </div>

    <div class="content-layout">

        <div class="main-feed">
            <div class="gallery-grid">
                <?php
                if ($conn) {
                    // Peschiamo gli ultimi 20 outfit (Tabella Outfit maiuscola)
                    $sql_outfits = "SELECT * FROM Outfit ORDER BY timestamp DESC LIMIT 20";
                    $result_outfits = mysqli_query($conn, $sql_outfits);

                    if ($result_outfits && mysqli_num_rows($result_outfits) > 0) {
                        while ($outfit = mysqli_fetch_assoc($result_outfits)) {
                            $idOutfit = $outfit['id'];

                            // CONTEGGIO LIKE (Tabella Likes con L maiuscola)
                            $q_likes = mysqli_query($conn, "SELECT COUNT(*) as totale FROM Likes WHERE idOutfit = '$idOutfit'");
                            $n_likes = mysqli_fetch_assoc($q_likes)['totale'];
                            ?>
                            <article class="look-card" style="position: relative;">
                                <img src="uploads/<?php echo htmlspecialchars($outfit['immagine']); ?>"
                                     alt="<?php echo htmlspecialchars($outfit['descrizione'] ?? 'Look'); ?>"
                                     style="width: 100%; height: 100%; object-fit: cover;">

                                <div class="look-overlay">
                                    <div class="overlay-user">
                                        <div class="overlay-mini-avatar">👤</div>
                                        @<?php echo htmlspecialchars($outfit['username']); ?>
                                    </div>
                                </div>

                                <div class="look-stats" title="Accedi per mettere like">
                                    <span style="color: #bbb;">🤍</span>
                                    <span><?php echo $n_likes; ?></span>
                                </div>
                            </article>
                            <?php
                        }
                    } else {
                        echo "<p style='color: var(--text-muted); grid-column: 1 / -1; text-align: center; padding: 40px;'>Nessun outfit caricato ancora.</p>";
                    }
                }
                ?>
            </div>
        </div>

        <aside class="suggested-sidebar">
            <?php if (!empty($utenti_suggeriti)): ?>
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
                                <a href="view/login.php" class="follow-btn-index" onclick="alert('Devi accedere o registrarti per seguire questo utente!');">Segui</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </aside>

    </div>
</main>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<aside class="profile-sidebar" id="profile-sidebar">
    <div class="sidebar-header">
        <h2>Il mio Profilo</h2>
        <button class="close-sidebar-btn" id="close-sidebar">×</button>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-avatar-large">👤</div>
        <h3 class="sidebar-username">Benvenuto su Fitgram</h3>
        <p class="sidebar-bio">
            Accedi o registrati per vedere il tuo profilo, caricare look e interagire con la community.
        </p>

        <a href="view/login.php" class="edit-profile-btn" style="margin-bottom:10px; display: block; text-align: center; text-decoration: none;">
            Accedi
        </a>

        <a href="view/registrazione.php" class="edit-profile-btn" style="background-color: var(--accent-dark); display: block; text-align: center; text-decoration: none;">
            Registrati
        </a>
    </div>
</aside>

<?php
if($conn) mysqli_close($conn);
require_once 'includes/footer.php';
?>

</body>
</html>