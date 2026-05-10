<?php
session_start();

require_once '../Model/UtenteModel.php';

// Controllo sicurezza: l'utente deve essere loggato
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$mio_id = $_SESSION['user_id'];
$model = new UtenteModel($conn); // $conn deve arrivare dal tuo file database.php

// 1. GESTIONE DEL FORM (quando l'utente clicca "Salva")
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'update_profilo') {
        // Prendo i dati dal form e li pulisco
        $nuovo_nome = trim($_POST['nome']);
        $nuovo_username = trim($_POST['username']);
        $nuova_bio = trim($_POST['bio']);

        // Chiamo il Model per aggiornare il DB
        $aggiornamento = $model->updateProfilo($mio_id, $nuovo_nome, $nuovo_username, $nuova_bio);

        if ($aggiornamento) {
            $_SESSION['messaggio_successo'] = "Profilo aggiornato con successo!";
            // Aggiorno anche la sessione dell'username se l'ha cambiato
            $_SESSION['username'] = $nuovo_username;
        } else {
            $_SESSION['messaggio_errore'] = "Errore durante l'aggiornamento.";
        }

        // Ricarico la pagina per mostrare i cambiamenti
        header("Location: ../View/impostazioni.php");
        exit;
    }
}
?>
