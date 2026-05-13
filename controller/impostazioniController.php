<?php
session_start();
require_once __DIR__ . '/../config/connessione.php';
require_once __DIR__ . '/../model/UtenteModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$db = new UtenteModel($conn);
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_profilo') {
        $nome = trim($_POST['nome']);
        $username = trim($_POST['username']);
        $bio = trim($_POST['bio']);

        if ($db->updateProfilo($user_id, $nome, $username, $bio)) {
            $_SESSION['messaggio_successo'] = "Profilo aggiornato!";
            $_SESSION['username'] = $username;
        }

        header("Location: ../view/impostazioni.php");
        exit;
    }
}