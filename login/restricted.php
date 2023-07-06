<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ak je pouzivatel prihlaseny, ziskam data zo session, pracujem s DB etc...
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $fullname = $_SESSION['fullname'];

    $name_parts = explode(' ', $fullname);
    $first_name = $name_parts[0];
    $last_name = $name_parts[sizeof($name_parts)-1];
    //place info into history about login from google
    $login = $email;

} else if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]){
    $email = $_SESSION['email'];
    $id = $_SESSION['login'];
    $fullname = $_SESSION['fullname'];

    $name_parts = explode(' ', $fullname);
    $first_name = $name_parts[0];
    $last_name = $name_parts[sizeof($name_parts)-1];
    $login = $id;
}

// TODO: Poskytnut pouzivatelovi docasne deaktivovat 2FA.
// TODO: Poskytnut pouzivatelovi moznost resetovania hesla.
else {
    // Ak pouzivatel prihlaseny nie je, presmerujem ho na hl. stranku.
    header('Location: login.php');
}


?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profil</title>
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
                <a class="nav-link" href="./restricted.php">Profil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../admin.php">Admin panel</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container text-center mt-5">
    <h1>Welcome <?php echo $fullname ?></h1>
    <h3>You are logged in as: <?php echo $email?></h3>
    <h3>Your id: <?php echo $id?></h3>

    <a role="button" class="secondary" href="logout.php">Log out</a></p>
    <br>
    <div id="filter-div"></div>
    <div id="userInfoTable"></div>

</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>

<script src="../userInfoTable.js">
    const id = <?php echo $login?>
</script>
</body>
</html>