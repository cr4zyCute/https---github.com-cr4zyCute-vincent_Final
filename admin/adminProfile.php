<?php
include '../database/dbcon.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin details
$query = "SELECT * FROM admin WHERE id = '$admin_id'";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
} else {
    echo "Admin profile not found.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./admin-css/adminProfile.css">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../images/bsitlogo.png" alt="Logo">
            <span>BSIT</span>
        </div>
        <div class="icons">
            <a href="./admin-dashboard.php"><i class="bi bi-house-door-fill"></i></a>
            <a href="#home"><i class="bi bi-envelope-fill"></i></a>
            <a href="announcements.html"><i class="bi bi-megaphone-fill announcement-icon"></i></a>
            <div class="dropdown">
                <a href="./adminProfile.php">
                    <?php if (!empty($admin['image'])): ?>
                 <img src="../images-data/<?= htmlspecialchars($admin['adminProfile']) ?>" alt="Profile Image" class="profile-image" >
                    <?php else: ?>
                 <img src="../images-data/<?= htmlspecialchars($admin['adminProfile']) ?>" alt="Profile Image" class="profile-image" >
                    <?php endif; ?>
                </a>
                <div class="dropdown-content">
                    <a href="#profile">Profile Settings</a>
                    <a href="./includes/logout.php">Log out</a>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard">
        <div class="left-section">
            <div class="profile">
                <?php if (!empty($admin['image'])): ?>
                 <img src="../images-data/<?= htmlspecialchars($admin['adminProfile']) ?>" alt="Profile Image" class="profile-image" >
                <?php else: ?>
                 <img src="../images-data/<?= htmlspecialchars($admin['adminProfile']) ?>" alt="Profile Image" class="profile-image" >
                <?php endif; ?>
                <p><?= htmlspecialchars($admin['admin_username']) ?></p>
                <p>Email: <?= htmlspecialchars($admin['admin_email']) ?></p>
                <!-- Add more admin details as needed -->
            </div>
            <div class="posts">
                <h3>Your Post</h3>
            </div>
        </div>
        <div class="right-section">
            <h3>Announcement</h3>
            <div class="create-announcement">
                <input type="text" placeholder="Create Announcement...">
                <button>+</button>
            </div>
            <div class="announcement">
                <p class="title"><?= htmlspecialchars($admin['admin_username']) ?></p>
                <p class="time">1hr</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem ea eligendi nam ad doloribus.</p>
            </div>
        </div>
    </div>
</body>
</html>