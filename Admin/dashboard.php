<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

require_once '../config/connessione.php';

$username_loggato = $_SESSION['username'];
$id_utente_loggato = $_SESSION['user_id'];
$nome_display = $_SESSION['nome']; // Preso dal login

$num_followers = 0; $num_seguite = 0; $num_look = 0;
$utenti_suggeriti = [];

if ($conn) {
    // 1. Statistiche (Follower e Seguiti)
    $q_f1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Followers WHERE idSeguito = $id_utente_loggato");
    $num_followers = mysqli_fetch_assoc($q_f1)['total'];

    $q_f2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Followers WHERE idFollower = $id_utente_loggato");
    $num_seguite = mysqli_fetch_assoc($q_f2)['total'];

    // 2. Conteggio Look (Tabella Outfit con O maiuscola!)
    $q_l = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Outfit WHERE username = '$username_loggato'");
    $num_look = mysqli_fetch_assoc($q_l)['total'];

    // 3. Suggeriti
    $sql_sugg = "SELECT u.id, u.username, u.nome FROM Utenti u 
                 WHERE u.id != $id_utente_loggato 
                 AND NOT EXISTS (SELECT 1 FROM Followers f WHERE f.idSeguito = u.id AND f.idFollower = $id_utente_loggato)
                 ORDER BY RAND() LIMIT 5";
    $res_sugg = mysqli_query($conn, $sql_sugg);
    while ($row = mysqli_fetch_assoc($res_sugg)) { $utenti_suggeriti[] = $row; }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Fitgram - Dashboard</title>
    <link rel="stylesheet" href="../styles/css/main.css">
    <link rel="stylesheet" href="../styles/css/feed.css">
</head>
<body>
<?php require_once '../includes/header.php'; ?>

<main class="lookbook-container">
    <div class="content-layout">
        <div class="main-feed">
            <div class="gallery-grid">
                <?php
                $sql_outfits = "SELECT * FROM Outfit ORDER BY timestamp DESC LIMIT 20";
                $result_outfits = mysqli_query($conn, $sql_outfits);

                while ($outfit = mysqli_fetch_assoc($result_outfits)) {
                    $idO = $outfit['id'];
                    // Query Like (Tabella Likes con L maiuscola)
                    $q_l = mysqli_query($conn, "SELECT COUNT(*) as tot FROM Likes WHERE idOutfit = '$idO'");
                    $n_likes = mysqli_fetch_assoc($q_l)['tot'];
                    $q_c = mysqli_query($conn, "SELECT 1 FROM Likes WHERE idOutfit = '$idO' AND idUtente = '$id_utente_loggato'");
                    $ha_like = mysqli_num_rows($q_c) > 0;
                    ?>
                    <article class="look-card" style="position: relative;">
                        <img src="../uploads/<?php echo htmlspecialchars($outfit['immagine']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <div class="look-overlay">
                            <div class="overlay-user">@<?php echo htmlspecialchars($outfit['username']); ?></div>
                        </div>
                        <div style="position:absolute; bottom:10px; right:10px; background:white; padding:5px 10px; border-radius:20px;">
                            <a href="../controller/like_controller.php?idOutfit=<?php echo $idO; ?>" style="text-decoration:none;">
                                <?php echo $ha_like ? "❤️" : "🤍"; ?>
                            </a>
                            <span style="font-weight:bold;"><?php echo $n_likes; ?></span>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </div>

        <aside class="suggested-sidebar">
            <?php foreach($utenti_suggeriti as $u): ?>
                <div class="suggested-card">
                    <strong><?php echo htmlspecialchars($u['nome'] ?? $u['username']); ?></strong>
                    <form action="azione_follow.php" method="POST">
                        <input type="hidden" name="id_da_seguire" value="<?php echo $u['id']; ?>">
                        <button type="submit" class="follow-btn-index">Segui</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </aside>
    </div>
</main>

<aside class="profile-sidebar" id="profile-sidebar">
    <div class="sidebar-content">
        <h3 class="sidebar-username">@<?php echo htmlspecialchars($username_loggato); ?></h3>
        <div class="sidebar-stats">
            <div><strong><?php echo $num_look; ?></strong><br>Look</div>
            <div><strong><?php echo $num_followers; ?></strong><br>Follower</div>
            <div><strong><?php echo $num_seguite; ?></strong><br>Seguiti</div>
        </div>
        <a href="../controller/logoutController.php" class="edit-profile-btn logout-btn">Esci</a>
    </div>
</aside>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>