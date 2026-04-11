<?php
function findUserByUsername($conn, $username) {
    $sql = "SELECT * FROM Utenti WHERE username = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return null;
    }
    mysqli_stmt_bind_param($stmt, "s", $username);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }

    return null;
}

?>
