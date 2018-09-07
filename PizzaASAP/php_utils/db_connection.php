<?php
    function connect() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "pizza_asap";

        //connessione
        $conn = new mysqli($servername, $username, $password, $database); //utente del db

        if($conn->connect_errno) {
            echo "Connessione al database MySQL fallita: [" .$conn->connect_errno. "] - " .$conn->connect_error;
        }

        return $conn;
    }
?>
