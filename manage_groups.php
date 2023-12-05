<?php
session_start();
// error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establish database connection
$conn = mysqli_connect('localhost', 'root', '', 'user_db');
header("Location: view_group.php");
// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch groups from the database
$sql = "SELECT * FROM groups";
$result = mysqli_query($conn, $sql);

// Initialize an array to store groups
$groups = [];

// Check if there are any groups
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $groups[] = $row;
    }
}

// Close the result set
mysqli_free_result($result);

// Close the database connection
mysqli_close($conn);
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