<?php
session_start();
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
    <a href="../index.php" class="back-link">
        ← Torna alla Home
    </a>
    <div class="elegant-tagline">Fitgram</div>
</nav>

<main class="settings-container">
    <div class="settings-header">
        <h1>⚙️ Impostazioni Account</h1>
    </div>

    <section class="settings-section">
        <h2>Profilo</h2>

        <div class="setting-item">
            <div class="setting-info">
                <h3>Informazioni Personali</h3>
                <p>Aggiorna il tuo nome, username o la bio del profilo.</p>
            </div>
            <button class="btn-action">Modifica</button>
        </div>

        <div class="setting-item">
            <div class="setting-info">
                <h3>Foto Profilo</h3>
                <p>Cambia l'avatar che gli altri utenti vedono.</p>
            </div>
            <button class="btn-action">Carica Foto</button>
        </div>
    </section>

    <section class="settings-section">
        <h2>Sicurezza e Accesso</h2>
        <div class="setting-item">
            <div class="setting-info">
                <h3>Email</h3>
                <p>Gestisci l'indirizzo email collegato al tuo account.</p>
            </div>
            <button class="btn-action">Gestisci</button>
        </div>

        <div class="setting-item">
            <div class="setting-info">
                <h3>Password</h3>
                <p>Aggiorna la tua password per mantenere l'account sicuro.</p>
            </div>
            <button class="btn-action">Cambia Password</button>
        </div>
    </section>

    <section class="settings-section">
        <h2>Gestione Account</h2>
        <div class="setting-item">
            <div class="setting-info">
                <h3>Elimina Account</h3>
                <p>Rimuovi permanentemente il tuo profilo e i tuoi look da Fitgram.</p>
            </div>
            <button class="btn-action btn-danger">Elimina</button>
        </div>
    </section>

</main>

</body>
</html>