<?php
date_default_timezone_set('UTC');
require_once('../lib/DatabaseManager.php');

$email = isset($_POST['email']) ? $_POST['email'] : (isset($_GET['email']) ? $_GET['email'] : '');
if($email == ''){
    die("false");
}
mysql_real_escape_string($email);
//mysqli_real_escape_string($email);

$ip = $_SERVER['REMOTE_ADDR'];
$created_at = gmdate("Y-m-d H:i:s");

$sql = "INSERT INTO curioususer (email,ip,created_at) VALUES ('$email','$ip','$created_at')";
DatabaseConnector::query($sql,true);
if(mysql_errno() == 1062){
    die("exist");
} else if(mysql_errno() > 0){
    die("false");
} else {
    die("true");
}
