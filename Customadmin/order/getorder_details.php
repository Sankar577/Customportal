<?php
include("../../lib/config.php");

if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];
   
    $sql = "SELECT * FROM custom_order_details WHERE order_id = '".$orderID."'";
 
    $result = $db_cms->select_query($sql);

    $customer_sql = "SELECT * FROM custom_customer WHERE custom_id = '".$result[0]['custom_id']."'";
    $custom_result = $db_cms->select_query($customer_sql);
  
    
    if (!empty($result)) {
        $order = $result[0];
        echo json_encode([
            'success' => true,
            'products' => json_decode($order['orders'], true),// Adjust based on your DB schema
            'address' => $custom_result[0]['address'],
            'cus_name' => $custom_result[0]['customer_name'],
            'email' => $custom_result[0]['email'],
            'phone' => $custom_result[0]['phone_number'],
            'totalAmount' => $order['total'],
            'tax' => null
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
