<?php
session_start();

// Se l'utente non è loggato o manca l'ID da seguire, lo blocco
if (!isset($_SESSION['username']) || !isset($_POST['id_da_seguire'])) {
    header("Location: index.php");
    exit();
}

$username_loggato = $_SESSION['username'];
$id_da_seguire = $_POST['id_da_seguire'];

$conn = mysqli_connect("localhost", "root", "", "my_fitgram");

if ($conn) {
    // Trovo l'ID di chi sta cliccando (l'utente loggato)
    $sql_user = "SELECT id FROM Utenti WHERE username = ?";
    $stmt_u = mysqli_prepare($conn, $sql_user);
    mysqli_stmt_bind_param($stmt_u, "s", $username_loggato);
    mysqli_stmt_execute($stmt_u);
    $res_u = mysqli_stmt_get_result($stmt_u);

    if ($row_u = mysqli_fetch_assoc($res_u)) {
        $mio_id = $row_u['id'];

        // Regola d'oro: non posso seguire me stesso!
        if ($mio_id != $id_da_seguire) {

            // Controllo se lo sto già seguendo
            $check_sql = "SELECT idFollower FROM Followers WHERE idFollower = ? AND idSeguito = ?";
            $stmt_check = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($stmt_check, "ii", $mio_id, $id_da_seguire);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                // Se lo seguo già -> SMETTI DI SEGUIRE
                $del_sql = "DELETE FROM Followers WHERE idFollower = ? AND idSeguito = ?";
                $stmt_del = mysqli_prepare($conn, $del_sql);
                mysqli_stmt_bind_param($stmt_del, "ii", $mio_id, $id_da_seguire);
                mysqli_stmt_execute($stmt_del);
            } else {
                // Se non lo seguo -> INIZIA A SEGUIRE
                $ins_sql = "INSERT INTO Followers (idFollower, idSeguito) VALUES (?, ?)";
                $stmt_ins = mysqli_prepare($conn, $ins_sql);
                mysqli_stmt_bind_param($stmt_ins, "ii", $mio_id, $id_da_seguire);
                mysqli_stmt_execute($stmt_ins);
            }
        }
    }
    mysqli_close($conn);
}

// Lavoro finito! Ritorno istantaneamente alla pagina in cui ero
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
