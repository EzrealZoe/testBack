<?php
header('Access-Control-Allow-Origin:http://192.168.3.96:8080');
header('Access-Control-Allow-Headers:content-type');
header('Access-Control-Request-Method:GET,POST');
header("Access-Control-Allow-Credentials:true");
$ans = array("status" => "200");
session_start();
//删除session
if(isset($_SESSION["project1_username"])&&isset($_SESSION["project1_password"])){
    unset($_SESSION["project1_username"]);
    unset($_SESSION["project1_password"]);
}
session_write_close();

exit(json_encode($ans));

