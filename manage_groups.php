<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
var_dump($_SESSION); //  for debugging to see the current state of the session variables.

// Establish database connection
$conn = mysqli_connect('localhost', 'root', '', 'user_db');

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
            <h1>Manage Groups</h1>

            <div class="group-list">
                <?php if (!empty($groups)) : ?>
                    <ul>
                        <?php foreach ($groups as $group) : ?>
                            <li>
                                <form method="POST" action="add_members.php">
                                    <input type="hidden" name="group_id" value="<?php echo $group['group_id']; ?>">

                                    <!-- Remove the input label for member names -->
                                    
                                    <!-- You can add more input fields as needed -->

                                    <button type="submit" name="view_group" class="group-btn">
                                        <?php echo htmlspecialchars($group['group_name']); ?>
                                    </button>
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
