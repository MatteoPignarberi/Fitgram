<?php
session_start();
require_once __DIR__ . '/../config/connessione.php';
require_once __DIR__ . '/../model/UtenteModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update_profilo') {
    $model = new UtenteModel($conn);
    $user_id = $_SESSION['user_id'];

    // Dati testuali
    $nome = $_POST['nome'];
    $username = $_POST['username'];
    $bio = $_POST['bio'];

    // Gestione Immagine
    if (isset($_FILES['foto_profilo']) && $_FILES['foto_profilo']['error'] === 0) {
        $upload_dir = __DIR__ . '/../uploads/';

        // Se la cartella non esiste, la crea
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

        $file_ext = pathinfo($_FILES['foto_profilo']['name'], PATHINFO_EXTENSION);
        $file_name = "profile_" . $user_id . "_" . time() . "." . $file_ext;
        $dest_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['foto_profilo']['tmp_name'], $dest_path)) {
            // Aggiorna il nome del file nel database
            $model->updateFoto($user_id, $file_name);
        }
    }

    $model->updateProfilo($user_id, $nome, $username, $bio);
    $_SESSION['messaggio_successo'] = "Profilo aggiornato!";

    header("Location: ../view/impostazioni.php");
    exit;
}