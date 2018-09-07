<?php
    require_once '../php_utils/db_connection.php';

    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $conn = connect();

    $email = $_SESSION["user"];
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
        <title>Contatti</title>
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
                        <a class="nav-link active" href="#top">Contatti  <span class="fas fa-address-book"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="impostazioni.php">Impostazioni  <span class="fas fa-wrench"></span></a>
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
                        <h1 class="display-1" id="top">Contatti</h1>
                    </div>
                </div>
                <div class="container-fluid bg-grey" id="content">
                    <div class="row align-item-center">
                        <div class="col-md-12 align-self-center">
                            <p class="info owner">
                                <p class="h5 text-center">Pizza ASAP! <span class="text-muted">di Pinco Pallino</span></p>
                            </p>
                        </div>
                    </div>
                    <div class="row newline align-item-center">
                        <div class="col-md-12 align-self-center">
                            <p class="h6 text-center">
                                <span class="fas fa-envelope" style="color:#343a40"></span>
                                info_pizzaasap@pizza.com
                            </p>
                        </div>
                    </div>
                    <div class="row newline align-item-center">
                        <div class="col-md-12 align-self-center">
                            <p class="h6 text-center">
                                <span class="fas fa-phone" style="color:#343a40"></span>
                                +39 0544 00000
                            </p>
                        </div>
                    </div>
                    <div class="row newline align-item-center">
                        <div class="col-md-12 align-self-center">
                            <p class="h6 text-center">
                                 <span class="fas fa-map-pin" style="color:#343a40"></span>
                                 Una via di Cesena &nbsp;
                                 n. 0 </br>
                                 47521</br>
                                 Cesena (FC)
                            </p>
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
