<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="assets/css/registrazione.css">
</head>
<body>

<div class="container">
    <h1>Registrati</h1>

    <form id="form-registrazione">

        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="cognome" placeholder="Cognome" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="email" name="email" placeholder="Email" required>

        <div id="messaggio-esito" style="margin-bottom: 15px; font-size: 14px;"></div>

        <input type="submit" value="Registrati">

    </form>

    <a href="login.php" class="login-link">Hai già un account? Accedi</a>
</div>

<script>
    document.getElementById('form-registrazione').addEventListener('submit', function(e) {
        // Blocca il ricaricamento della pagina
        e.preventDefault();

        // Raccoglie i dati e individua il div del messaggio
        const formData = new FormData(this);
        const divMessaggio = document.getElementById('messaggio-esito');

        // Mostra un testo di caricamento
        divMessaggio.innerHTML = "<span style='color: #999;'>Verifica in corso...</span>";

        // Invia i dati a salvaUtente.php
        fetch('salvaUtente.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                // Se la risposta non è ok a livello di rete, lancia un errore
                if (!response.ok) {
                    throw new Error('Errore di rete');
                }
                return response.json();
            })
            .then(data => {
                // Analizza la risposta JSON e stampa il messaggio
                if (data.status === 'error') {
                    divMessaggio.innerHTML = `
                <div style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px; border: 1px solid #f5c6cb;">
                    ${data.message}
                </div>`;
                }
                else if (data.status === 'success') {
                    divMessaggio.innerHTML = `
                <div style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; border: 1px solid #c3e6cb;">
                    ${data.message}
                </div>`;

                    // Reindirizza al login dopo 2 secondi
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                }
            })
            .catch(error => {
                // Gestisce errori di connessione o se salvaUtente.php restituisce HTML invece di JSON
                console.error('Errore Dettagliato:', error);
                divMessaggio.innerHTML = `
            <div style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px;">
                Errore di comunicazione col server. Controlla la Console (F12).
            </div>`;
            });
    });
</script>

</body>
</html>
