<?php
require 'database/dbcon.php';
session_start(); 

if (!empty($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    $query = "
        SELECT student.*, credentials.email, credentials.password
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
    } else {
        header("Location: studentProfile.php");
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $yearlvl = mysqli_real_escape_string($conn, $_POST['yearlvl']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
        
        $imageQueryPart = ""; 
        $imageName = basename($_FILES['profileImage']['name']);
        $imagePath = 'images-data/' . $imageName;

$imageQueryPart = ""; 
if (!empty($_FILES['profileImage']['name'])) {
    $imageName = basename($_FILES['profileImage']['name']);
    $imagePath = 'images-data/' . $imageName;

    if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
        $imageQueryPart = ", student.image = '$imageName'";
    } else {
        echo "Failed to upload image.";
        exit();
    }
}


    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>User Profile</title>
    <link rel="stylesheet" href="css/studentProfile.css">
</head>
<body>
<nav class="navbar">
    <a href="#" class="logo">CTU</a>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="#about">Message</a>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle">Settings</a>
            <div class="dropdown-content">
                <a href="#profile">Profile Settings</a>
                <a href="index.php">Log out</a>
            </div>
        </div>
    </div>
    <div class="nav-icons">
        <a href="home.php" title="Home"><i class="fa-solid fa-house"></i></a>
        <a href="#message" title="Message"><i class="fa-solid fa-envelope"></i></a>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle"><i class="fa-solid fa-gear"></i></a>
            <div class="dropdown-content">
                <a href="#profile">Profile Settings</a>
                <a href="index.php">Log out</a>
            </div>
        </div>
    </div>
</nav>

    <div class="profile-container">
        <img src="./images/student.jpg" alt="Profile Image" class="profile-image">
        <div class="profile-info">
            <h1><?php  echo htmlspecialchars($student['firstname'])?></h1>
            <h2>Photographer</h2>
            <p><strong>Age:</strong> 28</p>
            <p><strong>Education:</strong> BFA in Photography</p>
            <p><strong>Location:</strong> New York, NY</p>
        </div>
    </div>
    
    <div class="grid-container">
        <div class="card">
            <h3>Bio</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
        <div class="card">
            <h3>Goals</h3>
            <p>To travel the world and capture its beauty.</p>
        </div>
        <div class="card">
            <h3>Motivations</h3>
            <p>Inspired by nature and human connection.</p>
        </div>
    </div>
<script>
    const menuBtn = document.getElementById('menu-btn');
    const navLinks = document.getElementById('nav-links');

    menuBtn.addEventListener('click', () => {
        navLinks.classList.toggle('show');
    });
</script>
<script src="./js/studentProfile.js" ></script>
</body>
</html>