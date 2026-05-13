<?php
session_start();
if (isset($_SESSION['user_id'])) { header("Location: Admin/dashboard.php"); exit(); }
require_once 'config/connessione.php';
$utenti_suggeriti = [];
if ($conn) {
    $res_s = mysqli_query($conn, "SELECT id, username, nome FROM Utenti ORDER BY RAND() LIMIT 5");
    while ($row = mysqli_fetch_assoc($res_s)) { $utenti_suggeriti[] = $row; }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Fitgram - Esplora</title>
    <link rel="stylesheet" href="styles/css/main.css">
    <link rel="stylesheet" href="styles/css/feed.css">
    <link rel="stylesheet" href="styles/css/components.css">
</head>
<body>
<?php require_once 'includes/header.php'; ?>
<main class="lookbook-container">
    <div class="style-navigation">
        <button class="style-pill active">Tutti i look</button>
        <button class="style-pill">Streetwear</button>
    </div>
    <div class="content-layout">
        <div class="main-feed">
            <div class="gallery-grid">
                <?php
                $res_o = mysqli_query($conn, "SELECT * FROM Outfit ORDER BY timestamp DESC LIMIT 20");
                while ($outfit = mysqli_fetch_assoc($res_o)) {
                    $idO = $outfit['id'];
                    $n_likes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Likes WHERE idOutfit = $idO"))['t'];
                    ?>
                    <article class="look-card" style="position: relative;">
                        <img src="uploads/<?php echo $outfit['immagine']; ?>" style="width:100%; height:100%; object-fit:cover;">
                        <div class="look-overlay"><div class="overlay-user">@<?php echo $outfit['username']; ?></div></div>
                        <div style="position:absolute; bottom:10px; right:10px; background:white; padding:5px 10px; border-radius:20px; font-size:12px;">
                            🤍 <?php echo $n_likes; ?>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </div>
        <aside class="suggested-sidebar">
            <h3>Suggeriti</h3>
            <?php foreach($utenti_suggeriti as $u): ?>
                <div class="suggested-card">
                    <strong><?php echo $u['username']; ?></strong>
                    <a href="view/login.php" class="follow-btn-index">Segui</a>
                </div>
            <?php endforeach; ?>
        </aside>
    </div>
</main>
<?php require_once 'includes/footer.php'; ?>
</body>
</html>