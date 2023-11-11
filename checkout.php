<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
body {
    font-family: Arial, sans-serif;
}

/* .container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
} */

label, input {
    display: block;
    margin-bottom: 10px;
}

input {
    width: 100%;
    padding: 8px;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #45a049;
}
</style>
<body>
<?php require_once('nav.php'); ?>

<h1>Checkout</h1>

    <div class="container">
        <form action="process_payment.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required><br>

            <label for="card_number">Credit Card Number:</label>
            <input type="text" id="card_number" name="card_number" required><br>

            <label for="exp_date">Expiration Date:</label>
            <input type="text" id="exp_date" name="exp_date" required><br>

            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" required><br>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>

</html>

