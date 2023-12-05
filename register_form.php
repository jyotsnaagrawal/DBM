<?php
// Include the configuration file
include 'config.php';
// Initialize the $error array to store error messages
$error = [];

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);



// Check if the registration form is submitted
if (isset($_POST['submit'])) {
    // Sanitize user input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $user_type = $_POST['user_type'];

    // Check if the user already exists in the database
    $selectQuery = "SELECT * FROM user_form WHERE email = ?";
    $stmt = mysqli_prepare($conn, $selectQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if the user already exists and the password matches
    if ($row = mysqli_fetch_assoc($result) && password_verify($password, $row['password'])) {
        $error[] = 'User already exists!';
    } else {
        // Check if passwords match
        if ($password != $cpassword) {
            $error[] = 'Passwords do not match!';
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $insertQuery = "INSERT INTO user_form(name, email, password, user_type) VALUES(?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashedPassword, $user_type);
            mysqli_stmt_execute($stmt);

            // Redirect to login page after successful registration
            header('location: login_form.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Form</title>

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
            <li><a href="login_form.php">Login</a></li>
           
        </ul>
    </nav>
</header>

<div class="form-container">
   <form action="" method="post">
      <h3>Register Now</h3>
      <?php
      // Display errors if any
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="text" name="name" required placeholder="Enter your name" autocomplete="name">
      <input type="email" name="email" required placeholder="Enter your email" autocomplete="email">
      <input type="password" name="password" required placeholder="Enter your password" autocomplete="new-password">
      <input type="password" name="cpassword" required placeholder="Confirm your password" autocomplete="new-password">
      <select name="user_type" autocomplete="user-type">
        
         <option value="admin">Admin</option>
         
      </select>
      <input type="submit" name="submit" value="Register Now" class="form-btn">
      <p>Already have an account? <a href="login_form.php">Login now</a></p>
   </form>
</div>

</body>
</html>
