<?php
require_once('includes/conn.php');   
session_start();
$user_id = $_SESSION['user_id'];

$product_id = $_GET['id'];

$q = "SELECT * FROM product_tb WHERE id = '$product_id'";
$qr = mysqli_query($my_conn , $q);
$rw = mysqli_num_rows($qr);
if ($rw > 0) {
    // print_r(mysqli_fetch_assoc($qr));
    $pp = mysqli_fetch_assoc($qr);
}

if (isset($_POST['cart_btn'])) {
    $product_id = $_POST['id'];
    $product_price = $pp['product_price']; // Assuming this is a valid value

    // Add some basic validation to prevent SQL injection
    $product_id = mysqli_real_escape_string($my_conn, $product_id);
    $user_id = mysqli_real_escape_string($my_conn, $user_id);
    $product_price = mysqli_real_escape_string($my_conn, $product_price);

    $qr = "INSERT INTO cart_tb(product_id, user_id, product_price, quantity) VALUES ('$product_id', '$user_id', '$product_price', 1)";

    $rr = mysqli_query($my_conn , $qr);

    if (!$rr) {
        die('Error: ' . mysqli_error($my_conn));
    }

    $row = mysqli_affected_rows($my_conn);
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="..//dist/css/bootstrap.min.css">
    <style>
        .product-image {
            max-width: 100%;
            height: auto;
        }
        .product-description {
            margin-top: 20px;
        }
    </style>

    
</head>
<body>

<?php require_once('nav.php'); ?>


<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img class="product-image" style="height: 400px;" src="<?php echo $pp['product_image'] ?>" alt="Product Image">

            <?php
            // <img id="img-img" src = "'.$row['product_image'].'" role="img" width="100%" height="150" class=" card-img-top w-50">
            ?>
        </div>
        <div class="col-md-6">
            <h2><?php echo $pp['product_name'] ?></h2>
            <p class="text-muted">Category: <?php echo $pp['category_name'] ?></p>
            <h3 class="d-inline-block">₦<?php echo number_format($pp['product_price']); ?></h3>
            <h4 style="text-decoration: line-through;" class="text-muted d-inline-block">₦<?php echo number_format($pp['former_price']); ?></h4>

            <!-- <s><h4 class="text-muted card-title text-black">₦ '.$ff.'</h4></s> -->

            <!-- <h3 class="d-inline-block text-danger text-sm p-1" style="font-weight: 300; font-size: 17px; background-color:#ecc0ff;"> -->
            <!-- </h3> -->
            
            <div class="d-inline-block" style="position: absolute; background-color: red; color: white; padding: 5px 10px; font-size: 16px; border-radius: 5px;">
            <?php echo '-'.$pp['product_discount'].'%' ?>

         </div>


            <p></p>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $product_id; ?>">
            <button class="btn btn-primary" name ="cart_btn" >Add to Cart</button>
        </form>
            
        </div>
        <div class="col-12">
            <h3>Product Description</h3>
            <p> <?php echo $pp['product_description'] ?> </p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <!-- <h3>Product Description</h3> -->
            <p>
            </p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


