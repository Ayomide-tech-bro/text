<?php
// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "commerce_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the query parameter is set
if (isset($_GET['query'])) {
    $search_query = $_GET['query'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM product_tb WHERE product_name LIKE ?");
    $search_query = '%' . $search_query . '%'; // Add wildcard characters for partial matching
    $stmt->bind_param("s", $search_query);

    $stmt->execute();
    $result = $stmt->get_result();

    // Display search results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>{$row['product_name']}</p>"; // Adjust this to display your actual product information
        }
    } else {
        echo "No results found";
    }

    $stmt->close();
}

$conn->close();
?>
