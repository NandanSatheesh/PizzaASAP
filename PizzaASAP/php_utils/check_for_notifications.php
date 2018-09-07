<?php
    //includere file dopo db_connection.php
    require_once 'db_connection.php';

    function check_for_notifications($email, $isAdmin) {
        $table = "";
        if($isAdmin == TRUE) {
            $table = "notifiche_admin";
        }else {
            $table = "notifiche_utente";
        }
        $query = "SELECT ID FROM ".$table." WHERE nuova=true AND email=?";
        $conn = connect();

        $stmt = $conn->prepare($query);

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id);
        $affected = 0;
        while($stmt->fetch()) {
            $affected = $stmt->num_rows;
            $query = "UPDATE TABLE " .$table. " SET nuova=false WHERE ID=".$id;
            $res = $conn->query($query);
        }
        $stmt->close();
        $conn->close();
        return $affected;
    }
?>
