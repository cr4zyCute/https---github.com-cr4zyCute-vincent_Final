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
        <a href="#" class="logo">BrandLogo</a>
        <div class="nav-links">
            <a href="#home">Home</a>
            <a href="#about">Message</a>
            <a href="#services">Setting</a>
        </div>
        <div class="nav-icons">
            <a href="#home" title="Home"><i class="fa-solid fa-house"></i></a>
            <a href="#message" title="Message"><i class="fa-solid fa-envelope"></i></a>
            <a href="#services" title="Settings"><i class="fa-solid fa-gear"></i></a>
        </div>
    </nav>
    <div class="profile-container">
        <img src="./images/student.jpg" alt="Profile Image" class="profile-image">
        <div class="profile-info">
            <h1>Lucia Alvarez</h1>
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
</body>
</html>