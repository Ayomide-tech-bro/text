<?php 
require_once('../dashboard/includes/conn.php');

$error = null;
$email = null;
$password = null; 

if (isset($_POST['btn_login'])) {
    $email    = sanitize_var($my_conn, $_POST['email']);
    $password = sanitize_var($my_conn, $_POST['password']);

    $query = "SELECT * FROM user_tb WHERE email=?";
    $stmt = mysqli_prepare($my_conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $n_row1 = mysqli_num_rows($result);

    if ($n_row1 > 0) {
        $rd = mysqli_fetch_array($result);
        $md_password = SALT_PREFIX . $password . SALT_SUFFIX;
        if (password_verify($md_password, $rd['password']) === true) {

            // Save user info in session
            $_SESSION['first_name'] = $rd['first_name'];
            $_SESSION['last_name'] = $rd['last_name'];
            $_SESSION['email'] = $rd['email'];
            $_SESSION['telephone'] = $rd['telephone'];
            $_SESSION['user_type'] = $rd['user_type'];
            $_SESSION['user_id'] = $rd['id'];

            header('location:../dashboard/homepage.php');

        } else {
            $error = "Invalid email or password. Please try again.";
        }
    } else {
        $error = "Invalid email or password. Please try again.";
    }
}
?>

<!-- Your HTML login form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="../dist/css/bootstrap.min.css">
</head>
<body>

    <div class="login-container">
        <h1>Login</h1>

        <?php
            if ($error != '') {
                echo ' <div id="error-msg" class="alert alert-danger error message" style="color: red; margin-bottom: 10px;">' . $error . '</div>';
            }
            ?>
        <form id="login-form" method="post"> 

        <p class="text-center pt-4">Don't have an account? <a href="signup.php">Register</a></p>

            <label for="email">Email:</label>
            <input type="email" id="email" value="<?php echo  $email?>"  name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" value="<?php echo  $password?>"  name="password" required>
            
            <button type="submit" name="btn_login">Login</button>

            <tr>
                                    <td colspan="2">
                                          <p class="mb-0 text-center">
                                              <a href="forget_password.php"> Forget Password</a>
                                          </p>
                                    </td>
                                </tr>
        </form>
    </div>
    
</body>
</html>
