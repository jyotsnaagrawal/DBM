<?php
session_start();

if (!(isset($_SESSION['user_name']) || isset($_SESSION['admin_name']))) {
    header('Location: login_form.php');
    exit();
}

include 'config.php';

// Fetch and display admin-specific information from user_form table
if (isset($_SESSION['admin_name'])) {
   $adminId = $_SESSION['admin_id']; // Assuming you store admin_id in the session
} else if (isset($_SESSION['user_name'])) {
   $adminId = $_SESSION['user_id']; // Assuming you store user_id in the session
}

$selectAdmin = "SELECT * FROM user_db.user_form WHERE user_id = ?";
$stmtAdmin = mysqli_prepare($conn, $selectAdmin);
mysqli_stmt_bind_param($stmtAdmin, "i", $adminId);
mysqli_stmt_execute($stmtAdmin);
$adminData = mysqli_stmt_get_result($stmtAdmin)->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Dashboard</title>
   <style>
      body {
         margin: 0;
         padding: 0;
         font-family: 'Poppins', sans-serif;
         background: #f2f2f2;
      }

      header {
         background: #333;
         padding: 15px 20px;
         color: white;
      }

      .container {
         display: flex;
         justify-content: center;
         align-items: center;
         height: 80vh;
         background: white;
         border-radius: 10px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
         margin: 20px;
      }

      .content {
         text-align: center;
         padding: 20px;
      }

      .content h1 {
         font-size: 36px;
         color: #333;
      }

      .nav-links {
         list-style: none;
         display: flex;
      }

      .nav-links li {
         margin-right: 20px;
      }

      .nav-links a {
         text-decoration: none;
         color: #fff;
         font-size: 16px;
         font-weight: bold;
         padding: 10px;
         border-radius: 5px;
         transition: background 0.3s ease;
      }

      .nav-links a:hover {
         background: #555;
      }

      .container .content i {
         font-size: 50px;
         color: crimson;
      }

      .group-buttons {
         margin-top: 20px;
      }

      .group-buttons button {
         padding: 15px 30px;
         font-size: 20px;
         margin: 0 10px;
         background: #333;
         color: #fff;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         transition: background 0.3s ease;
      }

      .group-buttons button:hover {
         background: crimson;
      }
   </style>
</head>
<body>
   <header>
      <nav>
         <div class="logo">
            <!-- Add your logo image or text here -->
            <img src="css/images/logo.png" alt="Logo">
         </div>
         <ul class="nav-links">
            <li><a href="individual_dashboard.php">Dashboard</a></li>
            <li><a href="add_expense.php">Add Individual Expense</a></li>
            <li><a href="individual_expense_view.php">My Individual Expenses</a></li>
            <li><a href="group_owe.php">How Much I Owe</a></li>
            <li><a href="logout.php">Logout</a></li>
         </ul>
      </nav>
   </header>

   <div class="container">
      <div class="content">
         <h1>Welcome to Expense Fusion Pro, <?php echo $adminData['name']; ?></h1>
         <!-- Your admin-specific content goes here -->
         
         <div class="group-buttons">
            <button onclick="creatGroup()">Create Group</button>
            <button onclick="manageGroup()">Manage Group</button>
         </div>

         
      </div>
   </div>

   <!-- Add your other HTML content here -->

   <script>
    function creatGroup() {
        // Redirect to the add expense page
        window.location.href = 'create_group.php';
    }

    function manageGroup() {
        // Redirect to the settle up page
        window.location.href = 'manage_groups.php';
    }
   </script>
</body>
</html>
