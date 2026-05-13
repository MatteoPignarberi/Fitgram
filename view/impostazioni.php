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
    <style>
        :root {
            --bg-site: #f4f1ee; /* Il crema dello sfondo che si vede nello screen */
            --card-bg: #ffffff;
            --accent-site: #b29491; /* Il marrone rosato dei bottoni "Segui" */
            --text-dark: #4a4a4a;
            --text-light: #8e8e8e;
            --soft-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-site);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
        }

        /* Header minimale */
        .top-nav {
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.5rem;
            color: var(--accent-site);
        }

        .back-link {
            text-decoration: none;
            color: var(--text-light);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Container centrale */
        .settings-container {
            max-width: 700px;
            margin: 0 auto 100px auto;
            padding: 0 20px;
        }

        .settings-card {
            background: var(--card-bg);
            border-radius: 30px;
            padding: 50px;
            box-shadow: var(--soft-shadow);
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
            font-size: 2.2rem;
            margin-bottom: 40px;
            text-align: center;
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--accent-site);
            margin: 40px 0 20px 0;
            font-weight: 600;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 10px;
        }

        /* Avatar Section */
        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 40px;
        }

        .avatar-circle {
            width: 90px;
            height: 90px;
            background-color: var(--bg-site);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--accent-site);
            font-family: 'Playfair Display', serif;
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        input[type="text"],
        input[type="email"],
        textarea,
        select {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid #eee;
            border-radius: 15px;
            background-color: #fafafa;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            color: var(--text-dark);
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--accent-site);
            background-color: #fff;
        }

        /* Opzioni Extra (Toggle-like) */
        .option-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .option-info h4 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .option-info p {
            margin: 5px 0 0 0;
            font-size: 0.8rem;
            color: var(--text-light);
        }

        /* Bottone in stile "Segui" dello screen */
        .btn-save {
            width: 100%;
            background-color: var(--accent-site);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 50px; /* Molto morbido */
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 40px;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .btn-save:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .alert-success {
            background-color: #f4f1ee;
            color: var(--accent-site);
            padding: 15px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 30px;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
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