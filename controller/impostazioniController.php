<?php
session_start();

// Percorsi assoluti basati sulla posizione di questo file
require_once __DIR__ . '/../config/connessione.php';
require_once __DIR__ . '/../model/UtenteModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Assicurati che $conn sia definita in connessione.php
if (!isset($conn)) {
    die("Errore: Connessione al database non trovata.");
}

$model = new UtenteModel($conn);
$mio_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profilo') {
        $nome = trim($_POST['nome'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        if ($model->updateProfilo($mio_id, $nome, $username, $bio)) {
            $_SESSION['messaggio_successo'] = "Profilo aggiornato correttamente!";
            $_SESSION['username'] = $username; // Aggiorno lo username in sessione
        } else {
            $_SESSION['messaggio_errore'] = "Errore durante l'aggiornamento.";
        }

        header("Location: ../view/impostazioni.php");
        exit;
    }
}