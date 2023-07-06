<?php

//required for debugging, delete on release
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config.php');

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = $db->prepare("SELECT person.id, person.name, person.surname, placement.id, placement.game_id, placement.placing, 
    placement.discipline, game.year, game.city, game.country
    FROM person
    JOIN placement ON person.id = placement.person_id
    JOIN game ON placement.game_id = game.id
    WHERE person.id = ?");
    $query->execute([$_GET['id']]);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
} catch(PDOException $e) {
    echo $e->getMessage();
}

header('Content-Type: application/json');

?>

