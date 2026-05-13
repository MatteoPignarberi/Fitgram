<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

require_once '../config/connessione.php';

$id_loggato = $_SESSION['user_id'];
$user_loggato = $_SESSION['username'];
$nome_loggato = $_SESSION['nome'] ?? $user_loggato;
$foto_sessione = $_SESSION['foto_profilo'] ?? 'default_avatar.png';

// Statistiche reali
$foll = 0; $segu = 0; $look_count = 0;
if ($conn) {
    $foll = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Followers WHERE idSeguito = $id_loggato"))['t'] ?? 0;
    $segu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Followers WHERE idFollower = $id_loggato"))['t'] ?? 0;
    $look_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Outfit WHERE username = '$user_loggato'"))['t'] ?? 0;
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
        /* FIX FOTO PROFILO: Impedisce l'ingrandimento eccessivo */
        .sidebar-avatar-large {
            width: 120px !important;
            height: 120px !important;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 15px;
            border: 3px solid var(--accent-light);
        }
        .sidebar-avatar-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
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
                $outfits = mysqli_query($conn, "SELECT * FROM Outfit ORDER BY timestamp DESC");
                while ($o = mysqli_fetch_assoc($outfits)):
                    $idO = $o['id'];
                    $n_l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Likes WHERE idOutfit = $idO"))['t'] ?? 0;
                    $ha_l = mysqli_num_rows(mysqli_query($conn, "SELECT 1 FROM Likes WHERE idOutfit = $idO AND idUtente = $id_loggato")) > 0;
                    ?>
                    <article class="look-card" style="position: relative;">
                        <img src="../uploads/<?php echo htmlspecialchars($o['immagine']); ?>" alt="Look">
                        <div class="look-overlay">
                            <div class="overlay-user">@<?php echo htmlspecialchars($o['username']); ?></div>
                        </div>

                        <div style="position: absolute; bottom: 12px; right: 12px; z-index: 10;">
                            <a href="../controller/likeController.php?idOutfit=<?php echo $idO; ?>" style="text-decoration: none; background: white; padding: 6px 12px; border-radius: 20px; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                <span style="font-size: 18px;"><?php echo $ha_l ? "❤️" : "🤍"; ?></span>
                                <span style="color: #333; font-weight: 600; font-family: 'Montserrat';"><?php echo $n_l; ?></span>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>

        <aside class="suggested-sidebar">
            <section class="suggested-section">
                <h3>Suggeriti per te</h3>
                <div class="suggested-list">
                    <?php
                    $sugg = mysqli_query($conn, "SELECT id, username, nome FROM Utenti WHERE id != $id_loggato LIMIT 5");
                    while($s = mysqli_fetch_assoc($sugg)): ?>
                        <div class="suggested-card">
                            <div class="suggested-avatar">👤</div>
                            <div class="suggested-info">
                                <strong><?php echo htmlspecialchars($s['nome'] ?? $s['username']); ?></strong>
                                <span>@<?php echo htmlspecialchars($s['username']); ?></span>
                            </div>
                            <form action="azione_follow.php" method="POST" style="margin:0;">
                                <input type="hidden" name="id_da_seguire" value="<?php echo $s['id']; ?>">
                                <button type="submit" class="follow-btn-index">Segui</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
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
    <div class="sidebar-content" style="display: flex; flex-direction: column; align-items: center; padding: 20px;">

        <div class="sidebar-avatar-large" style="width: 120px; height: 120px; min-width: 120px; min-height: 120px; border-radius: 50%; overflow: hidden; margin-bottom: 15px; border: 3px solid #eee; display: block;">
            <img src="../uploads/<?php echo htmlspecialchars($foto_sessione); ?>"
                 alt="Profilo"
                 style="width: 100%; height: 100%; object-fit: cover; display: block;">
        </div>

        <h3 class="sidebar-username" style="margin-bottom: 5px;">@<?php echo htmlspecialchars($user_loggato); ?></h3>
        <p style="color: #666; font-size: 0.9em; margin-bottom: 20px;">Appassionato di stile</p>

        <div class="sidebar-stats" style="width: 100%; display: flex; justify-content: space-around; text-align: center; border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 15px 0; margin-bottom: 20px;">
            <div><strong><?php echo $look_count; ?></strong><br><span style="font-size: 0.8em; color: #888;">Look</span></div>
            <div><strong><?php echo $foll; ?></strong><br><span style="font-size: 0.8em; color: #888;">Follower</span></div>
            <div><strong><?php echo $segu; ?></strong><br><span style="font-size: 0.8em; color: #888;">Seguiti</span></div>
        </div>

        <div style="width: 100%; display: flex; flex-direction: column; gap: 10px;">
            <a href="modifica_profilo.php" class="edit-profile-btn" style="text-align: center; text-decoration: none; padding: 10px; background: #000; color: #fff; border-radius: 5px; font-weight: 500;">Modifica Profilo</a>
            <a href="../controller/logoutController.php" class="logout-link" style="text-align: center; text-decoration: none; padding: 10px; color: #d93025; font-weight: 600; font-size: 0.9em;">Esci dall'account</a>
        </div>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>