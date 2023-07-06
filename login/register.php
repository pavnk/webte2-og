<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Konfiguracia PDO
require_once '../config.php';
// Kniznica pre 2FA
require_once 'PHPGangsta/GoogleAuthenticator.php';

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    echo $e->getMessage();
}

// ------- Pomocne funkcie -------
function checkEmpty($field) {
    // Funkcia pre kontrolu, ci je premenna po orezani bielych znakov prazdna.
    // Metoda trim() oreze a odstrani medzery, tabulatory a ine "whitespaces".
    if (empty(trim($field))) {
        return true;
    }
    return false;
}

function checkLength($field, $min, $max) {
    // Funkcia, ktora skontroluje, ci je dlzka retazca v ramci "min" a "max".
    // Pouzitie napr. pre "login" alebo "password" aby mali pozadovany pocet znakov.
    $string = trim($field);     // Odstranenie whitespaces.
    $length = strlen($string);      // Zistenie dlzky retazca.
    if ($length < $min || $length > $max) {
        return false;
    }
    return true;
}

function checkUsername($username) {
    // Funkcia pre kontrolu, ci username obsahuje iba velke, male pismena, cisla a podtrznik.
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        return false;
    }
    return true;
}

function checkPassword($password){
    if (!preg_match('/^[a-zA-Z0-9_!@#$%&*]+$/', $password)) {
        return true;
    }
    return false;
}

function checkName($name){
    if (!preg_match('/^[a-zA-Z]+$/', trim($name))) {
        return false;
    }
    return true;
}

function checkGmail($email) {
    // Funkcia pre kontrolu, ci zadany email je gmail.
    if (!preg_match('/^[\w.+\-]+@gmail\.com$/', trim($email))) {
        return false;
    }
    else if (!preg_match('/^[\w.+\-]+@stuba\.sk$/', trim($email))) {
        return false;
    }
    return true;
}

function userExist($db, $login, $email) {
    // Funkcia pre kontrolu, ci pouzivatel s "login" alebo "email" existuje.
    $exist = false;

    $param_login = trim($login);
    $param_email = trim($email);

    $sql = "SELECT id FROM users WHERE login = :login OR email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":login", $param_login, PDO::PARAM_STR);
    $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $exist = true;
    }

    unset($stmt);

    return $exist;
}

// ------- ------- ------- -------



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errmsg = "";

    // Validacia username
    if (checkEmpty($_POST['login']) === true) {
        $errmsg .= "<p>Put in login.</p>";
    } elseif (checkLength($_POST['login'], 6,32) === false) {
        $errmsg .= "<p>Login has to be min. 6 and max. 32 symbols.</p>";
    } elseif (checkUsername($_POST['login']) === false) {
        $errmsg .= "<p>Login can contain only small, large letters, numbers and underscore.</p>";
    }

    // Kontrola pouzivatela
    if (userExist($pdo, $_POST['login'], $_POST['email']) === true) {
        $errmsg .= "User with this e-mail / login already exists.</p>";
    }

    // Validacia mailu
    if (checkGmail($_POST['email'])) {
        $errmsg .= "Log in with google";
        // Ak pouziva google mail, presmerujem ho na prihlasenie cez Google.
         header("Location: login_google.php");
    }

    if(checkEmpty($_POST['password']) === true){
        $errmsg .="<p>Put in password.</p>";
    } elseif(checkLength($_POST['password'],6,32) === false) {
        $errmsg .= "<p>Password needs to be at least 6 symbols long.</p>";
    } elseif(checkPassword($_POST['password']) === true){
        $errmsg .= "<p>Password can contain only small, large letters, numbers.</p>";
    }

    if(checkEmpty($_POST['firstname']) === true){
        $errmsg .="<p>Put in name.</p>";
    } elseif(checkLength($_POST['firstname'],1,32) === false) {
        $errmsg .= "<p>Name has to be at least 1 symbol long.</p>";
    } elseif(checkName($_POST['firstname']) === false){
        $errmsg .= "<p>Name can contain only small, large letters.</p>";
    }

    if(checkEmpty($_POST['lastname']) === true){
        $errmsg .="<p>Put in surname.</p>";
    } elseif(checkLength($_POST['lastname'],1,32) === false) {
        $errmsg .= "<p>Surname needs to be at lest 1 symbol long.</p>";
    } elseif(checkName($_POST['lastname']) === false){
        $errmsg .= "<p>Surname can contain only small, large letters.</p>";
    }

    if (empty($errmsg)) {
        $sql = "INSERT INTO users (fullname, login, email, password, 2fa_code) VALUES (:fullname, :login, :email, :password, :2fa_code)";

        $fullname = $_POST['firstname'] . ' ' . $_POST['lastname'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);

        // 2FA pomocou PHPGangsta kniznice: https://github.com/PHPGangsta/GoogleAuthenticator
        $g2fa = new PHPGangsta_GoogleAuthenticator();
        $user_secret = $g2fa->createSecret();
        $codeURL = $g2fa->getQRCodeGoogleUrl('Olympic Games', $user_secret);

        // Bind parametrov do SQL
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":login", $login, PDO::PARAM_STR);
        $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(":2fa_code", $user_secret, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // qrcode je premenna, ktora sa vykresli vo formulari v HTML.
            $qrcode = $codeURL;
        } else {
            echo "Something went wrong";
        }

        unset($stmt);
    }
    unset($pdo);
}

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login/register s 2FA - Register</title>
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
<div class="container text-center mt-5">
    <h1>Registration</h1>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="firstname">
            Name:
            <input type="text" name="firstname" class="form-control" value="" id="firstname" placeholder="napr. Nikolas" required>
        </label>
        <br>
        <label for="lastname">
            Surname:
            <input type="text" name="lastname" class="form-control" value="" id="lastname" placeholder="napr. pavlis" required>
        </label>
        <br>
        <label for="email">
            E-mail:
            <input type="email" name="email" class="form-control" value="" id="email" placeholder="napr. xpavlisn@example.com" required>
        </label>
        <br>
        <label for="login">
            Login:
            <input type="text" name="login" class="form-control" value="" id="login" placeholder="napr. xpavlisn" required">
        </label>
        <br>
        <label for="password">
            Password:
            <input type="password" name="password" class="form-control" value="" id="password" required>
        </label>
        <br>
        <button type="submit" class="btn btn-primary">Create account</button>

        <?php
        if (!empty($errmsg)) {
            // Tu vypis chybne vyplnene polia formulara.
            echo $errmsg;
        }
        if (isset($qrcode)) {
            // Pokial bol vygenerovany QR kod po uspesnej registracii, zobraz ho.
            $message = '<p>Scan QR code into your Athenticator app: <br><img src="'.$qrcode.'" alt="qr kod pre aplikaciu authenticator"></p>';

            echo $message;
            echo '<p>Now you can log in: <a href="login.php" role="button">Login</a></p>';
        }
        ?>

    </form>
    <p>Already registered? <a href="login.php">Log in.</a></p>
</div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>


</body>
</html>