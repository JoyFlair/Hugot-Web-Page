<?php 
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

class User{
    //login
    function login($json){
        include "connection-pdo.php";
        //{userma,e: 'pitok', password:12345 }

        
        $json = json_decode($json, true);
        $sql = "SELECT * FROM tblusers WHERE usr_username = :username AND usr_password = :password";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $json['username']);
        $stmt->bindParam(':password', $json['password']);
        $stmt->execute();
        $returnValue = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($conn); unset($stmt);
        return json_encode($returnValue);

    }

    //register
    function register($json){
        include "connection-pdo.php";
        
    }

    function setUsers($json){
        //code

    }

}

// submitted by the client - operation and json data

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $operation = $_GET['operation'];
    $json = $_GET['json'];
}else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $operation = $_GET['operation'];
    $json = $_GET['json'];
}

$user = new User();
switch($operation){
    case "login":
        echo $user->login($json);
        break;
    case "register":
        echo $user->register($json);
        break;
    case "setUsers":
        echo $user->setUsers($json);
        break;   
               
}
?>
