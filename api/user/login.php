<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// files needed to connect to database
include_once __DIR__.'/../config/database.php';
include_once __DIR__.'/../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set user property values
$user->username = $data->username;
$username_exists = $user->usernameExists();
 
// generate json web token
// https://tools.ietf.org/html/rfc7519#section-4.1
include_once __DIR__.'/../config/core.php';
include_once __DIR__.'/../libs/php-jwt-master/src/BeforeValidException.php';
include_once __DIR__.'/../libs/php-jwt-master/src/ExpiredException.php';
include_once __DIR__.'/../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once __DIR__.'/../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// check if username exists and if password is correct
//TODO: HASTA QUE USEMOS HASH
$user->password = password_hash($user->password, PASSWORD_BCRYPT);
if($username_exists && password_verify($data->password, $user->password)){
 
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
    // generate jwt
    $jwt = USE_JWT ? JWT::encode($token, $key) : "";
    
    // set response code
    http_response_code(200);
    
    echo json_encode(
            array(
                "success" => true,
                "message" => "Successful login.",
                "name" => $user->name,
                "surname" => $user->surname,
                "jwt" => $jwt
            )
        );
 
}

// login failed
else{
 
    // set response code
    http_response_code(401);
 
    // tell the user login failed
    echo json_encode(array("success" => false,
        "message" => "Login failed."
    ));
}
