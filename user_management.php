<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "commerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['change_to_admin'])) {
    $username = $_POST['username'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE username = ?");
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        // User role successfully updated
        echo "<p class='message success'>User '$username' is now an admin.</p>";
    } else {
        // Error occurred during update
        echo "<p class='message error'>Error updating user role: " . $conn->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "commerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['change_to_admin'])) {
    $username = $_POST['username'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE username = ?");
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        // User role successfully updated
        echo "<p class='message success'>User '$username' is now an admin.</p>";
    } else {
        // Error occurred during update
        echo "<p class='message error'>Error updating user role: " . $conn->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change User Role</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php require_once('nav.php'); ?>

    <div class="container mt-5">
        <h1>Change User Role</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <button type="submit" class="btn btn-primary" name="change_to_admin">Change to Admin</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
