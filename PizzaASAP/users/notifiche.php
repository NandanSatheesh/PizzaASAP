<?php

    require_once '../php_utils/db_connection.php';

    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION["user"])) {
        header("location: ../users/userlogin.php");
        die();
    }

    $conn = connect();

    if(isset($_POST["cancella"])){
      $delete= $conn->prepare("delete from notifiche_utente where email=?");
      $delete->bind_param("s", $_SESSION["user"]);
      $delete->execute();
      $delete->close();
      $_POST= array();
    }

    $stmt = $conn->prepare("SELECT * FROM notifiche_utente WHERE email=? ORDER BY giorno DESC");
    $email = $_SESSION["user"];
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

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
        <link rel="stylesheet" type="text/css" href="../styles/notifiche.css">
        <script src="../scripts/notifications_user.js"></script>

        <title>Notifiche</title>
    </head>
    <body>
        <nav class="navbar fixed-top navbar-dark bg-dark navbar-expand-md">
            <a class="navbar-brand" href="#">PizzaASAP!</a>
            <div class="d-flex flex-row order-2 order-md-2 justify-content-end">
                <ul class="navbar-nav flex-row">
                    <li class="nav-item ml-auto">
                        <input type="hidden" id="cart-field" name="cart-field" value="<?=$_SESSION["user"]?>">
                        <a class="btn btn-link nav-link" href="checkout.php" id="cart-link">Carrello <span class="fas fa-shopping-cart" id="cart-icon"></span><span class="<?=$hidden?> badge badge-pill badge-danger" id="cart-count"><?= $cart_items ?></span></a>
                    </li>
                </ul>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse order-3 order-md-3" id="navbarNavDropdown">
                <ul class="navbar-nav ml-auto">
                    <li>
                        <a class="nav-link" href="home.php">Home  <span class="fas fa-home"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="info.php">Contatti  <span class="fas fa-address-book"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="impostazioni.php">Impostazioni  <span class="fas fa-wrench"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#top">Notifiche  <span class="fas fa-bell" id="bell-icon"></span><span class="hidden badge badge-pill badge-danger" id="bell-count">0</span></a>
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
                        <h1 class="display-1" id="top">Notifiche</h1>
                    </div>
                </div>
            </div>
            <div class="content container">
                <div class="container-fluid bg-grey rounded">
                  <form action= "notifiche.php" method= "post">
                    <h2>Notifiche</h2>
                    <hr class="mb-4">
                        <div class="notifications-container">
                        <?php
                            if($res->num_rows == 0) {
                                echo '
                                <div class="row text-center" id="no-notifications">
                                    <div class="col-md-12 mb-4">
                                        <span class="text-muted">Non ci sono notifiche :(</span>
                                    </div>
                                </div>';
                            }
                            else {
                                while($row = $res->fetch_assoc()) {
                                    $query = "UPDATE notifiche_utente SET nuova=false WHERE ID=".$row["ID"];
                                    $conn->query($query);
                                    if(strcmp($row["tipo"], "1") == 0) {
                                        echo '<div class="row bg-lightgreen notification-row">
                                                <div class="col-md-1 align-self-center icon-col">
                                                    <span class="fas fa-check"></span>';
                                    }
                                    else if (strcmp($row["tipo"], "2") == 0) {
                                        echo '<div class="row bg-lightyellow notification-row">
                                                <div class="col-md-1 align-self-center icon-col">
                                                    <span class="fas fa-comment-alt"></span>';
                                    }
                                    echo
                                    '
                                        </div>
                                        <div class="col-md-9 align-self-center">
                                            <span class="notification-text">'.$row["testo"].'</span>
                                        </div>
                                        <div class="col-md-2 align-self-center">
                                            <span class="notification-date">'.$row["giorno"].'</span>
                                        </div>
                                    </div>';
                                }
                            }
                            $conn->close();
                        ?>
                    </div>
                </div>
                <div class= "row text-center">
                  <div class= "col-md-12 align-self-center">
                    <input type= "submit" value= "Cancella notifiche" class= "btn btn-danger">
                  </div>
                </div>
            </div>
            <input type= "hidden" id= "cancella" value= "cancella" name= "cancella">
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
    </body>
</html>
