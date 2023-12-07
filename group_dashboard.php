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

$groupId = isset($_GET['group_id']) ? $_GET['group_id'] : null;

// Fetch group details
if ($groupId) {
    $selectGroupQuery = "SELECT * FROM groups WHERE group_id = ?";
    $stmtGroup = mysqli_prepare($conn, $selectGroupQuery);
    mysqli_stmt_bind_param($stmtGroup, "i", $groupId);
    mysqli_stmt_execute($stmtGroup);
    $groupResult = mysqli_stmt_get_result($stmtGroup);
    $group = mysqli_fetch_assoc($groupResult);
    mysqli_stmt_close($stmtGroup);

    // Fetch group expenses
    $selectExpensesQuery = "SELECT * FROM group_expenses WHERE group_id = ?";
    $stmtExpenses = mysqli_prepare($conn, $selectExpensesQuery);
    mysqli_stmt_bind_param($stmtExpenses, "i", $groupId);
    mysqli_stmt_execute($stmtExpenses);
    $expensesResult = mysqli_stmt_get_result($stmtExpenses);
    $expenses = mysqli_fetch_all($expensesResult, MYSQLI_ASSOC);
    mysqli_stmt_close($stmtExpenses);
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
    <title>Group Dashboard</title>
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
            <h1>Group Dashboard</h1>

            <?php if (isset($group)) : ?>
                <h2><?php echo $group['group_name']; ?></h2>
                <!-- <p><?php echo $group['description']; ?></p> -->

                <h3>Group Expenses</h3>
                <?php if (!empty($expenses)) : ?>
                    <ul>
                        <?php foreach ($expenses as $expense) : ?>
                            <li>
                                <strong>Expense Name: <?php echo $expense['expense_name']; ?></strong>
                                <p>Amount: <?php echo $expense['amount']; ?></p>
                                <p>Date: <?php echo $expense['date']; ?></p>
                                <!-- Add more details as needed -->
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>No expenses recorded for this group.</p>
                <?php endif; ?>
            <?php else : ?>
                <p>No group found with the specified ID.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
