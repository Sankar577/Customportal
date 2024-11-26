<?php
include("../../lib/config.php");
if (isset($_GET['sku'])) {
    $sku = $_GET['sku'];
    $sql = "SELECT * FROM custom_product_management WHERE Stock_keeping_unit = '$sku' LIMIT 1";
    $res = $db_cms->select_query($sql);
    echo json_encode($res);
} else {
    echo json_encode(["message" => "No product found for this ID."]);
}
