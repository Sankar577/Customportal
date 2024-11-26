<?php
ob_start();
session_start();
include("../lib/config.php");
$sitetitle = "Rental_Management- " . $site_title;
$is_add_enabled = true;
$is_edit_enabled = true;
$is_delete_enabled = true;
$table_name = "" . DB_PREFIX . "_rental_management";
$current_page = "rental_Management.php";
$product_id = trim($_REQUEST['product_id']);
$width_w = "1500";
$height_w = "300";
include("error_msg.php");

if (!empty($_POST["submit_action"])) {
    $product_id = $db_cms->removeQuote($_POST["product_id"]);
    $product_name = $db_cms->removeQuote($_POST["product_name"]);
    $custom_name = $db_cms->removeQuote($_POST["cusomer_name"]);
    $rsd =   $db_cms->removeQuote($_POST["Rental_Start_Date"]);
    $rd = $db_cms->removeQuote($_POST["Rental_End_Date"]);
    $amount = $db_cms->removeQuote($_POST["Total_Rental_Amount"]);

    if (!empty($_POST["edit_action"])) {
        $sql_img = "SELECT cm.customer_name, cpm.product_name, cpm.product_id,crm.Rental_End_Date,crm.Rental_Start_Date,crm.Total_Rental_Amount,crm.RentalID
                                FROM
                                custom_rental_management crm
                                INNER JOIN
                          custom_product_management cpm ON crm.product_id = cpm.product_id
                                INNER JOIN
                          custom_customer cm ";


        $res_img = $db_cms->select_query($sql_img);

        $sql =  "UPDATE custom_rental_management crm INNER JOIN custom_product_management cpm ON crm.product_id = cpm.product_id INNER JOIN
       custom_customer cm  SET `Rental_Start_Date`='" . $rsd . "', `Rental_End_Date`='" . $rd . "', `Total_Rental_Amount`='" . $amount . "'
      WHERE crm.product_id='" . $db_cms->removeQuote($_POST["edit_action"]) . "'";
    } else {


        $sql_img = "SELECT product_id FROM custom_rental_management 
       WHERE product_id = $product_id ";



        $res_img = $db_cms->count_query($sql_img);



        if ($res_img != 0) {

            $sql = "UPDATE $table_name  SET `Rental_Start_Date`='" . $rsd . "', `Rental_End_Date`='" . $rd . "', `Total_Rental_Amount`='" . $amount . "' WHERE `product_id`='" . $product_id . "'";
        } else {

            $sql = " INSERT INTO custom_rental_management (`Rental_Start_Date`, `Rental_End_Date`, `Total_Rental_Amount`, `product_id`, `custom_id`)
SELECT 
    '$rsd' AS Rental_Start_Date, 
    '$rd' AS Rental_End_Date, 
    '$amount' AS Total_Rental_Amount, 
    cpm.product_id, 
    cm.custom_id
FROM custom_product_management cpm
JOIN custom_customer cm
WHERE cpm.product_id = $product_id";
        }
    }
    $res = $db_cms->update_query($sql);   // <-  normal query function this


    if ($res != FALSE) {
        $_SESSION["cms_status"] = "success";
        if (!empty($_POST["edit_action"])) {
            $_SESSION["cms_msg"] = "Updated successfully!";
            header('Location:' . $current_page . '');
            exit();
        } else {
            $_SESSION["cms_msg"] = "Added successfully!";
            header('Location:' . $current_page . '');
            exit();
        }
    } else {

        $_SESSION["cms_status"] = "error";
        $_SESSION["cms_msg"] = (!empty($_POST["edit_action"])) ? "Unable to update!" : "Unable to add!";
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
            Rental Management
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Rental Management</a></li>
            <li class="active">Rental Management</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header col-xs-11">
                        <?php
                        if (!empty($_REQUEST["action"])) {
                        ?>
                            <h3 class="box-title">Rental Management - <?php echo ucfirst($_REQUEST["action"]); ?> </h3>
                            <div class="pull-right">
                                <a class="btn bg-purple" href="<?php echo $current_page; ?>"><i class="fa  fa-chevron-left"></i> &nbsp;Back</a>
                            </div>
                        <?php
                        } else {
                        ?>
                            <h3 class="box-title">Rental Management</h3>
                            <div class="pull-right">
                                <!-- <a class="btn bg-maroon <?php echo ($is_add_enabled) ? "" : "disabled"; ?>" href="<?php echo ($is_add_enabled) ? "?action=add" : "javascript:void(0);"; ?>"><i class="fa  fa-plus"></i> &nbsp;Add</a> -->
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="clearfix"></div>
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
                    <?php
                    $no_action = false;
                    if (!empty($_REQUEST["action"])) {

                        $product_id = "";
                        $product_name = "";
                        $custom_name = "";
                        $rsd = "";
                        $rd = "";
                        $Rid = "";


                        if (($_REQUEST["action"] == "edit" || $_REQUEST["action"] == "view") && !empty($_REQUEST["product_id"])) {

                            $sql = "SELECT cm.customer_name, cpm.product_name, cpm.product_id,crm.Rental_End_Date,crm.Rental_Start_Date,crm.Total_Rental_Amount,crm.RentalID
                                FROM
                                custom_rental_management crm
                                INNER JOIN
                                custom_product_management cpm ON crm.product_id = cpm.product_id
                                INNER JOIN
                                custom_customer cm   WHERE cpm.product_id='" . $db_cms->removeQuote($_REQUEST["product_id"]) . "'";

                            $res = $db_cms->select_query($sql);

                            $product_id = get_symbol($res[0]["product_id"]);
                            $Rid = get_symbol($res[0]["RentalID"]);
                            $product_name = get_symbol($res[0]["product_name"]);
                            $custom_name = get_symbol($res[0]["customer_name"]);
                            $rsd = get_symbol($res[0]["Rental_Start_Date"]);
                            $rd = get_symbol($res[0]["Rental_End_Date"]);
                            $amount = get_symbol($res[0]["Total_Rental_Amount"]);
                        }
                        if ($_REQUEST["action"] == "edit" || $_REQUEST["action"] == "add") {

                    ?>
                            <form role="form" class="form-horizontal" action="" method="post" enctype="multipart/form-data" id="banner">
                                <div class="box-body">


                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Product Id<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" name="product_id" id="product_id" class="form-control" value="<?php echo $product_id; ?>" onkeyup="fetchProductData()">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Product Name<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo $product_name; ?>" onkeyup="fetchProductDataByName()" autocomplete="off">
                                                <div id="productSuggestions" class="suggestions" style="display:none"></div>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Customer Name<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" name="customer_name" id="customer_name" class="form-control" value="<?php echo $custom_name; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Rental Start Date<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="date" name="Rental_Start_Date" id="Rental_Start_Date" class="form-control" value="<?php echo $rsd; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Rental End Date<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="date" name="Rental_End_Date" id="Rental_End_Date" class="form-control" value="<?php echo $rd; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Amount<span class="star">*</span>:</label>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <input type="text" name="Total_Rental_Amount" id="Total_Rental_Amount" class="form-control" value="<?php echo $amount; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">&nbsp;</label>
                                        <div class="col-sm-6">
                                            <?php
                                            $action = ($_REQUEST["action"] == "edit") ? "edit_action" : (($_REQUEST["action"] == "add") ? "add_action" : "none_action");
                                            $val = (!empty($res)) ? $res[0]["product_id"] : "1";
                                            ?>
                                            <div class="form-group">
                                                <input type="hidden" name="<?php echo $action; ?>" value="<?php echo $val; ?>" />

                                                <input type="submit" id="submit_action" name="submit_action" value="Submit" class="btn btn-success" />
                                                &nbsp;<a class="btn bg-purple" href="<?php echo $current_page; ?>">Back</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>


                        <?php
                        } elseif ($_REQUEST["action"] == "view") {

                        ?>

                            <form role="form" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Product Id</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $product_id;
                                                ?>
                                            </div>

                                        </div>
                                    </div>


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
                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Customer Name</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $custom_name;
                                                ?>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Rent Start Date</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $rsd;
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Return Date</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $rd;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Amount</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $amount;
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-2"></div>
                                        <div class="col-xs-6">
                                            <a class="btn bg-purple" href="<?php echo $current_page; ?>"><i class="fa  fa-chevron-left"></i> &nbsp;Back</a>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        <?php
                        } else {
                            goto no_action;
                        }
                    } else {
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
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Customer Name</th>
                                        <th>Rent Start Date</th>
                                        <th>Return Date</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $sql = " SELECT cm.customer_name, cpm.product_name,cpm.product_id,crm.Rental_End_Date,crm.Rental_Start_Date,crm.Total_Rental_Amount
                                             FROM
                                             custom_rental_management crm
                                             INNER JOIN
                                            custom_product_management cpm ON cpm.product_id = crm.product_id
                                                INNER JOIN
                                                custom_customer cm ;";





                                    $res = $db_cms->select_query($sql);

                                    if ($res != FALSE) {
                                        $i = 1;

                                        foreach ($res as $row) {

                                    ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $row["product_id"]; ?></td>
                                                <td><?php echo $row["product_name"]; ?></td>
                                                <td><?php echo $row["customer_name"]; ?></td>
                                                <td><?php echo $row["Rental_Start_Date"]; ?></td>
                                                <td><?php echo $row["Rental_End_Date"]; ?></td>
                                                <td><?php echo $row["Total_Rental_Amount"]; ?></td>


                                                <td>
                                                    <a class="btn btn-info" href="?action=view&product_id=<?php echo $row["product_id"]; ?>"><i class="fa fa-eye"></i> View</a>
                                                    <a class="btn btn-success <?php echo ($is_edit_enabled) ? "" : "disabled"; ?>" href="<?php echo ($is_edit_enabled) ? "?action=edit&product_id=" . $row["product_id"] : "javascript:void(0);"; ?>"><i class="fa fa-edit"></i> Edit</a>
                                                    <a onClick="return confirmDelete();" href="rental_Management.php?product_id=<?= $row['product_id'] ?>&action=delete" class="btn btn-danger">Delete</a>

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
    function fetchProductData() {
        var productId = document.getElementById("product_id").value.trim();

        if (productId.length > 0) {
            $.ajax({
                url: 'rental/fetch_id.php',
                type: 'GET',
                data: {
                    id: productId
                },
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data) {
                        $('#product_name').val(data.product.product_name);
                        $('#customer_name').val(data.customer.customer_name);
                    } else {
                        alert('No product found with this ID!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed: ' + error);
                    alert('Error fetching product details!');
                }
            });
        }
    }


    function fetchProductDataByName() {
        var query = $('#product_name').val();

        if (query.length > 0) {
            $.ajax({
                url: 'suggestion.php',
                method: 'POST',
                data: {
                    product_name: query
                },
                dataType: 'json',
                success: function(data) {
                    // Clear previous suggestions
                    $('#productSuggestions').empty().show();


                    if (data.status === 'success' && data.products.length > 0) {


                        data.products.forEach(function(product) {

                            $('#productSuggestions').append('<div class="suggestion-item" onclick="fillProductDetails(\'' + product.product_id + '\')">' + product.product_name + '</div>');

                        });
                    } else {
                        $('#productSuggestions').append('<div class="suggestion-item">No products found</div>');
                    }
                },
                error: function() {
                    $('#productSuggestions').empty().append('<div class="suggestion-item">Error fetching products</div>');
                }
            });
        } else {
            $('#productSuggestions').hide();
        }
    }

    // Function to fill product details when a suggestion is clicked
    function fillProductDetails(product_id) {

        var productid = product_id.trim();

        if (productid.length > 0) {
            $.ajax({
                url: 'rental/rental_name.php',
                type: 'GET',
                data: {
                    product_id: productid
                },
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data) {
                        $('#product_name').val(data.product.product_name);
                        $('#product_id').val(data.product.product_id);
                        $('#customer_name').val(data.customer.customer_name);
                        $('#Stock_keeping_unit').val(data[0].Stock_keeping_unit);
                        $('#Category').val(data[0].Category);
                        $('#Total_Stock').val(data[0].Total_Stock);
                    } else {
                        alert('No product found with this name!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed: ' + error);
                    alert('Error fetching product details!');
                }
            });
        }

        $('#productSuggestions').empty();
    }


    function showProductSuggestions(products) {

        $('#productSuggestions').empty();

        products.forEach(function(product) {
            $('#productSuggestions').append(
                '<div class="suggestion-item" onclick="fillProductDetails(\'' + product.product_name + '\')">' +
                product.product_name +
                '</div>'
            );
        });
    }




    // Fetch product details based on SKU 
    function fetchProductDataBySku() {
        var sku = document.getElementById("Stock_keeping_unit").value.trim();

        if (sku.length > 0) {
            $.ajax({
                url: 'fetch_product_by_sku.php',
                type: 'GET',
                data: {
                    sku: sku
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data && data.product_id) {
                        $('#product_id').val(data.product_id);
                        $('#product_name').val(data.product_name);
                        $('#Product_price').val(data.Product_price);
                        $('#Category').val(data.Category);
                        $('#Total_Stock').val(data.Total_Stock);
                    } else {
                        alert('No product found with this SKU!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed: ' + error);
                    alert('Error fetching product details!');
                }
            });
        }
    }

    $(function() {
        $(".delete_process").click(function() {
            $(".delete_modal_box").find("#del_proceed").attr("href", "?action=delete&id=" + $(this).data("delete-id"));
        });
    });

    function confirmDelete() {
        answer = confirm("Do you want to delete this item?");
        if (answer == 0) {
            return false;
        }
    }

    function bannerimagecheck() {
        document.getElementById('theValue').value = $('input[id=upload_file]').val().replace(/C:\\fakepath\\/i, '');
        return true;
    }

    $(document).ready(function() {

        $('#submit_action').click(function() {
            $("#banner").validate({
                ignore: [],

                rules: {
                    product_name: {
                        required: true,
                    },
                    customer_name: {

                        required: true,
                    },

                    Rental_End_Date: {
                        required: true,


                    },
                    Rental_Start_Date: {
                        required: true,

                    },
                    product_id: {
                        required: true,
                    }
                },
                messages: {
                    product_name: {
                        required: "Please Enter Product Name"
                    },
                    product_id: {
                        required: "Please Enter Product Id"
                    },
                    Rental_Start_Date: {
                        required: "Please Enter Rental Start Date"
                    },
                    Rental_End_Date: {
                        required: "Please Enter Rental End Date"
                    },
                    customer_name: {
                        required: "Please Enter Customer Name"
                    },


                },
            });

        });
    });

    function validation_image() {

        var str = CKEDITOR.instances.banner_subtitle.getData();

        if (str.length > 3000) {
            alert("Please enter 3000 characters only");
            return false;
        }

        var file = document.getElementById('theValue').value;
        var FileExt = file.substr(file.lastIndexOf('.') + 1);

        if (file != "") {
            if (FileExt == "gif" || FileExt == "GIF" || FileExt == "JPEG" || FileExt == "jpeg" || FileExt == "jpg" || FileExt == "JPG" || FileExt == "png") {

                return true;
            } else {
                alert("Upload Gif or Jpg or png images only");
                document.getElementById('theValue').focus();
                return false;
            }
        }
    }

    let table = new DataTable('#no_search', {
        responsive: true

    });
</script>
<?php
include("include/footer.php");
?>
<style>
    /* Style for the suggestions dropdown */
    .suggestions {
        position: absolute;
        border: 1px solid #ccc;
        background-color: #fff;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        z-index: 999;
        display: none;
    }

    .suggestion-item {
        padding: 8px;
        cursor: pointer;
    }

    .suggestion-item:hover {
        background-color: #f0f0f0;
    }
</style>