<?php
session_start();
require_once "connessione.php"; // include la connessione

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    // Validazione dati
    if (strlen($username) < 3) $errors[] = "Username minimo 3 caratteri.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email non valida.";
    if (strlen($password) < 6) $errors[] = "Password minimo 6 caratteri.";

    if (empty($errors)) {
        // Controllo email già registrata
        $stmt = $conn->prepare("SELECT id FROM utenti WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email già registrata.";
        } else {
            // Hash della password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Inserimento utente
            $stmt = $conn->prepare("INSERT INTO utenti (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $success = "Registrazione completata! Ora puoi fare login.";
            } else {
                $errors[] = "Errore durante la registrazione.";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione - Fitgram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Corpo e background */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff6b6b, #ff9472);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Box centrale */
        .box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            width: 360px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            color: #ff6b6b;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border-radius: 8px;
            border: none;
            background: #ff6b6b;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ff4a4a;
        }

        .error {
            color: red;
            font-size: 14px;
            margin: 5px 0;
        }

        .success {
            color: green;
            font-size: 14px;
            margin: 5px 0 15px 0;
        }

        .link {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #ff6b6b;
            transition: 0.3s;
        }

        .link:hover {
            color: #ff4a4a;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Registrati a Fitgram</h2>

    <!-- Mostra errori -->
    <?php foreach ($errors as $error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>

    <!-- Mostra messaggio di successo -->
    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <!-- Form registrazione -->
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Registrati</button>
    </form>

    <a class="link" href="login.php">Hai già un account? Accedi</a>
</div>

</body>
</html>