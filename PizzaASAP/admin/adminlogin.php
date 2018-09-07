<?php
    require_once '../php_utils/db_connection.php';
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_POST["email"])) {
        $conn = connect();
        $stmt = $conn->prepare("select email, password from admin where email=?");
        $login_email = $_POST["email"];
        $stmt->bind_param("s", $login_email);
        $stmt->execute();
        $stmt->bind_result($r_email, $r_password);
        $stmt->store_result();
        $stmt->fetch();

        if($stmt->num_rows > 0) {
            if(strcmp($_POST["password"], $r_password) === 0) {
                $_SESSION['admin'] = $login_email;
                header("location: adminWelcome.php");
                $stmt->close();
                $conn->close();
                die();
            }
        }
        else {
            ?><script type="text/javascript">
                alert("E-mail o password sconosciuti");
            </script>
            <?php
            $stmt->close();
            $conn->close();
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
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
            integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
            crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"
            integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
            crossorigin="anonymous"></script>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="../styles/customBgJumbo.css">
    </head>
    <body>
        <div class="bg">
            <div class="jumbotron jumbotron-fluid">
                <div class="container-fluid">
                    <div class="row">
                        <h1 class="display-1">Pizza ASAP!</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="display-4">Consegne a domicilio a Cesena</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid" id="loginCardContainer">
                <div class="card card-block">
                    <div class="card-title">
                        <h3 class="card-header card-header-portrait">Staff login</h3><h3 class="card-header card-header-landscape">Pizza ASAP!</h3>
                    </div>
                    <div class="container-fluid">
                        <div class="row text-center" id="alignedRow">
                            <div class="col-md-8 mb-4 offset-md-2">
                                <form action="adminlogin.php" method="post">
                                    <div class="form-group">
                                        <label for="email">E-mail:</label>
                                        <input type="email" name="email" class="form-control" id="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password:</label>
                                        <input type="password" name="password" class="form-control" id="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Accedi</button>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </body>
</html>
