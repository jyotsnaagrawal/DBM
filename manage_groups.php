<?php
session_start();
ini_set('display_errors', 1);

// Establish database connection
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['member_name']) && isset($_POST['admin_id'])) {
        // Sanitize and validate input data (you may want to add more validation)
        $member_name = mysqli_real_escape_string($conn, $_POST['member_name']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);

        // Check if group_id is set in the form
        if (isset($_POST['group_id'])) {
            $group_id = mysqli_real_escape_string($conn, $_POST['group_id']);

            // Start a transaction to ensure atomicity
            mysqli_autocommit($conn, false);

            // Check if the group_id exists in the groups table
            $check_group_sql = "SELECT * FROM groups WHERE group_id = '$group_id' FOR UPDATE";
            $result = mysqli_query($conn, $check_group_sql);

            if (mysqli_num_rows($result) == 0) {
                // Group doesn't exist, insert it first
                $insert_group_sql = "INSERT INTO groups (group_id) VALUES ('$group_id')";

                if (!mysqli_query($conn, $insert_group_sql)) {
                    mysqli_rollback($conn);
                    echo "Error inserting group: " . mysqli_error($conn);
                    exit;
                }
            }

            // Commit the transaction
            mysqli_commit($conn);

            // Insert the member into the database with group_id
            $insert_sql = "INSERT INTO group_members (group_id, member_name, user_id, created_at) VALUES ('$group_id', '$member_name', '$admin_id', current_timestamp())";
        } else {
            // Insert the member into the database without specifying group_id
            $insert_sql = "INSERT INTO group_members (member_name, user_id, created_at) VALUES ('$member_name', '$admin_id', current_timestamp())";
        }

        if (mysqli_query($conn, $insert_sql)) {
            echo "Member added successfully.";
        } else {
            echo "Error: " . $insert_sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "All fields are required.";
    }
}

// ... Rest of your existing code
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="manage_groups.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Groups</title>
</head>

<body>
    <header>
        <!-- Your navigation/header content here -->
    </header>

    <div class="container">
        <div class="content">
            <h1>Manage Groups</h1>

            <div class="group-list">
                <?php if (!empty($groups)) : ?>
                    <ul>
                        <?php foreach ($groups as $group) : ?>
                            <li>
                            <form method="POST" action="manage_groups.php">
 <!-- Corrected form action -->
                                     <!-- <label for="group_id">Select Group:</label>
                                    <select id="group_id" name="group_id" required>
                                        <?php foreach ($groups as $group) : ?>
                                            <option value="<?php echo $group['group_id']; ?>"><?php echo $group['group_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>  -->
                                    <label for="member_name">Member Name:</label>
                                    <input type="text" id="member_name" name="member_name" required>
                                    <input type="hidden" name="admin_id" value="<?php echo $_SESSION['admin_id']; ?>"> <!-- Add hidden input for admin_id -->
                                    <button type="submit">Add Member</button>
                                    
                                </form>

                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>No groups found.</p>
                <?php endif; ?>
            </div>
            <div class="group-buttons">
                <button class="create-group-btn" onclick="createGroup()">Create Group</button>
            </div>
        </div>
    </div>

    <!-- Add your other HTML content here -->

    <script>
        function createGroup() {
            window.location.href = 'create_group.php';
        }
    </script>
</body>

</html>