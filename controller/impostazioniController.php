<?php
session_start();
include_once "../config/connessione.php";
include_once "../model/UtenteModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] === 'update_profilo') {
    $model = new UtenteModel($conn);
    $user_id = $_SESSION['user_id']; // Recuperiamo l'ID dell'utente loggato

    $nome = $_POST['nome'] ?? '';
    $username = $_POST['username'] ?? '';
    $bio = $_POST['bio'] ?? '';

    // LOGICA UPLOAD (Copiata dal tuo lookcontroller)
    if (isset($_FILES['foto_profilo']) && $_FILES['foto_profilo']['error'] === 0) {
        // Usiamo lo stesso formato: timestamp + nome originale
        $nomeFile = time() . "_" . basename($_FILES['foto_profilo']['name']);
        $dir_destinazione = "../uploads/";

        if (!is_dir($dir_destinazione)) {
            mkdir($dir_destinazione, 0777, true);
        }

        $target = $dir_destinazione . $nomeFile;

        if (move_uploaded_file($_FILES['foto_profilo']['tmp_name'], $target)) {
            // Se il file è salvato, scriviamo il nome nel DB
            $model->updateFoto($user_id, $nomeFile);
        }
    }

    // Aggiorna i testi
    if ($model->updateProfilo($user_id, $nome, $username, $bio)) {
        $_SESSION['messaggio_successo'] = "Profilo aggiornato con eleganza.";
    }

    header("Location: ../view/impostazioni.php");
    exit();
}