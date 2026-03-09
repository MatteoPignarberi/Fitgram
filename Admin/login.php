<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Minimal Outfit</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #fafafa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #ffffff;
            padding: 50px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            text-align: center;
            width: 100%;
            max-width: 320px;
        }
        h1 {
            font-weight: 300;
            margin-bottom: 30px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-size: 20px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #eaeaea;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fcfcfc;
            transition: border 0.3s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #333;
        }
        input[type="submit"] {
            background-color: #FAD6C9;
            color: black;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #555;
            color: white;
        }
        a.login-link {
            display: block;
            margin-top: 25px;
            font-size: 12px;
            color: #999;
            text-decoration: none;
            transition: color 0.3s;
        }
        a.login-link:hover {
            color: #333;
        }
    </style>
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
        fetch('verificaLogin.php', {
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
                        window.location.href = 'dashboard.php'; // Cambia questo nome con la tua pagina principale!
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