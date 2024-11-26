
<?php
include("../../lib/config.php");
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $sql = "SELECT 
            cpm.product_name,
            cpm.product_id,
            cpm.Product_price
        FROM
           custom_product_management cpm
        WHERE product_id = '$product_id'";
    $res = $db_cms->select_query($sql);
    echo json_encode($res);
} else {
    echo json_encode(["message" => "No product found for this ID."]);
}
?>
