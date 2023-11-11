<?php

require_once('includes/conn.php');

session_start();

if(isset($_SESSION['user_id'])) {
    $active_user_id = $_SESSION['user_id'];
} else {
    // Redirect to the login page if user is not logged in
    header("Location: ../user/login.php");
    exit(); // Ensure that no further code is executed after the redirect
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST["category_name"];

    // Validate input to prevent SQL injection (you should use prepared statements in production)
    $category_name = mysqli_real_escape_string($my_conn, $category_name);

    // Get the current timestamp
    // $timestamp = time();
    $timestamp = date("Y-m-d H:i:s"); // Current timestamp


    // Insert the category into the database
    $sql = "INSERT INTO category_tb (category_name, user_id, timestamp) VALUES ('$category_name', '$active_user_id', '$timestamp')";

    if (mysqli_query($my_conn, $sql)) {
        $msg = "Category added successfully";
    } else {
        $error = "Error: " . $sql . "<br>" . mysqli_error($my_conn);
    }
}

mysqli_close($my_conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dist\css\bootstrap.min.css">
    <style>
        .container-box {
            max-width: 350px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label, input, button {
            display: block;
            margin-bottom: 10px;
            width: 100%;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php require_once('nav.php'); ?>

    <div class="container-box">
        <?php
        if (isset($msg)) {
            echo '<div class="alert alert-primary" style="color: blue; margin-bottom: 10px;">' . $msg . '</div>';
        }
        if (isset($error)) {
            echo ' <div id="error-msg" class="alert alert-danger error message" style="color: red; margin-bottom: 10px;">' . $error . '</div>';
        }
        ?>

        <h1>Add Category</h1>
        <!-- <form action="" method="post">
            <label for="category_name">Category Name:</label>
            <input type="text" id="category_name" name="category_name" required>
            <button type="submit">Add Category</button>
        </form> -->

        <form action="" method="post">
                    <div class="form-group">
                        <label for="category_name">Category Name:</label>
                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
    </div>
<!-- <p>This Page Lead To <a href="add_product.php">Add Product Page</a></p> -->
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// require_once('includes/conn.php');

// session_start();

// if(isset($_SESSION['user_id'])) {
//     $active_user_id = $_SESSION['user_id'];
// } else {
//     header('location: login.php'); // Redirect to login page if user is not logged in
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $product_name = sanitize_var($my_conn, $_POST["product_name"]);
//     $category_name = sanitize_var($my_conn, $_POST["category_name"]);
//     $product_price = floatval($_POST["product_price"]);
//     $former_price = floatval($_POST["former_price"]);
//     $product_discount = floatval($_POST["product_discount"]);
//     $stock_quantity = intval($_POST["stock_quantity"]);
//     $product_description = sanitize_var($my_conn, $_POST["product_description"]);

//     // Generate a unique filename for uploaded image
//     $targetDir = "uploads/product_image/";
//     $targetFile = $targetDir . uniqid() . '_' . basename($_FILES['product_image']['name']);
//     $uploadOk = 1;
//     $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

//     // Check if image file is a actual image or fake image
//     $check = getimagesize($_FILES['product_image']['tmp_name']);
//     if($check === false) {
//         $error = "File is not an image.";
//         $uploadOk = 0;
//     }

//     // Check if file already exists
//     if (file_exists($targetFile)) {
//         $error = "Sorry, file already exists.";
//         $uploadOk = 0;
//     }

//     // Check file size
//     if ($_FILES['product_image']['size'] > 500000) {
//         $error = "Sorry, your file is too large.";
//         $uploadOk = 0;
//     }

//     // Allow only certain file formats
//     if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//         && $imageFileType != "gif") {
//         $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//         $uploadOk = 0;
//     }

//     if ($uploadOk == 0) {
//         $error = "Sorry, your file was not uploaded.";
//     } else {
//         // File uploaded successfully, proceed with database insertion

//         if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
//             $timestamp = time();

//             $sql = "INSERT INTO product_tb (user_id, product_name, category_name, product_price, former_price, product_discount, stock_quantity, product_description, product_image, timestamp)
//                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

//             $stmt = mysqli_prepare($my_conn, $sql);
//             mysqli_stmt_bind_param($stmt, "dssdddsdsd", $active_user_id, $product_name, $category_name, $product_price, $former_price, $product_discount, $stock_quantity, $product_description, $targetFile, $timestamp);

//             if (mysqli_stmt_execute($stmt)) {
//                 $msg = "Product added successfully!";
//             } else {
//                 $error = "Error: " . $sql . "<br>" . mysqli_error($my_conn);
//             }

//             mysqli_stmt_close($stmt);
//         } else {
//             $error = "Error uploading file.";
//         }
//     }
// }

// mysqli_close($my_conn);
?>
