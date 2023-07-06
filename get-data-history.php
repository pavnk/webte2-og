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
try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM history WHERE login='$login'";
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


} catch(PDOException $e) {
    echo $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($results);
?>