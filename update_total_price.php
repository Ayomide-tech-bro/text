<?php
require_once('includes/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['product_id']) && isset($_GET['total_price'])) {
        $product_id = $_GET['product_id'];
        $quantity = $_GET['quantity'];
        $total_price = $_GET['total_price'];


        // Sanitize input if needed
        $product_id = mysqli_real_escape_string($my_conn, $product_id);
        $quantity = mysqli_real_escape_string($my_conn, $quantity);
        $total_price = mysqli_real_escape_string($my_conn, $total_price);

        // Update total_price in the database
        $updateQuery = "UPDATE cart_tb SET quantity='$quantity', total_price = '$total_price' WHERE product_id = '$product_id'";
        mysqli_query($my_conn, $updateQuery);

        // You can add error handling or return a response if needed
        // For simplicity, I'm not including detailed error handling in this example
    }
}
?>
