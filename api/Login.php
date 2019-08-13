<?php
    $con = mysqli_connect("localhost", "root", "", "usuarios");
    
    $username = $_POST["username"];
    $password = $_POST["password"];
    $access = "true";
    
    $statement = mysqli_prepare($con, "SELECT * FROM user WHERE username = ? AND password = ? AND access = ?");
    mysqli_stmt_bind_param($statement, "sss", $username, $password, $access);
    mysqli_stmt_execute($statement);
    
    mysqli_stmt_store_result($statement);
    mysqli_stmt_bind_result($statement, $userID, $name, $surname, $username, $password, $access);
    
    $response = array();
    $response["succes"] = false; 
    
    while(mysqli_stmt_fetch($statement)){
        $response["succes"] = true;  
        $response["name"] = $name;
        $response["surname"] = $surname;
    }
    
    echo json_encode($response);
?>