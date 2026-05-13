<?php
session_start();

// 1. SICUREZZA
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

// === VARIABILI DI SESSIONE ===
$username_loggato = $_SESSION['username'];
$id_utente_loggato = $_SESSION['user_id'];
$nome_mostrato = $_SESSION['nome'] ?? $username_loggato;
$foto_profilo = $_SESSION['foto_profilo'] ?? 'default_avatar.png';
$bio = "Appassionato di stile e fitness. Sempre alla ricerca del fit perfetto.";

$num_followers = 0;
$num_seguite = 0;
$num_look = 0;
$utenti_suggeriti = [];

require_once '../config/connessione.php';

if ($conn) {
    // Statistiche profilo
    $q_f1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Followers WHERE idSeguito = $id_utente_loggato");
    $num_followers = mysqli_fetch_assoc($q_f1)['total'];

    $q_f2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Followers WHERE idFollower = $id_utente_loggato");
    $num_seguite = mysqli_fetch_assoc($q_f2)['total'];

    // Conteggio Look (Tabella OUTFIT con la O maiuscola come da DB!)
    $q_l = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Outfit WHERE username = '$username_loggato'");
    $num_look = mysqli_fetch_assoc($q_l)['total'];

    // Suggeriti
    $sql_sugg = "SELECT id, username, nome FROM Utenti 
                 WHERE id != $id_utente_loggato 
                 AND id NOT IN (SELECT idSeguito FROM Followers WHERE idFollower = $id_utente_loggato)
                 ORDER BY RAND() LIMIT 5";
    $res_sugg = mysqli_query($conn, $sql_sugg);
    while ($row = mysqli_fetch_assoc($res_sugg)) {
        $utenti_suggeriti[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitgram - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/css/main.css">
    <link rel="stylesheet" href="../styles/css/components.css">
    <link rel="stylesheet" href="../styles/css/feed.css">
    <script src="../js/homepage.js" defer></script>
    <style>
        /* CSS specifico per non rompere la griglia */
        .look-card { position: relative; overflow: hidden; border-radius: 8px; aspect-ratio: 1/1; }
        .look-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
        .look-card:hover img { transform: scale(1.05); }

        .like-container-dash {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(255, 255, 255, 0.9);
            padding: 4px 10px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .like-btn { text-decoration: none; font-size: 18px; line-height: 1; }
        .like-count { font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 14px; color: #333; }
    </style>
</head>
<body>

<?php require_once '../includes/header.php'; ?>

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

                    while ($outfit = mysqli_fetch_assoc($result_outfits)) {
                        $idO = $outfit['id'];

                        // Calcolo Like dinamico
                        $res_count = mysqli_query($conn, "SELECT COUNT(*) as tot FROM Likes WHERE idOutfit = $idO");
                        $n_likes = mysqli_fetch_assoc($res_count)['tot'];

                        $res_check = mysqli_query($conn, "SELECT 1 FROM Likes WHERE idOutfit = $idO AND idUtente = $id_utente_loggato");
                        $ha_like = mysqli_num_rows($res_check) > 0;
                        ?>
                        <article class="look-card">
                            <img src="../uploads/<?php echo htmlspecialchars($outfit['immagine']); ?>" alt="Look">

                            <div class="look-overlay">
                                <div class="overlay-user">
                                    <div class="overlay-mini-avatar">👤</div>
                                    @<?php echo htmlspecialchars($outfit['username']); ?>
                                </div>
                            </div>

                            <div class="like-container-dash">
                                <a href="../controller/likeController.php?idOutfit=<?php echo $idO; ?>" class="like-btn">
                                    <?php echo $ha_like ? "❤️" : "🤍"; ?>
                                </a>
                                <span class="like-count"><?php echo $n_likes; ?></span>
                            </div>
                        </article>
                    <?php } } ?>
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
                            <form action="azione_follow.php" method="POST" style="margin:0;">
                                <input type="hidden" name="id_da_seguire" value="<?php echo $u['id']; ?>">
                                <button type="submit" class="follow-btn-index">Segui</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </aside>
    </div>
</main>

<aside class="profile-sidebar" id="profile-sidebar">
    <div class="sidebar-header">
        <h2>Il mio Profilo</h2>
        <button class="close-sidebar-btn" id="close-sidebar">×</button>
    </div>
    <div class="sidebar-content">
        <div class="sidebar-avatar-large">
            <img src="../uploads/<?php echo $foto_profilo; ?>" alt="Foto" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
        </div>
        <h3 class="sidebar-username">@<?php echo htmlspecialchars($username_loggato); ?></h3>
        <p class="sidebar-bio"><?php echo htmlspecialchars($bio); ?></p>

        <div class="sidebar-stats">
            <div><strong><?php echo $num_look; ?></strong><br>Look</div>
            <div><strong><?php echo $num_followers; ?></strong><br>Follower</div>
            <div><strong><?php echo $num_seguite; ?></strong><br>Seguiti</div>
        </div>

        <a href="../view/impostazioni.php" class="edit-profile-btn">Modifica Profilo</a>
        <a href="../controller/logoutController.php" class="edit-profile-btn logout-btn" style="margin-top:10px;">Esci</a>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>