<?php
require_once('config.php');

//required for debugging, delete on release
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT person.id, person.name, person.surname, game.year, game.city, game.country, game.type, placement.discipline, placement.placing
    FROM person
    JOIN placement ON person.id = placement.person_id
    JOIN game ON game.id = placement.game_id
    WHERE placement.placing = 1";
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($results);
?>