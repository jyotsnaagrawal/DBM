<?php
@include 'config.php';
session_start();
if (!isset($_SESSION['admin_name'])) {
    header('location:login_form.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>

    <!-- Custom CSS file link -->
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
                <li><a href="#">Home</a></li>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">

        <div class="content">
            <h3>Hi, <span>Admin</span></h3>
            <h1>Welcome <span><?php echo $_SESSION['admin_name'] ?></span></h1>
            <p>This is an admin page</p>
            <!-- Add more content or buttons as needed -->
        </div>

    </div>

</body>

</html>
