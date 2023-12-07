<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php';

// Initialize variables
$userId = $_SESSION['user_id'];

// Fetch all group IDs
$getAllGroupsQuery = "SELECT group_id FROM groups";
$stmtAllGroups = mysqli_prepare($conn, $getAllGroupsQuery);
mysqli_stmt_execute($stmtAllGroups);
$allGroupsResult = mysqli_stmt_get_result($stmtAllGroups);

// Fetch and display information for each group
while ($row = mysqli_fetch_assoc($allGroupsResult)) {
    // Initialize variables
    $groupId = $row['group_id'];
    $members = [];
    $group = [];

    // Fetch members for the group
    $getMembersQuery = "SELECT * FROM group_members WHERE group_id = ?";
    $stmtMembers = mysqli_prepare($conn, $getMembersQuery);
    mysqli_stmt_bind_param($stmtMembers, "i", $groupId);
    mysqli_stmt_execute($stmtMembers);
    $membersResult = mysqli_stmt_get_result($stmtMembers);

    if (!$stmtMembers) {
        die("Error in SQL query preparation: " . mysqli_error($conn));
    }

    // Fetch members and store them in an array
    $members = mysqli_fetch_all($membersResult, MYSQLI_ASSOC);

    // Free the result set
    mysqli_free_result($membersResult);

    // Close the statement
    mysqli_stmt_close($stmtMembers);

    // Fetch group details
    $getGroupQuery = "SELECT * FROM groups WHERE group_id = ?";
    $stmtGroup = mysqli_prepare($conn, $getGroupQuery);
    mysqli_stmt_bind_param($stmtGroup, "i", $groupId);
    mysqli_stmt_execute($stmtGroup);
    $groupResult = mysqli_stmt_get_result($stmtGroup);

    if (!$stmtGroup) {
        die("Error in SQL query preparation: " . mysqli_error($conn));
    }

    // Fetch group details
    $group = mysqli_fetch_assoc($groupResult);

    // Free the result set
    mysqli_free_result($groupResult);

    // Close the statement
    mysqli_stmt_close($stmtGroup);

    // Display group information
    echo "<p>Group Members for Group ID {$groupId}:</p>";
    echo "<ul>";
    foreach ($members as $member) {
        echo "<li>{$member['member_name']}</li>";
    }
    echo "</ul>";
    echo "<hr>";

    echo "<p>Group Details for Group ID {$groupId}:</p>";
    echo "<hr>";
    echo "<table>";
    echo "<hr>";
    echo "<tr><th>Group Name</th></tr>";
    echo "<hr>";
    echo "<tr><td>" . (isset($group['group_name']) ? htmlspecialchars($group['group_name']) : 'N/A') . "</td></tr>";
    echo "</table>";

    // Display individual expenses for the admin user
    if ($_SESSION['admin_name'] === 'Admin User') {
        $getAdminExpensesQuery = "SELECT expense_name, amount, details FROM expenses WHERE paid_by = ? AND group_id = ?";
        $stmtAdminExpenses = mysqli_prepare($conn, $getAdminExpensesQuery);
        mysqli_stmt_bind_param($stmtAdminExpenses, "ii", $userId, $groupId);
        mysqli_stmt_execute($stmtAdminExpenses);

        // Get the result set
        $adminExpensesResult = mysqli_stmt_get_result($stmtAdminExpenses);

        echo "<p>Individual Expenses for Admin User:</p>";
        echo "<ul>";

        while ($expense = mysqli_fetch_assoc($adminExpensesResult)) {
            echo "<li>{$expense['expense_name']}, {$expense['amount']}, {$expense['details']}</li>";
        }

        echo "</ul>";

        // Free the result set
        mysqli_free_result($adminExpensesResult);
        mysqli_stmt_close($stmtAdminExpenses);
    }

    // Fetch members for the group
    $getMembersQuery = "SELECT * FROM group_members WHERE group_id = ?";
    $stmtMembers = mysqli_prepare($conn, $getMembersQuery);
    mysqli_stmt_bind_param($stmtMembers, "i", $groupId);

    if (!$stmtMembers) {
        die("Error in SQL query preparation: " . mysqli_error($conn));
    }

    mysqli_stmt_execute($stmtMembers);

    if (!$stmtMembers) {
        die("Error in SQL query execution: " . mysqli_error($conn));
    }

    $members = mysqli_stmt_get_result($stmtMembers)->fetch_all(MYSQLI_ASSOC);
    mysqli_stmt_close($stmtMembers);

    // Display group members
    echo "<p>Group Members:</p>";
    echo "<ul>";
    foreach ($members as $member) {
        echo "<li>{$member['member_name']}</li>";
    }
    echo "</ul>";

    // Display expense details
    $getAdminExpenseDetailsQuery = "SELECT expense_name, amount FROM expenses WHERE paid_by = ? AND group_id = ?";
    $stmtAdminExpenseDetails = mysqli_prepare($conn, $getAdminExpenseDetailsQuery);
    mysqli_stmt_bind_param($stmtAdminExpenseDetails, "ii", $userId, $groupId);
    mysqli_stmt_execute($stmtAdminExpenseDetails);
    $adminExpenseDetailsResult = mysqli_stmt_get_result($stmtAdminExpenseDetails);

    echo "<p>Expense Details:</p>";
    echo "<ul>";
    while ($expenseDetail = mysqli_fetch_assoc($adminExpenseDetailsResult)) {
        echo "<li>{$group['group_name']} paid {$expenseDetail['amount']} for {$expenseDetail['expense_name']}.</li>";
    }
    echo "</ul>";

    mysqli_stmt_close($stmtAdminExpenseDetails);  // Make sure to close the statement after fetching results

    // Display group balances
    echo "<p>Group Balances:</p>";
    foreach ($members as $member) {
        $balance = calculateGroupBalance($member['user_id'], $groupId, $conn);
        echo "{$member['member_name']}: {$balance}<br>";
    }

    // Display amounts owed to each member
    echo "<p>Amounts Owed:</p>";
    foreach ($members as $member) {
        $amountOwed = calculateAmountOwed($member['user_id'], $groupId, $conn);
        echo "{$member['member_name']} is owed {$amountOwed} for group expenses.<br>";
    }
}

// Function to calculate group balance for a member
function calculateGroupBalance($userId, $groupId, $conn)
{
    // Calculate total expenses paid by the user
    $getPaidExpensesQuery = "SELECT SUM(amount) as total_paid FROM expenses WHERE paid_by = ? AND group_id = ?";
    $stmtPaidExpenses = mysqli_prepare($conn, $getPaidExpensesQuery);
    mysqli_stmt_bind_param($stmtPaidExpenses, "ii", $userId, $groupId);
    mysqli_stmt_execute($stmtPaidExpenses);
    $paidExpensesResult = mysqli_stmt_get_result($stmtPaidExpenses);
    $totalPaid = mysqli_fetch_assoc($paidExpensesResult)['total_paid'];
    mysqli_stmt_close($stmtPaidExpenses);

    // Calculate total expenses where the user owes money
    $getOwedExpensesQuery = "SELECT SUM(amount) as total_owed FROM expenses WHERE owe_to = ? AND group_id = ?";
    $stmtOwedExpenses = mysqli_prepare($conn, $getOwedExpensesQuery);
    mysqli_stmt_bind_param($stmtOwedExpenses, "ii", $userId, $groupId);
    mysqli_stmt_execute($stmtOwedExpenses);
    $owedExpensesResult = mysqli_stmt_get_result($stmtOwedExpenses);
    $totalOwed = mysqli_fetch_assoc($owedExpensesResult)['total_owed'];
    mysqli_stmt_close($stmtOwedExpenses);

    // Calculate the balance
    $balance = $totalPaid - $totalOwed;

    return $balance;
}

// Function to calculate amounts owed to each member
function calculateAmountOwed($userId, $groupId, $conn) {
    // 1. Query to get total expenses paid by the user in the group
    $getTotalExpensesQuery = "SELECT SUM(amount) AS total_expenses FROM expenses WHERE paid_by = ? AND group_id = ?";
    $stmtTotalExpenses = mysqli_prepare($conn, $getTotalExpensesQuery);
    mysqli_stmt_bind_param($stmtTotalExpenses, "ii", $userId, $groupId);
    mysqli_stmt_execute($stmtTotalExpenses);
    $resultTotalExpenses = mysqli_stmt_get_result($stmtTotalExpenses);
    $totalExpenses = mysqli_fetch_assoc($resultTotalExpenses)['total_expenses'];

    // 2. Query to get total expenses for the group
    $getGroupTotalExpensesQuery = "SELECT SUM(amount) AS group_total_expenses FROM expenses WHERE group_id = ?";
    $stmtGroupTotalExpenses = mysqli_prepare($conn, $getGroupTotalExpensesQuery);
    mysqli_stmt_bind_param($stmtGroupTotalExpenses, "i", $groupId);
    mysqli_stmt_execute($stmtGroupTotalExpenses);
    $resultGroupTotalExpenses = mysqli_stmt_get_result($stmtGroupTotalExpenses);
    $groupTotalExpenses = mysqli_fetch_assoc($resultGroupTotalExpenses)['group_total_expenses'];

    // 3. Calculate the amount owed based on your business logic
    // For example, if the user's expenses are 20% of the group's total expenses, the user is owed 20% of the total expenses
    $amountOwed = ($groupTotalExpenses > 0) ? ($totalExpenses / $groupTotalExpenses) : 0;

    // Close the statements
    mysqli_stmt_close($stmtTotalExpenses);
    mysqli_stmt_close($stmtGroupTotalExpenses);

    // Return the calculated amount
    return $amountOwed;
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
    <title>View Group - <?php echo htmlspecialchars($group['group_name']); ?></title>
    <link rel="stylesheet" href="">
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
            <h1>View Group - <?php echo htmlspecialchars($group['group_name']); ?></h1>

            <div class="group-details">
                <h2>Group Details</h2>
              <table>
                    <tr>
                        <th>Group Name</th>
                        <td><?php echo htmlspecialchars($group['group_name']); ?></td>
                    </tr>

                </table>
            </div>

            <div class="expenses">
                <h2>Expenses</h2>
                <?php if (!empty($expenses)) : ?>
                    <table>
                        <tr>
                            <th>Expense Name</th>
                            <th>Amount</th>
                            <th>Date</th>

                        </tr>
                        <?php foreach ($expenses as $expense) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($expense['expense_name']); ?></td>
                                <td><?php echo htmlspecialchars($expense['amount']); ?></td>
                                <td><?php echo htmlspecialchars($expense['date']); ?></td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <strong>Split Details:</strong><br>
                                    <?php echo getSplitDetails($expense['expense_id']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </table>
                <?php else : ?>
                    <p>No expenses found for this group.</p>
                <?php endif; ?>
                <form method="POST" action="view_group.php?group_id=<?php echo $groupId; ?>">
                    <label for="expense_name">Expense Name:</label>
                    <input type="text" id="expense_name" name="expense_name" required>

                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" step="0.01" required>

                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>

                    <label for="paid_by">Paid By:</label>
                    <select id="paid_by" name="paid_by" required>
                        <?php
                        foreach ($members as $member) {
                            echo "<option value='{$member['user_id']}'>{$member['member_name']}</option>";
                        }
                        ?>
                    </select>

                    <label for="owe_to">Owe To:</label>
                    <select id="owe_to" name="owe_to" required>
                        <?php
                        foreach ($members as $member) {
                            echo "<option value='{$member['user_id']}'>{$member['member_name']}</option>";
                        }
                        ?>
                    </select>

                
                <button type="submit" name="add_expense">Add Expense</button>
                </form>
            </div>

            <div class="members">
                <h2>Group Members</h2>
                <?php if (!empty($members)) : ?>
                    <table>
                        <tr>
                            <th>Member Name</th>
                            <!-- Add more member details as needed -->
                        </tr>
                        <?php foreach ($members as $member) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($member['member_name']); ?></td>
                                <!-- Add more member details as needed -->
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else : ?>
                    <p>No members found for this group.</p>
                <?php endif; ?>

                <form method="POST" action="view_group.php?group_id=<?php echo $groupId; ?>">
                    <!-- ... other form fields ... -->
                    <label for="paid_by">Paid By:</label>
                    <select id="paid_by" name="paid_by" required>
                        <option value="">Select User</option> <!-- Add a default option -->
                        <?php
                        foreach ($members as $member) {
                            echo "<option value='{$member['user_id']}'>{$member['member_name']}</option>";
                        }
                        ?>
                    </select>

                    <label for="owe_to">Owe To:</label>
                    <select id="owe_to" name="owe_to" required>
                        <option value="">Select User</option> <!-- Add a default option -->
                        <?php
                        foreach ($members as $member) {
                            echo "<option value='{$member['user_id']}'>{$member['member_name']}</option>";
                        }
                        ?>
                    </select>



                    <button type="submit" name="add_expense">Add Expense</button>
                </form>

            </div>

            <div class="summary">
                <h2>Summary</h2>
                <!-- Add your summary information here -->
            </div>
        </div>
    </div>
</body>

</html>
<script>
    document.querySelector('form').addEventListener('submit', function() {
        var paidBySelect = document.getElementById('paid_by');
        var oweToSelect = document.getElementById('owe_to');

        var paidById = paidBySelect.options[paidBySelect.selectedIndex].value;
        var oweToId = oweToSelect.options[oweToSelect.selectedIndex].value;

        alert('Paid By ID: ' + paidById + ', Owe To ID: ' + oweToId);
    });
</script>




<?php

// Function to get the user who paid for a specific expense
function getPaidBy($expenseId)
{
    global $conn;

    $getPaidByQuery = "SELECT group_members.member_name
                      FROM group_members
                      LEFT JOIN expenses ON group_members.user_id = expenses.paid_by
                      WHERE expenses.expense_id = ?";
    $stmtPaidBy = mysqli_prepare($conn, $getPaidByQuery);
    mysqli_stmt_bind_param($stmtPaidBy, "i", $expenseId);
    mysqli_stmt_execute($stmtPaidBy);
    $paidBy = mysqli_stmt_get_result($stmtPaidBy)->fetch_assoc();
    mysqli_stmt_close($stmtPaidBy);

    return ($paidBy && isset($paidBy['member_name'])) ? $paidBy['member_name'] : 'N/A';
}

// Function to get the users who owe for a specific expense
function getOwes($expenseId)
{
    global $conn;

    $getOwesQuery = "SELECT group_members.member_name
                     FROM group_members
                     LEFT JOIN expenses ON group_members.user_id = expenses.owe_to
                     WHERE expenses.expense_id = ?";
    $stmtOwes = mysqli_prepare($conn, $getOwesQuery);
    mysqli_stmt_bind_param($stmtOwes, "i", $expenseId);
    mysqli_stmt_execute($stmtOwes);
    $owesResult = mysqli_stmt_get_result($stmtOwes);

    // Fetch owes details
    $owes = [];
    while ($row = mysqli_fetch_assoc($owesResult)) {
        $owes[] = $row['member_name'];
    }

    mysqli_stmt_close($stmtOwes);

    return (!empty($owes)) ? implode(', ', $owes) : 'N/A';
}

// Function to get the split details for a specific expense
function getSplitDetails($expenseId)
{
    global $conn;

    // Query to retrieve split details based on the expense ID
    $splitDetailsQuery = "SELECT user_id, amount FROM expense_splits WHERE expense_id = ?";
    $stmtSplitDetails = mysqli_prepare($conn, $splitDetailsQuery);
    mysqli_stmt_bind_param($stmtSplitDetails, "i", $expenseId);
    mysqli_stmt_execute($stmtSplitDetails);
    $splitDetailsResult = mysqli_stmt_get_result($stmtSplitDetails);

    // Fetch split details
    $splitDetails = [];
    while ($row = mysqli_fetch_assoc($splitDetailsResult)) {
        $splitDetails[] = [
            'user_id' => $row['user_id'],
            'amount' => $row['amount'],
        ];
    }

    mysqli_stmt_close($stmtSplitDetails);

    // Format split details for display
    $formattedDetails = [];
    foreach ($splitDetails as $split) {
        $formattedDetails[] = "User ID: {$split['user_id']}, Amount: {$split['amount']}";
    }

    // Return the formatted split details
    return (!empty($formattedDetails)) ? implode('<br>', $formattedDetails) : 'N/A';
}

?>