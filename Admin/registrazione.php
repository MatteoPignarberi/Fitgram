<?php
require "connessione.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!empty($username) && !empty($email) && !empty($password)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO utenti (username, email, password) VALUES (?, ?, ?)");

        try {
            $stmt->execute([$username, $email, $hashedPassword]);
            $message = "Registrazione completata! Puoi fare il login.";
        } catch(PDOException $e) {
            $message = "Email già registrata.";
        }
    } else {
        $message = "Compila tutti i campi.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrati • Fitgram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial;
            background: linear-gradient(135deg, #ff6b6b, #ff9472);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            width: 350px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #ff6b6b;
            border: none;
            color: white;
            cursor: pointer;
        }
        .message {
            margin-bottom: 10px;
            color: green;
        }
        a {
            display: block;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Crea Account</h2>

    <?php if($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Registrati</button>
    </form>

    <a href="login.php">Hai già un account? Login</a>
</div>

</body>
</html>