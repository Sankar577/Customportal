<?php
include("../lib/config.php");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['product_name'])) {
    $product_name = $_POST['product_name'];
    $product_name = $db_cms->real_escape_string($product_name);
    $sql = "SELECT * FROM custom_product_management WHERE product_name LIKE ?";
    $searchTerm = "%" . $product_name . "%";
    $products = $db_cms->select_queryy($sql, "s", [$searchTerm]);
    echo json_encode([
        'status' => 'success',
        'products' => $products
    ]);
}
