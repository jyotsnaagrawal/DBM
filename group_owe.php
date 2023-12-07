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
$selectGroupsQuery = "SELECT * FROM groups WHERE admin_id = ?";
$stmtGroups = mysqli_prepare($conn, $selectGroupsQuery);

// Use the appropriate session variable based on the role (admin or user)
$loggedInId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : $_SESSION['user_id'];

mysqli_stmt_bind_param($stmtGroups, "i", $loggedInId);
mysqli_stmt_execute($stmtGroups);

// Fetch the result and store it in $groups
$result = mysqli_stmt_get_result($stmtGroups);
$groups = [];

// Check if there are any groups
if ($result) {
    $groups = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Close the statement
mysqli_stmt_close($stmtGroups);

// Display owed amounts within the selected group
if (isset($_GET['group_id'])) {
    $groupId = $_GET['group_id'];

    // Fetch all members in the group
    $selectMembersQuery = "SELECT * FROM group_members WHERE group_id = ?";
    $stmtMembers = mysqli_prepare($conn, $selectMembersQuery);
    mysqli_stmt_bind_param($stmtMembers, "i", $groupId);
    mysqli_stmt_execute($stmtMembers);
    $members = mysqli_stmt_get_result($stmtMembers)->fetch_all(MYSQLI_ASSOC);

    // Fetch owed amounts
    $selectOwesQuery = "SELECT owe_to_gm.member_name as owe_to, paid_for_gm.member_name as owed_by, SUM(amount_owe) AS total_owed 
            FROM `group_expenses` ge, expenses_relation er, group_members owe_to_gm, group_members paid_for_gm 
            WHERE ge.group_id = ?
            AND ge.expense_id = er.expense_id 
            AND owe_to_gm.id = er.owe_to
            AND paid_for_gm.id = er.paid_for
            GROUP BY er.owe_to, er.paid_for";
    $stmtOwes = mysqli_prepare($conn, $selectOwesQuery);
    mysqli_stmt_bind_param($stmtOwes, "i", $groupId);
    mysqli_stmt_execute($stmtOwes);
    $owes = mysqli_stmt_get_result($stmtOwes)->fetch_all(MYSQLI_ASSOC);

    // Organize the owed amounts for easy display
    $owedAmounts = [];
    foreach ($owes as $owe) {
        $owedAmounts[$owe['owe_to']][$owe['owed_by']] = $owe['total_owed'];
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
            <h1>Group Owe</h1>

            <!-- Display group selection dropdown -->
            <form method="GET" action="group_owe.php">
                <label for="group_select">Select Group:</label>
                <select id="group_select" name="group_id" onchange="this.form.submit()" required>
                    <?php foreach ($groups as $group) : ?>
                        <option value="<?php echo $group['group_id']; ?>" <?php echo (isset($_GET['group_id']) && $_GET['group_id'] == $group['group_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($group['group_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php if (isset($owedAmounts) && !empty($owedAmounts)) : ?>
                <!-- Display owed amounts within the group -->
                <h2>Owed Amounts</h2>
                <table>
                    <tr>
                        <th>Paid By</th>
                        <th>Owe To</th>
                        <th>Total Owed Amount</th>
                    </tr>
                    <?php foreach ($owedAmounts as $paidBy => $oweToAmounts) : ?>
                        <?php foreach ($oweToAmounts as $oweTo => $amount) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($owe['owe_to']); ?></td>
                                <td><?php echo htmlspecialchars($owe['owed_by']); ?></td>
                                <td><?php echo $amount; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
