<?php
// Forza la visualizzazione degli errori su Altervista
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Verifica connessione
if (!file_exists('../config/connessione.php')) {
    die("Errore: Il file di connessione non esiste nel percorso specificato.");
}
require_once '../config/connessione.php';

// Verifica Model
if (!file_exists('../model/Look.php')) {
    die("Errore: Il file Model/Look.php non esiste nel percorso specificato.");
}
require_once '../model/Look.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$id_utente_loggato = $_SESSION['user_id'];

// Chiamata al Model (Assicurati che la funzione getArmadioUtente sia scritta identica nel model)
$miei_look = getArmadioUtente($conn, $id_utente_loggato);

// Verifica View
if (!file_exists('../view/armadio.php')) {
    die("Errore: Il file View/armadio.php non esiste.");
}
include '../view/armadio.php';
?>