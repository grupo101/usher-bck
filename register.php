<?php
    include('dbconn.php');
    //$con = mysqli_connect("localhost", "root", "", "usuarios");
    
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $access = $_POST["access"];
    $statement = mysqli_prepare($con, "INSERT INTO usuarios (name, surname, username, password, access) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($statement, "sssss", $name, $surname, $username, $password, $access);
    mysqli_stmt_execute($statement);
    
    $response = array();
    $response["succes"] = true;  
    
    echo json_encode($response);
?>
