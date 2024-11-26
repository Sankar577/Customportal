<?php
ob_start();
session_start();
include("../lib/config.php");

if (empty($_SESSION['user_id']) && empty($_SESSION['user_name'])) {
    $_SESSION["cms_status"] = "error";
    $_SESSION["cms_msg"] = "Please login now!";
    header('Location:login.php');
    exit();
}
$sitetitle = "order_management - " . $site_title;




//  manage settings + action---#
$is_add_enabled = true;
$is_edit_enabled = true;
$is_delete_enabled = true;

$table_name = "" . DB_PREFIX . "_order_details";
$current_page = "order_management.php";

if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "delete") {
    $sql = "DELETE FROM $table_name WHERE `id` = '" . intval($_REQUEST["id"]) . "'"; // Sanitize the ID
    $res = $db_cms->delete_query($sql);

    if ($res !== false) { // Check explicitly for `false`
        $_SESSION["cms_status"] = "success";
        $_SESSION["cms_msg"] = "Deleted successfully!";
        echo "<script>window.location.href = 'order_management.php?message=Deleted Successfully!';</script>";
    }
}



include("include/header.php");
?>

<?php
include("include/sidebar.php");
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Order Management
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Order Management</a></li>
            <li class="active">Order Management</li>
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
                            <h3 class="box-title">Order Management - <?php echo ucfirst($_REQUEST["action"]); ?> </h3>
                            <div class="pull-right">
                                <a class="btn bg-purple" href="<?php echo $current_page; ?>"><i
                                        class="fa  fa-chevron-left"></i> &nbsp;Back</a>
                            </div>
                        <?php
                        } else {
                        ?>
                            <h3 class="box-title">Order Management</h3>
                            <div class="pull-right">

                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="err">

                        <?php
                        if (isset($_GET['message'])) {
                            $message = htmlspecialchars($_GET['message']); // Sanitize the message
                            $status = $_GET['status']; // success or error

                            echo "<div class='status_msg_success'>$message</div>";
                        }

                        // if ($_SESSION["cms_msg"]) {

                        //     echo "<div class='status_msg_success'>'".$_SESSION["cms_msg"]."'</div>";
                        // }
                        ?>

                    </div>
                    <?php
                    $no_action = false;
                    if (!empty($_REQUEST["action"])) {
                        $product_id = "";

                        $product_name = "";
                        $sku = "";
                        $product_price = "";
                        $prod_desc = "";
                        $category = "";


                        if (($_REQUEST["action"] == "edit" || $_REQUEST["action"] == "view") && !empty($_REQUEST["id"])) {
                            $sql = "SELECT * FROM $table_name WHERE id='" . $db_cms->removeQuote($_REQUEST["id"]) . "'";

                            $res = $db_cms->select_query_with_row($sql);
                        }

                        if ($_REQUEST["action"] == "view") {
                    ?>
                            <form role="form" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                <div class="box-body">

                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Customer Name</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $res['custom_name'];
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Order ID</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                echo $res['order_id'];
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Total Price</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                $<?php
                                                    echo $res['total'];
                                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-2">Orders</label>
                                        <div class="col-xs-6">
                                            <div class="view_space">
                                                <?php
                                                // Assuming $res['orders'] contains the JSON string
                                                $orders = json_decode($res['orders'], true);

                                                if (!empty($orders)) {
                                                ?>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Product Name</th>
                                                                <th>Quantity</th>
                                                                <th>Unit Price</th>
                                                                <th>Total Price</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            // Loop through each order and display in table rows
                                                            foreach ($orders as $order) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                                                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                                                    <td>$<?php echo number_format($order['unit_price'], 2); ?></td>
                                                                    <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                                } else {
                                                    echo '<p>No orders found.</p>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="form-group">
                                        <div class="col-xs-2"></div>
                                        <div class="col-xs-6">
                                            <a class="btn bg-purple" href="<?php echo $current_page; ?>"><i
                                                    class="fa  fa-chevron-left"></i> &nbsp;Back</a>
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
                                <a class="btn btn-primary" href="add_order.php?action=add"><i class="fa fa-plus"></i> Add
                                    New</a>
                            </div>
                            <table id="no_search" class="text-center table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th align="center">S.No</th>
                                        <th align="center">Customer Name</th>
                                        <th align="center">Order ID</th>
                                        <th align="center">Total Price</th>

                                        <th align="center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $sql = "SELECT * FROM $table_name order by id desc";
                                    //    print_r($sql);
                                    //    exit();
                                    $res = $db_cms->select_query_with_rows($sql);

                                    if ($res != FALSE) {
                                        $i = 1;

                                        foreach ($res as $row) {

                                    ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td align="center"><?php echo $row["custom_name"]; ?></td>
                                                <td align="center"><?php echo $row["order_id"]; ?></td>
                                                <td align="center"> $<?php echo $row["total"]; ?></td>


                                                <td>
                                                    <a class="btn btn-info" href="?action=view&id=<?php echo $row["id"]; ?>"><i
                                                            class="fa fa-eye"></i> View</a>
                                                    <a class="btn btn-success <?php echo ($is_edit_enabled) ? "" : "disabled"; ?>"
                                                        href="add_order.php?action=edit&id=<?php echo $row['id']; ?>"><i
                                                            class="fa fa-edit"></i> Edit</a>

                                                    <a onClick="return confirmDelete();"
                                                        href="order_management.php?id=<?= $row['id'] ?>&action=delete"
                                                        class="btn btn-danger">Delete</a>

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
    $(function() {
        $(".delete_process").click(function() {
            $(".delete_modal_box").find("#del_proceed").attr("href", "?action=delete&id=" + $(this).data(
                "delete-id"));
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
                    Stock_keeping_unit: {

                        required: true,
                    },

                    Product_price: {
                        required: true,


                    },
                    product_description: {
                        required: true,

                    },
                    Category: {
                        required: true,
                    }
                },
                messages: {
                    product_name: {
                        required: "Please Enter Product Name"
                    },
                    Stock_keeping_unit: {
                        required: "Please Enter Stock Keeping Unit"
                    },
                    Product_price: {
                        required: "Please Enter Product Price"
                    },
                    product_description: {
                        required: "Please Enter Product Description"
                    },
                    Category: {
                        required: "Please Enter Category"
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
            if (FileExt == "gif" || FileExt == "GIF" || FileExt == "JPEG" || FileExt == "jpeg" || FileExt == "jpg" ||
                FileExt == "JPG" || FileExt == "png") {

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