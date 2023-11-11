<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Delete Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
        }

        .success {
            background-color: #28a745;
            color: #fff;
        }

        .error {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>

<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "commerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ...
if(isset($_POST['delete_product'])) {
    $productToDelete = $_POST['product_to_delete'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM product_tb WHERE product_name = ?");
    $stmt->bind_param("s", $productToDelete);

    if ($stmt->execute()) {
        // Product successfully deleted
        $deletedImagePath = "uploads/product_image/" . $productToDelete . ".jpg"; // Adjust the image extension if needed

        if (file_exists($deletedImagePath)) {
            unlink($deletedImagePath);
            echo "<p class='message success'>Product '$productToDelete' and its image have been successfully deleted.</p>";
        } else {
            echo "<p class='message success'>Product '$productToDelete' has been successfully deleted. Image not found.</p>";
        }
    } else {
        // Error occurred during deletion
        echo "<p class='message error'>Error deleting product: " . $conn->error . "</p>";
    }

    $stmt->close();
}
// ...




$products = [];
$result = $conn->query("SELECT product_name FROM product_tb");

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row["product_name"];
    }
}

$conn->close();
?>

<form action="" method="post">
    <h1>Delete Product</h1>
    <label for="product_to_delete">Select Product to Delete:</label>
    <select id="product_to_delete" name="product_to_delete">
        <?php
            foreach($products as $product) {
                echo "<option value='$product'>$product</option>";
            }
        ?>
    </select>
    <button type="submit" name="delete_product">Delete Product</button>
</form>

</body>
</html>
