<?php
session_start();
include_once "../db/db_config.php";
include_once "../model/Look.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descrizione = isset($_POST['descrizione']) ? $_POST['descrizione'] : '';
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Utente';

    // Gestione Link
    $links_array = isset($_POST['link_acquisto']) ? $_POST['link_acquisto'] : array();
    $links_filtrati = array();
    foreach ($links_array as $link) {
        if (!empty(trim($link))) {
            $links_filtrati[] = $link;
        }
    }
    $links_string = implode(", ", $links_filtrati);

    // Gestione File
    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === 0) {
        $nomeFile = time() . "_" . basename($_FILES['immagine']['name']);
        $dir_destinazione = "../uploads/";

        if (!is_dir($dir_destinazione)) {
            mkdir($dir_destinazione, 0777, true);
        }

        $target = $dir_destinazione . $nomeFile;

        if (move_uploaded_file($_FILES['immagine']['tmp_name'], $target)) {
            // Chiamata alla funzione del Model
            if (createLook($conn, $descrizione, $nomeFile, $username, $tags, $links_string)) {
                $_SESSION['msg_look'] = "<div class='msg success' style='color: #155724; background: #d4edda; padding: 15px; border-radius: 8px;'>✔ Look pubblicato!</div>";
            } else {
                $err = mysqli_error($conn);
                $_SESSION['msg_look'] = "<div class='msg error' style='color: #721c24; background: #f8d7da; padding: 15px; border-radius: 8px;'>❌ Errore DB: $err</div>";
            }
        } else {
            $_SESSION['msg_look'] = "❌ Errore nel salvataggio della foto.";
        }
    } else {
        $_SESSION['msg_look'] = "❌ Seleziona un'immagine valida.";
    }

    header("Location: ../view/carica_look.php");
    exit();
}
?>