<?php
session_start();

// 1. Controllo Accesso
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

require_once '../config/connessione.php';

// 2. Variabili di Sessione (Recuperate dal login_controller)
$id_loggato = $_SESSION['user_id'];
$user_loggato = $_SESSION['username'];
$nome_loggato = $_SESSION['nome'] ?? $user_loggato;
$foto_profilo = $_SESSION['foto_profilo'] ?? 'default_avatar.png';

// 3. Statistiche (Query originali)
$num_followers = 0; $num_seguite = 0; $num_look = 0;
if ($conn) {
    $q1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Followers WHERE idSeguito = $id_loggato");
    $num_followers = mysqli_fetch_assoc($q1)['total'];

    $q2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Followers WHERE idFollower = $id_loggato");
    $num_seguite = mysqli_fetch_assoc($q2)['total'];

    $q3 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Outfit WHERE username = '$user_loggato'");
    $num_look = mysqli_fetch_assoc($q3)['total'];
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
                $res_o = mysqli_query($conn, "SELECT * FROM Outfit ORDER BY timestamp DESC");
                while ($outfit = mysqli_fetch_assoc($res_o)):
                    $idO = $outfit['id'];
                    // Conteggio Like e verifica se l'utente ha messo like
                    $n_likes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Likes WHERE idOutfit = $idO"))['t'];
                    $check_l = mysqli_query($conn, "SELECT 1 FROM Likes WHERE idOutfit = $idO AND idUtente = $id_loggato");
                    $ha_like = mysqli_num_rows($check_l) > 0;
                    ?>
                    <article class="look-card">
                        <img src="../uploads/<?php echo htmlspecialchars($outfit['immagine']); ?>" alt="Look">
                        <div class="look-overlay">
                            <div class="overlay-user">
                                <div class="overlay-mini-avatar">👤</div>
                                @<?php echo htmlspecialchars($outfit['username']); ?>
                            </div>
                        </div>
                        <div style="position: absolute; bottom: 12px; right: 12px; z-index: 10;">
                            <a href="../controller/likeController.php?idOutfit=<?php echo $idO; ?>" style="text-decoration: none; background: rgba(255,255,255,0.9); padding: 5px 12px; border-radius: 20px; display: flex; align-items: center; gap: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                                <span style="font-size: 18px;"><?php echo $ha_like ? "❤️" : "🤍"; ?></span>
                                <span style="color: #333; font-weight: 600; font-family: 'Montserrat';"><?php echo $n_likes; ?></span>
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
                    $sql_sugg = "SELECT id, username, nome FROM Utenti WHERE id != $id_loggato LIMIT 5";
                    $res_sugg = mysqli_query($conn, $sql_sugg);
                    while ($s = mysqli_fetch_assoc($res_sugg)): ?>
                        <div class="suggested-card">
                            <div class="suggested-avatar">👤</div>
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
    <div class="sidebar-header">
        <h2>Il mio Profilo</h2>
        <button class="close-sidebar-btn" id="close-sidebar">×</button>
    </div>
    <div class="sidebar-content">
        <div class="sidebar-avatar-large">
            <?php if(!empty($_SESSION['foto_profilo']) && $_SESSION['foto_profilo'] !== 'default_avatar.png'): ?>
                <img src="../uploads/<?php echo $_SESSION['foto_profilo']; ?>" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
            <?php else: ?>
                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#f0f0f0; border-radius:50%; font-size:40px; color:#999;">
                    <?php echo strtoupper(substr($user_loggato, 0, 1)); ?>
                </div>
            <?php endif; ?>
        </div>
        <h3 class="sidebar-username">@<?php echo htmlspecialchars($user_loggato); ?></h3>
        <p class="sidebar-bio">Appassionato di stile e fitness.</p>

        <div class="sidebar-stats">
            <div><strong><?php echo $num_look; ?></strong><br>Look</div>
            <div><strong><?php echo $num_followers; ?></strong><br>Follower</div>
            <div><strong><?php echo $num_seguite; ?></strong><br>Seguiti</div>
        </div>

        <a href="modifica_profilo.php" class="edit-profile-btn">Modifica Profilo</a>
        <a href="../controller/logoutController.php" class="edit-profile-btn logout-btn" style="margin-top:10px; background:#f5f5f5; color:#cc0000; border:1px solid #ffcccc;">Esci</a>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>