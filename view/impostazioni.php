<?php
session_start();
require_once __DIR__ . '/../config/connessione.php';
require_once __DIR__ . '/../model/UtenteModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$model = new UtenteModel($conn);
$dati_utente = $model->getUtenteById($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impostazioni - Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/css/impostazioni.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-content">
        <a href="../Admin/dashboard.php" class="back-btn">← Home</a>
        <div class="logo">Fitgram</div>
        <div class="nav-spacer"></div>
    </div>
</nav>

<main class="main-container">
    <div class="settings-card">
        <aside class="settings-sidebar">
            <ul>
                <li class="active">Modifica Profilo</li>
                <li>Sicurezza</li>
                <li>Notifiche</li>
                <li>Privacy</li>
            </ul>
        </aside>

        <section class="settings-content">
            <header class="content-header">
                <div class="avatar-placeholder">
                    <?php echo strtoupper(substr($dati_utente['username'], 0, 1)); ?>
                </div>
                <div class="user-meta">
                    <h2><?php echo htmlspecialchars($dati_utente['username']); ?></h2>
                    <button class="change-photo">Cambia foto del profilo</button>
                </div>
            </header>

            <?php if (isset($_SESSION['messaggio_successo'])): ?>
                <div class="alert alert-success"><?= $_SESSION['messaggio_successo']; unset($_SESSION['messaggio_successo']); ?></div>
            <?php endif; ?>

            <form action="../controller/ImpostazioniController.php" method="POST" class="edit-form">
                <input type="hidden" name="action" value="update_profilo">

                <div class="form-row">
                    <label>Nome</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($dati_utente['nome'] ?? '') ?>" placeholder="Il tuo nome completo">
                </div>

                <div class="form-row">
                    <label>Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($dati_utente['username'] ?? '') ?>" required>
                    <small>In molti casi, potrai cambiare il tuo nome utente in un altro nome utente per altri 14 giorni.</small>
                </div>

                <div class="form-row">
                    <label>Bio</label>
                    <textarea name="bio" rows="3" placeholder="Scrivi qualcosa su di te..."><?= htmlspecialchars($dati_utente['bio'] ?? '') ?></textarea>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">Invia</button>
                </div>
            </form>
        </section>
    </div>
</main>

</body>
</html>