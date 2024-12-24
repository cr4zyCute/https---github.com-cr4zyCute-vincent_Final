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

if ($content || !empty($mediaFiles['name'][0])) {
    // Save post content to database
    $query = "INSERT INTO posts (student_id, content, created_at) VALUES ('$student_id', '$content', NOW())";
    mysqli_query($conn, $query);
    $postId = mysqli_insert_id($conn); // Get the ID of the newly created post

    // Handle media upload if any media files are provided
    if (!empty($mediaFiles['name'][0])) {
        foreach ($mediaFiles['tmp_name'] as $key => $tmp_name) {
            if ($mediaFiles['error'][$key] === 0) {
                $filePath = 'uploads/' . basename($mediaFiles['name'][$key]);
                move_uploaded_file($tmp_name, $filePath);

                // Update the post with media path
                $query = "UPDATE posts SET media = '$filePath' WHERE id = '$postId'";
                mysqli_query($conn, $query);
            }
        }
    }
}
if (!empty($post['media'])) {
    $mediaType = mime_content_type($post['media']);
    if (strpos($mediaType, 'image') !== false) {
        echo '<img src="' . htmlspecialchars($post['media']) . '" alt="Post Media" class="post-image">';
    } elseif (strpos($mediaType, 'video') !== false) {
        echo '<video controls class="post-video"><source src="' . htmlspecialchars($post['media']) . '" type="' . $mediaType . '"></video>';
    }
} elseif (!empty($post['content'])) {
    echo '<p>' . htmlspecialchars($post['content']) . '</p>';
}

$posts = [];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $posts[] = $row;
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
        <textarea name="content" placeholder="What on your mind? <?= htmlspecialchars($student['firstname']) ?>"></textarea>
        <div class="add-photos" onclick="triggerFileUpload()">
            <input type="file" id="media-upload" name="media[]" multiple accept="image/*,video/*" style="display: none;" onchange="previewFiles(event)">
            <p>Add photos/videos</p>
        </div><br>
        <div id="media-preview" class="media-grid"></div>
    </div>
    <button id="delete-post" class="delete-button" style="display: none;" onclick="clearFiles()">Cancel Post</button>
    <button type="submit">Post</button>
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
        

<?php
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
        echo '<img src="images-data/' . htmlspecialchars($post['profile_image']) . '" alt="Profile Image" class="profile-pic">';
        echo '<h3>' . htmlspecialchars($post['firstname'] . ' ' . $post['lastname']) . '</h3>';
        echo '</div>';
        echo '<p>' . htmlspecialchars($post['content']) . '</p>';
        if (!empty($post['media'])) {
            $mediaType = mime_content_type($post['media']);
            if (strpos($mediaType, 'image') !== false) {
                echo '<img src="' . htmlspecialchars($post['media']) . '" alt="Post Media" class="post-image">';
            } elseif (strpos($mediaType, 'video') !== false) {
                echo '<video controls class="post-video"><source src="' . htmlspecialchars($post['media']) . '" type="' . $mediaType . '"></video>';
            }
        }

        // Like button
        echo '<div class="post-actions">';
        echo '<form method="POST" action="like_post.php" class="like-form">';
        echo '<input type="hidden" name="post_id" value="' . htmlspecialchars($post['id']) . '">';
        echo '<button type="submit" class="like-button">Like (' . $post['like_count'] . ')</button>';
        echo '</form>';

        // Comment button
        echo '<button class="comment-toggle">Comment (' . $post['comment_count'] . ')</button>';
        echo '</div>';

        // Comment form
        echo '<form method="POST" action="comment_post.php" class="comment-form">';
echo '<input type="hidden" name="post_id" value="' . htmlspecialchars($post['id']) . '">';
echo '<textarea name="comment" placeholder="Write a comment..." required></textarea>';
echo '<button type="submit">Post Comment</button>';
echo '</form>';

        $commentQuery = "SELECT c.*, s.firstname, s.lastname, s.image AS profile_image
                         FROM comments c 
                         JOIN student s ON c.student_id = s.id 
                         WHERE c.post_id = " . intval($post['id']) . " 
                         ORDER BY c.created_at ASC";
        $commentResult = mysqli_query($conn, $commentQuery);

        if ($commentResult && mysqli_num_rows($commentResult) > 0) {
            echo '<div class="comments">';
            while ($comment = mysqli_fetch_assoc($commentResult)) {
                echo '<div class="comment">';
                   echo '<img src="images-data/' . htmlspecialchars($comment['profile_image']) . '" alt="Profile Image" class="profile-pic">';
                echo '<strong>' . htmlspecialchars($comment['firstname'] . ' ' . $comment['lastname']) . ':</strong> ';
                echo '<p>' . htmlspecialchars($comment['content']) . '</p>';
                echo '</div>';
            }
            echo '</div>';
        }

        echo '</div>'; // End of comment-section
        echo '</div>'; // End of post
    }
}
?>
    </main>
    </div>
        <aside class="trending">
            <h4>Trending</h4>
            <ul>
                <li>#SunsetViews</li>
                <li>#TravelGoals</li>
                <li>#TechTrends</li>
            </ul>
        </aside>
    </div>
 
 <script src="./js/home.js" ></script>
</body>
  
<script>
     document.querySelectorAll('.comment-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const commentSection = button.nextElementSibling;
            commentSection.style.display = commentSection.style.display === 'none' ? 'block' : 'none';
        });
    });

    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', () => {
            // Handle like button click
            alert('Liked!');
            // Optionally, send like data to the server using AJAX
        });
    });

    document.querySelectorAll('.submit-comment').forEach(button => {
        button.addEventListener('click', () => {
            const commentInput = button.previousElementSibling;
            const commentText = commentInput.value;
            if (commentText) {
                alert('Comment submitted: ' + commentText);
                // Optionally, send comment data to the server using AJAX
                commentInput.value = ''; // Clear the input
            }
        });
    });
    
    document.addEventListener("DOMContentLoaded", function () {
    const commentButtons = document.querySelectorAll(".comment-button");

    commentButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            const postId = this.getAttribute("data-post-id");
            const commentInput = document.querySelector(`.comment-input[data-post-id='${postId}']`);
            const comment = commentInput.value;

            if (comment.trim() !== "") {
                fetch("comment_post.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `post_id=${postId}&comment=${encodeURIComponent(comment)}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        // Reload or update the comments dynamically
                        location.reload();
                    } else {
                        console.error("Failed to post comment");
                    }
                });
            }
        });
    });
});


</script>
</html>
