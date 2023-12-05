<?php
session_start();
ini_set('display_errors', 1);

include 'config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['member_name']) && isset($_POST['admin_id'])) {
        // Sanitize and validate input data (you may want to add more validation)
        $member_name = mysqli_real_escape_string($conn, $_POST['member_name']);
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);

        // ... Perform the insertion into the database (similar to your previous code) ...

        // For example, assuming there's a table named 'group_members'
        $insert_sql = "INSERT INTO group_members (member_name, user_id, created_at) VALUES ('$member_name', '$admin_id', current_timestamp())";

        if (mysqli_query($conn, $insert_sql)) {
            echo "Member added successfully.";
        } else {
            // If the query fails, display an error message
            echo "Error: " . $insert_sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "All fields are required.";
    }
}

// Query to retrieve groups from the database
$query = "SELECT * FROM groups";
$result = mysqli_query($conn, $query);

// Fetch groups into an array
$groups = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!-- The rest of your HTML code -->



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

            <form method="POST" action="manage_groups.php">
                <label for="member_name">Member Name:</label>
                <input type="text" id="member_name" name="member_name" required>
                <input type="hidden" name="admin_id" value="<?php echo $_SESSION['admin_id']; ?>">
                <button type="submit">Add Member</button>
            </form>

            <div class="group-list">
                <?php if (!empty($groups)) : ?>
                    <ul>
                        <?php foreach ($groups as $group) : ?>
                            <li>
                                <!-- Display group information here -->
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

    <script>
        function createGroup() {
            window.location.href = 'create_group.php';
        }
    </script>
</body>

</html>