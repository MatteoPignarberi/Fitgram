<?php
session_start();
require_once __DIR__ . '/../config/connessione.php';
require_once __DIR__ . '/../model/UtenteModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update_profilo') {
    $model = new UtenteModel($conn);
    $user_id = $_SESSION['user_id'];

    // Recupero dati dal form
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $bio = isset($_POST['bio']) ? $_POST['bio'] : '';

    // --- MECCANISMO CARICAMENTO FOTO (SIMILE A LOOKCONTROLLER) ---
    if (isset($_FILES['foto_profilo']) && $_FILES['foto_profilo']['error'] === 0) {

        // Genero il nome file come fai per i look (time + nome originale)
        $nomeFile = time() . "_" . basename($_FILES['foto_profilo']['name']);
        $dir_destinazione = "../uploads/";

        // Creo la cartella se non esiste
        if (!is_dir($dir_destinazione)) {
            mkdir($dir_destinazione, 0777, true);
        }

        $target = $dir_destinazione . $nomeFile;

        if (move_uploaded_file($_FILES['foto_profilo']['tmp_name'], $target)) {
            // Se il caricamento riesce, aggiorno il nome della foto nel DB
            // Assicurati di avere questo metodo nel tuo UtenteModel
            $model->updateFoto($user_id, $nomeFile);
        } else {
            $_SESSION['msg_error'] = "Errore nel salvataggio della foto profilo.";
        }
    }

    // Aggiorno gli altri dati del profilo
    if ($model->updateProfilo($user_id, $nome, $username, $bio)) {
        $_SESSION['messaggio_successo'] = "Profilo aggiornato con successo!";
    } else {
        $_SESSION['msg_error'] = "Errore durante l'aggiornamento dei dati.";
    }

    header("Location: ../view/impostazioni.php");
    exit();
}