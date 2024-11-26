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


if ($_REQUEST['action'] == "edit") {
    $edit_mode = true;
    $payment_sql = "SELECT * FROM custom_payment_details where id = '" . $_REQUEST['id'] . "'";
    $payment_res = $db_cms->select_query($payment_sql);
    $order_products = json_decode($payment_res[0]['orders'], true);
    $customer_id = $payment_res[0]['custom_id'];
    $grant_total = $payment_res[0]['total'];
    $order_id =  $payment_res[0]['order_id'];
    $id = $_REQUEST['id'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];

    // Validate Order ID
    if (empty($_POST['orderID'])) {
        $errors[] = "Order ID is required.";
    }

    // Validate Tax
    if (empty($_POST['tax']) || !is_numeric($_POST['tax']) || $_POST['tax'] < 0) {
        $errors[] = "Tax must be a valid positive number.";
    }

    // Validate Paid Amount
    if (empty($_POST['paidAmount']) || !is_numeric($_POST['paidAmount']) || $_POST['paidAmount'] < 0) {
        $errors[] = "Paid Amount must be a valid positive number.";
    }

    // Check if Paid Amount exceeds Final Total
    $finalTotal = $_POST['totalAmount'] + $_POST['tax'];
    if ($_POST['paidAmount'] > $finalTotal) {
        $errors[] = "Paid amount cannot exceed the final total.";
    }

    // If there are errors, display them
    if (!empty($errors)) {
        echo "<ul class='error'>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    } else {

        $sql = "SELECT * FROM custom_order_details WHERE order_id = '" . $_POST['orderID'] . "'";
        $result = $db_cms->select_query($sql);


        // Capture form fields
        $orderID = $_POST['orderID'] ?? '';
        $paymentID = "PAY_" . mt_rand(1000, 9999);


        $address = $_POST['address'] ?? '';
        $email = $_POST['email'] ?? '';
        $customer = $_POST['customer'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $totalAmount = $_POST['totalAmount'] ?? 0;
        $tax = $_POST['tax'] ?? 0;
        $paidAmount = $_POST['paidAmount'] ?? 0;
        $products =  $result[0]['orders'];
        $balance = $_POST['balance'] ?? 0;
        $final_total = $_POST['finalTotal'] ?? 0;

        if ($_REQUEST['action'] == "add") {
            $sql = "INSERT INTO custom_payment_details(`order_id`,`payment_id`,`address`,`email`,`sub_total`,`tax`,`paid_amount`,`products`,`pay_status`,`balance`,`final_total`,`custome_name`) VALUES ('" . $orderID . "','" . $paymentID . "','" . $address . "','" . $email . "', '" . $totalAmount . "', '" . $tax . "','" . $paidAmount . "','" . $products . "', '1','" . $balance . "','" . $final_total . "','" . $customer . "')";
            // echo $sql;
            $res = $db_cms->insert_query($sql);


            if ($res) {
                $sql_pay = "UPDATE custom_order_details SET `pay_status`='1' WHERE `order_id`='" . $orderID . "'";

                $res_pay = $db_cms->update_query($sql_pay);
                echo "<script>window.location.href = 'payment_management.php?message=Added Successfully';</script>";
            }
        }
        if ($_REQUEST['action'] == "edit") {

            $sql = "UPDATE custom_payment_details SET `order_id`='" . $orderID . "',`payment_id`='" . $paymentID . "',`address`='" . $address . "', `email`='" . $email . "',`sub_total`='" . $totalAmount . "',`tax`='" . $tax . "',`paid_amount`='" . $paidAmount . "',`products`='" . $products . "',`balance`='" . $balance . "',`balance`='" . $balance . "',`final_total` = '" . $final_total . "',`custome_name` = '" . $customer . "' WHERE `id`='" . $id . "'";

            $res = $db_cms->update_query($sql);
            if ($res) {
                echo "<script>window.location.href = 'payment_management.php?message=Updated Successfully';</script>";
            }
        }
    }
}
include("include/header.php");

?>
<link rel="stylesheet" href="css/custom_file.css">
<?php
include("include/sidebar.php");
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Product Management
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Payment Management</a></li>
            <li class="active">Payment Management</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box" style="padding:30px;">
                    <h2>Payment</h2>
                    <form id="paymentForm" class="paymentForm" method="POST" action="">
                        <div class="row">
                            <!-- Customer Information Section -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="orderID">Order ID</label>
                                    <select id="orderID" name="orderID" class="form-control">
                                        <option value="">Select Order ID</option>
                                        <?php
                                        if ($_REQUEST['action'] == "edit") {
                                            $order_sql = "SELECT * FROM custom_order_details ORDER BY id DESC";
                                        }
                                        if ($_REQUEST['action'] == "add") {
                                            $order_sql = "SELECT * FROM custom_order_details WHERE pay_status != '1' ORDER BY id DESC";
                                        }

                                        $order_res = $db_cms->select_query($order_sql);

                                        foreach ($order_res as $order) {
                                            $selected = ($order['order_id'] == $order_id) ? 'selected' : '';

                                            // Ensure the selected order is displayed even if pay_status is '1'
                                            if ($order['pay_status'] != '1' || $selected) {
                                                echo "<option value='{$order['order_id']}' $selected>{$order['order_id']}</option>";
                                            }
                                        } ?>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="customer">Customer Name</label>
                                    <input type="customer" id="customer" name="customer" class="form-control"
                                        value="<?= $payment_res[0]['custome_name'] ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea id="address" name="address" value="<?= $payment_res[0]['address'] ?>"
                                        class="form-control" readonly><?= $payment_res[0]['address'] ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" value="<?= $payment_res[0]['email'] ?>" name="email"
                                        class="form-control" readonly>
                                </div>


                            </div>
                        </div>

                        <!-- Product Details Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Product Details</h3>
                                <div class="form-group">
                                    <table class="table table-striped table-bordered" id="productsTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Product ID</th>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($payment_res[0]['products'])) {
                                                $products = json_decode($payment_res[0]['products'], true); // Decode the JSON string into an associative array
                                                if (is_array($products)) {
                                                    foreach ($products as $product) { ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                                                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                                                            <td><?php echo htmlspecialchars($product['unit_price']); ?></td>
                                                            <td><?php echo htmlspecialchars($product['total_price']); ?></td>
                                                        </tr>
                                            <?php }
                                                }
                                            } else {
                                                echo '<tr><td colspan="5">No products found</td></tr>';
                                            }
                                            ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Amount Details Section -->
                        <div class="row">
                            <div class="col-md-12"></div> <!-- Empty space -->
                            <div class="row">
                                <div>
                                    <div class="amount-section">
                                        <h3>Amount Details</h3>

                                        <!-- Sub Total Section -->
                                        <div class="form-group">
                                            <label for="totalAmount">Sub Total</label>
                                            <input type="text" id="totalAmount"
                                                value="<?= $payment_res[0]['sub_total'] ?>" name="totalAmount"
                                                class="form-control text-right" readonly>
                                        </div>

                                        <!-- Tax and Final Total in the same row -->
                                        <div class="form-group d-flex justify-content-between">
                                            <div class="col">
                                                <label for="tax">Tax</label>
                                                <input type="text" id="tax" value="<?= $payment_res[0]['tax'] ?>"
                                                    name="tax" class="form-control text-right" oninput="updateTotal()">
                                            </div>
                                            <div class="col">
                                                <label for="finalTotal">Final Total</label>
                                                <input type="text" id="finalTotal"
                                                    value="<?= $payment_res[0]['final_total'] ?>" name="finalTotal"
                                                    class="form-control text-right" readonly>
                                            </div>
                                        </div>

                                        <!-- Paid Amount and Balance in the same row -->
                                        <div class="form-group d-flex justify-content-between">
                                            <div class="col">
                                                <label for="paidAmount">Paid Amount</label>
                                                <input type="text" id="paidAmount" name="paidAmount"
                                                    value="<?= $payment_res[0]['paid_amount'] ?>"
                                                    class="form-control text-right" oninput="updateBalance()">
                                            </div>
                                            <div class="col">
                                                <label for="balance">Balance</label>
                                                <input type="text" id="balance" name="balance"
                                                    class="form-control text-right"
                                                    value="<?= $payment_res[0]['balance'] ?>" readonly>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary btn-block">Submit Payment</button>
                                    </div>

                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <style>

    </style>

    <script>
        document.getElementById('orderID').addEventListener('change', function() {
            const orderID = this.value;
            const taxRate = 0.10; // Define the tax rate (10%)
            if (orderID) {
                fetch('order/getorder_details.php?orderID=' + orderID)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate other fields
                            document.getElementById('address').value = data.address;
                            document.getElementById('email').value = data.email;
                            document.getElementById('customer').value = data.cus_name;

                            document.getElementById('totalAmount').value = data.totalAmount;

                            // Initially set final total to total amount (without tax)
                            document.getElementById('finalTotal').value = parseFloat(data.totalAmount).toFixed(
                                2);

                            // Populate products table
                            const productsTableBody = document.querySelector('#productsTable tbody');
                            productsTableBody.innerHTML = ''; // Clear existing rows

                            data.products.forEach(product => {
                                const row = `
                            <tr>
                                <td>${product.product_id}</td>
                                <td>${product.product_name}</td>
                                <td>${product.quantity}</td>
                                <td>${product.unit_price}</td>
                                <td>${product.total_price}</td>
                            </tr>
                        `;
                                productsTableBody.insertAdjacentHTML('beforeend', row);
                            });
                        } else {
                            alert('Error fetching order details.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        function updateTotal() {
            const totalAmount = parseFloat(document.getElementById('totalAmount').value) || 0;
            const tax = parseFloat(document.getElementById('tax').value) || 0;

            // Only update the final total if tax is entered manually
            const finalTotal = totalAmount + tax;
            document.getElementById('finalTotal').value = finalTotal.toFixed(2);

            // Update balance if paidAmount is already filled
            updateBalance();
        }

        function updateBalance() {
            const finalTotal = parseFloat(document.getElementById('finalTotal').value) || 0;
            const paidAmount = parseFloat(document.getElementById('paidAmount').value) || 0;

            const balance = finalTotal - paidAmount;
            document.getElementById('balance').value = balance.toFixed(2);
        }

        document.getElementById('paymentForm').addEventListener('submit', function(event) {
            const orderID = document.getElementById('orderID').value;
            const tax = document.getElementById('tax').value;
            const paidAmount = document.getElementById('paidAmount').value;
            const finalTotal = document.getElementById('finalTotal').value;

            let hasErrors = false; // Flag to track if any errors occur

            // Reset any previous error messages
            const errorMessages = document.querySelectorAll('.error');
            errorMessages.forEach(function(error) {
                error.textContent = ''; // Clear existing errors
            });

            // Validate Order ID
            if (!orderID) {
                displayError('orderID', "Please select an Order ID.");
                hasErrors = true;
            }

            // Validate Tax
            if (tax === "" || isNaN(tax) || parseFloat(tax) < 0) {
                displayError('tax', "Please enter a valid tax amount.");
                hasErrors = true;
            }

            // Validate Paid Amount
            if (paidAmount === "" || isNaN(paidAmount) || parseFloat(paidAmount) < 0) {
                displayError('paidAmount', "Please enter a valid paid amount.");
                hasErrors = true;
            }

            // Check if Paid Amount exceeds Final Total
            if (parseFloat(paidAmount) > parseFloat(finalTotal)) {
                displayError('paidAmount', "Paid amount cannot exceed the final total.");
                hasErrors = true;
            }

            // If there are any errors, prevent form submission
            if (hasErrors) {
                event.preventDefault();
            }

            // Function to display error next to each field
            function displayError(fieldId, message) {
                const field = document.getElementById(fieldId);
                const errorElement = field.parentElement.querySelector('.error');

                if (errorElement) {
                    errorElement.textContent = message;
                } else {
                    const errorMessage = document.createElement('div');
                    errorMessage.classList.add('error');
                    errorMessage.textContent = message;
                    field.parentElement.appendChild(errorMessage);
                }
            }
        });
    </script>

</div>



<?php
include("include/footer.php");
?>