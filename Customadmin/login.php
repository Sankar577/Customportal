<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Panel - Log in</title>
	 <link rel="shortcut icon" href="img/c.webp">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/square/blue.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">   
</head>
<body class="hold-transition login-page">
<div class="login-box">
   
    <!-- login-logo -->
    <div class="login-box-body">
         <div class="login-logo">
            <a href="../index.php" target="_blank">
                <img src="img/portal.jpg" alt="images"/>
            </a>
        </div>
        <p class="login-box-msg">Admin Panel</p>

        <div class="err">
            <?php
            if(!empty($_SESSION["cms_status"]) && !empty($_SESSION["cms_msg"])){
                switch($_SESSION["cms_status"]){
                    case 'error':
                        ?>
                        <div class="status_msg_error">
                            <?php
                            echo $_SESSION["cms_msg"];
                            ?>
                        </div>
                        <?php
                        break;
                    case 'success':
                        ?>
                        <div class="status_msg_success">
                            <?php
                            echo $_SESSION["cms_msg"];
                            ?>
                        </div>
                        <?php
                        break;
                }
                unset($_SESSION["cms_status"]);
                unset($_SESSION["cms_msg"]);
            }
            ?>
        </div>

        <form action="check.php" id="form_submit" method="post">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" maxlength="15" autocomplete="off">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" maxlength="25">
                <span class="glyphicon glyphicon-lock form-control-feedback" autocomplete="off"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">

                    </div>
                </div>
                <div class="col-xs-4">
                    <input type="hidden" name="change_pwd" value="Change"/>
                    <input type="submit" class="btn btn-primary btn-block btn-flat" value="Sign In" onclick="return validation()"/>
                </div>
            </div>
        </form>     
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/icheck.min.js"></script>
<script>
    function validation()	
	{		
        $(".err").html("");
        web_user = document.getElementById('username').value;
        if(web_user === '')
        {
			
            $(".err").html("<div class=\"status_msg_error\"></div>");
            $(".err").find(".status_msg_error").html("Please enter username!").removeClass("shake_text").addClass("shake_text");
            document.getElementById('username').focus();
            return false;
        }

        web_pass = document.getElementById('password').value;
        if(web_pass === '')
        {
			
            $(".err").html("<div class=\"status_msg_error\"></div>");
            $(".err").find(".status_msg_error").html("Please enter password!").removeClass("shake_text").addClass("shake_text");
            document.getElementById('password').focus();
            return false;
        }
    }
        

    $(function () 
	{
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });

        $('#username').on("keydown",function(){
            if($(".err").find(".status_msg_error").length>0){
                $(".status_msg_error").detach();
            }
        });

        $('#password').on("keydown",function(){
            if($(".err").find(".status_msg_error").length>0){
                $(".status_msg_error").detach();
            }
        });
    });
</script>
</body>
</html>