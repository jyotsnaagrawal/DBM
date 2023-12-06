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

// // Fetch all groups 

// $selectGroupsQuery = "SELECT * FROM groups";
// $stmtGroups = mysqli_prepare($conn, $selectGroupsQuery);
// mysqli_stmt_execute($stmtGroups);
// $groupsResult = mysqli_stmt_get_result($stmtGroups);
// $groups = mysqli_fetch_all($groupsResult, MYSQLI_ASSOC);
// mysqli_stmt_close($stmtGroups);

// Fetch group members based on the selected group ID
if ($groupId) {
    $selectMembersQuery = "SELECT * FROM group_members WHERE group_id = ?";
    $stmtMembers = mysqli_prepare($conn, $selectMembersQuery);
    mysqli_stmt_bind_param($stmtMembers, "i", $groupId);
    mysqli_stmt_execute($stmtMembers);
    $membersResult = mysqli_stmt_get_result($stmtMembers);
    $members = mysqli_fetch_all($membersResult, MYSQLI_ASSOC);
    mysqli_stmt_close($stmtMembers);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Your existing code for adding new members to the group

   // $groupId = $_POST['group_id'];
    $expenseName = mysqli_real_escape_string($conn, $_POST['expense_name']);
    $amount = mysqli_real_escape_string($conn, $_POST['expense_amount']);
    $date = mysqli_real_escape_string($conn, $_POST['expense_date']);
    $paidBy = $_POST['paid_by']; // Modified to handle multiple selections
    $oweTo = $_POST['owe_to']; // Modified to handle multiple selections

    $insertExpenseQuery = "INSERT INTO group_expenses (group_id, expense_name, amount, date, paid_by, owe_to) VALUES (?, ?, ?, STR_TO_DATE(?, '%Y-%m-%d'), ?, ?)";
    $stmtExpense = mysqli_prepare($conn, $insertExpenseQuery);
    mysqli_stmt_bind_param($stmtExpense, "isdsii", $groupId, $expenseName, $amount, $date, $paidBy, $oweTo);

    if (mysqli_stmt_execute($stmtExpense)) {
        mysqli_stmt_close($stmtExpense);
        // Redirect or display success message
        header("Location: group_dashboard.php?group_id=$groupId");
        exit();
    } else {
        $error = 'Failed to add expense. Please try again.';
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
    <title>Add Group Expense</title>
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
                <li><a href="group_expenses.php">Group Expenses</a></li>
                <li><a href="#">Settle Up</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="content">
            <h1>Add Group Expense</h1>
            <?php if (isset($error)) : ?>
                <p class="error-msg"><?php echo $error; ?></p>
            <?php endif; ?>

            <!-- Your add group expense form goes here -->
            <form method="POST" action="add_group_expense.php">
                <!-- <label for="group_id">Select Group:</label>
                <select id="group_id" name="group_id" required>
                    <?php foreach ($groups as $group) : ?>
                        <option value="<?php echo $group['group_id']; ?>"><?php echo $group['group_name']; ?></option>
                    <?php endforeach; ?>
                </select> -->

                <label for="expense_name">Expense Name:</label>
                <input type="text" id="expense_name" name="expense_name" required>

                <label for="expense_amount">Expense Amount:</label>
                <input type="number" id="expense_amount" name="expense_amount" step="0.01" required>

                <label for="expense_date">Expense Date:</label>
                <input type="date" id="expense_date" name="expense_date" required>

                <!-- Modified "Paid By" field for multiple selections -->
                <label for="paid_by">Paid By:</label>
                <select id="paid_by" name="paid_by[]" multiple>
                    <?php foreach ($members as $member) : ?>
                        <option value="<?php echo $member['id']; ?>"><?php echo $member['member_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                 <!-- Modified "Owe To" field for multiple selections -->
                 <label for="owe_to">Owe To:</label>
                <select id="owe_to" name="owe_to[]" multiple>
                    <?php foreach ($members as $member) : ?>
                        <option value="<?php echo $member['id']; ?>"><?php echo $member['member_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                   

                <button type="submit">Submit Expense</button>
            </form>
        </div>
    </div>

    <!-- Add your other HTML content here -->

    <!-- Add your scripts if needed -->

</body>

</html>
