<?php
include 'config.php';

session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if the user exists
    $select = "SELECT * FROM user_form WHERE email = ?";
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        // Use password_verify to check the hashed password
        if (password_verify($password, $row['password'])) {
            // Set the appropriate session variables based on user_type
            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_id'] = $row['user_id'];
                $_SESSION['admin_name'] = $row['name'];
                header('location: admin_dashboard.php');
                exit(); // Add exit to stop script execution after redirection
            } elseif ($row['user_type'] == 'user') {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_name'] = $row['name'];
                header('location: individual_dashboard.php');
                exit(); // Add exit to stop script execution after redirection
            }
        } else {
            $error[] = 'Incorrect email or password!';
        }
    } else {
        $error[] = 'Incorrect email or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login Form</title>

   <!-- Custom CSS file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <nav>
        <div class="logo">
            <!-- Add your logo image or text here -->
            <img src="css/images/logo.png" alt="Logo">
        </div>
        <ul class="nav-links">
            <li><a href="#">Login</a></li>
            <li><a href="register_form.php">Signup</a></li>
        </ul>
    </nav>
</header>

<div class="form-container">
   <form action="" method="post">
      <h3>Login Now</h3>
      <?php
      // Display errors if any
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="email" name="email" required placeholder="Enter your email" autocomplete="username">
      <input type="password" name="password" required placeholder="Enter your password" autocomplete="current-password">
      <input type="submit" name="submit" value="Login Now" class="form-btn">
   
      <p>Don't have an account? <a href="register_form.php">Register now</a></p>
      </form>
</div>


</body>
</html>
