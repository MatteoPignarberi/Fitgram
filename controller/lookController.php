<?php
session_start();
include_once "../config/connessione.php";
include_once "../model/Look.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descrizione = isset($_POST['descrizione']) ? $_POST['descrizione'] : '';
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Utente';

    $links_array = isset($_POST['link_acquisto']) ? $_POST['link_acquisto'] : array();
    $links_filtrati = array();
    foreach ($links_array as $link) {
        if (!empty(trim($link))) {
            $links_filtrati[] = trim($link);
        }
    }
    $links_string = implode(", ", $links_filtrati);

    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === 0) {
        $nomeFile = time() . "_" . basename($_FILES['immagine']['name']);
        $dir_destinazione = "../uploads/";

        if (!is_dir($dir_destinazione)) {
            mkdir($dir_destinazione, 0777, true);
        }

        $target = $dir_destinazione . $nomeFile;

        if (move_uploaded_file($_FILES['immagine']['tmp_name'], $target)) {
            if (createLook($conn, $descrizione, $nomeFile, $username, $tags, $links_string)) {
                // MESSAGGIO DI SUCCESSO
                $_SESSION['msg_look'] = "Look pubblicato con successo!";

                // MODIFICA QUI: Torna alla Home (index.php) che si trova un livello sopra
                header("Location: ../Admin/dashboard.php");
                exit();
            } else {
                $_SESSION['msg_look'] = "Errore Database: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['msg_look'] = "Errore nel salvataggio della foto.";
        }
    } else {
        $_SESSION['msg_look'] = "Seleziona un'immagine valida.";
    }

    // Se c'è un errore, resta sulla pagina di caricamento per mostrarlo
    header("Location: ../view/carica_look.php");
    exit();
}
?>