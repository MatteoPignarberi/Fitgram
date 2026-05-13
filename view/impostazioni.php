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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/styles/css/impostazioni.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="top-nav">
    <a href="../Admin/dashboard.php" class="back-link">← torna indietro</a>
    <div class="logo-text">be fit. be style.</div>
</div>

<main class="settings-container">
    <div class="settings-card">
        <h1>Impostazioni</h1>

        <?php if (isset($_SESSION['messaggio_successo'])): ?>
            <div class="alert-success">
                Profilo aggiornato con eleganza.
                <?php unset($_SESSION['messaggio_successo']); ?>
            </div>
        <?php endif; ?>

        <form action="../controller/impostazioniController.php" method="POST">
            <input type="hidden" name="action" value="update_profilo">

            <div class="profile-header">
                <div class="avatar-circle">
                    <?= strtoupper(substr($dati_utente['username'], 0, 1)) ?>
                </div>
                <p style="color: var(--accent-site); font-size: 0.8rem; font-weight: 600; cursor: pointer;">Cambia foto</p>
            </div>

            <div class="section-title">Informazioni Pubbliche</div>

            <div class="form-group">
                <label>Nome Completo</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($dati_utente['nome'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($dati_utente['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Biografia</label>
                <textarea name="bio" rows="3"><?= htmlspecialchars($dati_utente['bio'] ?? '') ?></textarea>
            </div>

            <div class="section-title">Privacy e Preferenze</div>

            <div class="option-row">
                <div class="option-info">
                    <h4>Account Privato</h4>
                    <p>Solo le persone che approvi possono vedere i tuoi look.</p>
                </div>
                <select style="width: auto;">
                    <option>No</option>
                    <option>Si</option>
                </select>
            </div>

            <div class="option-row">
                <div class="option-info">
                    <h4>Notifiche Look</h4>
                    <p>Ricevi avvisi quando i tuoi creator preferiti postano.</p>
                </div>
                <select style="width: auto;">
                    <option>Attive</option>
                    <option>Silenziate</option>
                </select>
            </div>

            <div class="option-row">
                <div class="option-info">
                    <h4>Tema Interfaccia</h4>
                    <p>Personalizza l'aspetto della tua dashboard.</p>
                </div>
                <select style="width: auto;">
                    <option>Chiaro (Beige)</option>
                    <option>Scuro (Antracite)</option>
                </select>
            </div>

            <button type="submit" class="btn-save">Salva modifiche</button>
        </form>
    </div>
</main>

</body>
</html>