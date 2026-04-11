<?php
session_start();
require_once '../config/connessione.php';
require_once '../model/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST["nome"] ?? '';
    $cognome = $_POST["cognome"] ?? '';
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';
    $email = $_POST["email"] ?? '';

    $utenteEsistente = findUserByUsername($conn, $username);

    if ($utenteEsistente) {
        $_SESSION['errore_reg'] = "Questo username è già in uso. Scegline un altro!";
        header("Location: ../view/registrazione.php");
        exit();
    }

    $password_criptata = password_hash($password, PASSWORD_DEFAULT);

    $inserito = createUser($conn, $nome, $cognome, $username, $password_criptata, $email);

    if ($inserito) {
        $_SESSION['successo_reg'] = "Registrazione completata! Ora puoi fare il login.";
        header("Location: ../view/login.php");
        exit();
    } else {
        $_SESSION['errore_reg'] = "Errore durante il salvataggio dei dati.";
        header("Location: ../view/registrazione.php");
        exit();
    }
}
?>
