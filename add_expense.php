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
$adminId = $_SESSION['admin_id']; // Assuming you store admin_id in the session

$selectAdmin = "SELECT * FROM user_db.user_form WHERE user_id = ?";
$stmtAdmin = mysqli_prepare($conn, $selectAdmin);
mysqli_stmt_bind_param($stmtAdmin, "i", $adminId);
mysqli_stmt_execute($stmtAdmin);
$adminData = mysqli_stmt_get_result($stmtAdmin)->fetch_assoc();

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $expenseName = mysqli_real_escape_string($conn, $_POST['expense_name']);
    $expenseAmount = mysqli_real_escape_string($conn, $_POST['expense_amount']);
    $expenseDate = mysqli_real_escape_string($conn, $_POST['expense_date']);
    $deductible = isset($_POST['deductible']) ? 1 : 0; // Assuming checkbox value should be stored as a boolean

    // Perform database insertion using prepared statements to prevent SQL injection
    $insertExpense = "INSERT INTO individual_expenses (ID, expense_name, expense_amount, expense_date, deductible) VALUES (?, ?, ?, STR_TO_DATE(?, '%Y-%m-%d'), ?)";
    $stmt = mysqli_prepare($conn, $insertExpense);
    mysqli_stmt_bind_param($stmt, "isdsi", $adminId, $expenseName, $expenseAmount, $expenseDate, $deductible);

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
    <title>Add Expense</title>
    <!-- Add additional styles if needed -->
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
                <li><a href="#">Add an Expense</a></li>
                
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="content">
            <h1>Add Expense</h1>
            <!-- Display error message if insertion failed -->
            <?php if (isset($error)) : ?>
                <p class="error-msg"><?php echo $error; ?></p>
            <?php endif; ?>

            <!-- Your add expense form goes here -->
            <form method="POST" action="add_expense.php">
                <label for="expense_amount">Expense Amount:</label>
                <input type="number" id="expense_amount" name="expense_amount" step="0.01" required>

                <label for="expense_date">Expense Date:</label>
                <input type="date" id="expense_date" name="expense_date" required>


                <label for="deductible">Deductible:</label>
                <input type="checkbox" id="deductible" name="deductible">

                <label for="expense_name">Expense Name:</label>
                <input type="text" id="expense_name" name="expense_name" required>
 

                <button type="submit">Submit Expense</button>
            </form>
        </div>
    </div>

    <!-- Add your other HTML content here -->

    <!-- Add your scripts if needed -->

</body>
</html>
