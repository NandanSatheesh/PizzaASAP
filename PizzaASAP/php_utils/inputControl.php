<?php
    require_once 'db_connection.php';

    $conn = connect();

    $query="SELECT nome,descrizione,prezzo FROM menu WHERE ID=".$_POST["id"];
    $res = $conn->query($query);
    $row = $res->fetch_assoc();
    $result_string = $row["nome"]."_";
    $result_string .= $row["prezzo"]."_";
    $result_string .= $row["descrizione"];
    echo $result_string;
    $conn->close();
 ?>
