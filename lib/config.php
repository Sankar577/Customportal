<?php 
error_reporting(1); 
session_start();
ob_start();
$ip=$_SERVER["SERVER_ADDR"];
define("DB_PREFIX", "custom");
if($ip=="::1")
{ 
    date_default_timezone_set('Asia/Kolkata');
	define("DB_HOST", "localhost");
	define("DB_NAME", "customdb");
	define("DB_USER", "root");
	define("DB_PASS", "");
	$sitepath='http://localhost/lengo_cash/';
    $siteadminpath='http://localhost/CustomPortal\Customadmin\login.php';
}
else
{
    define("DB_HOST", "localhost");
    define("DB_NAME", "uqdggrqf_Custom_Portal");
    define("DB_USER", "uqdggrqf_custom");
    define("DB_PASS", "custom123@");
	$sitepath='http://nskfix.com/dev/sampletask/sankar/';
    $siteadminpath='https://development.zerosoft.in/Customportal/Customadmin/login.php';
   
}
include('mysqli_class.php');
$site_title="Customer_Portal";
$db_cms=new DBManager(); 
if($db_cms->connect(DB_HOST,DB_USER,DB_PASS,DB_NAME)==FALSE){
    echo "Could not connect";
    exit;
}
?>