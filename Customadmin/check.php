<?php
session_start();
include_once("../lib/config.php");
include_once("../lib/mysqli_class.php");

  if(!empty($_POST["username"]) && !empty($_POST["password"])){
        $db_res=$db_cms->WebAdminLogin($_POST["username"],$_POST["password"]);
        if(!$db_res){
		
			$_SESSION["cms_status"]="error";
			$_SESSION["cms_msg"]="Invalid Username/Password!";
            header("location:login.php");
            exit;
        }
        else{
			
            header("location:index.php");
            exit;
        }
    }
    else{
        echo "666";
        $_SESSION["cms_status"]="error";
        $_SESSION["cms_msg"]="Please enter the values!";
        header("location:login.php");
        exit;
    }
	
?>