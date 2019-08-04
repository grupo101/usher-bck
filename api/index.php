<?php  
function getPhpFromUrl($url) {
    $url = parse_url($url, PHP_URL_PATH); // dirname($_SERVER['PHP_SELF']);
    $position = strpos($url,".php");
    if ($position) 
        $url = substr($url,0,$position) . ".php";
    else
        $url = $url . ".php";
    return $url;
}
function getRelativeFileFromUrl($current,$url) {
    $position = strrpos($current,"/");
    if ($position)
        return "./" . substr($url,$position + 1,strlen($url)-$position);
    return false;
}
function getPath($url) {
    $position = strrpos($url,"/");
    if ($position)
        return substr($url,0,$position );
    return false;
}
    
$url = $_SERVER['REQUEST_URI'];
$url = getPhpFromUrl($url);
$api = getRelativeFileFromUrl($_SERVER['SCRIPT_NAME'],$url);
$apiPath = getPath($api);
if (@include $api) {
    // Plan A
} else {
    // Plan B - for when 'api.php' cannot be included
    
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Wrong command."));
    
    die();

    // echo '<HTML>
    //     <HEAD></HEAD>
    //     <BODY>
    //         <p>Esta es API del Middleware V.1</ br>El comando empleado es incorrecto </p>';
    // echo '<table cellpadding="10">' ; 
    // $indicesServer = array('PHP_SELF', 
    //                         'argv', 
    //                         'argc', 
    //                         'GATEWAY_INTERFACE', 
    //                         'SERVER_ADDR', 
    //                         'SERVER_NAME', 
    //                         'SERVER_SOFTWARE', 
    //                         'SERVER_PROTOCOL', 
    //                         'REQUEST_METHOD', 
    //                         'REQUEST_TIME', 
    //                         'REQUEST_TIME_FLOAT', 
    //                         'QUERY_STRING', 
    //                         'DOCUMENT_ROOT', 
    //                         'HTTP_ACCEPT', 
    //                         'HTTP_ACCEPT_CHARSET', 
    //                         'HTTP_ACCEPT_ENCODING', 
    //                         'HTTP_ACCEPT_LANGUAGE', 
    //                         'HTTP_CONNECTION', 
    //                         'HTTP_HOST', 
    //                         'HTTP_REFERER', 
    //                         'HTTP_USER_AGENT', 
    //                         'HTTPS', 
    //                         'REMOTE_ADDR', 
    //                         'REMOTE_HOST', 
    //                         'REMOTE_PORT', 
    //                         'REMOTE_USER', 
    //                         'REDIRECT_REMOTE_USER', 
    //                         'SCRIPT_FILENAME', 
    //                         'SERVER_ADMIN', 
    //                         'SERVER_PORT', 
    //                         'SERVER_SIGNATURE', 
    //                         'PATH_TRANSLATED', 
    //                         'SCRIPT_NAME', 
    //                         'REQUEST_URI', 
    //                         'PHP_AUTH_DIGEST', 
    //                         'PHP_AUTH_USER', 
    //                         'PHP_AUTH_PW', 
    //                         'AUTH_TYPE', 
    //                         'PATH_INFO', 
    //                         'ORIG_PATH_INFO') ;
    // foreach ($indicesServer as $arg) { 
    //     if (isset($_SERVER[$arg])) { 
    //         echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ; 
    //     } 
    //     else { 
    //         echo '<tr><td>'.$arg.'</td><td>-</td></tr>' ; 
    //     } 
    // } 
    // echo '</table>
    //     </BODY>
    // </HTML>' ; 
}
    
//    require_once "../{$helper_dir}/{$file}";
?>
