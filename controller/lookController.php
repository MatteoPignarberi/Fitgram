<?php
// controller/upload_look_controller.php
session_start();
include_once "../config/connessione.php";
include_once "../model/Look.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descrizione = $_POST['descrizione'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $username = $_SESSION['username'] ?? 'Utente_Test';

    // Trasformiamo l'array dei link in stringa
    $links_array = $_POST['link_acquisto'] ?? [];
    $links_filtrati = array_filter($links_array, fn($value) => !empty(trim($value)));
    $links_string = implode(", ", $links_filtrati);

    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === 0) {
        $nomeFile = time() . "_" . basename($_FILES['immagine']['name']);
        $target = "../uploads/" . $nomeFile;

        if (move_uploaded_file($_FILES['immagine']['tmp_name'], $target)) {

            // Proviamo l'inserimento
            if (createLook($conn, $descrizione, $nomeFile, $username, $tags, $links_string)) {
                $_SESSION['msg_look'] = "<div class='msg success'>Look pubblicato con successo!</div>";
            } else {
                // --- DEBUG: RECUPERIAMO L'ERRORE REALE ---
                $errore_db = mysqli_error($conn);
                $_SESSION['msg_look'] = "<div class='msg error'>Errore Database: " . $errore_db . "</div>";
            }
        } else {
            $_SESSION['msg_look'] = "<div class='msg error'>Errore nel caricamento fisico del file.</div>";
        }
    } else {
        $_SESSION['msg_look'] = "<div class='msg error'>File immagine non valido o mancante.</div>";
    }

    header("Location: ../view/carica_look.php");
    exit();
}