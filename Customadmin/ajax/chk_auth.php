<?php
session_start();
include_once("../../lib/config.php");
if(!empty($_POST["username"]) && !empty($_POST["password"])){
	$db_cms_res=$db_cms->chkAuth($_POST["username"],$_POST["userpassword"]);
	echo $db_cms_res; exit;
	if(!$db_cms_res){
		echo "2";  //  invalid login
	}
	else{		
		echo "1";  //   success
	}
}
else{
	echo "3";  // field empty
}
?>