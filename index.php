<?php
session_start();

// 1. VARIABILI DI DEFAULT
$is_logged = false;
$nome = "Ospite";
$username_mostrato = "Accedi";
$bio = "Benvenuto su Fitgram. Effettua il login per vedere il tuo profilo.";
$utenti_suggeriti = [];

// 2. RICHIAMIAMO LA CONNESSIONE AL DB
// Questo file crea la variabile $conn pronta per essere usata
require_once 'config/connessione.php';
/** @var mysqli $conn */

// 3. RECUPERO UTENTI DA SEGUIRE
$sql_utenti = "SELECT id, username, nome FROM Utenti ORDER BY RAND() LIMIT 5";
$result_utenti = mysqli_query($conn, $sql_utenti);
if ($result_utenti) {
    while ($row = mysqli_fetch_assoc($result_utenti)) {
        $utenti_suggeriti[] = $row;
    }
}

// 4. SE L'UTENTE E' LOGGATO, RECUPERIAMO I SUOI DATI
if (isset($_SESSION['username'])) {
    $is_logged = true;
    $username_loggato = $_SESSION['username'];
    $nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : $_SESSION['username'];
    $username_mostrato = $username_loggato;
    $bio = "Appassionato di stile e fitness. Sempre alla ricerca del fit perfetto.";

    $sql = "SELECT * FROM Utenti WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username_loggato);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($dati_utente = mysqli_fetch_assoc($result)) {
            if (!empty($dati_utente['bio'])) {
                $bio = $dati_utente['bio'];
            }
        }
        mysqli_stmt_close($stmt);
    }
}

// Chiudiamo la connessione alla fine delle operazioni
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/feed.css">
</head>
<body>
<?php require_once 'includes/header.php'; ?>

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
                <article class="look-card"><div class="look-image-placeholder">FOTO_01</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @m_pigna</div></div></article>
                <article class="look-card" style="background-color: #d1d5db;"><div class="look-image-placeholder">FOTO_02</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @ale_ono</div></div></article>
                <article class="look-card" style="background-color: #e2c9c8;"><div class="look-image-placeholder">FOTO_03</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @simone_dm</div></div></article>
                <article class="look-card" style="background-color: #dcd7d2;"><div class="look-image-placeholder">FOTO_04</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @cosmin_r</div></div></article>
                <article class="look-card" style="background-color: #c5d0d3;"><div class="look-image-placeholder">FOTO_05</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @sara_style</div></div></article>
                <article class="look-card"><div class="look-image-placeholder">FOTO_06</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @luca_fit</div></div></article>
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
                                <a href="Admin/login.php" class="follow-btn-index" onclick="alert('Devi accedere o registrarti per seguire questo utente!');">Segui</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
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

        <?php if($is_logged) { ?>

            <div class="sidebar-avatar-large">👤</div>
            <h3 class="sidebar-username">@<?php echo htmlspecialchars($username_loggato); ?></h3>
            <p class="sidebar-bio"><?php echo htmlspecialchars($bio); ?></p>

            <div class="sidebar-stats">
                <div><strong>12</strong><br>Look</div>
                <div><strong>340</strong><br>Follower</div>
                <div><strong>150</strong><br>Seguiti</div>
            </div>

            <a href="modifica_profilo.php" class="edit-profile-btn">
                Modifica le tue informazioni
            </a>

        <?php } else { ?>

            <div class="sidebar-avatar-large">👤</div>
            <h3 class="sidebar-username">Benvenuto su Fitgram</h3>
            <p class="sidebar-bio">
                Accedi o registrati per vedere il tuo profilo, caricare look e seguire altri creator.
            </p>

            <a href="Admin/login.php" class="edit-profile-btn" style="margin-bottom:10px;">
                Accedi
            </a>

            <a href="Admin/registrazione.php" class="edit-profile-btn" style="background-color: var(--accent-dark);">
                Registrati
            </a>

        <?php } ?>

    </div>
</aside>
<?php require_once 'includes/footer.php'; ?>
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