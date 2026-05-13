<?php
class UtenteModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUtenteById($id) {
        $sql = "SELECT nome, username, bio FROM Utenti WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateProfilo($id, $nome, $username, $bio) {
        $sql = "UPDATE Utenti SET nome = ?, username = ?, bio = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssi", $nome, $username, $bio, $id);
            return $stmt->execute();
        }
        return false;
    }
}
?>