<?php
function putInHistory($login, $id, $message, $login_type){
    include 'config.php';
    if($id == 0){
        //google
        try {
            $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO history (login, action, login_type) VALUES (:login, :action, :login_type)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            $stmt->bindParam(":action", $message, PDO::PARAM_STR);
            $stmt->bindParam(":login_type", $login_type, PDO::PARAM_STR);
            $stmt->execute();

        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    } else{
        //user
        try {
            $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO history (login, action, user_id, login_type) VALUES (:login, :action, :userid, :login_type)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            $stmt->bindParam(":action", $message, PDO::PARAM_STR);
            $stmt->bindParam(":userid", $id, PDO::PARAM_STR);
            $stmt->bindParam(":login_type", $login_type, PDO::PARAM_STR);
            $stmt->execute();

        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
    unset($stmt);
    unset($pdo);
}

function returnUserId($login){
    include 'config.php';
    try {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $db->prepare("SELECT id FROM users WHERE login = ?");
        $query->execute([$login]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = json_encode($results);

    } catch(PDOException $e) {
        echo $e->getMessage();
    }
    unset($stmt);
    unset($pdo);
    return $result;
}