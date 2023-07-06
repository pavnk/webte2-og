<?php
require_once('config.php');

//required for debugging, delete on release
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT person.id, person.name, person.surname, COUNT(placement.id) as placement_count
    FROM person
    JOIN placement ON person.id = placement.person_id
    WHERE placement.placing = 1
    GROUP BY person.id
    ORDER BY placement_count DESC";
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo $e->getMessage();
}

header('Content-Type: application/json');

echo json_encode($results);
?>

