<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required to encode json web token
include_once __DIR__.'/../config/core.php';
include_once __DIR__.'/../libs/php-jwt-master/src/BeforeValidException.php';
include_once __DIR__.'/../libs/php-jwt-master/src/ExpiredException.php';
include_once __DIR__.'/../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once __DIR__.'/../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// include database and object files
include_once __DIR__.'/../config/database.php';
include_once __DIR__.'/../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new User($db);
 
// get id of user to be edited
$data = json_decode(file_get_contents("php://input"));

// get id
$id=isset($data->id) ? $data->id : "";

// get jwt
$jwt=isset($data->jwt) ? $data->jwt : "";

// if jwt is not empty
if((!USE_JWT and $id) or (USE_JWT and $jwt)){
 
    // if decode succeed, show user details
    try {
        if (USE_JWT) {

            // decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));
            // set ID property of user to be edited
            $user->id = $decoded->data->id;
        }
        else{
            $user->id = $data->id;
        }
        
        // set user property values
        $user->name = isset($data->name) ? $data->name : "";
        $user->surname = isset($data->surname) ? $data->surname : "";
        $user->username = isset($data->username) ? $data->username : "";
        $user->password = isset($data->password) ? $data->password : "";
        
    }
    // if decode fails, it means jwt is invalid
    catch (Exception $e){
    
        // set response code
        http_response_code(401);
    
        // show error message
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
} 
else{

    // set response code
    http_response_code(400);

    // show error message
    echo json_encode(array(
        "message" => "Unable to update user. Data is incomplete."
    ));

    die();
}
 
// update the user
if($user->update()){

    // we need to re-generate jwt because user details might be different
    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "id" => $user->id,
            "name" => $user->name,
            "surname" => $user->surname,
            "username" => $user->username
        )
    );
    $jwt = USE_JWT ? JWT::encode($token, $key) : "";
    
    // set response code
    http_response_code(200);
    
    // response in json format
    echo json_encode(
            array(
                "message" => "User was updated.",
                "jwt" => $jwt
            )
        );
}
 
// if unable to update the user, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update user."));
}
