<?php 
ob_start();
session_start();
include("../lib/config.php"); 

if(empty($_SESSION['user_id']) && empty($_SESSION['user_name'])){
    $_SESSION["cms_status"]="error";
    $_SESSION["cms_msg"]="Please login now!";
    header('Location:login.php');
    exit();
} 
$sitetitle="Customer_Management - ".$site_title;

//  manage settings + action---#
$is_add_enabled=true;
$is_edit_enabled=true;
$is_delete_enabled=true;

$table_name="".DB_PREFIX."_customer";
$current_page="Customer_management.php";
$custom_id=trim($_REQUEST['custom_id']);
$width_w="1500";
$height_w="300";


if(!empty($_REQUEST["action"])) {
    if ($_REQUEST["action"] == "edit" || $_REQUEST["action"] == "add" || $_REQUEST["action"] == "delete" || $_REQUEST["action"] == "view" ) {
        if($_REQUEST["action"] == "edit" && !$is_edit_enabled){
            $_SESSION["cms_status"]="error";
            $_SESSION["cms_msg"]="Edit action disabled!";
            header('Location:'.$current_page.'');
            exit();
        }
        if($_REQUEST["action"] == "add" && !$is_add_enabled){
            $_SESSION["cms_status"]="error";
            $_SESSION["cms_msg"]="Add action disabled!";
            header('Location:'.$current_page.'');
            exit();
        }
        if($_REQUEST["action"] == "delete" && !$is_delete_enabled){
            $_SESSION["cms_status"]="error";
            $_SESSION["cms_msg"]="Delete action disabled!";
            header('Location:'.$current_page.'');
            exit();
        }

        if($_REQUEST["action"] == "delete" && $is_delete_enabled && !empty($_REQUEST["custom_id"])){
            $sql="DELETE FROM $table_name WHERE `custom_id`='".$db_cms->removeQuote($_REQUEST["custom_id"])."'";
            $res=$db_cms->delete_query($sql);
            if($res!=FALSE){
                $_SESSION["cms_status"]="success";
                $_SESSION["cms_msg"]="Deleted successfully!";
                header('Location:'.$current_page.'');
                exit();
            }
            else{
                $_SESSION["cms_status"]="error";
                $_SESSION["cms_msg"]="Unable to delete!";
                header('Location:'.$current_page.'');
                exit();
            }
        }
    }
    else{
        header('Location:'.$current_page.'');
        exit();
    }
}
// banner images
 if(!empty($_FILES["upload_file"]) && $_FILES["upload_file"]["error"]!=4) {
        if (validate_image($_FILES["upload_file"])) {
    if ($_FILES['upload_file']['name'] != "") {
        list($width,$height) = getimagesize($_FILES['upload_file']['tmp_name']);
        if($width<1500||$height<300)
        { 
            include('../includes/resize.php');
            $get_image = $_FILES['upload_file']['name'];
            $source = $_FILES['upload_file']['tmp_name'];
            $get_image = time().$get_image;
            $originalpath = "../webupload/original/client/".$get_image;
            $thumbnailpath = "../webupload/thumb/client/".$get_image;
            move_uploaded_file($source,$originalpath);
			 $objimg = new SimpleImage();
           $objimg -> load($originalpath);
            $objimg -> resize(1500,300);
            $objimg -> save($thumbnailpath);
			$objimg -> resize(197,97);
            $objimg -> save($smallpath);
		
			
        }
        else
        { 
            include('../includes/resize.php');
            $get_image = $_FILES['upload_file']['name'];
            $source = $_FILES['upload_file']['tmp_name'];
            $get_image = time().$get_image;
            $originalpath = "../webupload/original/client/".$get_image;
            $thumbnailpath = "../webupload/thumb/client/".$get_image;
            move_uploaded_file($source,$originalpath);
           $objimg = new SimpleImage();
            $objimg -> load($originalpath);
            $objimg -> resize(1500,300);
            $objimg -> save($thumbnailpath);
			$objimg -> resize(197,97);
            $objimg -> save($smallpath);
			
			

        }
    }
        }
}
else { 
    $get_image = get_entity($_REQUEST['theValue']); 
	
} 

$upload_option=$_POST["upload_option"];
if($upload_option=='html'){
$banner_image1=$_POST["banner_image1"];
$banner_image="";
}
else{
$banner_image1="";
$banner_image=$get_image;
}
if(!empty($_POST["submit_action"]) && (!empty($_POST["edit_action"]) || !empty($_POST["add_action"]))){   
    $custom_name = $db_cms->removeQuote($_POST["customer_name"]); 
    $ph_num = $db_cms->removeQuote($_POST["phone_number"]);
    $email =   $db_cms->removeQuote($_POST["email"]);
    $address= $db_cms->removeQuote($_POST["address"]);
    if(!empty($_POST["edit_action"])){
		$sql_img="SELECT phone_number FROM $table_name WHERE custom_id='".$db_cms->removeQuote($_REQUEST["custom_id"])."'";
		$res_img=$db_cms->select_query_with_rows($sql_img);	
		$sql="UPDATE custom_customer SET `customer_name`='Sankara',`phone_number`='9626585077',`email`='uthra.math@example.com', `address`='321 Birch St,chennai, IL' WHERE `custom_id`='1'";

        // print_r($sql);
        // exit();
        
    }
    else{
        $sql="INSERT INTO $table_name(`customer_name`,`phone_number`,`email`,`address`) VALUES ('".$custom_name."','".$ph_num."','".$email."','".$address."')";
	
    }
    $res = $db_cms->update_query($sql);   // <-  normal query function this

    if($res!=FALSE){
        $_SESSION["cms_status"]="success";
        if(!empty($_POST["edit_action"])){
            $_SESSION["cms_msg"]="Updated successfully!";
            header('Location:'.$current_page.'');
            exit();
        }
        else{
            $_SESSION["cms_msg"]="Added successfully!";
            header('Location:'.$current_page.'');
            exit();
        }
    }
    else{
        
        $_SESSION["cms_status"]="error";
        $_SESSION["cms_msg"]=(!empty($_POST["edit_action"]))?"Unable to update!":"Unable to add!";
    }  
} 

// -------------------#

include("include/header.php"); 
?>

<?php
include("include/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
            Customer management
                <small>Control panel</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i>Customer management</a></li>
                <li class="active">Customer management</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header col-xs-11">
                            <?php
                            if(!empty($_REQUEST["action"])){
                                ?>
                                <h3 class="box-title">Customer management - <?php echo ucfirst($_REQUEST["action"]);?> </h3>
                                <div class="pull-right">
                                    <a class="btn bg-purple" href="<?php echo $current_page;?>"><i class="fa  fa-chevron-left"></i> &nbsp;Back</a>
                                </div>
                                <?php
                            }
                            else{
                                ?>
                                <h3 class="box-title">customer management</h3>
                                <div class="pull-right">
                                  <!-- <a class="btn bg-maroon <?php echo ($is_add_enabled)?"":"disabled";?>" href="<?php echo ($is_add_enabled)?"?action=add":"javascript:void(0);";?>"><i class="fa  fa-plus"></i> &nbsp;Add</a> -->
                                </div>  
                                <?php
                            }
                            ?>
                        </div>
                        <div class="clearfix"></div>
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
                        <?php
                        $no_action=false;
                        if(!empty($_REQUEST["action"])){
                            $custom_id=""; 
                          
                            $custom_name="";
                            $ph_num="";
                            $email="";
                            $address="";
                           
                           
                            if(($_REQUEST["action"]=="edit" || $_REQUEST["action"]=="view") && !empty($_REQUEST["custom_id"])){
                                $sql="SELECT * FROM $table_name WHERE custom_id='".$db_cms->removeQuote($_REQUEST["custom_id"])."'";
                                $res=$db_cms->select_query_with_row($sql);
                                $custom_id=$res["custom_id"];
                                $custom_name=get_symbol($res["customer_name"]);
                                $ph_num=get_symbol($res["phone_number"]);
                                $email=get_symbol($res["email"]);   
                                $address=get_symbol($res["address"]);                            
                            }
                            if($_REQUEST["action"]=="edit" || $_REQUEST["action"]=="add"){
                                ?>
                        <form role="form" class="form-horizontal" action="" method="post" enctype="multipart/form-data" id="banner">
                            <div class="box-body">

                                 <div class="form-group">
                                        <label class="control-label col-xs-2">Customer name<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" name="customer_name" id="banner_title" class="form-control"  value="<?php echo $custom_name;?>" >
                                            </div>
                                        </div>
                                </div>    
                                <div class="form-group">
                                        <label class="control-label col-xs-2">phone number<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" name="phone_number" id="banner_title" class="form-control"  value="<?php echo $ph_num;?>">
                                            </div>
                                        </div>
                                </div>  
                                <div class="form-group">
                                        <label class="control-label col-xs-2">Email<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" name="email" id="banner_title" class="form-control"  value="<?php echo $email;?>" >
                                            </div>
                                        </div>
                                </div>  
                                <div class="form-group">
                                        <label class="control-label col-xs-2">Address<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" name="address" id="banner_title" class="form-control"  value="<?php echo $address;?>">
                                            </div>
                                        </div>
                                </div>  
                                

					
								<!-- <div class="form-group">
                                    <label class="col-sm-2 control-label">Image<span class="star">*</span>:</label>
                                    <div class="col-sm-6">
                                        <?php if($_REQUEST["action"]!="add"){  ?>
                                            <div>
                                                <img src="<?php echo $sitepath;?>webupload/thumb/client/<?php echo $banner_path;?>" alt="Image" style="width:500px;"/>
                                            </div>
                                            <br/>
                                        <?php  }  ?>
                                        <div class="form-group">
                                            <input type="file" id="upload_file" name="upload_file" accept=".jpeg, .jpg, .png, .gif"   onchange="return bannerimagecheck();"/>
                                            <p class="help-block">(Required Image Size (Width*Height):<?=$width_w?>*<?=$height_w?>. Upload .jpg, .jpeg, .gif, .png)</p>
                                            <input type="hidden" value="<?=$banner_path?>" id="theValue" name="theValue" />
                                        </div>
                                    </div>
                                </div> -->

                               
                                 

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">&nbsp;</label>
                                    <div class="col-sm-6">
                                        <?php
                                            $action=($_REQUEST["action"]=="edit")?"edit_action":(($_REQUEST["action"]=="add")?"add_action":"none_action");
                                            $val=(!empty($res))?$res["custom_id"]:"1";
                                            ?>
                                            <div class="form-group">
                                                <input type="hidden" name="<?php echo $action;?>" value="<?php echo $val;?>"/>
                                                <input type="submit" id="submit_action" name="submit_action" value="Submit" class="btn btn-success" /> 
                                                 &nbsp;<a class="btn bg-purple" href="<?php echo $current_page;?>">Back</a> 
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                                <?php
                            }
                            elseif($_REQUEST["action"]=="view"){
                                ?>
                        <form role="form" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                            <div class="box-body">

                                <div class="form-group">
                                        <label class="control-label col-xs-2">Name</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $custom_name;
                                                ?>
                                            </div>
                                        </div>
                                </div>
									<?php if($ph_num!=''){?>		
									<div class="form-group">
                                        <label class="control-label col-xs-2">phone_number</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $ph_num;
                                                ?>
                                            </div>
                                        </div>
                                </div>
									<?php } ?>
                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Email</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $email;
                                                ?>
                                            </div>
                                        </div>
                                </div>

                                <div class="form-group">
                                        <label class="control-label col-xs-2">Address</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $address;
                                                ?>
                                            </div>
                                        </div>
                                </div>
                              
                                 <div class="form-group">
                                    <div class="col-xs-2"></div>
                                    <div class="col-xs-6">
                                      <a class="btn bg-purple" href="<?php echo $current_page;?>"><i class="fa  fa-chevron-left"></i> &nbsp;Back</a>
                                  </div>
                                </div>
                             
                            </div>
                        </form>
                                <?php
                            }
                            else{
                                goto no_action;
                            }
                        }
                        else {
                            no_action:
                            $no_action = true;
                            ?>
                            <div class="box-body">
                            <div class="text-right mb-3">
    <a class="btn btn-primary" href="?action=add"><i class="fa fa-plus"></i> Add New</a>
</div>
                            <table id="no_search" class="text-center table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Customer_name</th>
                                    <th>phone_number</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                   
                                <?php
                                $sql="SELECT * FROM $table_name where custom_id order by custom_id asc";
                            
                                $res=$db_cms->select_query_with_rows($sql);
                                if($res!=FALSE){
                                    $i=1;
                                    foreach ($res as $row){
                                        ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                          <td><?php echo $row["customer_name"];?></td>
                                          <td><?php echo $row["phone_number"];?></td>
                                          <td><?php echo $row["email"];?></td>
                                          <td><?php echo $row["address"];?></td>
                                            <td>
                                                <a class="btn btn-info" href="?action=view&custom_id=<?php echo $row["custom_id"];?>"><i class="fa fa-eye"></i> View</a>
                                                <a class="btn btn-success <?php echo ($is_edit_enabled)?"":"disabled";?>" href="<?php echo ($is_edit_enabled)?"?action=edit&custom_id=".$row["custom_id"]:"javascript:void(0);";?>"><i class="fa fa-edit"></i> Edit</a>
                                                <a onClick="return confirmDelete();" href="Customer_management.php?custom_id=<?=$row['custom_id']?>&action=delete" class="btn btn-danger">Delete</a>
                                             
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                            </div>
                        <?php
                        }
                    ?>
                    </div>
                </div>
            </div>
        </section>
    </div> 


    <script>
        $(function () {             
            $(".delete_process").click(function(){
                $(".delete_modal_box").find("#del_proceed").attr("href","?action=delete&id="+$(this).data("delete-id"));
            });
        }); 
        function confirmDelete(){   
            answer = confirm("Do you want to delete this item?");
            if (answer ==0) 
            { 
                return false;
            }   
        } 
        
        function bannerimagecheck()
        {   
            document.getElementById('theValue').value=$('input[id=upload_file]').val().replace(/C:\\fakepath\\/i, ''); 
            return true;
        }

        $(document).ready(function () {
            
        $('#submit_action').click(function() {   
        $("#banner").validate({
			    ignore: [],
       
              rules: {
                    customer_name:{
                        required:true
                    },   
                    phone_number:{
                    
                        required:true
                    },	

                    email:{
                        required:true,
				
						
                     },
                     address:{
                        required:true,
                        
                    },
                },        
            messages:{
                customer_name:{required:"Please enter Customer name"}, 
				phone_number:{required:"Please enter phone number"}, 
                email:{required:"Please enter Email"},         
               address :{required:"Please enter address"},         
                     
                     
            },
        }); 
            
        });
        });
	function validation_image(){
	
		var str = CKEDITOR.instances.banner_subtitle.getData();	
	
		if(str.length>3000){
			alert("Please enter 3000 characters only");	
			return false;
		}
		
		var file= document.getElementById('theValue').value;
		var FileExt = file.substr(file.lastIndexOf('.')+1);
		
		if(file!="")
		{	
		if(FileExt == "gif" || FileExt == "GIF" || FileExt == "JPEG" || FileExt == "jpeg" || FileExt == "jpg" || FileExt == "JPG" || FileExt == "png")
		{
		
		return true;
		} 
		else
		{
		alert("Upload Gif or Jpg or png images only");		
		document.getElementById('theValue').focus();
		return false;
		}
		}
		}


//         $(document).ready( function () {
//     $('#no_search').DataTable()
// } );
let table = new DataTable('#no_search', {
    responsive: true
   
});
// $('#no_search').DataTable( {
//     ordering: true
//     searching: true,
// } );
    </script>

<?php 
include("include/footer.php");
?>