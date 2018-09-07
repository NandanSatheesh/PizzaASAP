<?php
    require_once '../php_utils/db_connection.php';
    require_once '../php_utils/menu_item.php';

    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION["user"])) {
        header("location: userlogin.php");
        die();
    }

    $conn = connect();

    $stmt = $conn->prepare("SELECT id_oggetto, qta FROM carrelli WHERE email=?");
    $email = $_SESSION["user"];

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    $cart = array();
    $pizza_data = array();
    $i = 0;
    $tot_qty = 0;

    //riempio struttura dati carrello
    while($row = $res->fetch_assoc()) {
        $cart[$i]["ID"] = $row["id_oggetto"];
        $cart[$i]["qta"] = $row["qta"];
        $tot_qty += $row["qta"];
        $i++;
    }

    $stmt->close();

    $i = 0;
    //riempio struttura dati con i dati delle pizze presi dal db
    foreach ($cart as $cart_item) {
        $query = "SELECT * FROM menu WHERE ID=" .$cart_item["ID"];
        $res = $conn->query($query);
        $pizza_data[$i] = $res->fetch_object('pizza');
        $i++;
    }

    $nome;
    $cognome;

    $cc_name = "";
    $cc_num = "";
    $exp = "";
    $cvv = "";

    $user_data_stmt = $conn->prepare("SELECT nome, cognome FROM utenti WHERE email=?");
    $user_data_stmt->bind_param("s", $email);
    $user_data_stmt->execute();
    $user_data_stmt->bind_result($nome, $cognome);
    $user_data_stmt->store_result();
    $user_data_stmt->fetch();
    $user_data_stmt->close();
    $nome_cognome = $nome." ".$cognome;

    $pay_stmt = $conn->prepare("SELECT numero_carta,scadenza,cvv FROM pagamento WHERE email=?"); //prende i dati della carta di credito
    $pay_stmt->bind_param("s", $email);
    $pay_stmt->execute();
    $pay_stmt->store_result();

    if($pay_stmt->num_rows > 0) {
        $pay_stmt->bind_result($cc_num, $exp, $cvv);
        $pay_stmt->fetch();
    }
    $pay_stmt->close();

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
        <script src="../scripts/checkout.js"></script>
        <script src="../scripts/notifications_user.js"></script>

        <title>Checkout</title>
    </head>
    <body>
        <nav class="navbar fixed-top navbar-dark bg-dark navbar-expand-md">
            <a class="navbar-brand" href="#">PizzaASAP!</a>
            <div class="d-flex flex-row order-2 order-md-2 justify-content-end">
                <ul class="navbar-nav flex-row">
                    <li class="nav-item">
                        <input type="hidden" id="cart-field" name="cart-field" value="<?=$_SESSION["user"]?>">
                        <a class="btn btn-link nav-link active" href="#top" id="cart-link">Carrello <span class="fas fa-shopping-cart" id="cart-icon"></span><span class="<?=$hidden?> badge badge-pill badge-danger" id="cart-count"><?= $cart_items ?></span></a>
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
                        <h1 class="display-1" id="top">Checkout</h1>
                    </div>
                </div>
            </div>
            <div id="content">
                <div class="container-fluid bg-grey">
                    <div class="row">
                        <div class="col-md-4 order-md-2 mb-4">
                            <h2 class="d-flex justify-content-between align-items-center mb-3 display-5">
                                <span>Carrello</span>
                                <span class="badge badge-secondary badge-pill" id="cart-items-count"><?= $tot_qty;?></span>
                                <input type="hidden" id="cart-rows" value="<?= count($cart) ?>">
                            </h2>
                            <div class="container-fluid">
                                <ul class="list-group mb-3">
                                    <?php
                                        for ($i = 0; $i < count($cart); $i++) {
                                            echo '<li class="list-group-item d-flex justify-content-between lh-condensed row text-center" id="row-'.$i.'">
                                                <div class="col-md-4 mr-auto">
                                                    <input type="hidden" id="id-prod-row-'.$i.'" value="'.$pizza_data[$i]->ID.'">
                                                    <h3 class="prod-title">'.$pizza_data[$i]->nome.'</h3>
                                                    <small class="text-muted">'.$pizza_data[$i]->descrizione.'</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <label><span class="qty text-center">€<span id="price-per-unit-'.$i.'">'.$pizza_data[$i]->prezzo.'</span> x <input type="number" class="form-control cart-item-quantity" id="qta-'.$i.'" value="'.$cart[$i]["qta"].'" title="quantity"></span></label>
                                                </div>
                                                <div class="col-md-3 align-self-center ml-auto justify-content-between">
                                                    <span class="text-muted ml-auto">€ <span class="pricetag" id="pricetag-'.$i.'">'.$cart[$i]["qta"]*number_format((float)$pizza_data[$i]->prezzo, 2, ".", "").'</span></span>
                                                </div>
                                                <div class="col-md-1 mb-1 align-self-center text-danger">
                                                    <span class ="fa fa-trash btn btn-danger del-icon" id="del-row-'.$i.'"></span>
                                                </div>
                                            </li>';
                                        }
                                    ?>
                                <li class="list-group-item d-flex justify-content-between lh-condensed row text-center">
                                    <div class="col-md-4">
                                        <span>Totale (EUR)</span>
                                    </div>
                                    <div class="col-md-4 offset-md-4">
                                        <span>€ <strong id="total"></strong></span>
                                    </div>
                                </ul>
                                <div class="row text-center">
                                    <div class="col-md-4 offset-md-4 align-self-center">
                                        <a href="../php_utils/empty_cart.php" class="btn btn-danger" id="empty-cart">Svuota carrello</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 order-md-1">
                          <h4 class="mb-3">Indirizzo di fatturazione</h4>
                          <form class="needs-validation" action="order.php" method="post" novalidate="">
                            <div class="row">
                              <div class="col-md-6 mb-3">
                                <label for="firstName">Nome</label>
                                <input type="text" class="form-control" id="firstName" placeholder="" name="nome" value="<?= $nome ?>" required="">
                                <div class="invalid-feedback">
                                  Il nome è obbligatorio.
                                </div>
                              </div>
                              <div class="col-md-6 mb-3">
                                <label for="lastName">Cognome</label>
                                <input type="text" class="form-control" id="lastName" placeholder="" name="cognome" value="<?= $cognome ?>" required="">
                                <div class="invalid-feedback">
                                  Il cognome è obbligatorio.
                                </div>
                              </div>
                            </div>

                            <div class="mb-3">
                              <label for="email">Email</label>
                              <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" value="<?= $email ?>">
                              <div class="invalid-feedback">
                                Inserire un indirizzo e-mail valido.
                              </div>
                            </div>

                            <div class="mb-3">
                              <label for="address">Indirizzo</label>
                              <input type="text" class="form-control" id="address" name="indirizzo" value="Nuovo Campus Universitario di Cesena (FC), 47522" disabled>
                              <div class="invalid-feedback">
                                Inserire un indirizzo valido per la consegna.
                              </div>
                            </div>
                            <hr class="mb-4">

                            <h4 class="mb-3">Pagamento</h4>

                            <div class="d-block my-3">
                                <fieldset>
                                    <legend>
                                        Metodi
                                    </legend>
                                    <div class="custom-control custom-radio">
                                        <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked="" required="">
                                        <label class="custom-control-label" for="credit">Carta di credito</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input id="debit" name="paymentMethod" type="radio" class="custom-control-input" required="">
                                        <label class="custom-control-label" for="debit">Carta di debito/bancomat</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input id="paypal" name="paymentMethod" type="radio" class="custom-control-input" required="">
                                        <label class="custom-control-label" for="paypal">Paypal</label>
                                    </div>

                                </fieldset>
                            </div>
                            <div class="row">
                              <div class="col-md-6 mb-3">
                                <label for="cc-name">Nome sulla carta</label>
                                <input type="text" class="form-control" id="cc-name" name="cc-nome" placeholder="" required="" value="<?= $nome_cognome?>">
                                <small class="text-muted">Nome intero come stampato sulla carta</small>
                                <div class="invalid-feedback">
                                  Il nome è obbligatorio.
                                </div>
                              </div>
                              <div class="col-md-6 mb-3">
                                <label for="cc-number">Numero carta</label>
                                <input type="text" class="form-control" id="cc-number" name="cc-num" placeholder="" required="" value="<?= $cc_num ?>" pattern="[0-9]{16}" title="16 cifre">
                                <div class="invalid-feedback">
                                  Inserire un numero valido.
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-3 mb-3">
                                <label for="cc-expiration">Scadenza</label>
                                <input type="text" class="form-control" id="cc-expiration" name="exp" placeholder="" required="" value="<?= $exp ?>" pattern="(?:0[1-9]|1[0-2])/[0-9]{2}" title="Data di scadenza, nel formato MM/YY">
                                <div class="invalid-feedback">
                                  Inserire una scadenza valida.
                                </div>
                              </div>
                              <div class="col-md-3 mb-3">
                                <label for="cc-cvv">CVV</label>
                                <input type="text" class="form-control" id="cc-cvv" name="cvv" placeholder="" required="" value="<?= $cvv ?>" pattern="[0-9]{3}" title="Codice segreto della carta, formato XYZ">
                                <div class="invalid-feedback">
                                  Inserire un codice di sicurezza valido.
                                </div>
                              </div>
                            </div>
                            <hr class="mb-4">
                            <button class="btn btn-success btn-block btn-lg" type="submit">Conferma</button>
                          </form>
                        </div>
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
    </body>
</html>
