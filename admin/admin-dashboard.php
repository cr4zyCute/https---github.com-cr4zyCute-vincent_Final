<?php
include '../database/dbcon.php';
session_start();

// if (!isset($_SESSION['admin_id'])) {
//     header("Location: admin-login.php");
//     exit();
// }
// $admin_id = $_SESSION['admin_id'];

// $query = "SELECT * FROM admin WHERE id = '$admin_id'";
// $result = mysqli_query($conn, $query);
// if ($result && mysqli_num_rows($result) > 0) {
//     $admin = mysqli_fetch_assoc($result);
// } else {
//     echo "Admin profile not found.";
//     exit();
// }

// // Query to fetch student details for the 'Manage Student' section
// $student_query = "SELECT id, firstname, lastname, image FROM student";
// $student_result = $conn->query($student_query);
// $forms = $conn->query("SELECT * FROM forms");
// $conn->query("UPDATE student SET admin_notified = 1 WHERE is_approved = 0 AND admin_notified = 0");

// $new_users = $conn->query("SELECT * FROM student WHERE is_approved = 0 AND admin_notified = 0");
// if ($new_users->num_rows === 0) {
//     echo "<p>No new user registrations found.</p>";
// } else {
//     echo "<p>New users found: " . $new_users->num_rows . "</p>";
// }

// Fetch all users
// $users = $conn->query("SELECT * FROM student");


// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin details
$query = "SELECT * FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc();
} else {
    echo "Admin profile not found.";
    exit();
}

// Fetch forms and students
$forms = $conn->query("SELECT * FROM forms")->fetch_all(MYSQLI_ASSOC);
$students = $conn->query("SELECT * FROM student")->fetch_all(MYSQLI_ASSOC);
$approved_students = $conn->query("SELECT * FROM student WHERE approved = 1")->fetch_all(MYSQLI_ASSOC);
$new_students = $conn->query("SELECT * FROM student WHERE is_approved = 0 AND admin_notified = 0")->fetch_all(MYSQLI_ASSOC);

// Update notification status for new students
$conn->query("UPDATE student SET admin_notified = 1 WHERE is_approved = 0 AND admin_notified = 0");

$students = $conn->query("SELECT * FROM student")->fetch_all(MYSQLI_ASSOC);
$forms = $conn->query("SELECT * FROM forms")->fetch_all(MYSQLI_ASSOC);


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
            <li><a href="adminForm.php" onclick="showSection('form')" aria-controls="form">Form</a></li>
            <li><a href="#" onclick="showSection('announcements')" aria-controls="announcements">announcements</a></li>
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
        <a href="#" onclick="showSection('home')" aria-controls="home"><i class="bi bi-house-door-fill"></i></a>
        <a href="#" onclick="showSection('notifications')" aria-controls="notifications"><i class="bi bi-envelope-fill"></i></a>

        <a href="#" onclick="showSection('announcements')" aria-controls="announcements"><i class="bi bi-megaphone-fill announcement-icon"></i></a>

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
// $sql = "
//     SELECT 
//         COUNT(*) AS total_students,
//         SUM(CASE WHEN yearlvl = 'First Year' THEN 1 ELSE 0 END) AS first_year_students,
//         SUM(CASE WHEN yearlvl = 'Second Year' THEN 1 ELSE 0 END) AS second_year_students,
//         SUM(CASE WHEN yearlvl = 'Third Year' THEN 1 ELSE 0 END) AS third_year_students,
//         SUM(CASE WHEN yearlvl = 'Fourth Year' THEN 1 ELSE 0 END) AS fourth_year_students
//     FROM student
// ";

// $result = $conn->query($sql);

// $total_students = $first_year_students = $second_year_students = $third_year_students = $fourth_year_students = 0;

// if ($result->num_rows > 0) {
//     $row = $result->fetch_assoc();
//     $total_students = $row['total_students'];
//     $first_year_students = $row['first_year_students'];
//     $second_year_students = $row['second_year_students'];
//     $third_year_students = $row['third_year_students'];
//     $fourth_year_students = $row['fourth_year_students'];
// }
?>

<section id="dashboard" class="active">
    <h1>Dashboard</h1>
    <div class="dashboard-boxes">
        <div class="dashboard-box">
            <h3>Total Students</h3>
             <!-- <p><?php echo $total_students; ?></p> -->
        </div>
        <div class="dashboard-box">
            <h3>First Year Student</h3>
             <!-- <p><?php echo $first_year_students; ?></p> -->
        </div>
        <div class="dashboard-box">
            <h3>Second Year Student</h3>
             <!-- <p><?php echo $second_year_students; ?></p> -->
        </div>
        <div class="dashboard-box">
            <h3>Third Year Student</h3>
             <!-- <p><?php echo $third_year_students; ?></p> -->
        </div>
        <div class="dashboard-box">
            <h3>Fourth Year Student</h3>
             <!-- <p><?php echo $fourth_year_students; ?></p> -->
        </div>
        <div class="dashboard-box">
            <h3>Total Post</h3>
            <p>3</p>
        </div>
    </div>

  <h2>Student List</h2>
<?php
// Fetch notifications
$notifications = $conn->query("SELECT * FROM notifications WHERE is_read = 0");
if (!$notifications) {
    die("Error fetching notifications: " . $conn->error);
}

// Fetch unapproved students along with their emails and images
$unapproved_users = $conn->query("
    SELECT 
        student.id AS student_id, 
        student.firstname, 
        student.image, 
        credentials.email 
    FROM 
        student 
    INNER JOIN 
        credentials AS credentials 
    ON 
        student.id = credentials.student_id 
    WHERE 
        student.approved = 0
");
if (!$unapproved_users) {
    die("Error fetching unapproved users: " . $conn->error);
}

// Mark notifications as read
$mark_read = $conn->query("UPDATE notifications SET is_read = 1 WHERE is_read = 0");
if (!$mark_read) {
    die("Error updating notifications: " . $conn->error);
}

// Handle file uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $imageName = basename($_FILES['profileImage']['name']);
    $imagePath = 'images-data/' . $imageName;

    if (!empty($imageName)) {
        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
            echo "Image uploaded successfully.";
        } else {
            echo "Failed to upload image.";
            exit();
        }
    }
}
?>
 <h2>Unapproved Students</h2>
    <?php if ($unapproved_users->num_rows > 0): ?>
        <form method="POST" action="approve_users.php">
            <button type="submit" name="action" value="approve" style="background-color: #007bff; color: #fff; border: none; padding: 10px 20px; font-size: 14px; border-radius: 5px; cursor: pointer; margin-right: 10px; transition: background-color 0.3s ease;">Approve Selected</button>
            <button type="submit" name="action" value="reject" style="background-color: #dc3545; color: #fff; border: none; padding: 10px 20px; font-size: 14px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;">Reject Selected</button>
  
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: 1px solid #ddd;">
                <thead>
                    <tr style="background-color: #f4f4f4; font-weight: bold;">
                        <th style="padding: 12px 15px; text-align: left; border: 1px solid #ddd; font-size: 14px; color: #333;">Profile</th>
                        <th style="padding: 12px 15px; text-align: left; border: 1px solid #ddd; font-size: 14px; color: #333;">Username</th>
                        <th style="padding: 12px 15px; text-align: left; border: 1px solid #ddd; font-size: 14px; color: #333;">Email</th>
                        <th style="padding: 12px 15px; text-align: left; border: 1px solid #ddd; font-size: 14px; color: #333;">Approve</th>
                        <th style="padding: 12px 15px; text-align: left; border: 1px solid #ddd; font-size: 14px; color: #333;">Reject</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = $unapproved_users->fetch_assoc()): ?>
                        <tr style="background-color: #f9f9f9; border: 1px solid #ddd;">
                           <td style="padding: 12px 15px; text-align: left; border: 1px solid #ddd;">
                            <center>
                                <img src="../images-data/<?= !empty($student['image']) ? htmlspecialchars($student['image']) : 'default-profile.jpg'; ?>" 
                                    alt="Profile Picture" 
                                    style="width: 150px; height: 150px;object-fit: cover; border: 1px solid #ccc;">
                            </center>
                            </td>

                            <td style="padding: 12px 15px; text-align: left; border: 1px solid #ddd;"><?= htmlspecialchars($student['firstname']); ?></td>
                            <td style="padding: 12px 15px; text-align: left; border: 1px solid #ddd;"><?= htmlspecialchars($student['email']); ?></td>
                            <td style="padding: 12px 15px; text-align: left; border: 1px solid #ddd;">
                                <input type="checkbox" name="approve_users[]" value="<?= $student['student_id']; ?>">
                            </td>
                            <td style="padding: 12px 15px; text-align: left; border: 1px solid #ddd;">
                                <input type="checkbox" name="reject_users[]" value="<?= $student['student_id']; ?>">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
      </form>
    <?php else: ?>
        <p>No students awaiting approval.</p>
    <?php endif; ?>
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
    <h2>Manage Students</h2>
    <h3>Student List</h3>
    
    <!-- Search Input -->
    <input 
        type="text" 
        id="searchInput" 
        placeholder="Search by ID, First Name, or Last Name..." 
        style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;">

    <table class="dashboard-table" id="student-table">
        <thead>
            <tr>
                <th>Profile Picture</th>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($students): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td>
                            <center>
                                <img src="../images-data/<?= !empty($student['image']) ? htmlspecialchars($student['image']) : 'default-profile.jpg'; ?>" 
                                    alt="Profile Picture" 
                                    style="width: 150px; height: 150px; object-fit: cover; border: 1px solid #ccc;">
                            </center>
                        </td>
                        <td><?= htmlspecialchars($student['id']); ?></td>
                        <td><?= htmlspecialchars($student['firstname']); ?></td>
                        <td><?= htmlspecialchars($student['lastname']); ?></td>
                        <td>
                            <!-- Edit Button -->
                                <a href="studentUpdate.php?id=<?= htmlspecialchars(string: $student['id']); ?>" 
                                style="display: inline-block; background-color: #3bd20f; color: #fff; padding: 10px 15px; font-size: 16px; text-align: center; text-decoration: none; border-radius: 5px; border: 1px solid transparent; transition: background-color 0.3s ease;" 
                                onmouseover="this.style.backgroundColor='#00ff00 ';" 
                                onmouseout="this.style.backgroundColor='#5dff2e';">
                                <i class="bi bi-pencil-square" style="font-size: 16px;"></i>
                                </a>


                            <!-- Delete Button -->
                            <form action="deleteStudent.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($student['id']); ?>">
                                <button type="submit"  style="display: inline-block; background-color:rgb(255, 0, 0); color: #fff; padding: 10px 15px; font-size: 16px; text-align: center; text-decoration: none; border-radius: 5px; border: 1px solid transparent; transition: background-color 0.3s ease;" 
                                onmouseover="this.style.backgroundColor='#ff7860';" 
                                onmouseout="this.style.backgroundColor='#c11d00';"
                                onclick="return confirm('Are you sure you want to delete this student?');"><i class="bi bi-trash3-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#student-table tbody tr');
        
        rows.forEach(row => {
            const id = row.cells[1].textContent.toLowerCase();
            const firstName = row.cells[2].textContent.toLowerCase();
            const lastName = row.cells[3].textContent.toLowerCase();

            if (id.includes(filter) || firstName.includes(filter) || lastName.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<section id="settings">
            <h1>Settings</h1>
            <p>Customize your admin panel settings here.</p>
</section>

<section id="announcements">
            <h1>Reports</h1>
            <p>View detailed reports and analytics.</p>
            <form action="postAnnouncement.php" method="POST">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required>
    
    <label for="content">Content:</label>
    <textarea id="content" name="content" required></textarea>
    
    <button type="submit">Post Announcement</button>
</form>
             <div class="right-section">
        <?php 
        
$sql = "SELECT a.title, a.content, a.created_at, ad.admin_username AS admin_username ,ad.admin_name AS admin_name 
        FROM announcements a 
        JOIN admin ad ON a.admin_id = ad.id 
        ORDER BY a.created_at DESC";

$result = mysqli_query($conn, $sql);

// Fetch student details (if needed)
$query = "SELECT * FROM admin WHERE id = '$admin_id'";
$studentResult = mysqli_query($conn, $query);

if ($studentResult && mysqli_num_rows($studentResult) > 0) {
    $student = mysqli_fetch_assoc($studentResult);
} else {
    echo "Student profile not found.";
    exit();
}
         ?>
         
 <div class="announcement">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                     <div class="profile-info">
                    <strong><?php echo htmlspecialchars($row['admin_name']); ?></strong>
                    <small class="role">
                       <i class="bi bi-people-fill"></i>
                       <small style="margin: 0px;" ><?php echo htmlspecialchars($row['admin_username']); ?></small>
                    </small>
                    <small class="time"><?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></small>
                     <p><?php echo htmlspecialchars($row['content']); ?></p>
                </div>
                    
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No announcements available.</p>
        <?php endif; ?>
    </div>
</div>
</section>

<section id="notifications">
    <?php

$notifications = $conn->query("SELECT * FROM notifications WHERE is_read = 0");

if (!$notifications) {
    die("Error fetching notifications: " . $conn->error);
}

// Fetch unapproved students along with their emails
$unapproved_users = $conn->query("
    SELECT 
        student.id AS student_id, 
        student.firstname, 
        credentials.email 
    FROM 
        student 
    INNER JOIN 
        credentials 
    ON 
        student.id =credentials.student_id 
    WHERE 
        student.approved = 0
");

if (!$unapproved_users) {
    die("Error fetching unapproved users: " . $conn->error);
}

// Mark notifications as read
$mark_read = $conn->query("UPDATE notifications SET is_read = 1 WHERE is_read = 0");

if (!$mark_read) {
    die("Error updating notifications: " . $conn->error);
}
?>
               <h1>Admin Notifications</h1>

    <h2>New Notifications</h2>
    <?php if ($notifications->num_rows > 0): ?>
        <ul>
            <?php while ($notification = $notifications->fetch_assoc()): ?>
                <li><?= htmlspecialchars($notification['message']); ?> (<?= $notification['created_at']; ?>)</li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No new notifications.</p>
    <?php endif; ?>

    <h2>Unapproved Students</h2>
    <?php if ($unapproved_users->num_rows > 0): ?>
       <form method="POST" action="approve_users.php">
    <table border="1">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Approve</th>
                <th>Reject</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($student = $unapproved_users->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($student['firstname']); ?></td>
                    <td><?= htmlspecialchars($student['email']); ?></td>
                    <td>
                        <input type="checkbox" name="approve_users[]" value="<?= $student['student_id']; ?>">
                    </td>
                    <td>
                        <input type="checkbox" name="reject_users[]" value="<?= $student['student_id']; ?>">
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <button type="submit" name="action" value="approve">Approve Selected</button>
    <button type="submit" name="action" value="reject">Reject Selected</button>
</form>

    <?php else: ?>
        <p>No students awaiting approval.</p>
    <?php endif; ?>

</section>

<section id="form">
    <h2>Create a New Form</h2>
    <form id="createForm" method="POST" action="create_form.php">
        <label for="form_name">Form Name:</label>
        <input type="text" name="form_name" id="form_name" required><br><br>
        <button type="button" onclick="addField()">Add Field</button>
        <button type="submit">Create Form</button>
        <div id="fields"></div>
    </form>

    <!-- List of Forms -->
    <section>
        <h2>Available Forms</h2>
        <ul>
            <?php foreach ($forms as $form): ?>
                <li>
                    <?= htmlspecialchars($form['form_name']); ?>
                    <button onclick="showSendModal(<?= $form['id']; ?>)">Send to student</button>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <!-- Modal for sending forms -->
    <div id="sendModal" style="display:none;">
        <h3>Send Form to Student</h3>
        <form method="POST" action="send_form.php">
            <input type="hidden" name="form_id" id="modalFormId">
            <label for="student_id">Select Student:</label>
            <select name="student_id" id="student_id" required>
                <?php if (!empty($approved_students)): ?>
    <?php foreach ($approved_students as $student): ?>
        <option value="<?= htmlspecialchars($student['id']); ?>">
            <?= htmlspecialchars($student['firstname']); ?>
        </option>
    <?php endforeach; ?>
<?php else: ?>
    <option disabled>No approved students available</option>
<?php endif; ?>

            </select><br><br>
            <button type="submit">Send Form</button>
        </form>
        <button onclick="document.getElementById('sendModal').style.display = 'none';">Close</button>
    </div>
</section>

<script>
     fieldCount = 0;

    function showSendModal(formId) {
        document.getElementById('modalFormId').value = formId;
        document.getElementById('sendModal').style.display = 'block';
    }

    function addField() {
        const fieldsDiv = document.getElementById('fields');
        const fieldHTML = `
            <div class="field">
                <label>Field Name:</label>
                <input type="text" name="fields[${fieldCount}][name]" required>
                <label>Field Type:</label>
                <select name="fields[${fieldCount}][type]">
                    <option value="text">Text</option>
                    <option value="number">Number</option>
                    <option value="email">Email</option>
                    <option value="textarea">Textarea</option>
                </select>
                <label>Required:</label>
                <input type="checkbox" name="fields[${fieldCount}][required]">
            </div>
        `;
        fieldsDiv.insertAdjacentHTML('beforeend', fieldHTML);
        fieldCount++;
    }
</script>

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
    </script>
</body>
</html>

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
        let fieldCount = <?php echo $fieldCount; ?>;

        function addField() {
            const fieldsDiv = document.getElementById('fields');
            fieldsDiv.innerHTML += `
                <div class="field">
                    <label>Field Name:</label>
                    <input type="text" name="fields[${fieldCount}][name]" required>
                    <label>Field Type:</label>
                    <select name="fields[${fieldCount}][type]">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="email">Email</option>
                        <option value="textarea">Textarea</option>
                    </select>
                    <label>Required:</label>
                    <input type="checkbox" name="fields[${fieldCount}][required]">
                </div>
            `;
            fieldCount++;
        }
    </script>
</body>
</html>
