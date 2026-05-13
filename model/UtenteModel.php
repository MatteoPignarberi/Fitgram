<?php
class UtenteModel {
    private $conn;

    public function __construct($connessione) {
        $this->conn = $connessione;
    }

    public function getUtenteById($id) {
        $stmt = $this->conn->prepare("SELECT nome, username, bio FROM Utenti WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateProfilo($id, $nome, $username, $bio) {
        $stmt = $this->conn->prepare("UPDATE Utenti SET nome = ?, username = ?, bio = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $username, $bio, $id);
        return $stmt->execute();
    }
    public function updateFoto($id, $nomeFoto) {
        $stmt = $this->conn->prepare("UPDATE Utenti SET foto = ? WHERE id = ?");
        $stmt->bind_param("si", $nomeFoto, $id);
        return $stmt->execute();
    }
}