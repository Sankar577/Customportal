<?php
include("../../lib/config.php");

if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];

    $sql = "SELECT * FROM custom_payment_details WHERE order_id = '" . $orderID . "'";

    $result = $db_cms->select_query($sql);


    if (!empty($result)) {
        $order = $result[0];
        echo json_encode([
            'success' => true,
            'products' => json_decode($order['products'], true), // Adjust based on your DB schema
            'address' => $order['address'],
            'cus_name' => $order['custome_name'],
            'email' => $order['email'],
            //'phone' => $custom_result[0]['phone_number'],
            'sub_total' => $order['sub_total'],
            'paid_amount' => $order['paid_amount'],
            'final_total' => $order['final_total'],
            'tax' => $order['tax'],
            'date' => $order['date'],
            'balance' => $order['balance'],
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
