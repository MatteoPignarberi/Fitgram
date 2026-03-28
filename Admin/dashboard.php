<?php
session_start();

// SICUREZZA: Se non c'è una sessione attiva, lo sbatto fuori al login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Nota: ho tolto "Admin/" perché sei già dentro la cartella!
    exit();
}

// === IL PASS VIP PER L'HEADER ===
$is_logged = true;
$username_mostrato = $_SESSION['username'];
// ================================

// Inizializzo le variabili base
$username_loggato = $_SESSION['username'];
$nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : $username_loggato;
$bio = "Appassionato di stile e fitness. Sempre alla ricerca del fit perfetto.";
$num_followers = 0;
$num_seguite = 0;
$num_look = 0;
$utenti_suggeriti = []; // Inizializzo l'array per i suggeriti

// Mi collego al DB (Usiamo il file intelligente!)
require_once '../config/connessione.php';
/** @var mysqli $conn */
if ($conn) {
    // 1. Prendo l'ID e la bio dell'utente loggato
    $sql = "SELECT id, bio FROM Utenti WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username_loggato);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($dati_utente = mysqli_fetch_assoc($result)) {
            $mio_id = $dati_utente['id']; // ECCO L'ID CHE CI SERVE!

            if (!empty($dati_utente['bio'])) {
                $bio = $dati_utente['bio'];
            }

            // 2. Conto i FOLLOWER (Quanti hanno il mio ID come 'idSeguito')
            $sql_f1 = "SELECT COUNT(*) AS total FROM Followers WHERE idSeguito = ?";
            $stmt_f1 = mysqli_prepare($conn, $sql_f1);
            mysqli_stmt_bind_param($stmt_f1, "i", $mio_id);
            mysqli_stmt_execute($stmt_f1);
            $res_f1 = mysqli_stmt_get_result($stmt_f1);
            $num_followers = mysqli_fetch_assoc($res_f1)['total'];

            // 3. Conto i SEGUITI (Quanti hanno il mio ID come 'idFollower')
            $sql_f2 = "SELECT COUNT(*) AS total FROM Followers WHERE idFollower = ?";
            $stmt_f2 = mysqli_prepare($conn, $sql_f2);
            mysqli_stmt_bind_param($stmt_f2, "i", $mio_id);
            mysqli_stmt_execute($stmt_f2);
            $res_f2 = mysqli_stmt_get_result($stmt_f2);
            $num_seguite = mysqli_fetch_assoc($res_f2)['total'];

            // 4. NUOVO: Conto quanti LOOK ha caricato questo utente
            $sql_l = "SELECT COUNT(*) AS total FROM Outfit WHERE username = ?";
            $stmt_l = mysqli_prepare($conn, $sql_l);
            mysqli_stmt_bind_param($stmt_l, "s", $username_loggato);
            mysqli_stmt_execute($stmt_l);
            $res_l = mysqli_stmt_get_result($stmt_l);
            $num_look = mysqli_fetch_assoc($res_l)['total'];

            // Query per utenti suggeriti usando NOT EXISTS
            $sql_sugg = "SELECT id, username, nome 
             FROM Utenti u
             WHERE id != ? 
             AND NOT EXISTS (
                 SELECT 1 FROM Followers f 
                 WHERE f.idSeguito = u.id 
                 AND f.idFollower = ?
             )
             ORDER BY RAND() LIMIT 5";

            $stmt_sugg = mysqli_prepare($conn, $sql_sugg);

            if ($stmt_sugg) {
                mysqli_stmt_bind_param($stmt_sugg, "ii", $mio_id, $mio_id);
                mysqli_stmt_execute($stmt_sugg);
                $res_sugg = mysqli_stmt_get_result($stmt_sugg);

                $utenti_suggeriti = [];
                while ($row = mysqli_fetch_assoc($res_sugg)) {
                    $utenti_suggeriti[] = $row;
                }
                mysqli_stmt_close($stmt_sugg);
            }

        } else {
            session_destroy();
            header("Location: Admin/login.php");
            exit();
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/feed.css">
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
        <button class="style-pill">Accessori</button>
    </div>

    <div class="content-layout">

        <div class="main-feed">
            <div class="gallery-grid">
                <?php
                if ($conn) {
                    // Peschiamo gli ultimi 20 outfit
                    $sql_outfits = "SELECT * FROM Outfit ORDER BY timestamp DESC LIMIT 20";
                    $result_outfits = mysqli_query($conn, $sql_outfits);

                    if ($result_outfits && mysqli_num_rows($result_outfits) > 0) {
                        while ($outfit = mysqli_fetch_assoc($result_outfits)) {
                            ?>
                            <article class="look-card">
                                <img src="../uploads/<?php echo htmlspecialchars($outfit['immagine']); ?>" alt="<?php echo htmlspecialchars(isset($outfit['descrizione']) ? $outfit['descrizione'] : 'Look'); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <div class="look-overlay">
                                    <div class="overlay-user">
                                        <div class="overlay-mini-avatar">👤</div>
                                        @<?php echo htmlspecialchars($outfit['username']); ?>
                                    </div>
                                </div>
                            </article>
                            <?php
                        }
                    } else {
                        echo "<p style='color: var(--text-muted); grid-column: 1 / -1; text-align: center; padding: 40px;'>Nessun outfit caricato ancora. Sii il primo!</p>";
                    }

                    mysqli_close($conn); // Chiudo la connessione solo alla fine!
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
                                    <strong><?php echo htmlspecialchars(!empty($u['nome']) ? $u['nome'] : $u['username']); ?></strong>
                                    <span>@<?php echo htmlspecialchars($u['username']); ?></span>
                                </div>

                                <form action="azione_follow.php" method="POST" style="margin: 0;">
                                    <input type="hidden" name="id_da_seguire" value="<?php echo $u['id']; ?>">
                                    <button type="submit" class="follow-btn-index">Segui</button>
                                </form>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php else: ?>
                <section class="suggested-section" style="text-align: center; color: var(--text-muted);">
                    <p style="font-size: 0.85rem; margin: 0;">Nessun nuovo suggerimento al momento!</p>
                </section>
            <?php endif; ?>
        </aside>

    </div> </main>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<aside class="profile-sidebar" id="profile-sidebar">
    <div class="sidebar-header">
        <h2>Il mio Profilo</h2>
        <button class="close-sidebar-btn" id="close-sidebar">×</button>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-avatar-large">👤</div>

        <h3 class="sidebar-username">@<?php echo htmlspecialchars($username_loggato); ?></h3>

        <p class="sidebar-bio">
            <?php echo htmlspecialchars($bio); ?>
        </p>

        <div class="sidebar-stats">
            <div><strong><?php echo $num_look; ?></strong><br>Look</div>
            <div><strong><?php echo $num_followers; ?></strong><br>Follower</div>
            <div><strong><?php echo $num_seguite; ?></strong><br>Seguiti</div>
        </div>

        <a href="modifica_profilo.php" class="edit-profile-btn" style="margin-bottom: 10px;">
            Modifica le tue informazioni
        </a>

        <a href="logout.php" class="edit-profile-btn logout-btn">
            Esci dall'account
        </a>
    </div>
</aside>
<?php require_once '../includes/footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const profileToggleBtn = document.getElementById('profile-toggle-btn');
        const sidebar = document.getElementById('profile-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const closeBtn = document.getElementById('close-sidebar');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        profileToggleBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);
    });
</script>

</body>
</html>