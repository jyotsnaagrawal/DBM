<?php
session_start();

if (!isset($_SESSION['admin_name'])) {
    header('Location: login_form.php');
    exit();
}

include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupName = mysqli_real_escape_string($conn, $_POST['group_name']);
    $adminId = $_SESSION['admin_id'];

    if ($adminId === null) {
        $error = 'Admin ID is not set. Please log in.';
    } else {
        $insertGroupQuery = "INSERT INTO groups (group_name, admin_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insertGroupQuery);
        mysqli_stmt_bind_param($stmt, "si", $groupName, $adminId);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: individual_dashboard.php');
            exit();
        } else {
            $error = 'Group creation failed. Please try again. ' . mysqli_error($conn);
        }
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
    <title>Create Group</title>
   
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <img src="css/images/logo.png" alt="Logo">
            </div>
            <ul class="nav-links">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="content">
            <h1>Create Group</h1>
            <?php if (isset($error)) : ?>
                <p class="error-msg"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="create_group.php">                <label for="group_name">Group Name:</label>
                <input type="text" id="group_name" name="group_name" required>
                <button type="submit">Create Group</button>
            </form>
        </div>
    </div>
</body>

</html>
