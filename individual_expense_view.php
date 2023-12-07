<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

// Check if either admin or user is logged in
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['user_id'])) {
    header('Location: login_form.php');
    exit();
}

// Prepare and execute query to fetch groups based on the logged-in user's role
$selectExpenses = "SELECT * FROM individual_expenses WHERE id = ?";
$stmtExpense = mysqli_prepare($conn, $selectExpenses);

// Use the appropriate session variable based on the role (admin or user)
$loggedInId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : $_SESSION['user_id'];

mysqli_stmt_bind_param($stmtExpense, "i", $loggedInId);
mysqli_stmt_execute($stmtExpense);

// Fetch the result and store it in $groups
$expenseList = mysqli_stmt_get_result($stmtExpense)->fetch_all(MYSQLI_ASSOC);

// Close the statement
mysqli_stmt_close($stmtExpense);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Owe</title>
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
                <li><a href="group_owe.php">How Much I Owe</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="content">

            <?php if (isset($expenseList) && !empty($expenseList)) : ?>
                <!-- Display owed amounts within the group -->
                <h2>My Expenses List</h2>
                <table>
                    <tr>
                        <th>Expense Name</th>
                        <th>Expense Date</th>
                        <th>Expense Amount</th>
                        <th>Deductible</th>
                    </tr>
                    <?php foreach ($expenseList as $expense) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($expense['expense_name']); ?></td>
                            <td><?php echo htmlspecialchars($expense['expense_date']); ?></td>
                            <td><?php echo htmlspecialchars($expense['expense_amount']); ?></td>
                            <td><?php echo htmlspecialchars($expense['deductible'] == 1 ? 'Y' : 'N'); ?></td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
