<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: restricted.php");
    exit;
}

require_once '../config.php';
require_once 'PHPGangsta/GoogleAuthenticator.php';

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    echo $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $sql = "SELECT fullname, email, login, password, created_at, 2fa_code FROM users WHERE login = :login";

    $stmt = $pdo->prepare($sql);

    // TODO: Upravit SQL tak, aby mohol pouzivatel pri logine zadat login aj email.
    $stmt->bindParam(":login", $_POST["login"], PDO::PARAM_STR);

    if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
            // Uzivatel existuje, skontroluj heslo.
            $row = $stmt->fetch();
            $hashed_password = $row["password"];

            if (password_verify($_POST['password'], $hashed_password)) {
                // Heslo je spravne.
                $g2fa = new PHPGangsta_GoogleAuthenticator();
                if ($g2fa->verifyCode($row["2fa_code"], $_POST['twofa'], 2)) {
                    // Heslo aj kod su spravne, pouzivatel autentifikovany.

                    // Uloz data pouzivatela do session.
                    $_SESSION["loggedin"] = true;
                    $_SESSION["login"] = $row['login'];
                    $_SESSION["fullname"] = $row['fullname'];
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["created_at"] = $row['created_at'];

                    include '../putDataHistory.php';
                    $id = $_SESSION['login'];
                    $userid = returnUserId($id);
                    $userid = json_decode($userid);
                    $userid = $userid[0]->id;
                    putInHistory($id, $userid, "Login", "User account");

                    // Presmeruj pouzivatela na zabezpecenu stranku.
                    header("location: restricted.php");
                }
                else {
                    echo "2FA not valid.";
                }
            } else {
                echo "Wrong name or password.";
            }
        } else {
            echo "Wrong name or password.";
        }
    } else {
        echo "Something went wrong!";
    }

    unset($stmt);
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
    <title>Login/register s 2FA - Login</title>

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
                <a class="nav-link" href="../best_test.php">Best olympic athletes</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">Login</a>
            </li>
        </ul>
    </div>
</nav>
    <div class="container text-center mt-5">
        <h1>Admin panel login</h1>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="">
            <label for="login">
                Login:
                <input type="text" name="login" class="form-control" value="" id="login" required>
            </label>
                <span id="login-error"></span>
            </div>
            <br>
            <div class="">
            <label for="password">
                Password:
                <input type="password" name="password" class="form-control" value="" id="password" required>
            </label>
                <span id="password-error"></span>
            </div>
            <br>
            <div class="">
            <label for="twofa">
                2FA code:
                <input type="number" name="twofa" class="form-control" value="" id="twofa" required>
            </label>
                <span id="2fa-error"></span>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Log in</button>
        </form>
        <p><a href="./login_google.php">Log in with google</a></p>
        <p>Don't have an account yet? <a href="register.php">Register here.</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>
</body>
</html>