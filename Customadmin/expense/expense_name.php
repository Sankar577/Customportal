<?php
include("../../lib/config.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $sql = "SELECT  
            cpm.product_name,
            cpm.product_id,
            cpm.Product_price
        FROM
           custom_product_management cpm 
      
        WHERE cpm.product_id = '$product_id'";



    $res = $db_cms->select_query($sql);
}
echo json_encode($res);
