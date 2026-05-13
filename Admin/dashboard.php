<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../view/login.php"); exit(); }
require_once '../config/connessione.php';

$id_loggato = $_SESSION['user_id'];
$user_loggato = $_SESSION['username'];

// Statistiche
$foll = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Followers WHERE idSeguito = $id_loggato"))['t'];
$segu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Followers WHERE idFollower = $id_loggato"))['t'];
$look_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Outfit WHERE username = '$user_loggato'"))['t'];

$suggeriti = mysqli_query($conn, "SELECT id, username FROM Utenti WHERE id != $id_loggato LIMIT 5");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Fitgram</title>
    <link rel="stylesheet" href="../styles/css/main.css">
    <link rel="stylesheet" href="../styles/css/feed.css">
    <link rel="stylesheet" href="../styles/css/components.css">
    <script src="../js/homepage.js" defer></script>
    <style>
        .like-btn-dash { position: absolute; bottom: 10px; right: 10px; background: white; padding: 5px 10px; border-radius: 20px; z-index: 5; text-decoration: none; }
    </style>
</head>
<body>
<?php require_once '../includes/header.php'; ?>
<main class="lookbook-container">
    <div class="content-layout">
        <div class="main-feed">
            <div class="gallery-grid">
                <?php
                $outfits = mysqli_query($conn, "SELECT * FROM Outfit ORDER BY timestamp DESC");
                while ($o = mysqli_fetch_assoc($outfits)) {
                    $idO = $o['id'];
                    $n_l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Likes WHERE idOutfit = $idO"))['t'];
                    $ha_l = mysqli_num_rows(mysqli_query($conn, "SELECT 1 FROM Likes WHERE idOutfit = $idO AND idUtente = $id_loggato")) > 0;
                    ?>
                    <article class="look-card" style="position: relative;">
                        <img src="../uploads/<?php echo $o['immagine']; ?>" style="width:100%; height:100%; object-fit:cover;">
                        <div class="look-overlay"><div class="overlay-user">@<?php echo $o['username']; ?></div></div>
                        <a href="../controller/like_controller.php?idOutfit=<?php echo $idO; ?>" class="like-btn-dash">
                            <?php echo $ha_l ? "❤️" : "🤍"; ?> <b><?php echo $n_l; ?></b>
                        </a>
                    </article>
                <?php } ?>
            </div>
        </div>
        <aside class="suggested-sidebar">
            <section class="suggested-section">
                <h3>Suggeriti per te</h3>
                <?php while($s = mysqli_fetch_assoc($suggeriti)): ?>
                    <div class="suggested-card">
                        <span>@<?php echo $s['username']; ?></span>
                        <form action="azione_follow.php" method="POST"><input type="hidden" name="id" value="<?php echo $s['id']; ?>"><button type="submit" class="follow-btn-index">Segui</button></form>
                    </div>
                <?php endwhile; ?>
            </section>
        </aside>
    </div>
</main>

<aside class="profile-sidebar" id="profile-sidebar">
    <div class="sidebar-content">
        <div class="sidebar-avatar-large"><?php echo strtoupper(substr($user_loggato, 0, 1)); ?></div>
        <h3>@<?php echo $user_loggato; ?></h3>
        <div class="sidebar-stats">
            <div><strong><?php echo $look_count; ?></strong><br>Look</div>
            <div><strong><?php echo $foll; ?></strong><br>Follower</div>
            <div><strong><?php echo $segu; ?></strong><br>Seguiti</div>
        </div>
        <a href="../controller/logoutController.php" class="edit-profile-btn logout-btn">Esci</a>
    </div>
</aside>
<?php require_once '../includes/footer.php'; ?>
</body>
</html>