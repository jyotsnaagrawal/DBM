<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_name'])) {
    header('Location: login_form.php');
    exit();
}

// Include your config file
include 'config.php';

// Fetch and display admin-specific information from user_form table
$adminId = $_SESSION['admin_id'];

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $expenseName = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['expense_name']));
    $expenseAmount = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['expense_amount']));
    $expenseDate = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['expense_date']));
    $deductible = isset($_POST['deductible']) ? 1 : 0;

    // Perform database insertion using prepared statements to prevent SQL injection
    $insertExpense = "INSERT INTO individual_expenses (expense_name, expense_amount, expense_date, deductible, ID) VALUES (?, ?, STR_TO_DATE(?, '%Y-%m-%d'), ?, ?)";
    $stmt = mysqli_prepare($conn, $insertExpense);
    mysqli_stmt_bind_param($stmt, "sdsii", $expenseName, $expenseAmount, $expenseDate, $deductible, $adminId);

    if (mysqli_stmt_execute($stmt)) {
        // Insertion successful
        mysqli_stmt_close($stmt);

        // Redirect to the dashboard or display a success message
        header('Location: individual_dashboard.php');
        exit();
    } else {
        // Insertion failed
        $error = 'Expense creation failed. Please try again. ' . mysqli_error($conn); // Provide more details for debugging
    }
}
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
            <li><a href="add_expense.php">Add an Expense</a></li>
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
