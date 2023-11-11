<?php
// session_start();

require_once('includes/conn.php');   


if(isset($_SESSION['user_id'])) {
    $active_user_id = $_SESSION['user_id'];
} else {
    // Handle case where user is not logged in
    // You might want to redirect them to a login page or handle it in some way.
}
?>

<?php
// Step 1: Establish a database connection (Replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "";
$database = "commerce_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Handle Form Submission (Assuming you're using a form to submit product details)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $active_user_id;
    $product_name = $_POST['product_name'];
    $category_name = $_POST['category_name'];
    $product_price = $_POST['product_price'];
    $former_price = $_POST['former_price'];
    $product_discount = $_POST['product_discount'];
    $stock_quantity = $_POST['stock_quantity'];
    $product_description = $_POST['product_description'];
    $timestamp = date("Y-m-d H:i:s"); // Current timestamp

      // First, insert the category (if it doesn't exist yet)
      $category_id = null;

      $category_result = $conn->query("SELECT id FROM category_tb WHERE category_name='$category_name'");
  
      if ($category_result->num_rows > 0) {
          $row = $category_result->fetch_assoc();
          $category_id = $row["id"];
      } else {
          $conn->query("INSERT INTO category_tb (category_name) VALUES ('$category_name')");
          $category_id = $conn->insert_id;
      }

    // Step 3: Upload Product Image (assuming you have an image upload input field)
    $targetDir = "uploads/product_image/"; // Create an 'uploads' directory in your project

    // Generate a unique filename for uploaded image
    // $targetFile = uniqid() . basename($_FILES['product_image']['name']);
    // $targetFile = $targetDir . uniqid() . basename($_FILES['product_image']['name']);
    $targetFile = $targetDir . uniqid() . basename($_FILES['product_image']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES['product_image']['tmp_name']);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo '<div class="alert alert-danger">' . "File is not an image." . '</div>';
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo '<div class="alert alert-danger">' . "Sorry, file already exists." . '</div>';
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES['product_image']['size'] > 500000) {
        echo '<div class="alert alert-danger">' . "Sorry, your file is too large." . '</div>';
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"  && $imageFileType != "jfif") {
        echo '<div class="alert alert-danger">' . "Sorry, only JPG, JPEG, PNG & GIF files are allowed." . '</div>';
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo '<div class="alert alert-danger">' . "Sorry, your file was not uploaded." . '</div>';
    } else {
        // File uploaded successfully, proceed with database insertion

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO product_tb (user_id, category_id, product_name, category_name, product_price, former_price, product_discount, stock_quantity, product_description, product_image, timestamp)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssddissss",$user_id, $category_id, $product_name, $category_name, $product_price, $former_price, $product_discount, $stock_quantity, $product_description, $targetFile, $timestamp);

            if ($stmt->execute()) {
                echo '<div class="alert alert-success">' . "Product added successfully!" . '</div>';
            } else {
                echo '<div class="alert alert-danger">' . "Error: " . $sql . "<br>" . $conn->error . '</div>';
            }

            $stmt->close();
        } else {
            echo '<div class="alert alert-danger">' . "Error uploading file." . '</div>';
        }
    }
}

// Step 4: Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

    <!-- Your existing CSS styles remain unchanged -->

<style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
}

/* .container {
    max-width: 500px;
    margin: 100px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
} */

label, input, textarea {
    display: block;
    margin-bottom: 10px;
}

input, textarea {
    width: 100%;
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="file"] {
    border: none;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}
</style>

<body>
<?php require_once('nav.php'); ?>

            <!-- Your form fields remain unchanged -->
            <h1>Add Product</h1>

    <div class="container">
        <form  method="post" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required><br>

            <label for="category_name">Category Name:</label>
            <input type="text" id="category_name" name="category_name" required><br>

            <label for="product_price">Product Price:</label>
            <input type="number" step="0.01" id="product_price" name="product_price" required><br>

            <label for="former_price">Former Price:</label>
            <input type="number" step="0.01" id="former_price" name="former_price" required><br>


            <label for="product_discount">Product Discount:</label>
            <input type="number" step="0.01" id="product_discount" name="product_discount" required><br>

            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" required><br>

            <label for="product_description">Product Description:</label><br>
            <textarea id="product_description" name="product_description" rows="4" cols="50" required></textarea><br>

            <label for="product_image">Product Image:</label>
            <input type="file" id="product_image" name="product_image" accept="image/*" required><br>

            <input type="submit" value="Add Product">





        </form>
    </div>
</body>
</html>