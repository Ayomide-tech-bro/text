<?php
// session_start();
?>

<?php
// session_start();
require_once('includes/conn.php');   


if(isset($_SESSION['user_id'])) {
    $active_user_id = $_SESSION['user_id'];
} else {
    // Handle case where user is not logged in
    // You might want to redirect them to a login page or handle it in some way.
}

if ( isset( $_POST['remove_cart'] ) ) {

    // print_r($_POST);
    $cart_id = $_POST['item_id'];
    $dqr = "DELETE from cart_tb WHERE product_id = '$cart_id'";
    $rdq = mysqli_query($my_conn , $dqr);
    $row0 = mysqli_affected_rows($my_conn);
    if ( $row0 > 0 ) {
        header( 'location: ggg.php' );
    } else {
        echo 'Something went wrong';
    }

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="dist/css/bootstrap.min.css"> -->
</head>
<body>



    <div class="container mt-5">
<a href="homepage.php">Home</a>
        <h1>Shopping Cart</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php


                $active_user_id = $_SESSION['user_id'];

                // Assume $cartItems is an array containing cart items fetched from the database
                // $cartItems = [
                //     ['id' => 1, 'name' => 'Product A', 'price' => 10.00, 'quantity' => 2],
                //     ['id' => 2, 'name' => 'Product B', 'price' => 20.00, 'quantity' => 1],
                // ];

                // $total = 0;

                // foreach ($cartItems as $item) {
                //     $subtotal = $item['price'] * $item['quantity'];
                //     $total += $subtotal;
                //     echo "
                //         <tr>
                //             <td>{$item['name']}</td>
                //             <td>{$item['price']}</td>
                //             <td>{$item['quantity']}</td>
                //             <td>$subtotal</td>
                //             <td>
                //                 <form method='post'>
                //                     <input type='hidden' name='remove_item' value='{$item['id']}'>
                //                     <button type='submit' class='btn btn-danger btn-sm'>Remove</button>
                //                 </form>
                //             </td>
                //         </tr>
                //     ";
                // }

                $qq = "SELECT * FROM cart_tb WHERE user_id = '$active_user_id'";
                $rr = mysqli_query( $my_conn , $qq);
                $row = mysqli_num_rows($rr); 

             
                $total = 0;
                
                if ($row > 0) {
                    while ( $result = mysqli_fetch_assoc($rr) ) {
                        $qty = $result['quantity'];
                        $dd = $result['product_id'];
                        $sq = "SELECT * FROM product_tb WHERE id = '$dd'";
                        $sq = mysqli_query($my_conn , $sq);
                        while ( $rs = mysqli_fetch_assoc($sq) ) {

                            $total += $rs['product_price'];
                            //  echo $result['product)_id'];
                        echo " <tr>
                                   <td>".$rs['product_name']."</td>
                                   <td>".naira. number_format($rs['product_price']) ."</td>
                                   <td>" .$qty."</td>
                                   <td>  <form method='post' action = ''>
                                  <input type='hidden' name='item_id' value='{$rs['id']}'>
                                  <button type='submit' name='remove_cart' class='btn btn-danger btn-sm'>Remove</button>
                                 </form></td>
                                  
                            </tr> ";
                        }
                       
                    }
                } else {
                    echo '<h4>Your cart is empty!</h4>';
                }
                ?>
            </tbody>
        </table>
        <div class="text-right">
            <h4>Grandtotal: ₦<?= number_format($total) ?></h4>
            <a href="#" class="btn btn-primary">Proceed to Checkout</a>
        </div>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
            $itemIdToRemove = $_POST['remove_item'];

            // Logic to remove item from cart (update database or session)

            // Assuming you have a function to remove an item by its ID
            // removeItem($itemIdToRemove);

            // Reload the page or redirect to refresh the cart display
            // header("Location: shopping_cart.php");
            // exit();
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


<?php
// Assuming $product_id is the ID of the product you want to retrieve
$product_id = $_POST['product_id']; // Assuming you have a form field with the product ID

// Query to fetch product details
$query = "SELECT * FROM product_tb WHERE id = ?";
$stmt = mysqli_prepare($my_conn, $query);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $product_name = $row['product_name'];
    $product_price = $row['product_price'];
    // Add more fields as needed

    // Now you have the product details, you can use them in your cart page
    // For example, display the product name and price
    echo "Product Name: $product_name<br>";
    echo "Price: $product_price<br>";
}
?>