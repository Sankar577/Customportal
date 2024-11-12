<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php
    // Don't edit this range -----------------------------------
    $sitetitle=(isset($sitetitle)?(!empty($sitetitle)?$sitetitle:"Admin Panel 1"):"Admin Panel 3");
    ?>
    <title><?php echo $sitetitle;?></title>
    <?php
    // ---------------------------------------------------------
    ?>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/skins/skin-custom-blue.css">

	
	
	<script src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.min.js"></script>
	<script src="js/jquery.repeater.js"></script>
	<script src="js/jquery.form-repeater.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
  
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>


 
</head>
<body class="hold-transition skin-blue sidebar-mini fixed">
<div class="wrapper">
<header class="main-header">
    <a href="../index.php" class="logo" target="_blank" style="height: 100px;    border: none;padding-top: 10px">
        <span class="logo-mini"><b>Custom Portal</b></span>
        <span class="logo-lg  ">
            <img src="img/portal.jpg"/>
        </span>
    </a>
    <nav class="navbar navbar-static-top" style="height: 25px;">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="img/avatar.png" class="user-image" alt="User Image">
                        <span class="hidden-xs">
                            <?php
                            if(!empty($_SESSION["user_name"])){
                                echo $_SESSION["user_name"];
                            }
                            else{
                                echo "User";
                            }
                            ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="img/avatar.png" class="img-circle" alt="User Image">
                            <p>
                                <?php
                                if(!empty($_SESSION["user_name"])){
                                    echo $_SESSION["user_name"]." - Administrator";
                                }
                                else{
                                    echo "User - Unknown";
                                }
                                ?>

                                <small></small>
                            </p>
                        </li>
                        <li class="user-body">
                            <div class="row">

                            </div>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="changepassword.php" class="btn btn-default btn-flat">Change Password</a>
                            </div>
                            <div class="pull-right">
                                <a href="logout.php" class="btn btn-default btn-flat">Log out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>