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
    <title>Impostazioni - Fitgram</title>
    <link rel="stylesheet" href="../styles/css/impostazioni.css">
</head>
<body>

<nav>
    <a href="../Admin/dashboard.php">← Torna alla Home</a>
    <span>Fitgram</span>
</nav>

<main class="settings-container">
    <h1>⚙️ Impostazioni Account</h1>

    <?php if (isset($_SESSION['messaggio_successo'])): ?>
        <div style="color: green;"><?= $_SESSION['messaggio_successo']; unset($_SESSION['messaggio_successo']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['messaggio_errore'])): ?>
        <div style="color: red;"><?= $_SESSION['messaggio_errore']; unset($_SESSION['messaggio_errore']); ?></div>
    <?php endif; ?>

    <section>
        <form action="../controller/impostazioniController.php" method="POST">
            <input type="hidden" name="action" value="update_profilo">

            <div class="form-group">
                <label>Nome Completo</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($dati_utente['nome'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($dati_utente['username'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Bio</label>
                <textarea name="bio" rows="3"><?= htmlspecialchars($dati_utente['bio'] ?? '') ?></textarea>
            </div>

            <button type="submit">Salva Modifiche</button>
        </form>
    </section>
</main>

</body>
</html>