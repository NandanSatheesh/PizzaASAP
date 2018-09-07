<?php

    require_once '../php_utils/db_connection.php';
    require_once '../php_utils/check_for_notifications.php';

    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION["admin"])) {
        header("location: ../err/error.php");
    }

    /*$new_not = check_for_notifications($_SESSION["admin"], TRUE);
    $isHidden = "";
    if($new_not == 0 || isset($_SESSION["not_count"])) {
        $isHidden = "hidden";
    }
    else {
        if(isset($_SESSION["not_count"])) {
            $_SESSION["not_count"] = intval($_SESSION["not_count"]) + $new_not;
        }
        else {
            $_SESSION["not_count"] = $new_not;
        }
    }*/

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
        <link rel="stylesheet" type="text/css" href="../styles/admin.css">
        <script src="../scripts/notifications.js"></script>

        <title>Amministrazione</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
            <a href="#top" class="navbar-brand">Pizza ASAP!</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse justify-content-stretch" id="navbarCollapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#top">Home  <span class="fas fa-home"></span></a>
                    </li>
                  <li>
                    <a class="nav-link" href="impostazioni.php">Impostazioni  <span class="fas fa-wrench"></span></a>
                </li>
                <li class="nav-item">
                    <input type="hidden" id="mail-field" value="<?= $_SESSION["admin"]?>">
                    <input type="hidden" id="is-admin" value="true">
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
                        <h1 class="display-1" id="top">Bentornato!</h1>
                    </div>
                </div>
            </div>
            <div id="content">
                <div class="container-fluid" id="main-tab">
                    <div class="row text-center">
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card card-block">
                                <div class="card-body">
                                    <h2 class="card-title">Aggiungi</h2>
                                    <p class="card-text">
                                        Inserimento di pizze per categoria
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <a class="btn btn-primary" id="add-to-menu" href="aggiungi.php">Prosegui</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card card-block">
                                <div class="card-body">
                                    <h2 class="card-title">Modifica</h2>
                                    <p class="card-text">
                                        Modifica di prezzo, nome o ingredienti di una singola voce del menu
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <a class="btn btn-primary" id="edit-menu" href="modifica.php">Prosegui</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card card-block">
                                <div class="card-body">
                                    <h2 class="card-title">Rimuovi</h2>
                                    <p class="card-text">
                                        Rimozione dal menu
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <a class="btn btn-primary" id="remove-menu" href="rimuovi.php">Prosegui</a>
                                </div>
                            </div>
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
