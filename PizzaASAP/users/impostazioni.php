<?php

    require_once '../php_utils/db_connection.php';

    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $email = $_SESSION["user"];
    $conn = connect();

    $stmt = $conn->prepare("SELECT nome,cognome,password FROM utenti WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($nome_db, $cognome_db, $pw);
    $stmt->store_result();
    $stmt->fetch();

    $success = 0;

    if(isset($_POST["new-pw"])) {
        if(strcmp($_POST["old-pw"], $pw) == 0) {
            $stmt_2 = $conn->prepare("UPDATE utenti SET password=? WHERE email=?");
            $stmt_2->bind_param("ss", $_POST["new-pw"], $email);
            $stmt_2->execute();
            if($stmt_2->affected_rows > 0) {
                $success = 1;
            }
            else {
                $success = -1;
            }
            $stmt_2->close();
            $_POST = array();
        }
    } else if (isset($_POST["cc-num"])) {
        $cc_nome = $_POST["nome"];
        $cc_cognome = $_POST["cognome"];
        $cc_num = $_POST["cc-num"];
        $exp = $_POST["exp"];
        $cvv = $_POST["cvv"];
        $stmt_3 = $conn->prepare("SELECT numero_carta,scadenza,cvv FROM pagamento WHERE email = ?");
        $stmt_3->bind_param("s", $email);
        $stmt_3->execute();
        $stmt_3->store_result();
        $affected = $stmt_3->num_rows;
        $stmt_3->close();
        if($affected > 0) {
            $stmt_4 = $conn->prepare("UPDATE pagamento SET numero_carta=?,scadenza=?,cvv=? WHERE email=?");
            $stmt_4->bind_param("ssss", $cc_num, $exp, $cvv, $email);
            $stmt_4->execute();
            $stmt_4->store_result();
            if($stmt_4->affected_rows > 0) {
                $success = 1;
            }
            else {
                $success = -1;
            }
            $stmt_4->close();
            if(strcmp($cc_nome, $nome_db) != 0 && strcmp($cc_cognome, $cognome_db) != 0) {
                $stmt_5 = $conn->prepare("UPDATE utenti SET nome=?,cognome=? WHERE email=?");
                $stmt_5->bind_param("sss", $cc_nome, $cc_cognome, $email);
                $stmt_5->execute();
                if($stmt_5->affected_rows > 0) {
                    $success = 1;
                }
                else {
                    $success = -1;
                }
                $stmt_5->close();
            }
        }
        else {
            $success = -1;
        }
        $_POST = array();
    }

    $stmt = $conn->prepare("SELECT nome,cognome,password FROM utenti WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($nome_db, $cognome_db, $pw);
    $stmt->store_result();
    $stmt->fetch();

    $nome_cognome = $nome_db." ".$cognome_db;
    $stmt->close();

    $stmt_1 = $conn->prepare("SELECT numero_carta, scadenza, cvv FROM pagamento WHERE email=?");
    $stmt_1->bind_param("s", $email);
    $stmt_1->execute();
    $stmt_1->bind_result($cc_num_db, $exp_db, $cvv_db);
    $stmt_1->store_result();
    $stmt_1->fetch();

    $stmt_1->close();

    //ciclo per icona carrello

    $cart_items = 0;
    $hidden = "hidden";
    $cart_stmt = $conn->prepare("SELECT qta FROM carrelli WHERE email=?");
    $cart_stmt->bind_param("s", $email);
    $cart_stmt->execute();
    $cart_stmt->store_result();
    $cart_stmt->bind_result($qta);
    if($cart_stmt->num_rows > 0) {
        $hidden = "";
        while($cart_stmt->fetch()) {
            $cart_items += $qta;
        }
    }
    $cart_stmt->close();

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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../styles/customBgJumbo.css">
        <link rel="stylesheet" type="text/css" href="../styles/home.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <script src="../scripts/smoothScrolling.js"></script>
        <link rel="stylesheet" type="text/css" href="../styles/account.css">
        <script src="../scripts/notifications_user.js"></script>
        <title>Impostazioni</title>
    </head>
    <body>
        <nav class="navbar fixed-top navbar-dark bg-dark navbar-expand-md">
            <a class="navbar-brand" href="#">PizzaASAP!</a>
            <div class="d-flex flex-row order-2 order-md-2 justify-content-end">
                <ul class="navbar-nav flex-row">
                    <li class="nav-item">
                        <input type="hidden" id="cart-field" name="cart-field" value="<?=$_SESSION["user"]?>">
                        <a class="btn btn-link nav-link" href="checkout.php" id="cart-link">Carrello <span class="fas fa-shopping-cart" id="cart-icon"></span><span class="<?=$hidden?> badge badge-pill badge-danger" id="cart-count"><?= $cart_items ?></span></a>
                    </li>
                </ul>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse order-3 order-lg-2" id="navbarNavDropdown">
                <ul class="navbar-nav ml-auto">
                    <li>
                        <a class="nav-link" href="home.php">Home  <span class="fas fa-home"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="info.php">Contatti  <span class="fas fa-address-book"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#top">Impostazioni  <span class="fas fa-wrench"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notifiche.php">Notifiche  <span class="fas fa-bell" id="bell-icon"></span><span class="hidden badge badge-pill badge-danger" id="bell-count">0</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../php_utils/logout.php">Logout  <span class="fas fa-sign-out-alt"></span></a>
                    </li>
                </ul>
            </div>
        </nav>
        <div id="wrapper">
            <div id="header">
                <div class="bg">
                    <div class="jumbotron jumbotron-fluid">
                        <h1 class="display-1" id="top">Impostazioni</h1>
                    </div>
                </div>
            </div>
            <div class="content">
                <?php
                if($success == 1) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  Dati inseriti correttamente.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
                }
                else if($success == -1) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  Inserimento dati fallito.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
                }
                ?>
                    <ul class="nav nav-pills mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#info-account">Informazioni account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#info-billing">Dati fatturazione</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <div id="info-account" class="container tab-pane active bg-grey rounded">
                            <h2>Informazioni account</h2>
                            <p class="text-muted">
                                Qui puoi cambiare la tua password.
                            </p>
                            <div class="container-fluid">
                                <form id="info-account-form" action="impostazioni.php" method="post">
                                    <div class="row newline">
                                        <div class="col-md-4">
                                            <label for="email">E-mail</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="email" name="new-email" class="form-control" id="email" value="<?= $email ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="row newline">
                                        <div class="col-md-4">
                                            <label for="old-pw">Vecchia password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="password" name="old-pw" class="form-control" id="old-pw" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row newline">
                                        <div class="col-md-4">
                                            <label for="new-pw">Nuova password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="password" name="new-pw" class="form-control" id="new-pw">
                                        </div>
                                    </div>
                                    <div class="row button-row text-center">
                                        <div class="col-md-12 align-self-center">
                                            <input type="submit" class="btn btn-primary" value="Conferma">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="info-billing" class="container bg-grey rounded tab-pane fade">
                            <h3>Informazioni di fatturazione</h3>
                            <p class="text-muted">
                                Qui puoi inserire le informazioni di fatturazione, in modo da non doverle riscrivere ogni volta.
                            </p>
                            <form id="info-billing-form" action="impostazioni.php" method="post">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="firstName">Nome</label>
                                        <input type="text" name="nome" class="form-control" id="firstName" placeholder="" value="<?= $nome_db ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lastName">Cognome</label>
                                        <input type="text"  name="cognome" class="form-control" id="lastName" placeholder="" value="<?= $cognome_db ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 mb-3">
                                        <label for="address">Indirizzo</label>
                                        <input type="text" class="form-control" id="address" placeholder="" value="Nuovo Campus Universitario di Cesena (FC), 47522" disabled>
                                    </div>
                                </div>
                                <hr class="md-4" />
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cc-name">Nome sulla carta</label>
                                        <input type="text" class="form-control" name="cc-nome" id="cc-name" placeholder="" value="<?= $nome_cognome ?>" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cc-number">Numero della carta</label>
                                        <input type="text" class="form-control" name="cc-num" id="cc-number" placeholder="" value="<?=$cc_num_db?>" required pattern="[0-9]{16}" title="Carta di credito. 16 cifre senza spazi.">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="cc-expiration">Scadenza</label>
                                        <input type="text" class="form-control" name="exp" id="cc-expiration" placeholder="" value="<?=$exp_db?>" required pattern="(?:0[1-9]|1[0-2])/[0-9]{2}" title="Data di scadenza, nel formato MM/YY">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cc-cvv">CVV</label>
                                        <input type="number" class="form-control" name="cvv" id="cc-cvv" placeholder="" value="<?=$cvv_db?>" required pattern="[0-9]{3}" title="Codice segreto della carta, formato XYZ">
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-md-12 align-self-center mb-4">
                                        <input type="submit" class="btn btn-primary" id="ok" value="Conferma">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="footer">
                    <footer class="container-fluid text-center">
                        <div class="row align-item-center footer-row">
                            <div class="col-md-12 align-self-center">
                                <a href="#top" title="To Top">
                                    <span class="fas fa-chevron-up" style="color:white"></span>
                                </a>
                            </div>
                        </div>
                        <div class="row align-item-center">
                            <div class="col-md-12 align-self-center">
                                <p class="copyright">
                                    © PizzaASAP! Inc., 2018
                                </p>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>
