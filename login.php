<?php
    include('dbconn.php');
    //$con = mysqli_connect("localhost", "root", "", "usuarios");

    $response = array();
    $response["succes"] = false;  
    
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $access = "true";
    
    $statement = mysqli_prepare($con, "SELECT userID as id, name, surname, username, password, access 
                                    FROM usuarios WHERE username = ? AND password = ? AND access = ?");
    mysqli_stmt_bind_param($statement, "sss", $username, $password, $access);
    mysqli_stmt_execute($statement);
    
    mysqli_stmt_bind_result($statement, $id, $name, $surname, $username, $password, $access);
    //mysqli_stmt_store_result($statement);
    
    while(mysqli_stmt_fetch($statement)){
        $response["succes"] = true;  
        $response["name"] = $name;
        $response["surname"] = $surname;
    }
}
    echo json_encode($response);
?>
