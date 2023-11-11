 <?php
require_once('../dashboard/includes/conn.php');

$first_name = '';
$last_name = '';
$email = '';
$telephone = '';
$password = '';
$msg = '';
$error = '';

if (isset($_POST['btn_signup'])) {
    $first_name = sanitize_var($my_conn, $_POST['first_name']);
    $last_name = sanitize_var($my_conn, $_POST['last_name']);
    $email = sanitize_var($my_conn, $_POST['email']);
    $telephone = sanitize_var($my_conn, $_POST['telephone']);
    $password = sanitize_var($my_conn, $_POST['password']);
    $c_password = sanitize_var($my_conn, $_POST['confirm_password']);
    $usr_type = 'standard';

    if ($password === $c_password) {
        // Hash the user password securely
        $hashed_pword = password_hash(SALT_PREFIX . $password . SALT_SUFFIX, PASSWORD_BCRYPT);

        // Check if the email is already registered
        $query = "SELECT id FROM user_tb WHERE email=?";
        $stmt = mysqli_prepare($my_conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $n_row1 = mysqli_num_rows($result);
        
        // header('location:../dashboard/homepage.php');

        if ($n_row1 > 0) {
            $error = "This email already exists with us. Please login if you've registered before.";
        } else {
            $sql = "INSERT INTO user_tb (first_name, last_name, email, telephone, user_typ, password) VALUES (?, ?, ?, ?, ? , ?)";
            $stmt = mysqli_prepare($my_conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssss", $first_name, $last_name, $email, $telephone, $usr_type, $hashed_pword);
            mysqli_stmt_execute($stmt);
            $n_row2 = mysqli_stmt_affected_rows($stmt);
            if ($n_row2 > 0) {
                $msg = 'Record saved successfully';
            } else {
                $error = 'Something went wrong, please try again!';
            }
        }
    } else {
        $error = "Passwords do not match. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
    <link rel="stylesheet" href="../dist/css/bootstrap.min.css">
</head>
<body>
    <form action="" method="post">
        <div class="signin-container">
            <h1>Sign Up</h1>
            <?php
            if ($msg != '') {
                echo '<div class="alert alert-primary" style="color: blue; margin-bottom: 10px;">' . $msg . '</div>';
            }
            if ($error != '') {
                echo ' <div id="error-msg" class="alert alert-danger error message" style="color: red; margin-bottom: 10px;">' . $error . '</div>';
            }
            ?>

            <p class="text-center pt-4">Already have an account? <a href="login.php">Login</a></p>

            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="telephone">Telephone:</label>
                <input type="telephone" id="telephone" name="telephone" required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" name="btn_signup">Sign Up</button>
        </div>
    </form>
</body>
</html> 



















<?php
// Databa

$servername = "localhost";
$username = "root";
$password = " ";
$database = "commerce_db";

// Create a connection
$my_conn = mysqli_connect('localhost','root','','commerce_db');

// Check connection
if ($my_conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $telephone= $_POST["telephone"];
    $user_type= $_POST["user_type"];
    $password= $_POST["password"];


    // Prepare and bind the SQL statement
    // $msg = $my_conn->prepare("INSERT INTO user (first_name, last_name, email, gender, password , user_typ') VALUES (?, ?, ?, ?, ? , ?)");
    // $msg->bind_param("ssssss", $first_name, $last_name, $email, $gender, $password , $standard);

    $msg = $my_conn->prepare("INSERT INTO user_tb (first_name, last_name, email, telephone, user_type password, ) VALUES (?, ?, ?, ?, ?, ?)");
$msg->bind_param("ssssss", $first_name, $last_name, $email, $telephone, $user_type , );


    // Execute the statement
    if ($msg->execute()) {
        echo "Record saved successfully.";

    } else {
        echo "Error: " . $msg->error;
    }

    // Close the statement
    $msg->close();
}

// Close the database connection
$my_conn->close();
?>



 
























<!-- 

<!DOCTYPE html>
<html>
<head>
  <title>Sign Up Page</title>
  <link rel="stylesheet" href="dist\css\bootstrap.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: indigo;
       margin-top: 60px 
    }
    .container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 5px;
      box-shadow: 0 5px 5px rgba(0, 0, 0, 0.1);
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
    }
    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #b11d1d;
      border-radius: 3px;
    }
    .form-group input:focus {
      outline: none;
      border-color: #007bff;
    }
    .error {
      color: rgb(207, 74, 74);
    }
  </style>
</head>
<body>
<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
  <div class="container">
  <center> <img src="dist\img\e lolo.png " alt="Image" width="60" height="60"></center>
  <center><h3><a href="login.php"> Signin </a>to Zella </h3></center>
   


    <form id="signupForm">
      <div class="form-group">
        <label for="first name">first name</label>
        <input type="text" id="username" name="first_name" required>
        <span class="error" id="usernameError"></span>
      </div>

      <div class="form-group">
        <label for="first name">Last name</label>
        <input type="text" id="username" name="last_name" required>
        <span class="error" id="usernameError"></span>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <span class="error" id="emailError"></span>
      </div>

      <div class="form-group">
                                <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" id="" class="form-select" required>
                                    <option value="male"></option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <span class="error" id="passwordError"></span>
      </div>
      
      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
        <span class="error" id="confirmPasswordError"></span>
      </div>
      <div>
      <div class="form-group col-12 text-center">
           <button class="btn btn-secondary  col-5 text-light " type="reset">Clear</button>
            
            <button class="btn col-5 align-item-center btn-primary " name="submit" type="submit">Sign up</button>

            
            </div>
    </div>
    </form>
  </div>

  
  </form>
</body>
</html>




 -->



























































































</body>














