
<?php
include("../../lib/config.php");

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // SQL query to fetch product details by product_id
    $sql = "SELECT 
                cpm.product_name,
                cpm.product_id
            FROM
                custom_product_management cpm
            WHERE cpm.product_id = '$product_id'";

    // Execute the query and fetch the product details
    $res = $db_cms->select_query($sql);

    // Check if product data exists
    if (!empty($res)) {
        $customers = "SELECT 
                         *
                      FROM
                          custom_customer cm";
             

        // Execute the query and fetch customer data
        $cname = $db_cms->select_query($customers);

        if (!empty($cname)) {
            $response = [
                'success' => true,
                'product' => [
                    'product_id' => $res[0]['product_id'],
                    'product_name' => $res[0]['product_name']
                ],
                'customer' => [
                    'customer_name' => $cname[0]['customer_name'],
                    'email' => $cname[0]['email'],
                    'phone_number' => $cname[0]['phone_number'],
                    'address' => $cname[0]['address']
                ]
            ];
            echo json_encode($response);
        } else {
            // If no customer data is found
            echo json_encode([
                'success' => false,
                'message' => 'No customer found for this product ID.'
            ]);
        }
    } else {
        // If no product is found
        echo json_encode([
            'success' => false,
            'message' => 'No product found for this ID.'
        ]);
    }
} else {
    // If the ID parameter is missing
    echo json_encode([
        'success' => false,
        'message' => 'Product ID is required.'
    ]);
}
?>


