<?php
session_start();
require_once '../config/connessione.php';
require_once '../model/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = findUserByUsername($conn, $_POST['username']);

    if ($user && password_verify($_POST['password'], $user['pass'])) {
        // DATI FONDAMENTALI PER LA SESSIONE
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['id_utente_loggato'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // DATI PER L'HEADER (Nome e Foto)
        // Se nel DB la colonna 'nome' è vuota, usiamo lo username
        $_SESSION['nome'] = !empty($user['nome']) ? $user['nome'] : $user['username'];
        // Se non hai una colonna foto, puoi usare un avatar di default per ora
        $_SESSION['foto_profilo'] = !empty($user['foto']) ? $user['foto'] : 'default_avatar.png';

        header("Location: ../Admin/dashboard.php");
        exit();
    } else {
        $_SESSION['errore_login'] = "Username o password errati.";
        header("Location: ../view/login.php");
        exit();
    }
}
?>

