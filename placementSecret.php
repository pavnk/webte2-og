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

} else if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
    $email = $_SESSION['email'];
    $id = $_SESSION['login'];
    $fullname = $_SESSION['fullname'];
}
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

    $query = $db->prepare("SELECT person.id, person.name, person.surname, placement.id, placement.game_id, placement.placing, 
    placement.discipline, game.year, game.city, game.country
    FROM placement
    JOIN person ON placement.person_id = person.id
    JOIN game ON placement.game_id = game.id
    WHERE placement.id = ?");
    $query->execute([$_GET['id']]);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $query2 = "SELECT * FROM game";
    $stmt2 = $db->query($query2);
    $games = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($_POST) && !empty($_POST['placement']) && !empty($_POST['discipline'])) {
        var_dump($_POST);
        $sql = "UPDATE placement SET game_id=?, placing=?, discipline=? WHERE id=?";
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([$_POST['game_id'], $_POST['placement'], $_POST['discipline'], intval($_GET['id'])]);

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
            putInHistory($email, 0, "Edited placement", "Google account");

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
            putInHistory($id, $userid, "Edited placement", "User account");
        }
        echo '<script>alert("Edited placement ");</script>';
        //header('Location: admin.php');
        header("Refresh:0");
        exit;

    }

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
    <title>Info placement</title>

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
    <h2>Edit placement</h2>
    <h3><?php echo $results[0]["name"] . " " . $results[0]["surname"]?></h3>
    <form action="#" method="post">
        <select name="game_id" class="form-control">
            <?php
            foreach($games as $game){
                if($game["id"] == $results[0]["game_id"]){
                    echo '<option selected value="' . $game["id"] . '">' . $game["type"] . ' ' . $game["year"] . ' ' . $game["city"] .
                        ' ' . $game["country"] . "</option>";
                }else {
                    echo '<option value="' . $game["id"] . '">' . $game["type"] . ' ' . $game["year"] . ' ' . $game["city"] .
                        ' ' . $game["country"] . "</option>";
                }
            }
            ?>
        </select>
        <div class="mb-3">
            <label for="InputPlacement" class="form-label">Placement:</label>
            <input type="number" name="placement" class="form-control" id="InputPlacement" value="<?php echo $results[0]["placing"]?>" required >
        </div>
        <div class="mb-3">
            <label for="InputDiscipline" class="form-label">Discipline:</label>
            <input type="text" name="discipline" class="form-control" id="InputDiscipline" value="<?php echo $results[0]["discipline"]?>" required >
        </div>
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

</body>
</html>
