<?php

session_start();

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $fullname = $_SESSION['fullname'];

    $name_parts = explode(' ', $fullname);
    $first_name = $name_parts[0];
    $last_name = $name_parts[sizeof($name_parts)-1];
    //place info into history about login from google
    include '../putDataHistory.php';
    $email = $_SESSION['email'];
    putInHistory($email, 0, "Logout", "Google account");

} else if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]){
    $email = $_SESSION['email'];
    $id = $_SESSION['login'];
    $fullname = $_SESSION['fullname'];

    $name_parts = explode(' ', $fullname);
    $first_name = $name_parts[0];
    $last_name = $name_parts[sizeof($name_parts)-1];

    include '../putDataHistory.php';
    $id = $_SESSION['login'];
    $userid = returnUserId($id);
    $userid = json_decode($userid);
    $userid = $userid[0]->id;
    putInHistory($id, $userid, "Logout", "User account");
}

// Uvolni vsetky session premenne.
session_unset();

// Vymaz vsetky data zo session.
session_destroy();

// Ak nechcem zobrazovat obsah, presmeruj pouzivatela na hlavnu stranku.
header('location:login.php');
exit;

?>