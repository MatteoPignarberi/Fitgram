<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login Fitgram</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

<?php if (isset($_SESSION['errore_login'])): ?>
    <div style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px; border: 1px solid #f5c6cb; margin-bottom: 15px;">
        <?php
        echo $_SESSION['errore_login'];
        unset($_SESSION['errore_login']);
        ?>
    </div>
<?php endif; ?>

<form id="form-login" action="../controller/loginController.php" method="POST">

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br><br>

    <button type="submit">Accedi</button>

</form>

</body>
</html>