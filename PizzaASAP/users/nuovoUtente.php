<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once '../php_utils/db_connection.php';

    $nome = $cognome = $indirizzo = $email = $pwd = $err = "";

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['nome'])) {
            $nome = test_input($_POST['nome']);

            if ( empty($nome) ) {
                $err .= "Il campo nome deve essere compilato\n";
            }
            elseif ( !preg_match("/^[a-zA-Z ]{1,32}$/",$nome) ) {
                $err .= "Il nome deve essere lungo da 1 a 32 caratteri, ammessi lettere e spazi\n";
            }
        }

        if (isset($_POST['cognome'])) {

            $cognome = test_input($_POST["cognome"]);

            if( empty($cognome) ) {
                $err .= "Il campo cognome deve essere compilato\n";
            }
            elseif ( !preg_match("/^[a-zA-Z ']{1,32}$/", $cognome) ) {
                $err .= "Lunghezza cognome tra 1 e 32 caratteri\n";
            }
        }

        if(isset($_POST['indirizzo'])) {

            $indirizzo = test_input($_POST["indirizzo"]);

            if (empty($indirizzo)) {
                $err .= "Il campo indirizzo deve essere compilato\n";
            }
            elseif (!strlen($indirizzo) > 128) {
                $err .= "Indirizzo lungo al massimo 128 caratteri\n";
            }
        }

        if (isset($_POST['email'])) {

            $email = test_input($_POST["email"]);

            if(empty($email)) {
                $err .= "Il campo email deve essere compilato\n";
            } else {
                $email = strtolower($email);

                if(!preg_match("/(?!.*\.\.)(^[^\.][^@\s]+@[^@\s]+\.[^@\s\.]+$)/", $email)) {
                    $err .= "La mail deve essere più corta di 128 caratteri. Vengono accettati caratteri speciali a patto che venga rispettato il formato di esempio username@dominio.organizzazione\n";
                }
            }
        }

        if (isset($_POST['password'])) {

            $pwd = test_input($_POST["password"]);

            if(empty($pwd)) {
                $err .= "Il campo password deve essere compilato\n";
            } elseif (preg_match("/^(.{0,7}|[^0-9]*|[^A-Z]*|[^a-z]*|[a-zA-Z0-9]*)$/", $pwd)) {
                $err .= "La password deve essere almeno di 8 caratteri e contenere almeno un lettera minuscola, una lettera maiuscola, un numero e un carattere speciale\n";
            }

        }

        if ($err == "") {
            $conn = connect();

            $stmt = $conn->prepare("insert into utenti values (?,?,?,?,?)");
            if(!$stmt) {
                $err .= "Non sono riuscito a preparare lo statement";
                exit();
            }
            $stmt->bind_param("sssss",$email,$nome,$cognome,$pwd,$indirizzo);

            $stmt->execute();
            $stmt->close();

            $stmt_1 = $conn->prepare("INSERT INTO pagamento (email) value (?)");
            $stmt_1->bind_param("s", $email);
            $stmt_1->execute();
            $stmt_1->close();

            $conn->close();

            //controllo se l'inserimento è andato a buon fine?

            $_SESSION['user'] = $email;

            header("location: home.php");
            die();
        }
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="PS">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="Ordina cibo a domicilio nella zona di Cesena">
        <meta name="keywords" content="cibo,domicilio,ordina,online,pizza,pasta">
        <link rel="icon" href="../favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
            integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB"
            crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
        <script src="../scripts/hideFadeElements.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
            integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
            crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"
            integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
            crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="../styles/customBgJumbo.css">
        <link rel="stylesheet" type="text/css" href="../styles/registrazione.css">
        <script src="../scripts/registrationUtils.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <title>Registrati</title>
    </head>
    <body>
        <div class="bg">
            <div class="bg_overlay toHide">
                <?php
                    if ($err != "") {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  '.$err.'
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
                    }
                 ?>
                <form action="nuovoUtente.php" method="post">
                    <div class="container-fluid">
                        <div class="card card-block toHide" id="signUpCard">
                            <div class="card-title">
                                <h3 class="card-header">Nuovo utente</h3>
                            </div>
                            <div class="text-container container-fluid text-center">
                                <p>
                                    Tutti i campi sono obbligatori.
                                </p>
                            </div>
                            <div class="card-content">
                                <div class="container-fluid form-container">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="nome">Nome</label>
                                                <input type="text" name="nome" class="form-control" id="nome" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="cognome">Cognome</label>
                                                <input type="text" name="cognome" class="form-control" id="cognome" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="indirizzo">Indirizzo</label>
                                                <input type="text" name="indirizzo" class="form-control" id="indirizzo"  value="Nuovo Campus Universitario di Cesena (FC), 47522" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-left-border">
                                            <div class="form-group">
                                                <label for="email">E-mail</label>
                                                <input type="email" name="email" class="form-control" id="email" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="pwd">Password</label>
                                                <div class="input-group-append">
                                                    <input type="password" name="password" class="form-control" id="pwd" value="" pattern=".{8,}" required title="Almeno 8 caratteri"><span class="fa fa-check" style="color:green;" aria-hidden="true"></span>
                                                    </div>
                                                </div>
                                                <small id="passwordHelpText" class="form-text text-muted">Almeno 8 caratteri, almeno una lettera maiuscola, almeno un simbolo e almeno un numero.</small>
                                                <div class="form-group">
                                                    <label for="pwd_ok">Conferma Password</label>
                                                    <div class="input-group-append">
                                                        <input type="password" name="password_ok" class="form-control" id="pwd_ok" value="" pattern=".{8,}" required title="Almeno 8 caratteri"><span class="fa fa-check" style="color:green;" aria-hidden="true"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <input type="submit" class="btn btn-primary" value="Registrati" id="submit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
