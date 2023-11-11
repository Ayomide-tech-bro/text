<?php
// session_start();
require_once('includes/conn.php');

function updateCart($product_id, $quantity, $user_id) {
    global $my_conn;
    $product_price = get_product_price($product_id);
    // $total_price = $product_price * $quantity;
// Assuming $product_price and $quantity are defined before this line
// $total_price = $product_price * $quantity;

    $sql = "INSERT INTO cart_tb (user_id, product_id, quantity, product_price, total_price) VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + ?, total_price = quantity * product_price";

    $stmt = mysqli_prepare($my_conn, $sql);

    mysqli_stmt_bind_param($stmt, "iiiiii", $user_id, $product_id, $quantity, $product_price, $total_price, $quantity);

    mysqli_stmt_execute($stmt);
}

// Function to get product price (you should define this function)
function get_product_price($product_id) {
    // Implement logic to get product price based on $product_id
}

// Function to get total items in cart for a user
function get_cart_item_count($user_id) {
    global $my_conn;
    $sql = "SELECT SUM(quantity) AS total_items FROM cart_tb WHERE user_id = ?";
    // $stmt = mysqli_prepare($my_conn, $query);
    $stmt = mysqli_prepare($my_conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['total_items'];
}

// Add item to cart
if(isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    updateCart($product_id, $quantity, $user_id);
    header('location: cart.php');
    exit();
}

// Update cart
if(isset($_POST['update_cart'])) {
    $user_id = $_SESSION['user_id'];
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];

    foreach($product_ids as $key => $product_id) {
        $quantity = $quantities[$key];
        updateCart($product_id, $quantity, $user_id);
    }
    header('location: cart.php');
    exit();
}

// Remove item from cart
if(isset($_POST['remove_cart'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $sql = "DELETE FROM cart_tb WHERE user_id = ? AND product_id = ?";
    // $stmt = mysqli_prepare($my_conn, $query);
    $stmt = mysqli_prepare($my_conn, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($stmt);
    header('location: cart.php');
    exit();
}

// Fetch cart items for the logged in user
$user_id = $_SESSION['user_id'];
// $query = "SELECT c.*, p.product_name, p.product_price, p.product_image FROM cart_tb c
$sql = "SELECT c.*, p.product_name, p.product_price, p.product_image FROM cart_tb c
        JOIN product_tb p ON c.product_id = p.id
        WHERE c.user_id = ?";



// $sql = "SELECT c.*, p.product_name, p.proudct_price, p.product_image From cart_tb c 
// FROM comments c
// INNER JOIN products p ON c.product_id = p.id
// WHERE c.user_id = ?";

// $stmt = mysqli_prepare($my_conn, $query);
$stmt = mysqli_prepare($my_conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$cartItems = [];
$total = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $total += $row['total_price'];
    $cartItems[] = $row;
}

$total_items = get_cart_item_count($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Your Website</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        Cart <?php if($total_items > 0) echo "<span class='badge badge-primary'>$total_items</span>"; ?>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Shopping Cart</h1>
        <form action="" method="post">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Product Image</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item) : ?>
                        <tr>
                            <td><img src="<?= $item['product_image'] ?>" alt="<?= $item['product_name'] ?>" style="max-width: 100px;"></td>
                            <td><?= $item['product_name'] ?></td>
                            <td><?= number_format($item['product_price']) ?></td>
                            <td>
                                <button type="button" class="btn btn-secondary" onclick="updateQuantity('minus', <?= $item['product_id'] ?>)">-</button>
                                <input type="number" name="quantity[]" value="<?= $item['quantity'] ?>" min="1" style="width: 50px;">
                                <input type="hidden" name="product_id[]" value="<?= $item['product_id'] ?>">
                                <button type="button" class="btn btn-secondary" onclick="updateQuantity('plus', <?= $item['product_id'] ?>)">+</button>
                            </td>
                            <td><?= number_format($item['total_price']) ?></td>
                            <td>
                                <button type="submit" name="update_cart" class="btn btn-primary">Update</button>
                                <button type="submit" name="remove_cart" class="btn btn-danger" value="<?= $item['product_id'] ?>">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
        <div class="text-right">
            <h4>Grand Total: ₦<?= number_format($total) ?></h4>
            <a href="#" class="btn btn-primary">Proceed to Checkout</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function updateQuantity(action, productId) {
            let quantityElement = document.getElementById(`quantity_${productId}`);
            let currentQuantity = parseInt(quantityElement.value);
            if (action === 'plus') {
                quantityElement.value = currentQuantity + 1;
            } else if (action === 'minus' && currentQuantity > 1) {
                quantityElement.value = currentQuantity - 1;
            }
        }
    </script>
</body>
</html>
