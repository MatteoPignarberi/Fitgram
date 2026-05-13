<?php
session_start();

// 1. SICUREZZA: Se non c'è una sessione attiva, torna al login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

// === VARIABILI DI SESSIONE ===
$is_logged = true;
$username_loggato = $_SESSION['username'];
$id_utente_loggato = $_SESSION['user_id'];
$nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : $username_loggato;
$bio = "Appassionato di stile e fitness. Sempre alla ricerca del fit perfetto.";

// Inizializzo contatori
$num_followers = 0;
$num_seguite = 0;
$num_look = 0;
$utenti_suggeriti = [];

// 2. CONNESSIONE AL DB
require_once '../config/connessione.php';

if ($conn) {
    // Recupero dati per la sidebar e statistiche
    $sql_id = "SELECT id FROM Utenti WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql_id);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username_loggato);
        mysqli_stmt_execute($stmt);
        $res_id = mysqli_stmt_get_result($stmt);
        if ($dati = mysqli_fetch_assoc($res_id)) {
            $mio_id = $dati['id'];

            // Conteggio Follower
            $q_f1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Followers WHERE idSeguito = $mio_id");
            $num_followers = mysqli_fetch_assoc($q_f1)['total'];

            // Conteggio Seguiti
            $q_f2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Followers WHERE idFollower = $mio_id");
            $num_seguite = mysqli_fetch_assoc($q_f2)['total'];

            // Conteggio Look (Tabella Outfit)
            $q_l = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Outfit WHERE username = '$username_loggato'");
            $num_look = mysqli_fetch_assoc($q_l)['total'];

            // Query Utenti Suggeriti
            $sql_sugg = "SELECT u.id, u.username, u.nome 
                         FROM Utenti u
                         WHERE u.id != $mio_id 
                         AND NOT EXISTS (SELECT 1 FROM Followers f WHERE f.idSeguito = u.id AND f.idFollower = $mio_id)
                         ORDER BY RAND() LIMIT 5";
            $res_sugg = mysqli_query($conn, $sql_sugg);
            while ($row = mysqli_fetch_assoc($res_sugg)) {
                $utenti_suggeriti[] = $row;
            }
        }
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
        /* Stile per l'area Like nella Dashboard */
        .look-stats-dashboard {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 5;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .like-link {
            text-decoration: none;
            font-size: 18px;
            transition: transform 0.2s;
        }
        .like-link:hover { transform: scale(1.2); }
        .like-count {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #333;
            font-size: 14px;
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
    </div>

    <div class="content-layout">
        <div class="main-feed">
            <div class="gallery-grid">
                <?php
                if ($conn) {
                    $sql_outfits = "SELECT * FROM Outfit ORDER BY timestamp DESC LIMIT 20";
                    $result_outfits = mysqli_query($conn, $sql_outfits);

                    if ($result_outfits && mysqli_num_rows($result_outfits) > 0) {
                        while ($outfit = mysqli_fetch_assoc($result_outfits)) {
                            $idO = $outfit['id'];

                            // CONTEGGIO LIKE (Tabella Likes)
                            $q_sum = mysqli_query($conn, "SELECT COUNT(*) as tot FROM Likes WHERE idOutfit = '$idO'");
                            $n_likes = mysqli_fetch_assoc($q_sum)['tot'];

                            // CONTROLLO SE IO HO MESSO LIKE
                            $q_check = mysqli_query($conn, "SELECT 1 FROM Likes WHERE idOutfit = '$idO' AND idUtente = '$id_utente_loggato'");
                            $ha_like = mysqli_num_rows($q_check) > 0;
                            ?>
                            <article class="look-card" style="position: relative;">
                                <img src="../uploads/<?php echo htmlspecialchars($outfit['immagine']); ?>" alt="Look" style="width: 100%; height: 100%; object-fit: cover;">

                                <div class="look-overlay">
                                    <div class="overlay-user">
                                        <div class="overlay-mini-avatar">👤</div>
                                        @<?php echo htmlspecialchars($outfit['username']); ?>
                                    </div>
                                </div>

                                <div class="look-stats-dashboard">
                                    <a href="../controller/likeController.php?idOutfit=<?php echo $idO; ?>" class="like-link">
                                        <?php echo $ha_like ? "❤️" : "🤍"; ?>
                                    </a>
                                    <span class="like-count"><?php echo $n_likes; ?></span>
                                </div>
                            </article>
                            <?php
                        }
                    } else {
                        echo "<p style='text-align:center; grid-column: 1/-1;'>Nessun look trovato.</p>";
                    }
                }
                ?>
            </div>
        </div>

        <aside class="suggested-sidebar">
            <?php if (!empty($utenti_suggeriti)): ?>
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
                                <form action="azione_follow.php" method="POST">
                                    <input type="hidden" name="id_da_seguire" value="<?php echo $u['id']; ?>">
                                    <button type="submit" class="follow-btn-index">Segui</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </aside>
    </div>
</main>

<aside class="profile-sidebar" id="profile-sidebar">
    <div class="sidebar-header">
        <h2>Il mio Profilo</h2>
        <button class="close-sidebar-btn" id="close-sidebar">×</button>
    </div>
    <div class="sidebar-content">
        <div class="sidebar-avatar-large">👤</div>
        <h3 class="sidebar-username">@<?php echo htmlspecialchars($username_loggato); ?></h3>
        <p class="sidebar-bio"><?php echo htmlspecialchars($bio); ?></p>

        <div class="sidebar-stats">
            <div><strong><?php echo $num_look; ?></strong><br>Look</div>
            <div><strong><?php echo $num_followers; ?></strong><br>Follower</div>
            <div><strong><?php echo $num_seguite; ?></strong><br>Seguiti</div>
        </div>

        <a href="modifica_profilo.php" class="edit-profile-btn" style="margin-bottom: 10px;">Modifica Profilo</a>
        <a href="../controller/logoutController.php" class="edit-profile-btn logout-btn">Esci</a>
    </div>
</aside>

<?php if($conn) mysqli_close($conn); require_once '../includes/footer.php'; ?>
</body>
</html>