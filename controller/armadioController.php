<?php
session_start();
require_once '../config/connessione.php';
require_once '../model/Look.php';

// Verifichiamo che l'utente sia loggato
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$id_utente_loggato = $_SESSION['user_id'];

// Recuperiamo i dati dal Model usando l'ID
$miei_look = getArmadioUtente($conn, $id_utente_loggato);

// Passiamo i dati alla View
include '../view/armadio.php';
?>