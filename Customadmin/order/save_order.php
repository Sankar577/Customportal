<?php 
ob_start();
session_start();
include("../../lib/config.php"); 
$table_order="".DB_PREFIX."_order_details";

if (!empty($_POST['orderData'])) {
    $action = $_POST['action'];
   $id = $_REQUEST['Id'];
    $orderData = json_decode($_POST['orderData'], true);

    $customerId = $orderData['customer_id'];
    $customerName = $orderData['customer_name'];
    $grandTotal = $orderData['grand_total'];
    $products = $orderData['products'];
    $jsonProducts = json_encode($products);
    
    $orderId = "ORD_" . mt_rand(1000, 9999);;
  
    if($action != "edit"){

    $sql="INSERT INTO $table_order(`custom_id`,`order_id`,`orders`,`total`,`custom_name`) VALUES ('".$customerId."','".$orderId."','".$jsonProducts."','".$grandTotal."', '".$customerName."')";
    $res = $db_cms->insert_query($sql);
    $msg = "Added Successfully!";
    }
    else{
     
        $sql="UPDATE $table_order SET `custom_name`='".$customerName."',`custom_id`='".$customerId."',`total`='".$grandTotal."',`orders`='".$jsonProducts."' WHERE `id`='".$id."'";
     
        $res = $db_cms->update_query($sql);
        $msg = "Updated Successfully!";
    }



    if ($res) {
        // Return the success message
        echo json_encode([
            "status" => 1,
            "message" => $msg
        ]);
    } else {
        // Return a failure message
        echo json_encode([
            "status" => 0,
            "message" => "Failed to process the request."
        ]);
    }

    
}
