<?php
session_start();
include_once "../config/connessione.php";
include_once "../model/Look.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descrizione = $_POST['descrizione'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $username = $_SESSION['username'] ?? 'Utente';
    $user_id = $_SESSION['user_id'] ?? null; // Fondamentale per il collegamento

    $links_array = $_POST['link_acquisto'] ?? array();
    $links_string = implode(", ", array_filter(array_map('trim', $links_array)));

    if ($user_id && isset($_FILES['immagine']) && $_FILES['immagine']['error'] === 0) {
        $nomeFile = time() . "_" . basename($_FILES['immagine']['name']);
        $target = "../uploads/" . $nomeFile;

        if (move_uploaded_file($_FILES['immagine']['tmp_name'], $target)) {
            // 1. Crea l'outfit e ottieni l'ID
            $newOutfitId = createLook($conn, $descrizione, $nomeFile, $username, $tags, $links_string);

            if ($newOutfitId) {
                // 2. Collega l'outfit all'utente (Punto fondamentale!)
                collegaOutfitUtente($conn, $user_id, $newOutfitId);

                $_SESSION['msg_look'] = "Look pubblicato e aggiunto al tuo armadio!";
                header("Location: ../controller/armadioController.php");
                exit();
            } else {
                $_SESSION['msg_look'] = "Errore durante il salvataggio nel database.";
            }
        } else {
            $_SESSION['msg_look'] = "Errore nel caricamento fisico della foto.";
        }
    } else {
        $_SESSION['msg_look'] = "Dati mancanti o immagine non valida.";
    }

    header("Location: ../view/carica_look.php");
    exit();
}