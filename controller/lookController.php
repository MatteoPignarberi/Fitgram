<?php
// controller/upload_look_controller.php
session_start();
include_once "../db/db_config.php";
include_once "../model/Look.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupero dati
    $descrizione = $_POST['descrizione'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $username = $_SESSION['username'] ?? 'Utente';

    // Gestione Link: trasformiamo l'array in stringa
    $links_array = $_POST['link_acquisto'] ?? [];
    $links_filtrati = array_filter($links_array, fn($value) => !empty(trim($value)));
    $links_string = implode(", ", $links_filtrati);

    // Gestione File
    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === 0) {
        $nomeFile = time() . "_" . basename($_FILES['immagine']['name']);
        $target = "../uploads/" . $nomeFile;

        if (move_uploaded_file($_FILES['immagine']['tmp_name'], $target)) {

            // Proviamo l'inserimento finale
            if (createLook($conn, $descrizione, $nomeFile, $username, $tags, $links_string)) {
                $_SESSION['msg_look'] = "<div class='msg success' style='color: green; background: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 20px;'>Look pubblicato con successo!</div>";
            } else {
                // Se fallisce ancora, stampiamo l'errore specifico di MySQL
                $errore_tecnico = mysqli_error($conn);
                $_SESSION['msg_look'] = "<div class='msg error' style='color: red;'>Errore database: $errore_tecnico</div>";
            }
        } else {
            $_SESSION['msg_look'] = "<div class='msg error' style='color: red;'>Errore nel caricamento del file nella cartella uploads.</div>";
        }
    } else {
        $_SESSION['msg_look'] = "<div class='msg error' style='color: red;'>Seleziona un'immagine valida.</div>";
    }

    header("Location: ../view/carica_look.php");
    exit();
}
?>