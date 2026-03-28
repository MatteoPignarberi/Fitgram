<?php
session_start();
// Distrugge tutte le variabili di sessione
session_unset();
// Distrugge la sessione stessa
session_destroy();

// Ti rimanda alla pagina di login
header("Location: ../index.php");
exit();
?>
