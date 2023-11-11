<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "commerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
if(isset($_POST['delete_category'])) {
    $categoryToDelete = $_POST['category_to_delete'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM category_tb WHERE category_name = ?");
    $stmt->bind_param("s", $categoryToDelete);

    if ($stmt->execute()) {
        // Category successfully deleted
        echo "<p class='message success'>Category '$categoryToDelete' has been successfully deleted.</p>";
    } else {
        // Error occurred during deletion
        echo "<p class='message error'>Error deleting category: " . $conn->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<?php
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categories = [];
$result = $conn->query("SELECT category_name FROM category_tb");

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categories[] = $row["category_name"];
    }
}

$conn->close();
?>



<?php
// // Assuming you have a list of categories
// $categories = ["Category 1", "Category 2", "Category 3"];

// // Check if a category is selected for deletion
// if(isset($_POST['delete_category'])) {
//     $categoryToDelete = $_POST['category_to_delete'];

//     // Check if the selected category exists in the list
//     if(in_array($categoryToDelete, $categories)) {
//         // Remove the category from the list
//         $categories = array_diff($categories, array($categoryToDelete));

//         // Display a success message
//         echo "<p>Category '$categoryToDelete' has been successfully deleted.</p>";
//     } else {
//         // Display an error message if the category doesn't exist
//         echo "<p>Category '$categoryToDelete' does not exist.</p>";
//     }
// }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
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
<body>

<form action="" method="post">
    <h1>Delete Category</h1>
    <label for="category_to_delete">Select Category to Delete:</label>
    <select id="category_to_delete" name="category_to_delete">
        <?php
            // Generate options for the select menu
            foreach($categories as $category) {
                echo "<option value='$category'>$category</option>";
            }
        ?>
    </select>
    <button type="submit" name="delete_category">Delete Category</button>
</form>

</body>
</html>