<?php
header('Access-Control-Allow-Origin:http://192.168.3.96:8080');
header('Access-Control-Allow-Headers:content-type');
header('Access-Control-Request-Method:GET,POST');
header("Access-Control-Allow-Credentials:true");

$ans = array("status" => "200");
session_start();
if(isset($_SESSION["project1_username"]) && isset($_SESSION["project1_password"])) {
    $username = $_SESSION["project1_username"];
    $password = $_SESSION["project1_password"];
} else {
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data["username"];
    $password = $data["password"];
}
session_write_close();
//$username = $_COOKIE["project1_username"];//前端直接读取cookie
//$password = $_COOKIE["project1_password"];

//正则验证用户名和密码
if (!preg_match("/^[A-Za-z][A-Za-z0-9_]{3,19}$/", $username)) {
    $ans["status"] = 2002;
    exit(json_encode($ans));
}
if (!preg_match("/^.{6,32}$/", $password)) {
    $ans["status"] = 2004;
    exit(json_encode($ans));
}

//连接数据库
$conn = mysqli_connect("localhost", "root", "wi2MyfO4,ci&", "project1");
if (!$conn) {
    //数据库连接失败
    $ans["status"] = 3001;
    exit(json_encode($ans));
}
if (!mysqli_set_charset($conn, "utf8MB4")) {
    //字符集设置失败
    $ans["status"] = 3002;
    exit(json_encode($ans));
}

//查询用户名与密码正确性
$sql = "select id from users where username = '$username' and password = '$password'";
$result = mysqli_query($conn, $sql);
if (mysqli_fetch_array($result)) {
    //用户名成功登录，写session
    session_start();
    $_SESSION["project1_username"] = $username;
    $_SESSION["project1_password"] = $password;
    session_write_close();
} else {
    //用户名和密码不正确
    $ans["status"] = 4001;
}

exit(json_encode($ans));