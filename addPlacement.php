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
    addPlacement();
    exit;

} else if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]){
    $email = $_SESSION['email'];
    $id = $_SESSION['login'];
    $fullname = $_SESSION['fullname'];
    addPlacement();
    exit;
} // TODO: Poskytnut pouzivatelovi docasne deaktivovat 2FA.
// TODO: Poskytnut pouzivatelovi moznost resetovania hesla.
else {
    // Ak pouzivatel prihlaseny nie je, presmerujem ho na hl. stranku.
    header('Location: login/login.php');
}

function addPlacement(){
    include('config.php');
    try {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO placement (person_id, game_id, placing, discipline) VALUES (:personid, :gameid, :placing, :discipline)";

        $personid = $_POST['person_id'];
        $gameid = $_POST['game_id'];
        $placing = $_POST['placement'];
        $discipline = $_POST['discipline'];

        // Bind parametrov do SQL
        $stmt = $db->prepare($sql);

        $stmt->bindParam(":personid", $personid, PDO::PARAM_INT);
        $stmt->bindParam(":gameid", $gameid, PDO::PARAM_INT);
        $stmt->bindParam(":placing", $placing, PDO::PARAM_INT);
        $stmt->bindParam(":discipline", $discipline, PDO::PARAM_STR);

        $stmt->execute();

    } catch(PDOException $e) {
        echo $e->getMessage();
    }
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
        putInHistory($email, 0, "Added placement", "Google account");

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
        putInHistory($id, $userid, "Added placement", "User account");
    }
    echo '<script>alert("Added placement to athlete");
    window.location.href="admin.php";</script>';
    exit;
}