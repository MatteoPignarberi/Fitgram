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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --text-main: #2d3436;
            --accent-color: #6c5ce7; /* Un viola elegante coerente con Fitgram */
            --border-radius: 20px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg-gradient);
            background-attachment: fixed;
            color: var(--text-main);
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .navbar {
            width: 100%;
            padding: 20px 0;
            text-align: center;
        }

        .navbar a {
            text-decoration: none;
            color: var(--accent-color);
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .navbar a:hover { opacity: 0.7; }

        .settings-card {
            background: var(--glass-bg);
            max-width: 600px;
            width: 90%;
            margin: 20px auto 50px auto;
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        }

        .header-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-title h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            margin: 0;
            color: var(--text-main);
        }

        .profile-pic-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 40px;
        }

        .avatar-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, var(--accent-color), #a29bfe);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            font-family: 'Playfair Display', serif;
            box-shadow: 0 10px 20px rgba(108, 92, 231, 0.2);
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
            margin-left: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #636e72;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #edf2f7;
            border-radius: 15px;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            transition: 0.3s;
            box-sizing: border-box;
            background: #fdfdfd;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
        }

        .btn-submit {
            width: 100%;
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 15px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.4s;
            margin-top: 10px;
            box-shadow: 0 8px 15px rgba(108, 92, 231, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(108, 92, 231, 0.3);
            background: #5b4bc4;
        }

        .alert-success {
            background: #d1f7e1;
            color: #1b5e20;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
            font-size: 0.9rem;
            border: 1px solid #c3e6cb;
        }

        .footer-tagline {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            margin-top: 20px;
            color: var(--accent-color);
            opacity: 0.6;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="../Admin/dashboard.php">← Torna alla Dashboard</a>
</nav>

<main class="settings-card">
    <div class="header-title">
        <h1>Il Tuo Profilo</h1>
        <div class="footer-tagline">Fitgram</div>
    </div>

    <div class="profile-pic-section">
        <div class="avatar-circle">
            <?= strtoupper(substr($dati_utente['username'], 0, 1)) ?>
        </div>
        <span style="color: var(--accent-color); cursor: pointer; font-size: 0.8rem; font-weight: 600;">Aggiorna Avatar</span>
    </div>

    <?php if (isset($_SESSION['messaggio_successo'])): ?>
        <div class="alert-success">
            ✨ <?= $_SESSION['messaggio_successo']; unset($_SESSION['messaggio_successo']); ?>
        </div>
    <?php endif; ?>

    <form action="../controller/impostazioniController.php" method="POST">
        <input type="hidden" name="action" value="update_profilo">

        <div class="form-group">
            <label>Nome Completo</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($dati_utente['nome'] ?? '') ?>" placeholder="Es. Mario Rossi">
        </div>

        <div class="form-group">
            <label>Nome Utente</label>
            <input type="text" name="username" value="<?= htmlspecialchars($dati_utente['username'] ?? '') ?>" placeholder="username_unico">
        </div>

        <div class="form-group">
            <label>La tua Bio</label>
            <textarea name="bio" rows="4" placeholder="Racconta qualcosa di te..."><?= htmlspecialchars($dati_utente['bio'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn-submit">Salva i cambiamenti</button>
    </form>
</main>

</body>
</html>