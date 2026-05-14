<?php
session_start();
require_once '../config/connessione.php';
require_once '../model/Look.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$id_utente_loggato = $_SESSION['user_id'];

// Questa funzione deve essere nel file model/Look.php
$miei_look = getArmadioUtente($conn, $id_utente_loggato);

// Carica la pagina vera e propria
include '../view/armadio.php';
?>