<?php
session_start();
require_once '../config/connessione.php';
require_once '../model/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cerchiamo l'utente nel database
    $user = findUserByUsername($conn, $_POST['username']);

    // Verifichiamo se l'utente esiste e la password è corretta
    if ($user && password_verify($_POST['password'], $user['pass'])) {

        // --- RIGHE FONDAMENTALI PER L'ACCESSO ---
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['id_utente_loggato'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // --- RECUPERO DATI PROFILO ---
        // Se la colonna 'nome' è vuota nel DB, usiamo lo username come ruota di scorta
        $_SESSION['nome'] = (!empty($user['nome'])) ? $user['nome'] : $user['username'];

        // Gestione foto profilo (se non esiste la colonna, usa un valore predefinito)
        $_SESSION['foto_profilo'] = (!empty($user['foto'])) ? $user['foto'] : 'default_avatar.png';

        // Reindirizzamento alla Dashboard (assicurati che il percorso sia corretto)
        header("Location: ../Admin/dashboard.php");
        exit();
    } else {
        // Se i dati sono sbagliati, rimanda al login con un errore
        $_SESSION['errore_login'] = "Username o password errati. Riprova.";
        header("Location: ../view/login.php");
        exit();
    }
}
?>

