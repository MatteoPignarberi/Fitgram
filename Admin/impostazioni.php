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
    <style>
        :root {
            --rosa-carne: #efd3d2;
            --beige-chiaro: #f8f5f0;
            --pure-white: #ffffff;
            --text-main: #3d3d3d;
            --text-muted: #888888;
            --accent-dark: #d8b4b2;
            --gray-border: #f0e4e2;
            --accent-pop: #b8807d;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--beige-chiaro);
            color: var(--text-main);
        }

        /* Navbar Semplificata per le impostazioni */
        nav {
            background-color: var(--pure-white);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--rosa-carne);
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .back-link {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .back-link:hover { color: var(--accent-pop); }

        .elegant-tagline {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.25rem;
            color: var(--accent-dark);
        }

        /* Contenitore Principale */
        .settings-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 0 20px;
        }

        .settings-header {
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--rosa-carne);
            padding-bottom: 10px;
        }

        .settings-header h1 {
            font-weight: 500;
            font-size: 1.8rem;
            margin: 0;
        }

        /* Box delle singole sezioni */
        .settings-section {
            background-color: var(--pure-white);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--gray-border);
        }

        .settings-section h2 {
            font-size: 1.2rem;
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--accent-pop);
        }

        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--beige-chiaro);
        }

        .setting-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .setting-info h3 {
            margin: 0 0 5px 0;
            font-size: 1rem;
            font-weight: 500;
        }

        .setting-info p {
            margin: 0;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .btn-action {
            background-color: var(--beige-chiaro);
            border: 1px solid var(--accent-dark);
            color: var(--text-main);
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            background-color: var(--accent-pop);
            color: var(--pure-white);
            border-color: var(--accent-pop);
        }

        .btn-danger {
            border-color: #ffcccc;
            color: #cc0000;
        }
        .btn-danger:hover {
            background-color: #cc0000;
            color: white;
            border-color: #cc0000;
        }

    </style>
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