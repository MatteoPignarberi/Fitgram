<?php
session_start();
require_once "connessione.php"; // include la connessione

$errors = [];
$success = "";

// Se l'utente arriva da registrazione
if (isset($_GET['registered']) && $_GET['registered'] == 1) {
    $success = "Registrazione completata! Ora puoi fare login.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    if (empty($email) || empty($password)) {
        $errors[] = "Inserisci email e password.";
    } else {
        // Prepara la query
        $stmt = $conn->prepare("SELECT id, username, password FROM utenti WHERE email = ?");
        if ($stmt === false) {
            $errors[] = "Errore nella query di login.";
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $hashedPassword);
                $stmt->fetch();

                if (password_verify($password, $hashedPassword)) {
                    // Login corretto
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $username;

                    header("Location: dashboard.php"); // redirect a pagina protetta
                    exit();
                } else {
                    $errors[] = "Password errata.";
                }
            } else {
                $errors[] = "Email non registrata.";
            }
            $stmt->close();
        }
    }
}
?>

    <!DOCTYPE html>
    <html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - Fitgram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff6b6b, #ff9472);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

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
    <h2>Login Fitgram</h2>

    <!-- Mostra messaggi -->
    <?php foreach ($errors as $error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Form login -->
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Accedi</button>
    </form>

    <a class="link" href="register.php">Non hai un account? Registrati</a>
</div>

</body>
    </html><?php
