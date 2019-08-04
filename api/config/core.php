<?php
// show error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
 

// set your default time-zone
date_default_timezone_set('Asia/Manila');
 
define("USE_JWT", false);
// variables used for jwt
$key = "u5H32-w38"; #usher-web
$iss = "http://example.org";
$aud = "http://example.com";
$iat = 1356999524;
$nbf = 1357000000;

// home page url
$home_url="http://localhost/usher-bck/api/";
 
// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
 
// set number of records per page
$records_per_page = 2;
 
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
