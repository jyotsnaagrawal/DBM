<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_name'])) {
    header('Location: login_form.php');
    exit();
}

$query = "SELECT * FROM groups";
$result = mysqli_query($conn, $query);
$groups = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
                <button class="view-page-btn" onclick="viewPage()">View Page</button>
                <button class="view-page-btn" onclick="addMember()">Add Member</button>
            </div>
        </div>
    </div>

    <script>
        function createGroup() {
            window.location.href = 'create_group.php';
        }
        function viewPage() {
            window.location.href = 'view_group.php';
        }
        function addMember() {
            window.location.href = 'add_members.php';
        }
    </script>
</body>

</html>
