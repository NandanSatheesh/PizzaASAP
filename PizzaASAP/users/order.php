<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once '../php_utils/db_connection.php';

    $success=0;

    $conn = connect();
    $stmt = $conn->prepare("INSERT INTO ordini (email, nome, cognome, tempo) VALUES (?,?,?,?)");
    $email = $_POST["email"];
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    $data = date("Y/m/d h:i:s");

    $stmt->bind_param("ssss", $email, $nome, $cognome, $data);
    $stmt->execute();
    if($stmt->affected_rows > 0) {
        $success = 1;
    }
    else {
        $success = -1;
    }

    $stmt->close();

    $admin_mail = "admin@pizzaasap.it";
    $testo = "L'utente " .$email. " ha appena terminato un ordine.";

    $notify_stmt = $conn->prepare("INSERT INTO notifiche_admin (email, tipo, testo, giorno, nuova) VALUES(?,1,?,?,1)");
    $notify_stmt->bind_param("sss", $admin_mail, $testo, $data);
    $notify_stmt->execute();
    $notify_stmt->close();

    $drop_cart_stmt = $conn->prepare("DELETE FROM carrelli WHERE email=?");
    $drop_cart_stmt->bind_param("s", $email);
    $drop_cart_stmt->execute();
    $drop_cart_stmt->close();
    $conn->close();

    $_SESSION["ok"] = $success;

    header("location: home.php");
?>
