<?php
session_start();
require 'database/dbcon.php';

if (empty($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student details
$query = "
    SELECT student.*, credentials.email
    FROM student 
    JOIN credentials ON student.id = credentials.student_id 
    WHERE student.id = '$student_id'
";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $student = mysqli_fetch_assoc($result);
} else {
    echo "Student profile not found.";
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/studentProfile.css">
    
</head>
<body>

<main>
<header>
    <nav>
        <div class="logo">BSIT</div>
        <div class="nav-icons">
            <a href="home.php"><i class="bi bi-house-door-fill"></i></a>
            <a href="#"><i class="bi bi-envelope-fill"></i></a>
            <div class="dropdown">
            <a href="studentProfile.php">
                 <img src="images-data/<?= htmlspecialchars($student['image']) ?>" alt="Profile Image" class="profile-pic" >
                <div class="dropdown-content">
                    <a href="#profile">Profile Settings</a>
                    <a href="./includes/logout.php">Log out</a>
                </div>
            </div>
            </a>
        </div>
    </nav>
</header>

<div class="profile-container">
    <img src="images-data/<?= htmlspecialchars($student['image']) ?>" alt="Profile Image" class="profile-image">
    <div class="profile-info">
        <h1><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></h1>
        <h2>Student</h2>
        <p><strong>Age:</strong> <?= htmlspecialchars($student['age']) ?></p>
        <p><strong>Education:</strong> <?= htmlspecialchars($student['yearlvl'] . ' - ' . $student['section']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($student['address']) ?></p>
    </div>
</div>

<div class="grid-container">
    <div class="card">
        <h3>Bio</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
    <div class="card">
        <h3>Goals</h3>
        <p>To excel in the IT industry and achieve academic success.</p>
    </div>
    <div class="card">
        <h3>Motivations</h3>
        <p>Inspired by technology and the future of innovation.</p>
    </div>
</div>
</main>
<script>
    const menuBtn = document.getElementById('menu-btn');
    const navLinks = document.getElementById('nav-links');

    menuBtn.addEventListener('click', () => {
        navLinks.classList.toggle('show');
    });
</script>

</body>
</html>