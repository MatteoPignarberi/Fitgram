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

// Percorso della foto: se non esiste nel DB, mostriamo il placeholder con l'iniziale
$foto_path = !empty($dati_utente['foto']) ? '../uploads/' . $dati_utente['foto'] : null;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impostazioni - Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/css/impostazioni.css?v=<?php echo time(); ?>">
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
                ✨ <?= $_SESSION['messaggio_successo']; unset($_SESSION['messaggio_successo']); ?>
            </div>
        <?php endif; ?>

        <form action="../controller/impostazioniController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update_profilo">

            <div class="profile-header">
                <div class="avatar-wrapper" onclick="document.getElementById('foto-input').click();">
                    <?php if ($foto_path && file_exists($foto_path)): ?>
                        <img src="<?= $foto_path ?>" id="preview" class="avatar-img">
                    <?php else: ?>
                        <div id="preview-placeholder" class="avatar-placeholder">
                            <?= strtoupper(substr($dati_utente['username'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>

                    <div class="avatar-overlay">
                        <span>MODIFICA</span>
                    </div>
                </div>
                <input type="file" name="foto_profilo" id="foto-input" style="display: none;" accept="image/*" onchange="previewImage(this)">
                <p class="photo-hint">Clicca sulla foto per caricarne una nuova</p>
            </div>

            <div class="section-title">Informazioni Pubbliche</div>

            <div class="form-group">
                <label>Nome Completo</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($dati_utente['nome'] ?? '') ?>" placeholder="Il tuo nome">
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($dati_utente['username'] ?? '') ?>" placeholder="username">
            </div>

            <div class="form-group">
                <label>Biografia</label>
                <textarea name="bio" rows="3" placeholder="Scrivi qualcosa su di te..."><?= htmlspecialchars($dati_utente['bio'] ?? '') ?></textarea>
            </div>

            <div class="section-title">Privacy e Preferenze</div>

            <div class="option-row">
                <div class="option-info">
                    <h4>Account Privato</h4>
                    <p>Solo i follower approvati vedranno i tuoi look.</p>
                </div>
                <select name="privacy" class="soft-select">
                    <option value="0">No</option>
                    <option value="1">Si</option>
                </select>
            </div>

            <button type="submit" class="btn-save">Salva modifiche</button>
        </form>
    </div>
</main>

<script>
    // Gestisce l'anteprima istantanea dell'immagine selezionata
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('preview');
                const placeholder = document.getElementById('preview-placeholder');

                if (preview) {
                    preview.src = e.target.result;
                } else if (placeholder) {
                    // Se c'era il placeholder (lettera), lo trasformiamo in immagine
                    placeholder.innerHTML = `<img src="${e.target.result}" id="preview" class="avatar-img" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">`;
                    placeholder.classList.remove('avatar-placeholder');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>