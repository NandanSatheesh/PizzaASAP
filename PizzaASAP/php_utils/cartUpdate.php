<?php
    require_once 'db_connection.php';

    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $conn = connect();

    $stmt_select = $conn->prepare("SELECT * FROM carrelli WHERE email=? and id_oggetto=?");
    $email = $_POST["email"];
    $id = intval($_POST["id"]);
    $qty = $_POST["qty"];

    $stmt_select->bind_param("si", $email, $id);
    $stmt_select->execute();
    $stmt_select->bind_result($r_mail,$r_id, $r_qty);
    $stmt_select->store_result();
    $stmt_select->fetch();

    if($stmt_select->num_rows > 0) { //se esiste gia un record della stessa persona per lo stesso oggetto, aggiorno la riga
        //calcolo la nuova quantita
        $tot = intval($qty) + intval($r_qty);

        $stmt_update = $conn->prepare("UPDATE carrelli SET qta=? where email=? and id_oggetto=?");
        $stmt_update->bind_param("isi", $tot,$email,$id);
        $stmt_update->execute();

        $stmt_update->close();

    }
    else {
        $stmt_insert = $conn->prepare("INSERT INTO carrelli VALUES (?,?,?)"); //prepared statment come protezione per sql injection

        $stmt_insert->bind_param("sii", $email, $id, $qty); //(formato, variabili)
        $stmt_insert->execute();

        $stmt_insert->close();
    }


    $conn->close();

?>
