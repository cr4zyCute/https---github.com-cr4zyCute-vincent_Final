<?php
    include 'database/dbcon.php';
    session_start();
    if(!isset($_SESSION['student_id'])){
        header("Location: index.php");
    }

$student_id = $_SESSION['student_id'];
$query = "SELECT * FROM student WHERE id = '$student_id'";
$result = mysqli_query($conn, $query);
$querypost = "SELECT * FROM posts WHERE id = '$student_id'";
$content = $_POST['content'] ?? '';
$mediaFiles = $_FILES['media'] ?? [];

if ($result && mysqli_num_rows($result) > 0) {
    $student = mysqli_fetch_assoc($result);
} else {
    echo "Student profile not found.";
    exit();
}
$query = "SELECT p.*, s.firstname, s.lastname, s.image AS profile_image 
          FROM posts p 
          JOIN student s ON p.student_id = s.id 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($post = mysqli_fetch_assoc($result)) {
        echo '<div class="post">';
        echo '<div class="post-header">';
        echo '<img src="images-data/' . htmlspecialchars($post['profile_image']) . '" alt="Profile Image" class="profile-pic">';
        echo '<h3>' . htmlspecialchars($post['firstname'] . ' ' . $post['lastname']) . '</h3>';
        echo '</div>';
        echo '<p>' . htmlspecialchars($post['content']) . '</p>';
        if (!empty($post['media_path'])) {
            $mediaType = mime_content_type($post['media_path']);
            if (strpos($mediaType, 'image') !== false) {
                echo '<img src="' . htmlspecialchars($post['media_path']) . '" alt="Post Media" class="post-image">';
            } elseif (strpos($mediaType, 'video') !== false) {
                echo '<video controls class="post-video"><source src="' . htmlspecialchars($post['media_path']) . '" type="' . $mediaType . '"></video>';
            }
        }
        echo '</div>';
    }
}
if ($content) {
    // Save post content to database
    $query = "INSERT INTO posts (student_id, content, created_at) VALUES ('$student_id', '$content', NOW())";
    mysqli_query($conn, $query);
    $postId = mysqli_insert_id($conn); // Get the ID of the newly created post

    // Handle media upload
    foreach ($mediaFiles['tmp_name'] as $key => $tmp_name) {
        if ($mediaFiles['error'][$key] === 0) {
            $filePath = 'uploads/' . basename($mediaFiles['name'][$key]);
            move_uploaded_file($tmp_name, $filePath);

            // Update the post with media path
            $query = "UPDATE posts SET media_path = '$filePath' WHERE id = '$postId'";
            mysqli_query($conn, $query);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
           <title>BSIT Home-Page</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
<header>
    <nav>
        <div class="logo">BSIT</div>
        <div class="search-bar">
            <input type="text" placeholder="Search...">
        </div>
        <div class="nav-icons">
            <a href="home.php"><i class="bi bi-house-door-fill"></i></a>
            <a href="#"><i class="bi bi-envelope-fill"></i></a>
            <div class="dropdown">
            <a href="studentProfile.php">
                 <img src="images-data/<?= htmlspecialchars($student['image']) ?>" alt="Profile Image" class="profile-image" >
                <div class="dropdown-content">
                    <a href="#profile">Profile Settings</a>
                    <a href="./includes/logout.php">Log out</a>
                </div>
            </div>
            </>
        </div>
    </nav>
</header>
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
                    <img src="images-data/<?= htmlspecialchars($student['image']) ?>" alt="Profile Image" class="profile-image">
                </a>
                <p class="profile-name"><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></p>
            </div>
            <textarea placeholder="What on your mind? <?= htmlspecialchars($student['firstname']) ?>"></textarea>
  <div class="add-photos" onclick="triggerFileUpload()">
  <input type="file" id="media-upload" name="media[]" multiple accept="image/*,video/*" style="display: none;" onchange="previewFiles(event)">
  <p>Add photos/videos</p>
</div><br>
<div id="media-preview"  class="media-grid"></div>
</div>
<button id="delete-post" class="delete-button" style="display: none;"  onclick="clearFiles()">Cancel Post</button>
        <div class="popup-footer">
            <button onclick="closePopup()">Post</button>
        </div>
        </form>
    </div>
</div>
    <div class="container">
        <main class="feed">
        <div class="add-post">
        <div class="profile-container">
                <a href="studentProfile.php">
                    <img src="images-data/<?= htmlspecialchars($student['image']) ?>" alt="Profile Image" class="profile-image">
                </a>
                <p class="profile-name" style="color: black; font-weight: bold;" ><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></p>
        </div>
        <h3>Create a Post </h3>
        
            <div class="post-form"  onclick="openPopup()">
            <textarea name="content" placeholder="What's on your mind?" rows="3" style="resize: none;"></textarea>

            <input type="file" id="upload-media" name="media[]" multiple accept="image/*,video/*" style="display: none;">
            <button type="submit" name="submit">Post</button>
            
            </div>
        </form>
        </div>

           <div class="post">
    <div class="post-header">
        <img src="./images/sampleprofile.jpg" alt="John Doe" class="profile-pic">
        <h3>John Doe</h3>
    </div>
    <p>Just had an amazing day at the beach! 🌊☀️</p>
    <img src="./images/bg.jpg" alt="Post image" class="post-image">
    <div class="post-interactions">
        <button class="like-btn">👍 Like</button>
        <button class="comment-btn">💬 Comment</button>
    </div>
    <div class="comments">
        <div class="comment">
            <img src="./images/sampleprofilewoman.jpg" alt="Jane Smith" class="post-image">
            <strong>Jane Smith:</strong> Wow, that looks amazing! 🌟
            <div class="comment-actions">
                <button class="like-comment-btn">👍 Like</button>
                <button class="reply-btn">💬 Reply</button>
            </div>
        </div>
    
        <div class="comment-form">
            <textarea placeholder="Write a comment..." rows="2"></textarea>
            <button class="submit-comment-btn">Post</button>
        </div>
    </div>
</div>
        </main>
        <aside class="trending">
            <h4>Trending</h4>
            <ul>
                <li>#SunsetViews</li>
                <li>#TravelGoals</li>
                <li>#TechTrends</li>
            </ul>
        </aside>
    </div>
    <footer>
        <p>&copy; 2024 BSIT. All rights reserved.</p>
    </footer>
 <script src="./js/home.js" ></script>
</body>
</html>
