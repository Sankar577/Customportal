<?php
session_start();
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);

$_SESSION["cms_status"]="success";
$_SESSION["cms_msg"]="You have successfully logged out!";
header('Location:login.php');
exit();
?>