<?php
session_start();
if (isset($_SESSION['username'])) {
    // Utente LOGGATO
    $link_ritorno = "..Admin/dashboard.php";
    $testo_ritorno = "← Torna alla Home";
} else {
    $link_ritorno = "../index.php";
    $testo_ritorno = "← Torna alla Home";
}

// 2. Richiamiamo la View! (Passandole implicitamente le variabili create qui sopra)
require_once '../view/premium.php';
?>
