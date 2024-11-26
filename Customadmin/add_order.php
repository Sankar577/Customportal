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
$sitetitle = "Product_Management - " . $site_title;

//  manage settings + action---#
$is_add_enabled = true;
$is_edit_enabled = true;
$is_delete_enabled = true;

$table_customer = "" . DB_PREFIX . "_customer";
$table_product = "" . DB_PREFIX . "_product_management";
$current_page = "add_order.php";

$custome_sql = "SELECT * FROM $table_customer order by custom_id desc";
$res_cus = $db_cms->select_query($custome_sql);

$product_sql = "SELECT * FROM $table_product order by product_id desc";
$product_res = $db_cms->select_query($product_sql);

if ($_REQUEST['action'] == "edit") {
    $edit_mode = true;
    $order_sql = "SELECT * FROM custom_order_details where id = '" . $_REQUEST['id'] . "'";
    $order_res = $db_cms->select_query($order_sql);
    $order_products = json_decode($order_res[0]['orders'], true);
    $customer_id = $order_res[0]['custom_id'];
    $grant_total = $order_res[0]['total'];
    $id = $_REQUEST['id'];
}
include("include/header.php");

?>
<link rel="stylesheet" href="css/custom_file.css">
<?php
include("include/sidebar.php");
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Product Management
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Product Management</a></li>
            <li class="active">Product Management</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box" style="padding:30px;">
                    <h2>Add Products</h2>
                    <div class="form-row" id="formRow">
                        <!-- Customer Dropdown -->
                        <select id="customer">
                            <option value="">Select Customer</option>
                            <?php if (!empty($res_cus)) {
                                foreach ($res_cus as $customer) { ?>
                                    <option value="<?= $customer['custom_id'] ?>"
                                        <?= ($customer['custom_id'] == $customer_id) ? 'selected' : '' ?>>
                                        <?= $customer['customer_name'] ?>
                                    </option>
                            <?php }
                            } ?>
                        </select>

                        <!-- Product Dropdown -->
                        <select id="product">
                            <option value="">Select Product</option>
                            <?php if (!empty($product_res)) {
                                foreach ($product_res as $product) { ?>
                                    <option value="<?= $product['product_id'] ?>" data-price="<?= $product['Product_price'] ?>">
                                        <?= $product['product_name'] ?>
                                    </option>
                            <?php }
                            } ?>
                        </select>

                        <!-- Quantity Input -->
                        <input type="number" id="quantity" placeholder="Quantity" min="1">
                        <button class="add-button" onclick="addProduct()">Add</button>
                    </div>
                    <div class="pull-right">
                        <?php
                        $buttonText = ($_REQUEST['action'] == "edit") ? "Edit Order" : "Save Order";
                        ?>
                        <button class="btn same-size btn-success"
                            onclick="saveOrder()"><?php echo $buttonText; ?></button>

                        <a class="btn same-size bg-purple" href="order_management.php">
                            <i class="fa fa-chevron-left"></i> &nbsp;Back
                        </a>
                    </div>
                    <!-- Updated Table to Display Products -->
                    <table id="productTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($order_products)) {
                                foreach ($order_products as $product) {
                                    $product_id = $product['product_id'];
                                    $product_name = $product['product_name'];
                                    $quantity = $product['quantity'];
                                    $unit_price = $product['unit_price'];
                                    $total_price = $product['total_price'];
                            ?>
                                    <tr data-product-id="<?= $product_id ?>">
                                        <td><?= $product_name ?></td>
                                        <td><?= $quantity ?></td>
                                        <td><?= number_format($unit_price, 2) ?></td>
                                        <td class="total-price"><?= number_format($total_price, 2) ?></td>
                                        <td><button class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong>Grand Total:</strong></td>
                                <td id="grandTotal"><?= number_format($grant_total, 2) ?></td>

                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Save Order Button -->
                    <!-- <button class="save-button" onclick="saveOrder()">Save Order</button> -->


                    <script>
                        function addProduct() {
                            const productSelect = document.getElementById('product');
                            const quantityInput = document.getElementById('quantity');

                            const productId = productSelect.value;
                            const productName = productSelect.options[productSelect.selectedIndex].text;
                            const productPrice = parseFloat(productSelect.options[productSelect.selectedIndex].getAttribute(
                                'data-price'));
                            const quantity = parseInt(quantityInput.value);

                            if (!productId || !quantity || isNaN(productPrice)) {
                                alert('Please select a product and enter a valid quantity.');
                                return;
                            }

                            // Check if the product already exists in the table
                            const existingRow = document.querySelector(
                                `#productTable tbody tr[data-product-id="${productId}"]`);
                            if (existingRow) {
                                // If the product exists, update the quantity and total price
                                const existingQuantityCell = existingRow.cells[1];
                                const existingTotalPriceCell = existingRow.cells[3];

                                const existingQuantity = parseInt(existingQuantityCell.textContent);
                                const newQuantity = existingQuantity + quantity;
                                const newTotalPrice = newQuantity * productPrice;

                                existingQuantityCell.textContent = newQuantity;
                                existingTotalPriceCell.textContent = newTotalPrice.toFixed(2);
                            } else {
                                // If the product doesn't exist, add a new row
                                const totalPrice = productPrice * quantity;

                                const tableBody = document.querySelector('#productTable tbody');
                                const newRow = document.createElement('tr');
                                newRow.setAttribute('data-product-id', productId);
                                newRow.innerHTML = `
            <td>${productName}</td>
            <td>${quantity}</td>
            <td>${productPrice.toFixed(2)}</td>
            <td class="total-price">${totalPrice.toFixed(2)}</td>
            <td><button class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
        `;
                                tableBody.appendChild(newRow);
                            }

                            // Update the grand total
                            updateGrandTotal();

                            // Clear the inputs after adding
                            productSelect.value = '';
                            quantityInput.value = '';
                        }

                        function updateGrandTotal() {
                            let grandTotal = 0;
                            const totalCells = document.querySelectorAll('#productTable .total-price');
                            totalCells.forEach(cell => {
                                grandTotal += parseFloat(cell.textContent);
                            });
                            document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
                        }

                        function removeRow(button) {
                            button.parentElement.parentElement.remove();
                            updateGrandTotal();
                        }

                        function saveOrder() {
                            var isEditMode = <?php echo $edit_mode ? 'true' : 'false'; ?>;
                            var Id = <?php echo $id ? "'$id'" : 'null'; ?>;
                            const rows = document.querySelectorAll('#productTable tbody tr');
                            const customerSelect = document.getElementById('customer');
                            const customerId = customerSelect.value;
                            const customerName = customerSelect.options[customerSelect.selectedIndex]?.text || '';
                            const grandTotal = document.getElementById('grandTotal').textContent;

                            if (!customerId) {
                                alert('Please select a customer.');
                                return;
                            }

                            if (rows.length === 0) {
                                alert('Please add at least one product.');
                                return;
                            }

                            const orderData = {
                                customer_id: customerId,
                                customer_name: customerName,
                                grand_total: grandTotal,
                                products: []
                            };

                            rows.forEach(row => {
                                const productId = row.getAttribute('data-product-id');
                                const productName = row.cells[0].textContent;
                                const quantity = row.cells[1].textContent;
                                const unitPrice = row.cells[2].textContent;
                                const totalPrice = row.cells[3].textContent;

                                orderData.products.push({
                                    product_id: productId,
                                    product_name: productName,
                                    quantity: quantity,
                                    unit_price: unitPrice,
                                    total_price: totalPrice
                                });
                            });

                            const orderJSON = JSON.stringify(orderData);

                            // Add the 'action' flag if it's an edit operation
                            const action = isEditMode ? 'edit' : 'add'; // Pass "edit" or "add" based on the mode

                            $.ajax({
                                url: 'order/save_order.php',
                                type: 'POST',
                                data: {
                                    action: action, // Pass the action (edit or add)
                                    Id: isEditMode ? Id : null,
                                    orderData: orderJSON
                                },
                                success: function(response) {
                                    const res = JSON.parse(response);
                                    if (res.status === 1) {
                                        console.log("1223");
                                        window.location.href =
                                            `order_management.php?status=${res.status}&message=${encodeURIComponent(res.message)}`;
                                    } else {
                                        // Handle failure (optional)
                                        $('.status_msg_success').text(res.message).show();
                                    }
                                },
                                error: function() {
                                    alert('Error saving order. Please try again.');
                                }
                            });
                        }
                    </script>

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