<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "my_fitgram";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Errore di connessione: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>