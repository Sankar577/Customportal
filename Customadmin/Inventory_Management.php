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
$sitetitle="Inventory_Management- ".$site_title;

//  manage settings + action---#
$is_add_enabled=true;
$is_edit_enabled=true;
$is_delete_enabled=true;

$table_name="".DB_PREFIX."_inventory_management";
$current_page="Inventory_Management.php";
$product_id=trim($_REQUEST['product_id']);
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

        if($_REQUEST["action"] == "delete" && $is_delete_enabled && !empty($_REQUEST["product_id"])){
            $sql="DELETE FROM $table_name WHERE `product_id`='".$db_cms->removeQuote($_REQUEST["product_id"])."'";
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

if(!empty($_POST["submit_action"]) ){  
   
    $product_name = $db_cms->removeQuote($_POST["product_name"]); 
    $sku = $db_cms->removeQuote($_POST["Stock_keeping_unit"]);
    $product_price =   $db_cms->removeQuote($_POST["Product_price"]);
    $prod_desc= $db_cms->removeQuote($_POST["product_description"]);
    $category= $db_cms->removeQuote($_POST["Category"]);
    if(!empty($_POST["edit_action"])){
        
		$sql_img= " SELECT
    cim.InventoryID,
    cim.Total_Stock,
    cpm.product_id,
    cpm.product_name
FROM
    custom_Inventory_Management cim
INNER JOIN
    custom_product_management cpm ON cim.product_id = cpm.product_id";
   

		$res_img=$db_cms->select_query($sql_img);	
       
		$sql="UPDATE $table_name SET `product_name`='".$product_name."',`Stock_keeping_unit`='".$sku."',`Product_price`='".$product_price."', `product_description`='".$prod_desc."', `Category`='".$category."' WHERE `product_id`='".$db_cms->removeQuote($_POST["edit_action"])."'";
        
    }
    else{
        $sql="INSERT INTO $table_name(`product_name`,`Stock_keeping_unit`,`Product_price`,`product_description`,`Category`) VALUES ('".$product_name."','".$sku."','".$product_price."','".$prod_desc."', '".$category."')";
	
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
            Inventory Management
                <small>Control panel</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i>Inventory Management</a></li>
                <li class="active">Inventory Management</li>
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
                                <h3 class="box-title">Inventory Management - <?php echo ucfirst($_REQUEST["action"]);?> </h3>
                                <div class="pull-right">
                                    <a class="btn bg-purple" href="<?php echo $current_page;?>"><i class="fa  fa-chevron-left"></i> &nbsp;Back</a>
                                </div>
                                <?php
                            }
                            else{
                                ?>
                                <h3 class="box-title">Inventory Management</h3>
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
                            
                            $product_id=""; 
                            $product_name="";
                            $sku="";
                            $product_price="";
                            $prod_desc="";
                            $category="";
                           
                           
                            if(($_REQUEST["action"]=="edit" || $_REQUEST["action"]=="view") && !empty($_REQUEST["product_id"])){
                       
                                $sql="SELECT cim.InventoryID, cim.Total_Stock,cpm.product_id,cpm.product_name,cpm.Product_price,cpm.Category
                                      FROM
                                       custom_Inventory_Management cim
                                        INNER JOIN
                                       custom_product_management cpm ON cim.product_id = cpm.product_id;";
                              
                                // $sql="SELECT * FROM $table_name WHERE product_id='".$db_cms->removeQuote($_REQUEST["product_id"])."'";
                                $res=$db_cms->select_query($sql);
                          
                            
                                $product_id=$res["product_id"];
                                $product_name=get_symbol($res[0]["product_name"]);
                                $sku=get_symbol($res[0]["Stock_keeping_unit"]);
                                $product_price=get_symbol($res [0]["Product_price"]);   
                                $prod_desc=get_symbol($res["product_description"]);   
                                $category=get_symbol($res[0]["Category"]);  
                                $Total_stack=get_symbol($res[0]["Total_Stock"]);   
                                  
                            }
                            if($_REQUEST["action"]=="edit" || $_REQUEST["action"]=="add"){
                        
                                ?>
                        <form role="form" class="form-horizontal" action="" method="post" enctype="multipart/form-data" id="banner">
                            <div class="box-body">

                                 <div class="form-group">
                                        <label class="control-label col-xs-2">Product Name<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" name="product_name" id="product_name" class="form-control"  value="<?php echo $product_name;?>"readonly >
                                            </div>
                                        </div>
                                </div>    
                               
                                <div class="form-group">
                                        <label class="control-label col-xs-2">Product Price<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="number" min="0" name="Product_price" id="Product_price" class="form-control"  value="<?php echo $product_price;?>"readonly >
                                            </div>
                                        </div>
                                </div> 
                                <div class="form-group">
                                        <label class="control-label col-xs-2">Category<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" name="Category" id="Category" class="form-control"  value="<?php echo $category;?>"readonly>
                                            </div>
                                        </div>
                                </div>  
                                <div class="form-group">
                                        <label class="control-label col-xs-2">Total Stock<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" name="Total_Stock" id="Total_Stock" class="form-control"  value="<?php echo $Total_stack;?>">
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
                
                                            $val=(!empty($res))?$res["product_id"]:"1";
                                            
                                            ?>
                                            <div class="form-group">
                                                <input type="hidden" name="<?php echo $action; ?>" value="<?php echo $val;?>"/>
                                                
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
                                        <label class="control-label col-xs-2">Product Name</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $product_name;
                                                ?>
                                            </div>
                                        </div>
                                </div>
									<?php if($product_price!=''){?>		
									<div class="form-group">
                                        <label class="control-label col-xs-2">Product Price</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $product_price;
                                                ?>
                                            </div>
                                        </div>
                                </div>
									<?php } ?>

                                <div class="form-group">
                                        <label class="control-label col-xs-2">Category</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $category;
                                                ?>
                                            </div>
                                        </div>
                                </div>
                                <div class="form-group">
                                        <label class="control-label col-xs-2">Total Stock</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $Total_stack;
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
                                    <th>Product Name</th>
                                    <th>Product Price</th>
                                    <th>Category</th>
                                    <th>Total Stock</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                   
                                <?php
                                $sql="SELECT cim.InventoryID, cim.Total_Stock,cpm.product_id,cpm.product_name,cpm.Product_price,cpm.Category
                                      FROM
                                       custom_Inventory_Management cim
                                        INNER JOIN
                                       custom_product_management cpm ON cim.product_id = cpm.product_id;";

                            
                                $res=$db_cms->select_query($sql);
                                // print_r($res);
                                // exit();
                                if($res!=FALSE){
                                    $i=1;
                                   
                                    foreach ($res as $row){
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                          <td><?php echo $row["product_name"];?></td>
                                          <td><?php echo $row["Product_price"];?></td>
                                          <td><?php echo $row["Category"];?></td>
                                          <td><?php echo $row["Total_Stock"];?></td>

                                            <td>
                                                <a class="btn btn-info" href="?action=view&product_id=<?php echo $row["product_id"];?>"><i class="fa fa-eye"></i> View</a>
                                                <a class="btn btn-success <?php echo ($is_edit_enabled)?"":"disabled";?>" href="<?php echo ($is_edit_enabled)?"?action=edit&product_id=".$row["product_id"]:"javascript:void(0);";?>"><i class="fa fa-edit"></i> Edit</a>
                                                <a onClick="return confirmDelete();" href="Product_management.php?product_id=<?=$row['product_id']?>&action=delete" class="btn btn-danger">Delete</a>
                                             
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
                product_name:{
                        required:true,
                    },   
                    Stock_keeping_unit:{
                    
                        required:true,
                    },	

                    Product_price:{
                        required:true,
				
						
                     },
                     product_description:{
                        required:true,
                        
                    },
                    Category:{
                        required:true,
                    }
                },        
            messages:{
                product_name:{required:"Please Enter Product Name"}, 
				Stock_keeping_unit:{required:"Please Enter Stock Keeping Unit"}, 
                Product_price:{required:"Please Enter Product Price"},         
                product_description:{required:"Please Enter Product Description"},    
                Category:{required:"Please Enter Category"},        
                     
                     
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