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
    delete();
    //echo '<script>alert("Deleted user ' . $_POST['name'] . $_POST['surname'] . '");
    //window.location.href="admin.php";</script>';
    //header('Location: admin.php');
    exit;

} else if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]){
    $email = $_SESSION['email'];
    $id = $_SESSION['login'];
    $fullname = $_SESSION['fullname'];
    delete();
    //echo '<script>alert("Deleted user ' . $_POST['name'] . $_POST['surname'] . '");
    //window.location.href="admin.php";</script>';
    //header('Location: admin.php');
    exit;
} // TODO: Poskytnut pouzivatelovi docasne deaktivovat 2FA.
// TODO: Poskytnut pouzivatelovi moznost resetovania hesla.
else {
    // Ak pouzivatel prihlaseny nie je, presmerujem ho na hl. stranku.
    header('Location: login/login.php');
}

function delete(){
    include('config.php');
    try {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $db->prepare("DELETE FROM person WHERE id=?");
        $query->execute([$_POST['person_id']]);
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
        putInHistory($email, 0, "Deleted person", "Google account");

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
        putInHistory($id, $userid, "Deleted person", "User account");
    }
    echo '<script>alert("Deleted athlete ' . $_POST['name'] . $_POST['surname'] . '");
    window.location.href="admin.php";</script>';
    //header('Location: admin.php');
    exit;
}