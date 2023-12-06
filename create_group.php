<?php
session_start();

// Check if the user is logged in
if (!(isset($_SESSION['user_name']) || isset($_SESSION['admin_name']))) {
    header('Location: login_form.php');
    exit();
}

include 'config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = null;
    $adminId = null;
    $groupName = mysqli_real_escape_string($conn, $_POST['group_name']);
    // Fetch and display admin-specific information from user_form table
    if (isset($_SESSION['admin_name'])) {
        $adminId = $_SESSION['admin_id']; // Assuming you store admin_id in the session
    } else if (isset($_SESSION['user_name'])) {
        $adminId = $_SESSION['user_id']; // Assuming you store user_id in the session
    } else {
        $error = 'Admin or User name is not set. Please log in.';
    }

    // Check if admin_id is set
    if ($adminId === null) {
        // Handle the case where admin_id is not set
        $error = 'Admin ID is not set. Please log in.';
    } else {
        // Insert new group into the database
        $insertGroupQuery = "INSERT INTO groups (group_name, admin_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insertGroupQuery);
        mysqli_stmt_bind_param($stmt, "si", $groupName, $adminId);
        if (mysqli_stmt_execute($stmt)) {
            // Group creation successful
            mysqli_stmt_close($stmt);
            header("Location: add_members.php?group_name=$groupName&admin_id=$adminId");
            exit();
        } else {
            // Group creation failed
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

        .error-msg {
            color: crimson;
            margin-top: 20px;
        }

        form {
            margin-top: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        label {
            font-size: 18px;
            margin-bottom: 10px;
            display: block;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            transition: border-color 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #555;
        }

        button {
            padding: 15px 30px;
            font-size: 20px;
            background: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: crimson;
        }
    </style>
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
            <!-- Display error message if group creation failed -->
            <?php if (isset($error)) : ?>
                <p class="error-msg"><?php echo $error; ?></p>
            <?php endif; ?>

            <!-- Group creation form -->
            <form method="POST" action="create_group.php">
                <label for="group_name">Group Name:</label>
                <input type="text" id="group_name" name="group_name" required>
                <button type="submit">Create Group</button>
            </form>
        </div>
    </div>

    <!-- Add your other HTML content here -->

    <!-- Add your scripts if needed -->
</body>
</html>
