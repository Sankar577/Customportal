<?php 
include("../../lib/config.php"); 
$new_pass = $_POST["pass_str"];
$sql="SELECT * FROM `".DB_PREFIX."_login` WHERE (`user_password`='".$new_pass."')";
$res=$db_cms->select_query_with_row($sql); 
if($res!=FALSE){
	echo '1';
}
else{
	echo '2';
}
?>