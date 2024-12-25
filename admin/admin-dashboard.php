<?php
include '../database/dbcon.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}
$admin_id = $_SESSION['admin_id'];

$query = "SELECT * FROM admin WHERE admin_id = '$admin_id'";
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
       <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./admin-css/dashboard.css">
    <link rel="stylesheet" href="./admin-css/admin-home.css">

    <title>Admin Page</title>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#" onclick="showSection('dashboard')" aria-controls="dashboard" aria-selected="true">Dashboard</a></li>
            <li><a href="#" onclick="showSection('home')" aria-controls="home">Home</a></li>
            <li><a href="#" onclick="showSection('student')" aria-controls="student">Manage Student</a></li>
            <li><a href="#" onclick="showSection('settings')" aria-controls="settings">Settings</a></li>
            <li><a href="#" onclick="showSection('reports')" aria-controls="reports">Reports</a></li>
        </ul>
        <div class="footer">
            &copy; 2024 Admin Panel
        </div>
    </div>

    <div class="main-content">
        <div class="header">
             <div class="logo">
            <img src="../images/bsitlogo.png" alt="Logo">
            <span>BSIT</span>
        </div>
        <div class="icons">
        <a href="#home"><i class="bi bi-house-door-fill"></i></a>
        
        <a href="messages.html"><i class="bi bi-envelope-fill"></i></a>
        <a href="announcements.html"><i class="bi bi-megaphone-fill announcement-icon"></i></a>
          <div class="dropdown">
            <a href="./adminProfile.php">
                 <img src="../images-data/<?= htmlspecialchars($admin['adminProfile']) ?>" alt="Profile Image" class="profile-image" >
                 <!-- <img src="../images/defaultProfile.jpg" alt="Profile Image" class="profile-image" > -->
                <div class="dropdown-content">
                    <a href="#profile">Profile Settings</a>
                    <a href="./logout.php">Log out</a>
                </div>
            </div>
    </div>
        </div>
<?php
$sql = "
    SELECT 
        COUNT(*) AS total_students,
        SUM(CASE WHEN yearlvl = 'First Year' THEN 1 ELSE 0 END) AS first_year_students,
        SUM(CASE WHEN yearlvl = 'Second Year' THEN 1 ELSE 0 END) AS second_year_students,
        SUM(CASE WHEN yearlvl = 'Third Year' THEN 1 ELSE 0 END) AS third_year_students,
        SUM(CASE WHEN yearlvl = 'Fourth Year' THEN 1 ELSE 0 END) AS fourth_year_students
    FROM student
";

$result = $conn->query($sql);

$total_students = $first_year_students = $second_year_students = $third_year_students = $fourth_year_students = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_students = $row['total_students'];
    $first_year_students = $row['first_year_students'];
    $second_year_students = $row['second_year_students'];
    $third_year_students = $row['third_year_students'];
    $fourth_year_students = $row['fourth_year_students'];
}
?>

<section id="dashboard" class="active">
    <h1>Dashboard</h1>
    <div class="dashboard-boxes">
        <div class="dashboard-box">
            <h3>Total Students</h3>
             <p><?php echo $total_students; ?></p>
        </div>
        <div class="dashboard-box">
            <h3>First Year Student</h3>
             <p><?php echo $first_year_students; ?></p>
        </div>
        <div class="dashboard-box">
            <h3>Second Year Student</h3>
             <p><?php echo $second_year_students; ?></p>
        </div>
        <div class="dashboard-box">
            <h3>Third Year Student</h3>
             <p><?php echo $third_year_students; ?></p>
        </div>
        <div class="dashboard-box">
            <h3>Fourth Year Student</h3>
             <p><?php echo $fourth_year_students; ?></p>
        </div>
        <div class="dashboard-box">
            <h3>System Alerts</h3>
            <p>3</p>
        </div>
    </div>

  <h2>Student List</h2>
<?php

$sql = "SELECT id, firstname, lastname, yearlvl, section, image FROM student";
$result = $conn->query($sql);
?>
<table class="dashboard-table">
    <thead>
        <tr>
            <th>Profile Picture</th>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Year Level</th>
            <th>Section</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img src="../images-data/<?php echo $row['image']; ?>" alt="Profile Picture" class="profile-table">
                        <?php else: ?>
                            <img src="images-data/default-profile.jpg" alt="Default Profile" class="profile-pic">
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($row['yearlvl']); ?></td>
                    <td><?php echo htmlspecialchars($row['section']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No students found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php
?>
</section>
        <section id="home">
           <div class="left-section">
            <div class="profile"  onclick="openPopup()">
                 <img src="../images-data/<?= htmlspecialchars($admin['adminProfile']) ?>" alt="Profile Image" class="profile-image" >
                <input type="text" placeholder="Create a Post......">
                <button>POST</button>
            </div>
                   
<div class="popup-overlay" id="post-popup">
    <div class="post-popup">
        <div class="popup-header">
            <span>Create post</span>
            <button onclick="closePopup()">×</button>
        </div>
   <form action="uploadPost.php" method="POST" enctype="multipart/form-data">
    <div class="postpopup-content">
        <div class="profile-container">
            <a href="studentProfile.php">
                <img src="../images-data/<?= htmlspecialchars($admin['adminProfile']) ?>" alt="Profile Image" class="profile-pic">
            </a>
            <p class="profile-name"><?= htmlspecialchars($admin['admin_username']) ?></p>
        </div>
        <textarea name="content" placeholder="What on your mind? <?= htmlspecialchars($admin['admin_username']) ?>"></textarea>
        <div class="add-photos" onclick="triggerFileUpload()">
            <input type="file" id="media-upload" name="media[]" multiple accept="image/*,video/*" style="display: none;" onchange="previewFiles(event)">
            <p>Add photos/videos</p>
        </div><br>
        <div id="media-preview" class="media-grid"></div>
    </div>
    <button id="delete-post" class="cancel-button" style="display: none;" onclick="clearFiles()">Cancel Post</button>
    <button class="post-btn" type="submit">Post</button>
</form>
    </div>
</div>

      <div class="left-section">
    <?php
function timeAgo($datetime) {

    $timestamp = strtotime($datetime);

    if ($timestamp === false) {
        return "Invalid datetime format";
    }

    $currentTime = time();
    $timeDifference = $currentTime - $timestamp;

    $minutes = floor($timeDifference / 60);
    $hours = floor($minutes / 60);
    $days = floor($hours / 24);

    if ($timeDifference < 60) {
        return "Just now";
    } elseif ($minutes == 1) {
        return "1 minute ago";
    } elseif ($minutes < 60) {
        return "$minutes minutes ago";
    } elseif ($hours == 1) {
        return "1 hour ago";
    } elseif ($hours < 24) {
        return "$hours hours ago";
    } elseif ($days == 1) {
        return "1 day ago";
    } else {
        return "$days days ago";
    }
}

    $query = "SELECT p.*, s.firstname, s.lastname, s.image AS profile_image, 
                (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS like_count,
                (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comment_count
              FROM posts p 
              JOIN student s ON p.student_id = s.id 
              ORDER BY p.created_at DESC";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($post = mysqli_fetch_assoc($result)) {
            echo '<div class="post">';
            echo '<div class="post-header">';
            echo '<div class="delete-container">';
        echo '<button class="delete-button" onclick="deletePost(' . htmlspecialchars($post['id']) . ')"><i class="bi bi-trash3-fill"></i></button>';
        echo '</div>';
                    echo '<img src="../images-data/' . htmlspecialchars($post['profile_image']) . '" alt="Profile Image" class="profile-pic">';
            echo '<div class="post-user-info">';
            echo '<strong>' . htmlspecialchars($post['firstname'] . ' ' . $post['lastname']) . '</strong>';
            echo '<span><i class="bi bi-mortarboard-fill"></i> Student</span>';
      echo '<span class="post-time">' . htmlspecialchars(timeAgo($post['created_at'])) . '</span>';

            echo '</div>';
            echo '</div>';

         echo '<div class="post-content">';
            echo '<p>' . htmlspecialchars($post['content']) . '</p>';
         echo '</div>';

            if (!empty($post['media'])) {
                echo '<div class="post-media">';
                echo '<img src="' .'../'. htmlspecialchars($post['media']) . '" alt="Post Media">';
                echo '</div>';
            }
                               echo '<div class="post-footer">';
                    echo '<form method="POST" action="comment_post.php" class="comment-form">';
                    echo '<input type="hidden" name="post_id" value="' . htmlspecialchars($post['id']) . '">';
                    echo '<textarea name="comment" placeholder="Write a comment..." required></textarea>';
                    echo '<button type="submit">Post Comment</button>';
                    echo '</form>';
                                    echo '<div class="post-actions">';
                    echo '<form method="POST" action="like_post.php" class="like-form">';
                        echo '<button class="like-button" onclick="toggleLike(this, ' . htmlspecialchars($post['id']) . ')">';
                        echo '<i class="bi bi-balloon-heart-fill" style="color: red;"></i> ';
                        echo '<span>Like</span> (<span class="like-count">' . htmlspecialchars($post['like_count']) . '</span>)';
                        echo '</button>';
                    echo '</form>';

                    echo '<button class="comment-button" onclick="toggleComments(' . htmlspecialchars($post['id']) . ')">';
                    echo '<i class="bi bi-chat-square-dots-fill" style="color: blue;"></i> ';
                    echo '<span>Comment</span> (<span class="comment-count">' . htmlspecialchars($post['comment_count']) . '</span>)';
                    echo '</button>';
                    echo '</div>';

            echo '</div>';
            $commentQuery = "SELECT c.*, s.firstname, s.lastname, s.image AS profile_image
                         FROM comments c 
                         JOIN student s ON c.student_id = s.id 
                         WHERE c.post_id = " . intval($post['id']) . " 
                         ORDER BY c.created_at ASC";
                         $commentResult = mysqli_query($conn, $commentQuery);
                         
                         echo '<div class="comments" id="comments-' . htmlspecialchars($post['id']) . '">';
                         echo '<h2>Comments</h2>';
        if ($commentResult && mysqli_num_rows($commentResult) > 0) {
            while ($comment = mysqli_fetch_assoc($commentResult)) {
                echo '<div class="comment">';
                echo '<img src="images-data/' . htmlspecialchars($comment['profile_image']) . '" alt="Profile Image" class="profile-pic">';
                echo '<strong>' . htmlspecialchars($comment['firstname'] . ' ' . $comment['lastname']) . ':</strong> ';
                echo '<p>' . htmlspecialchars($comment['content']) . '</p>';
                echo '</div>';
            }
        }
        echo '</div>';



            echo '</div>'; 
        }
    }
    ?>
</div>
        </div>
        </section>
        <section id="student">
            <h1>Manage Student</h1>
            <p>Here you can add, edit, or delete user accounts.</p>
        </section>
        <section id="settings">
            <h1>Settings</h1>
            <p>Customize your admin panel settings here.</p>
        </section>
        <section id="reports">
            <h1>Reports</h1>
            <p>View detailed reports and analytics.</p>
        </section>
    </div>
    <script src="../js/home.js" ></script>
    <script>
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.main-content section');
            sections.forEach(section => {
                section.classList.remove('active');
            });
            const activeSection = document.getElementById(sectionId);
            activeSection.classList.add('active');
            localStorage.setItem('activeSection', sectionId);
        }
        window.onload = function () {
            const savedSection = localStorage.getItem('activeSection') || 'dashboard';
            showSection(savedSection);
        };
    </script>
</body>
</html>
