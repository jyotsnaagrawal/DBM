<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['admin_name'])) {
    header('Location: login_form.php');
    exit();
}

include 'config.php';

$selectGroupsQuery = "SELECT * FROM groups WHERE admin_id = ?";
$stmtGroups = mysqli_prepare($conn, $selectGroupsQuery);
mysqli_stmt_bind_param($stmtGroups, "i", $_SESSION['admin_id']);
mysqli_stmt_execute($stmtGroups);
$groups = mysqli_stmt_get_result($stmtGroups)->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupId = $_POST['group_id'];
    $memberName = $_POST['member_name']; // No need for mysqli_real_escape_string

    $insertMemberQuery = "INSERT INTO group_members (group_id, member_name) VALUES (?, ?)";
    $stmtMember = mysqli_prepare($conn, $insertMemberQuery);
    mysqli_stmt_bind_param($stmtMember, "is", $groupId, $memberName);

    if (mysqli_stmt_execute($stmtMember)) {
        mysqli_stmt_close($stmtMember);
        header("Location: view_group.php?group_id=$groupId");
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

            <form method="POST" action="add_members.php"> <!-- Corrected form action -->
                <label for="group_id">Select Group:</label>
                <select id="group_id" name="group_id" required>
                    <?php foreach ($groups as $group) : ?>
                        <option value="<?php echo $group['group_id']; ?>"><?php echo $group['group_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="member_name">Member Name:</label>
                <input type="text" id="member_name" name="member_name" required>
                <input type="hidden" name="admin_id" value="<?php echo $_SESSION['admin_id']; ?>"> <!-- Add hidden input for admin_id -->
                <button type="submit">Add Member</button>
            </form>

        </div>
    </div>


</body>

</html>