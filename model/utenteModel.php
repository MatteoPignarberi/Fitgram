<?php
class UtenteModel {
    private $conn;

    public function __construct($connessione_db) {
        $this->conn = $connessione_db;
    }

    // Funzione per ottenere i dati attuali dell'utente
    public function getUtenteById($id) {
        $sql = "SELECT username, nome, bio FROM Utenti WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // Funzione per aggiornare il profilo
    public function updateProfilo($id, $nome, $username, $bio) {
        $sql = "UPDATE Utenti SET nome = ?, username = ?, bio = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssi", $nome, $username, $bio, $id);
            if (mysqli_stmt_execute($stmt)) {
                return true; // Aggiornamento riuscito
            }
        }
        return false; // Errore
    }
}
?>