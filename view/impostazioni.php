<?php
// View/impostazioni.php
session_start();

require_once '../config/connessione.php';

// 2. Includi il Model
require_once '../Model/UtenteModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// $mio_id = $_SESSION['user_id'];

// 3. Ora $conn esiste e possiamo avviare il Model senza far esplodere il server
// $model = new UtenteModel($conn);
// $dati_utente = $model->getUtenteById($mio_id);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impostazioni - Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/css/impostazioni.css">
    <style>
        /* Aggiunta di un po' di stile base per i form */
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 0.9em; margin-bottom: 5px; color: #555; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-error { background-color: #f8d7da; color: #721c24; }
    </style>
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