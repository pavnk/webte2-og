<?php
session_start();

require_once '../google_api/vendor/autoload.php';
require_once '../config.php';

// Inicializacia Google API klienta
$client = new Google\Client();

// Definica konfiguracneho JSON suboru pre autentifikaciu klienta.
// Subor sa stiahne z Google Cloud Console v zalozke Credentials.
$client->setAuthConfig('oauthlogin.json');

// Nastavenie URI, na ktoru Google server presmeruje poziadavku po uspesnej autentifikacii.
$redirect_uri = "https://site179.webte.fei.stuba.sk/zadanie1oh/login/redirect.php";
$client->setRedirectUri($redirect_uri);

// Definovanie Scopes - rozsah dat, ktore pozadujeme od pouzivatela z jeho Google uctu.
$client->addScope("email");
$client->addScope("profile");

// Vytvorenie URL pre autentifikaciu na Google server - odkaz na Google prihlasenie.
$auth_url = $client->createAuthUrl();


?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OAuth2 cez Google</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://unpkg.com/tabulator-tables@5.4.4/dist/css/tabulator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class=" navbar-collapse justify-content-md-center" id="navbarsExample08">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Olympic winners</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../best.php">Best olympic athletes</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="./login.php">Login</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container text-center mt-5 ">
    <?php
    // Ak som prihlaseny, existuje session premenna.
    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        header("location: restricted.php");
        exit;

    } else {
        // Ak nie som prihlaseny, zobraz mi tlacidlo na prihlasenie.
        echo '<h3>Not logged in</h3>';
        echo '<a role="button" class="btn btn-primary" href="' . filter_var($auth_url, FILTER_SANITIZE_URL) . '">Google log in</a>';
    }
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>

</body>
</html>