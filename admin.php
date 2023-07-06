<?php
require_once('config.php');

//required for debugging, delete on release
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

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //$query = "SELECT * FROM person";
    $query = "SELECT * FROM person";
    $stmt = $db->query($query);
    $persons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query2 = "SELECT * FROM game";
    $stmt2 = $db->query($query2);
    $games = $stmt2->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo $e->getMessage();
}

if(!empty($_POST) && !empty($_POST['name'])){
    if(checkIfExists($persons, $_POST['name'], $_POST['surname'])){
        header("Refresh:0");
        echo '<script>alert("Athlete already exists")</script>';
        exit;
    }
    $sql = "INSERT INTO person (name,surname,birth_day,birth_place,birth_country,death_day,death_place,death_country) VALUES (?,?,?,?,?,?,?,?)";
    $stmt = $db->prepare($sql);
    if (empty($_POST['death_day'])) {
        $deathday = NULL;
    } else{
        $deathday = $_POST['death_day'];
    }
    if (empty($_POST['death_place'])) {
        $deathplace = NULL;
    }else{
        $deathplace = $_POST['death_place'];
    }
    if (empty($_POST['death_country'])) {
        $deathcountry = NULL;
    }else{
        $deathcountry = $_POST['death_country'];
    }
    $success = $stmt->execute([$_POST['name'], $_POST['surname'], $_POST['birth_day'], $_POST['birth_place'], $_POST['birth_country'],
        $deathday, $deathplace, $deathcountry]);
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
        putInHistory($email, 0, "Insert person", "Google account");
        echo '<script>alert("Added athlete ' . $_POST['name'] . $_POST['surname'] . '");
    window.location.href="admin.php";</script>';
        //header('Location: admin.php');
        exit;

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
        putInHistory($id, $userid, "Insert person", "User account");
        echo '<script>alert("Added athlete ' . $_POST['name'] . $_POST['surname'] . '");
    window.location.href="admin.php";</script>';
        //header('Location: admin.php');
        exit;
    }
}

function checkIfExists($persons, $name, $surname){
    $exists = false;
    foreach($persons as $person){
        if($person["name"] == trim($name) && $person["surname"] == trim($surname)) {
            $exists = true;
        }
    }
    return $exists;
}

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin panel</title>

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
    <div class="container mt-5">
        <h1>Admin panel</h1>


        <div id="filter-div">

        </div>

        <div id="athletes"></div><br>
        <h2>Add athlete</h2>
        <form action="#" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="InputName" class="form-label">Name:</label>
                    <input type="text" name="name" class="form-control" id="InputName" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="InputSurname" class="form-label">Surname:</label>
                    <input type="text" name="surname" class="form-control" id="InputSurname" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="InputDate" class="form-label">Birth date:</label>
                    <input type="date" name="birth_day" class="form-control" id="InputDate" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="InputPlace" class="form-label">City:</label>
                    <input type="text" name="birth_place" class="form-control" id="InputPlace" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="InputCountry" class="form-label">Country:</label>
                    <input type="text" name="birth_country" class="form-control" id="InputCountry" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="InputDeathDay" class="form-label">Death Day:</label>
                    <input type="date" name="death_country" class="form-control" id="InputDeathDay" >
                </div>

                <div class="form-group col-md-4">
                    <label for="InputDeathPlace" class="form-label">Death place:</label>
                    <input type="text" name="death_country" class="form-control" id="InputDeathPlace" >
                </div>

                <div class="form-group col-md-4">
                    <label for="InputDeathCountry" class="form-label">Death country:</label>
                    <input type="text" name="death_country" class="form-control" id="InputDeathCountry" >
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button><br>
        </form>

        <h2>Delete athlete</h2>
        <form action="deletePerson.php" method="post">
            <select name="person_id" class="form-control">

                <?php
                    foreach($persons as $person){
                        echo '<option value="' . $person["id"] . '">' . $person["name"] . ' ' . $person["surname"] . "</option>";
                    }
                ?>
            </select>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button><br>
        </form>

        <h2>Add placement</h2>
        <form action="addPlacement.php" method="post">
            <div class="form-row">
            <select name="person_id" class="form-control col-md-6">

                <?php
                foreach($persons as $person){
                    echo '<option value="' . $person["id"] . '">' . $person["name"] . ' ' . $person["surname"] . "</option>";
                }
                ?>
            </select>
            <br>
            <select name="game_id" class="form-control col-md-6">

                <?php
                foreach($games as $game){
                    echo '<option value="' . $game["id"] . '">' . $game["type"] . ' ' . $game["year"] . ' ' . $game["city"] .
                        ' ' . $game["country"] . "</option>";
                }
                ?>
            </select>
            </div>
            <div class="form-row">
            <div class="form-group col-md-6">
                <label for="InputPlacement" class="form-label">Placement:</label>
                <input type="number" name="placement" class="form-control" id="InputPlacement" >
            </div>
            <div class="form-group col-md-6">
                <label for="InputDiscipline" class="form-label">Discipline:</label>
                <input type="text" name="discipline" class="form-control" id="InputDiscipline" >
            </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button><br>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>


    <script src="athleteTable.js"></script>

</body>
</html>