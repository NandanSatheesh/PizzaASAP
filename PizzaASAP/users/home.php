<?php

    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION["user"])) {
        header("location: userlogin.php");
        die();
    }

    require_once '../php_utils/db_connection.php'; //connessione al db

    $conn = connect();
    $query = "select * from classiche";
    $classiche = $conn->query($query);

    $query = "select * from speciali";
    $speciali = $conn->query($query);

    $query = "select * from calzoni";
    $calzoni = $conn->query($query);

    $query = "select * from bibite";
    $bibite = $conn->query($query);

    //ciclo per icona carrello

    $email = $_SESSION["user"];
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
        <script src="../scripts/shopping.js"></script>
        <script src="../scripts/notifications_user.js"></script>
        <title>Home</title>
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
                        <a class="nav-link active" href="#top">Home  <span class="fas fa-home"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="info.php">Contatti  <span class="fas fa-address-book"></span></a>
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
                        <h1 class="display-1" id="top">Pizza ASAP!</h1>
                    </div>
                </div>
            </div>
            <div id="content"><?php
                if(isset($_SESSION["ok"])) {
                    if($_SESSION["ok"] == 1) {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
      Creazione ordine avvenuta con successo.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>';
                    } else {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      Creazione ordine fallita.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>';

                    }
                    unset($_SESSION["ok"]);
                }
            ?>
                <div class="container-fluid menu">
                    <div class="row">
                        <div class="col-md-4 offset-md-4 center-block text-center">
                            <h2 class="display-3" id="menu">Menu</h2>
                        </div>
                        <div class="col-md-4 navigation align-self-end">
                            <p>
                                <a href="#pizze-classiche">Pizze classiche</a> | <a href="#pizze-speciali">Pizze speciali</a> | <a href="#calzoni">Calzoni</a> | <a href="#bibite">Bibite</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="container-fluid classiche">
                    <div class="row align-items-start">
                        <div class="col-md-10 offset-md-1">
                            <div class="header-categoria" id="pizze-classiche">
                                <h3>Pizze classiche</h3>
                            </div>
                        </div>
                    </div>
                    <?php
                    $count = 0;
                    while($row = $classiche->fetch_assoc()) {
                        if($count % 2 === 0) {
                            echo '                    <div class="row newline bg-grey align-items-start">';
                        }
                        else {
                            echo '                    <div class="row newline align-items-start">';
                        };
                        echo '
                                                <div class="col-md-6 offset-md-2">
                                                    <div class="row">
                                                        <h4 class="prod-title">'.$row["nome"].'</h4>
                                                    </div>
                                                    <div class="row">
                                                        <p class="prod-description">
                                                            '.$row["descrizione"].'
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 align-self-center">
                                                    <p class="prezzo">
                                                        €'.$row["prezzo"].'
                                                    </p>
                                                </div>
                                                <div class="col-md-1 align-self-center">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropDownMenuButton-'.$row["ID"].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">+</button>
                                                    <div class="dropdown-menu cart-menu" aria-labelledby="dropDownMenuButton-'.$row["ID"].'">
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span>Quanti?</span>
                                                            </div>
                                                            <div class="col-md-11 mb-3">
                                                                <input class="form-control qty-input" type="number" title="prod-quantity" id="qty-'.$row["ID"].'" value="1">
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-center mb-4">
                                                            <button class="btn btn-success" type="button" id="ok-'.$row["ID"].'" onclick="addToCart('.$row["ID"].')">Conferma</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                    $count++;
                    }
                    ?>

                </div>
                <div class="container-fluid speciali">
                    <div class="row align-items-start">
                        <div class="col-md-10 offset-md-1">
                            <div class="header-categoria" id="pizze-speciali">
                                <h3>Pizze speciali</h3>
                            </div>
                        </div>
                    </div>
                    <?php
                    $count = 0;
                    while($row = $speciali->fetch_assoc()) {
                        if($count % 2 === 0) {
                            echo '                    <div class="row newline bg-grey align-items-start">';
                        }
                        else {
                            echo '                    <div class="row newline align-items-start">';
                        };
                        echo '
                                                <div class="col-md-6 offset-md-2">
                                                    <div class="row">
                                                        <h4 class="prod-title">'.$row["nome"].'</h4>
                                                    </div>
                                                    <div class="row">
                                                        <p class="prod-description">
                                                            '.$row["descrizione"].'
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 align-self-center">
                                                    <p class="prezzo">
                                                        €'.$row["prezzo"].'
                                                    </p>
                                                </div>
                                                <div class="col-md-1 align-self-center">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropDownMenuButton-'.$row["ID"].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">+</button>
                                                    <div class="dropdown-menu cart-menu" aria-labelledby="dropDownMenuButton-'.$row["ID"].'">
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span>Quanti?</span>
                                                            </div>
                                                            <div class="col-md-11 mb-3">
                                                                <input class="form-control qty-input" type="number" title="prod-quantity" id="qty-'.$row["ID"].'" value="1">
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-center mb-4">
                                                            <button class="btn btn-success" type="button" id="ok-'.$row["ID"].'" onclick="addToCart('.$row["ID"].')">Conferma</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                    $count++;
                    }
                    ?>
                </div>
                <div class="container-fluid classiche">
                    <div class="row align-items-start">
                        <div class="col-md-10 offset-md-1">
                            <div class="header-categoria" id="calzoni">
                                <h3>Calzoni</h3>
                            </div>
                        </div>
                    </div>
                    <?php
                    $count = 0;
                    while($row = $calzoni->fetch_assoc()) {
                        if($count % 2 === 0) {
                            echo '                    <div class="row newline bg-grey align-items-start">';
                        }
                        else {
                            echo '                    <div class="row newline align-items-start">';
                        };
                        echo '
                                                <div class="col-md-6 offset-md-2">
                                                    <div class="row">
                                                        <h4 class="prod-title">'.$row["nome"].'</h4>
                                                    </div>
                                                    <div class="row">
                                                        <p class="prod-description">
                                                            '.$row["descrizione"].'
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 align-self-center">
                                                    <p class="prezzo">
                                                        €'.$row["prezzo"].'
                                                    </p>
                                                </div>
                                                <div class="col-md-1 align-self-center">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropDownMenuButton-'.$row["ID"].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">+</button>
                                                    <div class="dropdown-menu cart-menu" aria-labelledby="dropDownMenuButton-'.$row["ID"].'">
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span>Quanti?</span>
                                                            </div>
                                                            <div class="col-md-11 mb-3">
                                                                <input class="form-control qty-input" type="number" title="prod-quantity" id="qty-'.$row["ID"].'" value="1">
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-center mb-4">
                                                            <button class="btn btn-success" type="button" id="ok-'.$row["ID"].'" onclick="addToCart('.$row["ID"].')">Conferma</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                    $count++;
                    }
                    ?>
                </div>
                <div class="container-fluid classiche">
                    <div class="row align-items-start">
                        <div class="col-md-10 offset-md-1">
                            <div class="header-categoria" id="bibite">
                                <h3>Bibite</h3>
                            </div>
                        </div>
                    </div>
                    <?php
                    $count = 0;
                    while($row = $bibite->fetch_assoc()) {
                        if($count % 2 === 0) {
                            echo '                    <div class="row newline bg-grey align-items-start">';
                        }
                        else {
                            echo '                    <div class="row newline align-items-start">';
                        };
                        echo '
                                                <div class="col-md-6 offset-md-2">
                                                    <div class="row">
                                                        <h4 class="prod-title">'.$row["nome"].'</h4>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 align-self-center">
                                                    <p class="prezzo">
                                                        €'.$row["prezzo"].'
                                                    </p>
                                                </div>
                                                <div class="col-md-1 align-self-center">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropDownMenuButton-'.$row["ID"].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">+</button>
                                                    <div class="dropdown-menu cart-menu" aria-labelledby="dropDownMenuButton-'.$row["ID"].'">
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span>Quanti?</span>
                                                            </div>
                                                            <div class="col-md-11 mb-3">
                                                                <input class="form-control qty-input" type="number" title="prod-quantity" id="qty-'.$row["ID"].'" value="1">
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-center mb-4">
                                                            <button class="btn btn-success" type="button" id="ok-'.$row["ID"].'" onclick="addToCart('.$row["ID"].')">Conferma</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                    $count++;
                    }
                    ?>
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
    </body>
</html>
