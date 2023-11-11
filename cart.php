<script>
    function updateGrandTotal() {
        let totalElements = document.querySelectorAll('.productTotal');
        let grandTotalElement = document.getElementById('grandTotal');

        let grandTotal = 0;
        totalElements.forEach(element => {
            grandTotal += parseFloat(element.innerText);
        });

        grandTotalElement.innerText = grandTotal.toFixed(2);
    }

    function updateProductTotal(price, quantity, productId) {
        let productTotalElement = document.getElementById(`productTotal_${productId}`);
        let productTotal = price * quantity;
        productTotalElement.innerText = productTotal.toFixed(2);
        updateGrandTotal();
    }

    function updateQuantity(action, productId) {
        let quantityElement = document.getElementById(`quantity_${productId}`);
        let currentQuantity = parseInt(quantityElement.value);
        if (action === 'plus') {
            quantityElement.value = currentQuantity + 1;
        } else if (action === 'minus' && currentQuantity > 1) {
            quantityElement.value = currentQuantity - 1;
        }
        updateProductTotal(
            parseFloat(quantityElement.dataset.price),
            parseInt(quantityElement.value),
            productId
        );
    }

    function updateProductTotal(price, quantity, productId) {
    let productTotalElement = document.getElementById(`productTotal_${productId}`);
    let productTotal = price * quantity;
    productTotalElement.innerText = productTotal.toFixed(2);

    // Update the total_price in the database using AJAX
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Update successful
        }
    };
    xhttp.open("GET", `update_total_price.php?product_id=${productId}&total_price=${productTotal}`, true);
    xhttp.send();
}

// Function to update quantity (plus/minus buttons)
function updateQuantity(action, productId) {
    let quantityElement = document.getElementById(`quantity_${productId}`);
    let currentQuantity = parseInt(quantityElement.value);
    if (action === 'plus') {
        quantityElement.value = currentQuantity + 1;
    } else if (action === 'minus' && currentQuantity > 1) {
        quantityElement.value = currentQuantity - 1;
    }
    updateProductTotal(
        parseFloat(quantityElement.dataset.price),
        parseInt(quantityElement.value),
        productId
    );
}

function updateProductTotal(price, quantity, productId) {
    let productTotalElement = document.getElementById(`productTotal_${productId}`);
    let productTotal = price * quantity;
    productTotalElement.innerText = productTotal.toFixed(2);

    // Update the total_price and quantity in the database using AJAX
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Update successful
        }
    };
    xhttp.open("GET", `update_total_price.php?product_id=${productId}&total_price=${productTotal}&quantity=${quantity}`, true);
    xhttp.send();
}



</script>

<script>
    
</script>

<?php
require_once('includes/conn.php');   

session_start();

if(isset($_SESSION['user_id'])) {
    $active_user_id = $_SESSION['user_id'];
} else {
    // Handle case where user is not logged in
    // You might want to redirect them to a login page or handle it in some way.
}

if ( isset( $_POST['remove_cart'] ) ) {
    $cart_id = $_POST['item_id'];
    $dqr = "DELETE from cart_tb WHERE product_id = '$cart_id'";
    $rdq = mysqli_query($my_conn , $dqr);
    $row0 = mysqli_affected_rows($my_conn);
    if ( $row0 > 0 ) {
        header( 'location: cart.php' );
        exit();
    } else {
        echo 'Something went wrong';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $cart_id = $_POST['item_id'];
    $new_quantity = $_POST['new_quantity'];

    $updateQuery = "UPDATE cart_tb SET quantity = '$new_quantity' WHERE product_id = '$cart_id'";
    mysqli_query($my_conn, $updateQuery);
    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
.quantity-cell {
    display: flex;
    align-items: center;
    gap: 5px; /* Adjust the gap as needed */
}

    .small-input {
    width: 50px; /* Adjust the width as needed */
}
</style>
<body>
<?php require_once('nav.php'); ?>

    <div class="container mt-5">
        <!-- <a href="homepage.php">Home</a> -->
        <!-- <h1>Shopping Cart</h1> -->
        <form method="post">
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
                        $qq = "SELECT * FROM cart_tb WHERE user_id = '$active_user_id'";
                        $rr = mysqli_query($my_conn , $qq);
                        $total = 0;
                        if (mysqli_num_rows($rr) > 0) {
                            while ($result = mysqli_fetch_assoc($rr)) {
                                $qty = $result['quantity'];
                                $dd = $result['product_id'];
                                $sq = "SELECT * FROM product_tb WHERE id = '$dd'";
                                $sq = mysqli_query($my_conn , $sq);
                                while ($rs = mysqli_fetch_assoc($sq)) {
                                    $total += $rs['product_price'];
                                    echo " <tr>
                                            <td>{$rs['product_name']}</td>
                                            <td>&#x20A6;" . number_format($rs['product_price'], 2) . "</td>
                                            <td class='quantity-cell'> 
                                                <button type='button' class='btn btn-secondary' onclick='updateQuantity(\"minus\", {$rs['id']})'>-</button>
                                                <input type='number' name='quantity' class='form-control small-input' id='quantity_{$rs['id']}' 
                                                    data-price='{$rs['product_price']}' value='$qty' 
                                                    onchange='updateProductTotal({$rs['product_price']}, this.value, {$rs['id']})'>
                                                <button type='button' class='btn btn-secondary' onclick='updateQuantity(\"plus\", {$rs['id']})'>+</button>
                                            </td>

 


                                            <td><span id='productTotal_{$rs['id']}' class='productTotal'>{$rs['product_price']}</span></td>
                                            <td>
                                                <form method='post' action=''>
                                                    <input type='hidden' name='item_id' value='{$rs['id']}'>
                                                    <button type='submit' name='remove_cart' class='btn btn-danger btn-sm'>Remove</button>
                                                </form>
                                            </td>
                                        </tr>";
                                }
                            }
                        } else {
                            echo '<tr><td colspan="5"><h4>Your cart is empty!</h4></td></tr>';
                        }
                    ?>
                </tbody>
            </table>
            <div class="text-right">
                <h4>Grand Total: &#x20A6;<span id="grandTotal"><?= number_format($total, 2) ?></span></h4>
                <button type="submit" name="update_quantity" class="btn btn-primary">Update Quantities</button>
                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
