<?php
session_start();
require_once '../config/connessione.php';
require_once '../model/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = findUserByUsername($conn, $_POST['username']);

    if ($user && password_verify($_POST['password'], $user['pass'])) {
        // Uniformiamo i nomi per evitare errori nei Like
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['id_utente_loggato'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Reindirizzamento alla Dashboard nell'area Admin
        header("Location: ../Admin/dashboard.php");
        exit();
    } else {
        $_SESSION['errore_login'] = "Username o password errati. Riprova.";
        header("Location: ../view/login.php");
        exit();
    }
}
?>

