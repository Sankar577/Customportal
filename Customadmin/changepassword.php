<?php
//error_reporting(1);
ob_start();
session_start();
include("../lib/config.php");

if (empty($_SESSION['user_id']) && empty($_SESSION['user_name'])) {
    $_SESSION["cms_status"] = "error";
    $_SESSION["cms_msg"] = "Please login now!";
    header('Location:login.php');
    exit();
}

$sitetitle = "Change Password | " . $site_title;

$table_name = "" . DB_PREFIX . "_login";
$current_page = "changepassword.php";

if (!empty($_POST["change_pwd"])) {
    if (!empty($_POST["old_pass"]) && !empty($_POST["pass1"]) && !empty($_POST["pass2"])) {
        if ($_POST["pass1"] == $_POST["pass2"]) {
            $sql = "SELECT `user_id`, `user_name`, `user_password` FROM $table_name WHERE `user_id`='1'";
            $res = $db_cms->select_query_with_row($sql);
            if ($res == TRUE) {
                if ($_POST["old_pass"] == $res["user_password"]) {
                    $sql = "UPDATE $table_name SET `user_password`='" . $db_cms->removeQuote($_POST["pass1"]) . "' WHERE `user_id`='" . $res["user_id"] . "'";
                    $res = $db_cms->update_query($sql);
                    if ($res != FALSE) {
                        $_SESSION["cms_status"] = "success";
                        $_SESSION["cms_msg"] = "Changed successfully!";
                        header('Location:' . $current_page . '');
                        exit();
                    } else {
                        $_SESSION["cms_status"] = "error";
                        $_SESSION["cms_msg"] = "Unable to change password!";
                        header('Location:' . $current_page . '');
                        exit();
                    }
                } else {
                    $_SESSION["cms_status"] = "error";
                    $_SESSION["cms_msg"] = "Invalid old password!";
                    header('Location:' . $current_page . '');
                    exit();
                }
            } else {
                $_SESSION["cms_status"] = "error";
                $_SESSION["cms_msg"] = "Error occurred!";
                header('Location:' . $current_page . '');
                exit();
            }
        } else {
            $_SESSION["cms_status"] = "error";
            $_SESSION["cms_msg"] = "New Password mismatched!";
            header('Location:' . $current_page . '');
            exit();
        }
    } else {
        $_SESSION["cms_status"] = "error";
        $_SESSION["cms_msg"] = "Please fill all fields!";
        header('Location:' . $current_page . '');
        exit();
    }
}

include("include/header.php");

/*  Space in head tag  */
include("include/sidebar.php");
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Change Password
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Change Password</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Change Password</h3>
                    </div>
                    <div class="err">
                        <?php
                        if (!empty($_SESSION["cms_status"]) && !empty($_SESSION["cms_msg"])) {
                            switch ($_SESSION["cms_status"]) {
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
                    <form class="form-horizontal" id="form_submit" method="post" action="">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputPassword1" class="col-sm-2 control-label">Old Password <span class="star">*</span> &nbsp;:&nbsp;</label>

                                <div class="col-sm-6">
                                    <input type="password" name="old_pass" class="form-control" id="inputPassword1" placeholder="Old Password" autocomplete="off" maxlength="25" />
                                    <div class="error" id="err_inputPassword1"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword2" class="col-sm-2 control-label">Password <span class="star">*</span> &nbsp;:&nbsp;</label>

                                <div class="col-sm-6">
                                    <input type="password" name="pass1" class="form-control" id="inputPassword2" placeholder="Password" autocomplete="off" maxlength="25" />
                                    <div class="error" id="err_inputPassword2"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Re Enter Password <span class="star">*</span> &nbsp;:&nbsp;</label>

                                <div class="col-sm-6">
                                    <input type="password" name="pass2" class="form-control" id="inputPassword3" placeholder="Re-Enter Password" autocomplete="off" maxlength="25" />
                                    <div class="error" id="err_inputPassword3"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label"></label>
                                <div class="col-sm-6">
                                    <input type="hidden" name="change_pwd" value="Change" />
                                    <input type="button" name="change_pwd" class="btn btn-info" value="Update Password" onclick="validation()" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<?php
/*  Space in above </body> tag
 *  Use javascript inline, external
 */
?>
<script>
    function validation() {
        var aReqComplete = false;
        web_op = document.getElementById('inputPassword1').value;
        if (web_op === '') {
            document.getElementById("err_inputPassword1").innerHTML = "Please enter old password";
            document.getElementById('inputPassword1').focus();
            return false;
        }

        $.ajax({
            url: 'ajax/chk_pwd.php',
            data: {
                pass_str: web_op
            },
            type: 'post',
            cache: false,
            async: false,
            success: function(data) {
                if (data.toString() == "1") {
                    aReqComplete = true;
                } else if (data.toString() == "2") {
                    if (data.toString() == "2") {
                        document.getElementById("err_inputPassword1").innerHTML = "Invalid old password!";
                        return false;
                    } else {
                        document.getElementById("err_inputPassword1").innerHTML = null;
                    }
                } else if (data.toString() == "21") {
                    document.getElementById("err_inputPassword1").innerHTML = "Error occurred!";
                    return false;
                } else if (data.toString() == "4") {
                    document.getElementById("err_inputPassword1").innerHTML = "Database is busy now! Try again later";
                    return false;
                } else if (data.toString() == "3") {
                    document.getElementById("err_inputPassword1").innerHTML = "Your session has expired. Please login again!";
                    window.location.href = "index.php";
                    return false;
                }
            }
        });

        if (aReqComplete) {
            web_np1 = document.getElementById('inputPassword2').value;
            if (web_np1 == '') {
                document.getElementById("err_inputPassword2").innerHTML = "Please enter new password";
                document.getElementById('inputPassword2').focus();
                return false;
            } else {
                document.getElementById("err_inputPassword2").innerHTML = null;
            }

            web_np2 = document.getElementById('inputPassword3').value;
            if (web_np2 == '') {
                document.getElementById("err_inputPassword3").innerHTML = "Please re-enter new password";
                document.getElementById('inputPassword3').focus();
                return false;
            } else {
                document.getElementById("err_inputPassword3").innerHTML = null;
            }

            if (web_np1 != web_np2) {
                document.getElementById("err_inputPassword3").innerHTML = "New password mismatched";
                document.getElementById('inputPassword3').focus();
                return false;
            } else {
                document.getElementById("err_inputPassword3").innerHTML = null;
            }
            document.getElementById("form_submit").submit();
            return true;
        }
        return false;
    }
</script>
<?php
/*  Space in above </body> tag  */
include("include/footer.php");
?>