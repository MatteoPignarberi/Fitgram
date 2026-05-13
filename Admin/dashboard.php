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

// Statistiche reali dal database
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
    <link rel="stylesheet" href="../styles/css/components.css">
    <link rel="stylesheet" href="../styles/css/feed.css">
    <script src="../js/homepage.js" defer></script>

    <style>
        /* FIX MINIMI PER EVITARE DEFORMAZIONI */
        .lookbook-container { padding-top: 20px; }
        .content-layout { display: flex; justify-content: center; gap: 40px; align-items: flex-start; }

        /* Forza l'avatar a restare tondo e piccolo (Fix per Immagine 18:49) */
        .avatar-fixed {
            width: 120px !important; height: 120px !important;
            border-radius: 50% !important; overflow: hidden !important;
            border: 3px solid #eee; margin: 0 auto 15px;
        }
        .avatar-fixed img { width: 100%; height: 100%; object-fit: cover; }

        /* Pulsante + identico all'index */
        .add-btn-fixed {
            position: fixed; bottom: 30px; right: 30px;
            width: 60px; height: 60px; background: #b08d8d;
            color: white; border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            font-size: 30px; text-decoration: none; z-index: 1000;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        /* Bottone segui senza bordi strani */
        .follow-btn-index { border: none; background: #b08d8d; color: white; padding: 5px 15px; border-radius: 20px; cursor: pointer; }
    </style>
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<main class="lookbook-container">

    <div style="display:flex; justify-content:center; gap:10px; margin-bottom:30px;">
        <button class="style-pill active">TUTTI I LOOK</button>
        <button class="style-pill">STREETWEAR</button>
        <button class="style-pill">SARTORIALE</button>
        <button class="style-pill">MINIMAL</button>
    </div>

    <div class="content-layout">

        <div class="main-feed" style="flex: 0 1 650px;">
            <div class="gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                <?php
                $outfits = mysqli_query($conn, "SELECT * FROM Outfit ORDER BY timestamp DESC");
                while ($o = mysqli_fetch_assoc($outfits)):
                    $idO = $o['id'];
                    $res_l = mysqli_query($conn, "SELECT COUNT(*) as t FROM Likes WHERE idOutfit = $idO");
                    $n_l = mysqli_fetch_assoc($res_l)['t'] ?? 0;
                    ?>
                    <div class="look-card" style="position: relative;">
                        <img src="../uploads/<?php echo $o['immagine']; ?>" style="width:100%; border-radius:15px;">
                        <a href="../controller/like_controller.php?idOutfit=<?php echo $idO; ?>" style="position:absolute; bottom:10px; right:10px; background:white; padding:4px 10px; border-radius:20px; text-decoration:none; color:black; font-size:12px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
                            ❤️ <?php echo $n_l; ?>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <aside style="width: 320px; background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h3 style="font-family:'Playfair Display'; color:#b08d8d; margin-bottom:20px;">Suggeriti per te</h3>
            <?php
            $sugg = mysqli_query($conn, "SELECT id, username FROM Utenti WHERE id != $id_loggato LIMIT 5");
            while($s = mysqli_fetch_assoc($sugg)): ?>
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:15px;">
                    <span style="font-size:14px;">@<?php echo $s['username']; ?></span>
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
    <div style="text-align:center; padding:20px;">
        <div class="avatar-fixed">
            <img src="../uploads/<?php echo $foto_sessione; ?>">
        </div>
        <h3 style="margin-top:10px;">@<?php echo $user_loggato; ?></h3>

        <div style="display:flex; justify-content:space-around; margin:20px 0; border-top:1px solid #eee; border-bottom:1px solid #eee; padding:15px 0;">
            <div><strong><?php echo $look_count; ?></strong><br><small>Look</small></div>
            <div><strong><?php echo $foll; ?></strong><br><small>Follower</small></div>
            <div><strong><?php echo $segu; ?></strong><br><small>Seguiti</small></div>
        </div>

        <a href="../controller/logoutController.php" style="color:#b08d8d; text-decoration:none; font-weight:bold;">ESCI</a>
    </div>
</aside>

<a href="../view/carica_look.php" class="add-btn-fixed">+</a>

</body>
</html>