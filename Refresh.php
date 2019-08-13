<?php
    $con = mysqli_connect("localhost", "root", "", "usuarios");
    
    $statement = mysqli_prepare($con, "SELECT * FROM statusbanca");
    mysqli_stmt_execute($statement);
    
    mysqli_stmt_store_result($statement);
    mysqli_stmt_bind_result($statement, $log_id, $status);
    
    $response = array();
    $response["succes"] = false; 
    
    while(mysqli_stmt_fetch($statement)){
        $response["succes"] = true;  
        $response["status"] = $status;
    }
    
    echo json_encode($response);
?>