<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}
require_once '../config/connessione.php';

$id_loggato = $_SESSION['user_id'];
$user_loggato = $_SESSION['username'];
$foto_sessione = $_SESSION['foto_profilo'] ?? 'default_avatar.png';

// Conteggi statistiche
$foll = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Followers WHERE idSeguito = $id_loggato"))['t'] ?? 0;
$segu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Followers WHERE idFollower = $id_loggato"))['t'] ?? 0;
$look_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Outfit WHERE username = '$user_loggato'"))['t'] ?? 0;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Fitgram - Dashboard</title>
    <link rel="stylesheet" href="../styles/css/main.css">
    <link rel="stylesheet" href="../styles/css/dashboard_style.css">
    <script src="../js/homepage.js" defer></script>
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<main class="lookbook-container">
    <div class="style-navigation">
        <button class="style-pill">TUTTI I LOOK</button>
        <button class="style-pill">STREETWEAR</button>
        <button class="style-pill">SARTORIALE</button>
        <button class="style-pill">MINIMAL</button>
        <button class="style-pill">SPORTIVO</button>
    </div>

    <div class="content-layout">
        <div class="main-feed">
            <div class="gallery-grid">
                <?php
                $outfits = mysqli_query($conn, "SELECT * FROM Outfit ORDER BY timestamp DESC");
                while ($o = mysqli_fetch_assoc($outfits)):
                    $idO = $o['id'];
                    $n_l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Likes WHERE idOutfit = $idO"))['t'] ?? 0;
                    ?>
                    <article class="look-card" style="position: relative;">
                        <img src="../uploads/<?php echo $o['immagine']; ?>" alt="Look">
                        <div style="position: absolute; bottom: 10px; right: 10px; background: rgba(255,255,255,0.9); padding: 2px 8px; border-radius: 15px; font-size: 12px;">
                            ❤️ <?php echo $n_l; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>

        <aside class="suggested-sidebar">
            <h3 class="suggested-title">Suggeriti per te</h3>
            <?php
            $sugg = mysqli_query($conn, "SELECT id, username FROM Utenti WHERE id != $id_loggato LIMIT 5");
            while($s = mysqli_fetch_assoc($sugg)): ?>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <span style="font-size: 14px;">@<?php echo $s['username']; ?></span>
                    <form action="azione_follow.php" method="POST" style="margin:0;">
                        <input type="hidden" name="id_da_seguire" value="<?php echo $s['id']; ?>">
                        <button type="submit" class="follow-btn-index">Segui</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </aside>
    </div>
</main>

<aside class="profile-sidebar" id="profile-sidebar">
    <div class="sidebar-content" style="text-align: center; padding: 20px;">
        <div class="avatar-fixed-container">
            <img src="../uploads/<?php echo $foto_sessione; ?>" alt="Profilo">
        </div>
        <h3 style="margin-bottom: 15px;">@<?php echo $user_loggato; ?></h3>
        <div style="display: flex; justify-content: space-around; margin-bottom: 20px;">
            <div style="font-size: 13px;"><strong><?php echo $look_count; ?></strong><br>Look</div>
            <div style="font-size: 13px;"><strong><?php echo $foll; ?></strong><br>Follower</div>
            <div style="font-size: 13px;"><strong><?php echo $segu; ?></strong><br>Seguiti</div>
        </div>
        <a href="../controller/logoutController.php" style="color: #b08d8d; text-decoration: none; font-weight: bold; font-size: 14px;">ESCI</a>
    </div>
</aside>

<a href="../view/carica_look.php" class="add-look-btn">+</a>

</body>
</html>