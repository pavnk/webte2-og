<?php
require_once('config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $fullname = $_SESSION['fullname'];


} else if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]){
    $email = $_SESSION['email'];
    $id = $_SESSION['login'];
    $fullname = $_SESSION['fullname'];

} // TODO: Poskytnut pouzivatelovi docasne deaktivovat 2FA.
// TODO: Poskytnut pouzivatelovi moznost resetovania hesla.
else {
    // Ak pouzivatel prihlaseny nie je, presmerujem ho na hl. stranku.
    header('Location: login/login.php');
}

$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$url_components = parse_url($url);

parse_str($url_components['query'], $params);

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!empty($_POST) && !empty($_POST['name'])) {
        var_dump($_POST);
        $sql = "UPDATE person SET name=?, surname=?, birth_day=?, birth_place=?, birth_country=?, death_day=?, death_place=?,
                  death_country=? WHERE id=?";
        $stmt = $db->prepare($sql);
        if(!isset($_POST['death_day']))
            $deathday = NULL;
        if(!isset($_POST['death_place']))
            $deathplace = NULL;
        if(!isset($_POST['death_country']) || (empty($_POST['death_country'])))
            $deathcountry = NULL;
        $success = $stmt->execute([$_POST['name'], $_POST['surname'], $_POST['birth_day'], $_POST['birth_place'], $_POST['birth_country'],
            $deathday, $deathplace, $deathcountry, intval($_POST['person_id'])]);

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

            $email = $_SESSION['email'];
            $id = $_SESSION['id'];
            $fullname = $_SESSION['fullname'];

            $name_parts = explode(' ', $fullname);
            $first_name = $name_parts[0];
            $last_name = $name_parts[sizeof($name_parts)-1];
            //place info into history about login from google
            include 'putDataHistory.php';
            $email = $_SESSION['email'];
            putInHistory($email, 0, "Edited person", "Google account");

        } else if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]){
            $email = $_SESSION['email'];
            $id = $_SESSION['login'];
            $fullname = $_SESSION['fullname'];

            $name_parts = explode(' ', $fullname);
            $first_name = $name_parts[0];
            $last_name = $name_parts[sizeof($name_parts)-1];

            include 'putDataHistory.php';
            $id = $_SESSION['login'];
            $userid = returnUserId($id);
            $userid = json_decode($userid);
            $userid = $userid[0]->id;
            putInHistory($id, $userid, "Edited person", "User account");
        }
        echo '<script>alert("Aupdated athlete info ' . $_POST['name'] . $_POST['surname'] . '");
    window.location.href="admin.php";</script>';
        //header('Location: admin.php');
        exit;
    }

    $query = "SELECT * FROM person where id=?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_GET['id']]);
    $person = $stmt->fetch(PDO::FETCH_ASSOC);


    $query = "select placement.*, game.city from placement join game on placement.game_id = game.id where placement.person_id=?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_GET['id']]);
    $placements = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo $e->getMessage();
}
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Info</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://unpkg.com/tabulator-tables@5.4.4/dist/css/tabulator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class=" navbar-collapse justify-content-md-center" id="navbarsExample08">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./index.php">Olympic winners</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./best_test.php">Best olympic athletes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./login/restricted.php">Profile</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="./admin.php">Admin panel</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container text-center">
    <h1 class="header">Olympic games</h1>
    <div id="filter-div">

    </div>
    <div id="info"></div>

    <form action="#" method="post">
        <input type="hidden" name="person_id" value="<?php echo $person['id']; ?>">
        <div class="mb-3">
            <label for="InputName" class="form-label">Name:</label>
            <input type="text" name="name" value="<?php echo $person['name']; ?>" class="form-control" id="InputName" required>
        </div>

        <div class="mb-3">
            <label for="InputSurname" class="form-label">Surname:</label>
            <input type="text" name="surname" value="<?php echo $person['surname']; ?>" class="form-control" id="InputSurname" required>
        </div>

        <div class="mb-3">
            <label for="InputDate" class="form-label">Birth date:</label>
            <input type="date" name="birth_day" value="<?php echo $person['birth_day']; ?>" class="form-control" id="InputDate" required>
        </div>

        <div class="mb-3">
            <label for="InputPlace" class="form-label">City:</label>
            <input type="text" name="birth_place" value="<?php echo $person['birth_place']; ?>" class="form-control" id="InputPlace" required>
        </div>

        <div class="mb-3">
            <label for="InputCountry" class="form-label">Country:</label>
            <input type="text" name="birth_country" value="<?php echo $person['birth_country']; ?>" class="form-control" id="InputCountry" required>
        </div>

        <div class="mb-3">
            <label for="InputDeathDay" class="form-label">Death Day:</label>
            <input type="date" name="death_country" value="<?php echo $person['death_day']; ?>" class="form-control" id="InputDeathDay" >
        </div>

        <div class="mb-3">
            <label for="InputDeathPlace" class="form-label">Death place:</label>
            <input type="text" name="death_country" value="<?php echo $person['death_place']; ?>" class="form-control" id="InputDeathPlace" >
        </div>

        <div class="mb-3">
            <label for="InputDeathCountry" class="form-label">Death country:</label>
            <input type="text" name="death_country" value="<?php echo $person['death_country']; ?>" class="form-control" id="InputDeathCountry" >
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h2>Delete placement</h2>
    <form action="deletePlacement.php" method="post">
        <select name="placement_id" class="form-control">
            <?php
            foreach($placements as $placement){
                echo '<option value="' . $placement["id"] . '">' . $placement["placing"] . ' ' . $placement["discipline"] . ' ' . $placement["city"] . "</option>";
            }
            ?>
        </select>
        <br>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>

<script src="infoTable.js">
    const id = <?php echo $params['id'] ?>
</script>
</body>
</html>
