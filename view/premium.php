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
    <link rel="stylesheet" href="../styles/css/premium.css">
<body>

<nav>
    <a href="../index.php" class="logo">Fitgram</a>
    <a href="../index.php" class="back-link">← Torna alla Home</a>
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
            <a href="../view/registrazione.php" class="subscribe-btn">Inizia Gratis</a>
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