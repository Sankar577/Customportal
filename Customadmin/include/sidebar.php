<?php
$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentFile);
$currentFile = $parts[count($parts) - 1];

if ($currentFile == "index.php") {
    $home = "class='active'";
} else {
    $home = "";
}
if ($currentFile == "setting.php") {
    $setting = "class='active'";
} else {
    $setting = "";
}
if ($currentFile == "services.php") {
    $services = "class='active'";
} else {
    $services = "";
}
if ($currentFile == "Product_management.php") {
    $about_us = "class='active'";
} else {
    $about_us = "";
}
if ($currentFile == "contend.php") {
    $contend_us = "class='active'";
} else {
    $contend_us = "";
}
if ($currentFile == "join.php") {
    $join_us = "class='active'";
} else {
    $join_us = "";
}
if ($currentFile == "OurTeam.php") {
    $team_us = "class='active'";
} else {
    $team_us = "";
}
if ($currentFile == "client.php") {
    $client = "class='active'";
} else {
    $client = "";
}
if ($currentFile == "customer_management.php") {
    $Customer_management = "class='active'";
} else {
    $Customer_management = "";
}
if ($currentFile == "Inventory_Management.php") {
    $inventery_manage = "class='active'";
} else {
    $inventery_manage = "";
}
if ($currentFile == "order_management.php" || $currentFile == "add_order.php") {
    $order_manage = "class='active'";
} else {
    $order_manage = "";
}
if ($currentFile == "payment_management.php" || $currentFile == "add_payment.php") {
    $pay_manage = "class='active'";
} else {
    $pay_manage = "";
}
if ($currentFile == "rental_management.php") {
    $Rental_management = "class='active'";
} else {
    $Rental_management = "";
}
if ($currentFile == "invoice_management.php" || $currentFile == "add_invoice.php") {
    $invoice_manage = "class='active'";
} else {
    $invoice_manage = "";
}
if ($currentFile == "expense_management.php") {
    $Expense_management = "class='active'";
} else {
    $Expense_management = "";
}


?>
<aside class="main-sidebar" style="top: 50px;">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li <?php echo $home; ?>>
                <a href="index.php">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>Home</span>
                </a>

            </li>
            <li <?php echo $Customer_management; ?>><a href="customer_management.php"><i class="fa fas fa-building" aria-hidden="true"></i>Customer Management</a></li>
            <li <?php echo $about_us; ?>><a href="Product_management.php"><i class="fa fas fa-cube  pictofixedwidth" aria-hidden="true"></i>Product Management</a></li>
            <li <?php echo $inventery_manage; ?>><a href="Inventory_Management.php"><i class="fa fa-id-card" aria-hidden="true"></i>Inventory Management</a></li>
            <li <?php echo $order_manage; ?>><a href="order_management.php"><i class="fa fa-cart-plus" aria-hidden="true"></i>Order Management</a></li>
            <li <?php echo $pay_manage; ?>><a href="payment_management.php"><i class="fa fa-money" aria-hidden="true"></i>Payment Management</a></li>
            <li <?php echo $Rental_management; ?>><a href="rental_management.php"><i class="	fa fa-institution" aria-hidden="true"></i>Rental Management</a></li>
            <li <?php echo $invoice_manage; ?>><a href="invoice_management.php"><i class="fa fa-list-alt" aria-hidden="true"></i>Invoice Management</a></li>
            <li <?php echo $Expense_management; ?>><a href="expense_management.php"><i class="fa fa-line-chart" aria-hidden="true"></i>Expense Management</a></li>











        </ul>
    </section>
</aside>