<?php 
require_once "../lib/util.php";
session_start();
killSession();
$goBackURL = "https://".$_SERVER['HTTP_HOST'];
header("Location:".$goBackURL. "/index.php");
exit();
?>
