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

// Step 2: Fetch Products
if(isset($_GET['category'])) {
    $selectedCategory = $_GET['category'];
    $sql = "SELECT * FROM product_tb WHERE category_id = $selectedCategory";
} else {
    $sql = "SELECT * FROM product_tb";
}

$result = $conn->query($sql);

// Step 3: Display Products
if ($result->num_rows > 0) {
// ...
while($row = $result->fetch_assoc()) {
    $nn = substr($row['product_name'], 0, 15);
    $pp = number_format($row['product_price']);

    // echo '<div class="col-md-3 my-1 col-6 p-1">
    echo '<div class="col-md-3 my-1 col-6 p-1" onmouseover="showAddToCartButton('.$row['id'].')" onmouseout="hideAddToCartButton('.$row['id'].')">

            <div class="card w-100 text-center">
                <a href="pdetails.php?id='.$row['id'].'"> 
                    <div class="card-head w-100"> 
                        <img id="img-img" src="'.$row['product_image'].'" role="img" width="100%" height="100%" class="card-img-top w-100 object-fit-contain">
                        <div style="position: absolute; top: 10px; right: 10px; background-color: red; color: white; padding: 5px 10px; font-size: 16px; border-radius: 5px;">
                            -'.$row['product_discount'].'%
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-black">'.$nn.'...</h5>
                        <h4 class="card-title text-black">₦'.$pp.'</h4>
                    </div> 
                </a>
                </div> 
                
        </div>';
}
// ...
// <button id="add-to-cart-button-'.$row['id'].'" class="btn btn-primary add-to-cart-button" onclick="addToCart('.$row['id'].')">Add to Cart</button>


} else {
    echo " No products found.";
}

// Step 4: Close the database connection
$conn->close();
?>

<style>
    #img-img{
        object-fit: cover;
    }

    a{
        text-decoration: none;
        color: black;
    }

    .add-to-cart-button {
        display: none;
    }

    /* Style for the product container */
/* .product {
    max-width: 300px;
    margin: 20px;
    padding: 10px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Style for product details */
/* .product h2 {
    font-size: 20px;
    margin-bottom: 10px;
}

.product p {
    font-size: 16px;
    margin-bottom: 5px;
}

.product img {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
} */



</style>
<!-- <link rel="stylesheet" href="../dist/css/bootstrap.min.css"> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>

<script>
  function showAddToCartButton(productId) {
        var addToCartButton = document.getElementById('add-to-cart-button-' + productId);
        addToCartButton.style.display = 'block';
    }

    function hideAddToCartButton(productId) {
        var addToCartButton = document.getElementById('add-to-cart-button-' + productId);
        addToCartButton.style.display = 'none';
    }


    function addToCart(productId) {
        // Assuming you have a function to handle adding items to the cart
        // You can use AJAX to send the product ID to the server and update the cart
        // Here's a simple example using fetch API
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product added to cart successfully!');
            } else {
                alert('Error adding product to cart: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>

