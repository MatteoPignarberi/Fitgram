<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login Fitgram</title>
    <link rel="stylesheet" href="../styles/css/login.css">
</head>
<body>

<div class="container">

    <h1>Login</h1>

    <?php if (isset($_SESSION['errore_login'])): ?>
        <div style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px; border: 1px solid #f5c6cb; margin-bottom: 15px; font-size: 14px;">
            <?php
            echo $_SESSION['errore_login'];
            unset($_SESSION['errore_login']);
            ?>
        </div>
    <?php endif; ?>

    <form id="form-login" action="../controller/loginController.php" method="POST">

        <input type="text" id="username" name="username" placeholder="Username" required>

        <input type="password" id="password" name="password" placeholder="Password" required>

        <input type="submit" value="Accedi">

        <p style="text-align: center; font-size: 0.85rem; margin-top: 15px; color: #888;">
            Non hai un account? <a href="registrazione.php" style="text-decoration: none; font-weight: 600;">Registrati</a>
        </p>
    </form>

</div>

</body>
</html>