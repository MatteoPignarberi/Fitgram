<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abbonamenti - Fitgram</title>
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

        /* Navbar Semplificata per la pagina abbonamenti */
        nav {
            background-color: var(--pure-white);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--rosa-carne);
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.5rem;
            color: var(--accent-dark);
            text-decoration: none;
            font-weight: 600;
        }

        .back-link {
            text-decoration: none;
            color: var(--text-main);
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.3s;
        }

        .back-link:hover { color: var(--accent-pop); }

        /* Contenitore Piani */
        .pricing-container {
            max-width: 900px;
            margin: 4rem auto;
            padding: 0 20px;
            text-align: center;
        }

        .pricing-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--text-main);
            margin-bottom: 10px;
        }

        .pricing-header p {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 3rem;
        }

        .pricing-cards {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Stile delle Card */
        .card {
            background-color: var(--pure-white);
            border-radius: 15px;
            padding: 40px 30px;
            width: 100%;
            max-width: 350px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid var(--gray-border);
            transition: transform 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: translateY(-10px);
            border-color: var(--accent-dark);
        }

        .card.premium {
            border: 2px solid var(--accent-pop);
            box-shadow: 0 15px 40px rgba(184, 128, 125, 0.2);
        }

        .premium-badge {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--accent-pop);
            color: var(--pure-white);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .card h2 { margin: 0; font-size: 1.5rem; font-weight: 600; }
        .price { font-size: 2.5rem; font-weight: 300; margin: 20px 0; color: var(--accent-pop); }
        .price span { font-size: 1rem; color: var(--text-muted); }

        .features {
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
            text-align: left;
            flex-grow: 1;
        }

        .features li {
            padding: 10px 0;
            border-bottom: 1px solid var(--beige-chiaro);
            font-size: 0.9rem;
            color: var(--text-main);
        }

        .features li::before {
            content: '✓';
            color: var(--accent-pop);
            font-weight: bold;
            margin-right: 10px;
        }

        .subscribe-btn {
            background-color: var(--pure-white);
            color: var(--text-main);
            border: 1px solid var(--text-main);
            padding: 15px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .subscribe-btn:hover {
            background-color: var(--text-main);
            color: var(--pure-white);
        }

        .card.premium .subscribe-btn {
            background-color: var(--accent-pop);
            color: var(--pure-white);
            border: none;
        }

        .card.premium .subscribe-btn:hover {
            background-color: var(--text-main);
        }

    </style>
</head>
<body>

<nav>
    <a href="index.php" class="logo">Fitgram</a>
    <a href="index.php" class="back-link">← Torna alla Home</a>
</nav>

<main class="pricing-container">
    <div class="pricing-header">
        <h1>Scegli il tuo stile</h1>
        <p>Sblocca tutte le funzionalità di Fitgram ed espandi il tuo guardaroba digitale.</p>
    </div>

    <div class="pricing-cards">
        <div class="card">
            <h2>Standard</h2>
            <div class="price">€0<span>/mese</span></div>
            <ul class="features">
                <li>Esplora tutti i look</li>
                <li>Carica fino a 5 look al mese</li>
                <li>Profilo utente base</li>
                <li>Salva i tuoi look preferiti</li>
            </ul>
            <a href="Admin/registrazione.php" class="subscribe-btn">Inizia Gratis</a>
        </div>

        <div class="card premium">
            <div class="premium-badge">Più Popolare</div>
            <h2>Premium</h2>
            <div class="price">€9.99<span>/mese</span></div>
            <ul class="features">
                <li>Caricamento look illimitato</li>
                <li>Badge utente verificato/Premium</li>
                <li>Statistiche avanzate sul profilo</li>
                <li>Zero pubblicità</li>
                <li>Accesso anticipato alle tendenze</li>
            </ul>
            <form action="checkout.php" method="POST">
                <input type="hidden" name="piano" value="premium">
                <button type="submit" class="subscribe-btn" style="width: 100%; cursor: pointer;">Abbonati Ora</button>
            </form>
        </div>
    </div>
</main>

</body>
</html>