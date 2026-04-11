<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="../styles/css/registrazione.css">
</head>
<body>

<div class="container">
    <h1>Registrati</h1>

    <?php if (isset($_SESSION['errore_reg'])): ?>
        <div style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px; border: 1px solid #f5c6cb; margin-bottom: 15px; font-size: 14px;">
            <?php
            echo $_SESSION['errore_reg'];
            unset($_SESSION['errore_reg']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['successo_reg'])): ?>
        <div style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; border: 1px solid #c3e6cb; margin-bottom: 15px; font-size: 14px;">
            <?php
            echo $_SESSION['successo_reg'];
            unset($_SESSION['successo_reg']);
            ?>
        </div>
    <?php endif; ?>

    <form action="../controller/registrazioneController.php" method="POST">

        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="cognome" placeholder="Cognome" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="email" name="email" placeholder="Email" required>

        <input type="submit" value="Registrati">

    </form>

    <a href="login.php" class="login-link">Hai già un account? Accedi</a>
</div>

</body>
</html>