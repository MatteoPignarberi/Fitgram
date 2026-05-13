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

// Statistiche
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
    <title>Fitgram - Dashboard</title>
    <link rel="stylesheet" href="../styles/css/main.css">
    <link rel="stylesheet" href="../styles/css/components.css">
    <link rel="stylesheet" href="../styles/css/feed.css">
    <script src="../js/homepage.js" defer></script>
    <style>
        /* FIX DEFINITIVI PER EVITARE IL CASINO DELLO SCREENSHOT */
        .lookbook-container { width: 100%; max-width: 1400px; margin: 0 auto; padding-top: 80px; }
        .content-layout { display: flex; justify-content: space-between; gap: 30px; padding: 20px; }
        .main-feed { flex: 1; }
        .suggested-sidebar { width: 350px; flex-shrink: 0; }

        /* Fix Foto Profilo */
        .avatar-fixed {
            width: 120px !important; height: 120px !important;
            border-radius: 50% !important; overflow: hidden !important;
            border: 3px solid #eee !important; margin-bottom: 15px;
        }
        .avatar-fixed img { width: 100%; height: 100%; object-fit: cover; }

        /* Fix Bottoni Segui */
        .follow-btn-index { border: none !important; outline: none !important; cursor: pointer; }
    </style>
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<main class="lookbook-container">

    <div class="style-navigation" style="text-align: center; margin-bottom: 20px;">
        <button class="style-pill active">Tutti i look</button>
        <button class="style-pill">Streetwear</button>
        <button class="style-pill">Sartoriale</button>
        <button class="style-pill">Minimal</button>
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
                        <img src="../uploads/<?php echo htmlspecialchars($o['immagine']); ?>" style="width:100%; height:100%; object-fit:cover; border-radius:12px;">
                        <div class="look-overlay" style="border-radius:12px;">
                            <div class="overlay-user">@<?php echo htmlspecialchars($o['username']); ?></div>
                        </div>
                        <a href="../controller/likeController.php?idOutfit=<?php echo $idO; ?>" class="like-btn" style="position:absolute; bottom:10px; right:10px; background:white; padding:5px 10px; border-radius:20px; text-decoration:none; z-index:10; display:flex; align-items:center; gap:5px; box-shadow:0 2px 5px rgba(0,0,0,0.2);">
                            <span><?php echo $ha_l ? "❤️" : "🤍"; ?></span>
                            <b style="color:black;"><?php echo $n_l; ?></b>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>

        <aside class="suggested-sidebar">
            <section class="suggested-section" style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 20px; font-family: 'Playfair Display'; color: #b08d8d;">Suggeriti per te</h3>
                <div class="suggested-list">
                    <?php
                    $sugg = mysqli_query($conn, "SELECT id, username, nome FROM Utenti WHERE id != $id_loggato LIMIT 5");
                    while($s = mysqli_fetch_assoc($sugg)): ?>
                        <div class="suggested-card" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:15px;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:40px; height:40px; background:#f0f0f0; border-radius:50%; display:flex; align-items:center; justify-content:center;">👤</div>
                                <div style="display:flex; flex-direction:column;">
                                    <strong style="font-size:14px;"><?php echo htmlspecialchars($s['nome'] ?? $s['username']); ?></strong>
                                    <span style="font-size:12px; color:#888;">@<?php echo htmlspecialchars($s['username']); ?></span>
                                </div>
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
    <div class="sidebar-header" style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-family: 'Playfair Display';">Il mio Profilo</h2>
        <button id="close-sidebar" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
    </div>
    <div class="sidebar-content" style="display:flex; flex-direction:column; align-items:center; padding:20px;">
        <div class="avatar-fixed">
            <img src="../uploads/<?php echo htmlspecialchars($foto_sessione); ?>" alt="Profilo">
        </div>
        <h3>@<?php echo htmlspecialchars($user_loggato); ?></h3>
        <div class="sidebar-stats" style="width:100%; display:flex; justify-content:space-around; margin:20px 0; border-top:1px solid #eee; border-bottom:1px solid #eee; padding:15px 0;">
            <div><strong><?php echo $look_count; ?></strong><br><small>Look</small></div>
            <div><strong><?php echo $foll; ?></strong><br><small>Follower</small></div>
            <div><strong><?php echo $segu; ?></strong><br><small>Seguiti</small></div>
        </div>
        <a href="../controller/logoutController.php" style="color: #d93025; text-decoration: none; font-weight: 600; margin-top: 20px;">Esci dall'account</a>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>