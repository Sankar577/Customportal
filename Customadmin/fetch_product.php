<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'customdb';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

   
    $stmt = $conn->prepare("SELECT * FROM custom_product_management WHERE product_id = ?");
    $stmt->bind_param("s", $product_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_data = [
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'Product_price' => $row['Product_price'],
            'Category' => $row['Category']
        ];
        
        echo json_encode(['status' => 'success', 'data' => $product_data]);
    } else {
        
        echo json_encode(['status' => 'error', 'message' => 'No product found for this ID.']);
    }

    $stmt->close();
}

$conn->close();
?>
