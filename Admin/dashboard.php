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

// Statistiche originali
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
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<main class="lookbook-container">

    <div class="style-navigation">
        <button class="style-pill active">Tutti i look</button>
        <button class="style-pill">Streetwear</button>
        <button class="style-pill">Sartoriale</button>
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
                    <article class="look-card">
                        <img src="../uploads/<?php echo htmlspecialchars($o['immagine']); ?>" alt="Outfit">
                        <div class="look-overlay">
                            <div class="overlay-user">@<?php echo htmlspecialchars($o['username']); ?></div>
                        </div>
                        <a href="../controller/like_controller.php?idOutfit=<?php echo $idO; ?>" class="like-container-dash" style="position: absolute; bottom: 10px; right: 10px; background: white; padding: 5px 10px; border-radius: 20px; text-decoration: none; display: flex; align-items: center; gap: 5px; z-index: 5;">
                            <span><?php echo $ha_l ? "❤️" : "🤍"; ?></span>
                            <span style="color: black; font-weight: bold;"><?php echo $n_l; ?></span>
                        </a>
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
                            <div class="suggested-info">
                                <strong><?php echo htmlspecialchars($s['nome'] ?? $s['username']); ?></strong>
                                <span>@<?php echo htmlspecialchars($s['username']); ?></span>
                            </div>
                            <form action="azione_follow.php" method="POST" style="margin:0;">
                                <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
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
    <div class="sidebar-content">
        <div class="sidebar-avatar-large">
            <?php if(!empty($_SESSION['foto_profilo']) && $_SESSION['foto_profilo'] !== 'default_avatar.png'): ?>
                <img src="../uploads/<?php echo $_SESSION['foto_profilo']; ?>" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
            <?php else: ?>
                <?php echo strtoupper(substr($user_loggato, 0, 1)); ?>
            <?php endif; ?>
        </div>
        <h3>@<?php echo htmlspecialchars($user_loggato); ?></h3>
        <div class="sidebar-stats">
            <div><strong><?php echo $look_count; ?></strong><br>Look</div>
            <div><strong><?php echo $foll; ?></strong><br>Follower</div>
            <div><strong><?php echo $segu; ?></strong><br>Seguiti</div>
        </div>
        <a href="../controller/logoutController.php" class="edit-profile-btn logout-btn">Esci</a>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>