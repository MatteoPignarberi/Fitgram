<?php
session_start();
include_once "../config/connessione.php";
include_once "../model/UtenteModel.php";

// Controllo sicurezza
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update_profilo') {
    $model = new UtenteModel($conn);
    $user_id = $_SESSION['user_id'];

    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';

    // --- LOGICA CARICAMENTO FOTO ---
    if (isset($_FILES['foto_profilo']) && $_FILES['foto_profilo']['error'] === 0) {
        $nomeFileOriginale = basename($_FILES['foto_profilo']['name']);
        $estensione = strtolower(pathinfo($nomeFileOriginale, PATHINFO_EXTENSION));

        // Generiamo un nome unico: user_ID_timestamp.estensione
        $nuovoNomeFile = "user_" . $user_id . "_" . time() . "." . $estensione;
        $dir_destinazione = "../uploads/";

        // Creiamo la cartella se non esiste
        if (!is_dir($dir_destinazione)) {
            mkdir($dir_destinazione, 0777, true);
        }

        $target = $dir_destinazione . $nuovoNomeFile;

        if (move_uploaded_file($_FILES['foto_profilo']['tmp_name'], $target)) {
            // 1. Aggiorna il Database
            if ($model->updateFoto($user_id, $nuovoNomeFile)) {
                // 2. AGGIORNA LA SESSIONE (Fondamentale per l'Header)
                $_SESSION['foto_profilo'] = $nuovoNomeFile;
            }
        }
    }

    // Aggiornamento dati testuali
    if ($model->updateProfilo($user_id, $nome, $username, $bio)) {
        $_SESSION['messaggio_successo'] = "Profilo aggiornato con successo!";
        $_SESSION['username'] = $username;
        $_SESSION['nome'] = $nome;
    }

    header("Location: ../view/impostazioni.php");
    exit();
}
?>