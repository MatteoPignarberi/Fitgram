<?php
session_start();

// TRAPPOLA 1: Controllo se il database esiste in quel percorso
if (!file_exists('../config/connessione.php')) {
    die("<h1>🚨 ERRORE: DATABASE NON TROVATO!</h1><p>PHP non trova il file in <b>../config/connessione.php</b>. Sicuro che la cartella 'config' esista?</p>");
}
require_once '../config/connessione.php';

// TRAPPOLA 2: Controllo se il Model esiste in quel percorso
if (!file_exists('../Model/UtenteModel.php')) {
    die("<h1>🚨 ERRORE: MODEL NON TROVATO!</h1><p>PHP non trova il file in <b>../Model/UtenteModel.php</b>. Controlla le maiuscole/minuscole!</p>");
}
require_once '../Model/UtenteModel.php';

// Controllo sicurezza...
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$mio_id = $_SESSION['user_id'];
$model = new UtenteModel($conn);

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