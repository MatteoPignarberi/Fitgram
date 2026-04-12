<?php
session_start();
require_once '../config/connessione.php';
require_once '../model/Look.php'; // Includiamo il nuovo magazziniere dei Look!

// Protezione aggiuntiva nel controller
if (!isset($_SESSION['username'])) {
    header("Location: ../view/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descrizione = $_POST['descrizione'] ?? '';
    $username = $_SESSION['username'];

    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {

        $fileTmpPath = $_FILES['immagine']['tmp_name'];
        $fileName = $_FILES['immagine']['name'];

        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $estensioni_permesse = array('jpg', 'jpeg', 'png', 'gif', 'webp');

        if (in_array($fileExtension, $estensioni_permesse)) {

            // Generiamo il nome unico
            $nuovoNomeFile = md5(time() . $fileName) . 'Fitgram.' . $fileExtension;

            $cartellaUpload = '../resources/Images/';

            if (!is_dir($cartellaUpload)) {
                mkdir($cartellaUpload, 0777, true);
            }

            $percorso_destinazione = $cartellaUpload . $nuovoNomeFile;

            // Spostiamo il file
            if(move_uploaded_file($fileTmpPath, $percorso_destinazione)) {

                // SALVATAGGIO NEL DATABASE TRAMITE MODEL
                $inserito = createLook($conn, $descrizione, $nuovoNomeFile, $username);

                if ($inserito) {
                    $_SESSION['msg_look'] = "<div class='msg success'>Look pubblicato con successo! <a href='../index.php'>Torna alla Home</a></div>";
                } else {
                    $_SESSION['msg_look'] = "<div class='msg error'>Errore nel salvataggio sul database.</div>";
                }

            } else {
                $_SESSION['msg_look'] = "<div class='msg error'>Errore nello spostamento del file. Controlla i permessi della cartella.</div>";
            }
        } else {
            $_SESSION['msg_look'] = "<div class='msg error'>Formato non valido! Carica solo JPG, PNG, GIF o WEBP.</div>";
        }
    } else {
        $_SESSION['msg_look'] = "<div class='msg error'>Nessun file selezionato o errore nel caricamento.</div>";
    }

    // Finito il lavoro, rimandiamo l'utente alla View per fargli vedere l'esito
    header("Location: ../view/carica_look.php");
    exit();
}
?>
