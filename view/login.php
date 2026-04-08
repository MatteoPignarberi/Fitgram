<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/css/login.css">
    <link rel="stylesheet" href="../styles/css/components.css">
</head>
<body>

<div class="container">
    <h1>Accedi</h1>
    <form id="form-login">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <div id="messaggio-esito" style="margin-bottom: 15px; font-size: 14px;"></div>

        <input type="submit" value="Accedi">
    </form>

    <a href="registrazione.php" class="login-link">Non hai un account? Registrati</a>
</div>

<script>
    document.getElementById('form-login').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const divMessaggio = document.getElementById('messaggio-esito');
        divMessaggio.innerHTML = "<span style='color: #999;'>Verifica in corso...</span>";

        // Attenzione: ora chiamiamo un file diverso!
        fetch('../Admin/verificaLogin.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) throw new Error('Errore di rete');
                return response.json();
            })
            .then(data => {
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

                    // Reindirizza alla pagina principale dopo 1.5 secondi
                    setTimeout(() => {
                        window.location.href = '../Admin/dashboard.php'; // Cambia questo nome con la tua pagina principale!
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Errore Dettagliato:', error);
                divMessaggio.innerHTML = `
            <div style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px;">
                Errore di comunicazione col server.
            </div>`;
            });
    });
</script>

</body>
</html>