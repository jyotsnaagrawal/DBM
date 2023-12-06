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

$groupName = isset($_GET['group_name']) ? $_GET['group_name'] : null;
$adminId = isset($_GET['admin_id']) ? $_GET['admin_id'] : null;

// Fetch group info
$selectGroupQuery = "SELECT * FROM groups WHERE group_name = ? AND admin_id = ?";
$stmtGroup = mysqli_prepare($conn, $selectGroupQuery);
mysqli_stmt_bind_param($stmtGroup, "si", $groupName, $adminId);
mysqli_stmt_execute($stmtGroup);
$groupResult = mysqli_stmt_get_result($stmtGroup);
$group = mysqli_fetch_all($groupResult, MYSQLI_ASSOC);
mysqli_stmt_close($stmtGroup);
$groupId = $group[0]['group_id'];



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
    var_dump($groupId);
    $groupId = $_POST['group_id'];
    $memberName = mysqli_real_escape_string($conn, $_POST['member_name']);

    $insertMemberQuery = "INSERT INTO group_members (group_id, member_name) VALUES (?, ?)";
    $stmtMember = mysqli_prepare($conn, $insertMemberQuery);
    mysqli_stmt_bind_param($stmtMember, "is", $groupId, $memberName);

    if (mysqli_stmt_execute($stmtMember)) {
        mysqli_stmt_close($stmtMember);
        // Redirect to the "add members" page with the corresponding group ID
        header("Location: add_group_expense.php?group_id=$groupId");
        exit();
    } else {
        $error = 'Failed to add member. Please try again.';
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
    <title>Add Members to Group</title>
</head>

<body>
    <header>
        <!-- Include your navigation/header content here -->
    </header>

    <div class="container">
        <div class="content">
            <h1>Add Members to Group</h1>
            <?php if (isset($error)) : ?>
                <p class="error-msg"><?php echo $error; ?></p>
            <?php endif; ?>

            <!-- Display the list of members for the selected group -->
            <?php if (!empty($members)) : ?>
                <h2>Group Members:</h2>
                <ul>
                    <?php foreach ($members as $member) : ?>
                        <li><?php echo $member['member_name']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form method="POST" action="add_members.php">
                <!-- <label for="group_id">Select Group:</label>
                <select id="group_id" name="group_id" required>
                    <?php foreach ($groups as $group) : ?>
                        <option value="<?php echo $group['id']; ?>"><?php echo $group['group_name']; ?></option>
                    <?php endforeach; ?>
                </select> -->
                <input type='hidden' name='group_id' value='<?php echo "$groupId";?>'/>
                <label for="member_name">Member Name:</label>
                <input type="text" id="member_name" name="member_name" required>
                <button type="submit">Add Member</button>
            </form>
        </div>
    </div>
</body>

</html>