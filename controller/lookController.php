<?php
// controller/upload_look_controller.php
session_start();

// Includiamo la configurazione del database e il model
include_once "../config/connessione.php";
include_once "../model/Look.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recupero i dati base dal form
    $descrizione = $_POST['descrizione'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $username = $_SESSION['username'] ?? 'ospite';

    // 2. GESTIONE LINK: Trasformo l'array dei link in una stringa unica
    // Prendo l'array, pulisco i campi vuoti e li unisco con una virgola
    $links_array = $_POST['link_acquisto'] ?? [];
    $links_filtrati = array_filter($links_array, function($val) {
        return !empty(trim($val)); // Rimuove link lasciati in bianco
    });
    $links_string = implode(", ", $links_filtrati);

    // 3. GESTIONE IMMAGINE
    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === 0) {
        $nomeFile = time() . "_" . basename($_FILES['immagine']['name']); // Aggiungo timestamp per evitare duplicati
        $target = "../uploads/" . $nomeFile;

        // Creiamo la cartella uploads se non esiste
        if (!is_dir("../uploads/")) {
            mkdir("../uploads/", 0777, true);
        }

        if (move_uploaded_file($_FILES['immagine']['tmp_name'], $target)) {

            // 4. SALVATAGGIO NEL DATABASE (Tabella outfit)
            // Passiamo i 6 parametri richiesti dalla funzione createLook
            if (createLook($conn, $descrizione, $nomeFile, $username, $tags, $links_string)) {
                $_SESSION['msg_look'] = "<div class='msg success'>Look pubblicato con successo!</div>";
                header("Location: ../view/carica_look.php");
                exit();
            } else {
                $_SESSION['msg_look'] = "<div class='msg error'>Errore nel salvataggio sul database. Verifica le colonne tags e link_acquisto.</div>";
            }
        } else {
            $_SESSION['msg_look'] = "<div class='msg error'>Errore nello spostamento del file nella cartella uploads.</div>";
        }
    } else {
        $_SESSION['msg_look'] = "<div class='msg error'>Devi selezionare un'immagine valida.</div>";
    }

    // Se arriviamo qui, c'è stato un errore
    header("Location: ../view/carica_look.php");
    exit();
}
?>