<?php
    require_once 'db_connection.php';

    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $conn = connect();

    $stmt_select = $conn->prepare("DELETE FROM carrelli WHERE email=? and id_oggetto=?");
    $email = $_POST["email"];
    $id = intval($_POST["id"]);
    echo $email;
    echo $id;

    $stmt_select->bind_param("si", $email, $id);
    $stmt_select->execute();

?>
