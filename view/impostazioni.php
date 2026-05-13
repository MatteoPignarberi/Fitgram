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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #ffffff;
            --secondary-bg: #fafafa;
            --border: #dbdbdb;
            --accent: #0095f6;
            --text: #262626;
            --text-muted: #8e8e8e;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--secondary-bg);
            color: var(--text);
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            padding: 12px 20px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-content {
            max-width: 935px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-content a { text-decoration: none; color: var(--text); font-weight: 600; }

        .main-wrapper {
            max-width: 935px;
            width: 100%;
            margin: 30px auto;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 3px;
            display: flex;
            min-height: 600px;
        }

        /* Sidebar */
        .sidebar {
            width: 230px;
            border-right: 1px solid var(--border);
        }

        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar li {
            padding: 16px 25px;
            cursor: pointer;
            font-size: 14px;
        }
        .sidebar li.active {
            font-weight: 600;
            border-left: 2px solid var(--text);
        }
        .sidebar li:hover:not(.active) { background: var(--secondary-bg); }

        /* Contenuto */
        .content {
            flex: 1;
            padding: 40px 60px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #eee;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-info h2 { margin: 0; font-size: 20px; font-weight: 400; }
        .change-photo { color: var(--accent); font-weight: 600; cursor: pointer; border: none; background: none; padding: 0; font-size: 14px; }

        /* Form */
        .form-group {
            display: flex;
            margin-bottom: 16px;
        }

        .form-group label {
            width: 150px;
            text-align: right;
            padding-right: 32px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 8px;
        }

        .input-container { flex: 1; max-width: 350px; }

        input[type="text"], textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 3px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .help-text {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 8px;
            line-height: 1.4;
        }

        .btn-save {
            background: var(--accent);
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            margin-left: 182px;
            margin-top: 20px;
        }

        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        .alert-success { background: #e6f4ea; color: #1e7e34; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-content">
        <a href="../Admin/dashboard.php">← Home</a>
        <div style="font-size: 20px; font-weight: bold;">Fitgram</div>
        <div></div>
    </div>
</nav>

<main class="main-wrapper">
    <aside class="sidebar">
        <ul>
            <li class="active">Modifica profilo</li>
            <li>Cambia la password</li>
            <li>Notifiche push</li>
            <li>Privacy e sicurezza</li>
        </ul>
    </aside>

    <section class="content">
        <div class="profile-header">
            <div class="avatar"><?= strtoupper(substr($dati_utente['username'], 0, 1)) ?></div>
            <div class="user-info">
                <h2><?= htmlspecialchars($dati_utente['username']) ?></h2>
                <button class="change-photo">Cambia la foto del profilo</button>
            </div>
        </div>

        <?php if (isset($_SESSION['messaggio_successo'])): ?>
            <div class="alert alert-success"><?= $_SESSION['messaggio_successo']; unset($_SESSION['messaggio_successo']); ?></div>
        <?php endif; ?>

        <form action="../controller/impostazioniController.php" method="POST">
            <input type="hidden" name="action" value="update_profilo">

            <div class="form-group">
                <label>Nome</label>
                <div class="input-container">
                    <input type="text" name="nome" value="<?= htmlspecialchars($dati_utente['nome'] ?? '') ?>">
                    <p class="help-text">Aiuta le persone a scoprire il tuo account utilizzando il nome con cui sei conosciuto.</p>
                </div>
            </div>

            <div class="form-group">
                <label>Username</label>
                <div class="input-container">
                    <input type="text" name="username" value="<?= htmlspecialchars($dati_utente['username'] ?? '') ?>">
                    <p class="help-text">Puoi modificare il tuo nome utente in qualsiasi momento.</p>
                </div>
            </div>

            <div class="form-group">
                <label>Bio</label>
                <div class="input-container">
                    <textarea name="bio" rows="3"><?= htmlspecialchars($dati_utente['bio'] ?? '') ?></textarea>
                </div>
            </div>

            <button type="submit" class="btn-save">Invia</button>
        </form>
    </section>
</main>

</body>
</html>