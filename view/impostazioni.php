<?php

session_start();

require_once '../config/connessione.php';

require_once '../model/UtenteModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$mio_id = $_SESSION['user_id'];

// 3. Ora $conn esiste e possiamo avviare il Model senza far esplodere il server
$model = new UtenteModel($conn);
$dati_utente = $model->getUtenteById($mio_id);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impostazioni - Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/css/impostazioni.css">
</head>
<body>

<nav>
    <a href="../Admin/dashboard.php" class="back-link">← Torna alla Home</a>
    <div class="elegant-tagline">Fitgram</div>
</nav>
<main class="settings-container">
    <div class="settings-header">
        <h1>⚙️ Impostazioni Account</h1>
    </div>

    <?php if (isset($_SESSION['messaggio_successo'])): ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['messaggio_successo'];
            unset($_SESSION['messaggio_successo']); // Lo cancello dopo averlo mostrato
            ?>
        </div>
    <?php endif; ?>

    <section class="settings-section">
        <h2>Profilo</h2>

        <div class="setting-item" style="display: block;"> <form action="../Controller/ImpostazioniController.php" method="POST">
                <input type="hidden" name="action" value="update_profilo">
                <div class="setting-info" style="margin-bottom: 15px;">

                    <h3>Informazioni Personali</h3>
                </div>
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($dati_utente['nome'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($dati_utente['username'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Bio</label>
                    <textarea name="bio" rows="3"><?php echo htmlspecialchars($dati_utente['bio'] ?? ''); ?></textarea>
                </div>
                <button type="submit" class="btn-action">Salva Modifiche</button>
            </form>
        </div>
        <div class="setting-item">
            <div class="setting-info">
                <h3>Foto Profilo</h3>
                <p>Cambia l'avatar che gli altri utenti vedono.</p>
            </div>
            <button class="btn-action">Carica Foto</button>
        </div>
    </section>
</main>
</body>
</html>