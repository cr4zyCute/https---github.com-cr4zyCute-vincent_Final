<?php
session_start();
require 'database/dbcon.php';

if (empty($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}
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
        <p><strong>ID:</strong> <?= htmlspecialchars($student['id']) ?></p>
        <h2><i class="bi bi-mortarboard-fill"></i>Student</h2>
    </div>
</div>

<div class="grid-container">
    <div class="card">
        <h3>Information</h3>
        <p><strong>First Name:</strong> <?= htmlspecialchars($student['firstname']) ?></p>
        <p><strong>Middle Name:</strong> <?= htmlspecialchars($student['middlename']) ?></p>
        <p><strong>Last Name:</strong> <?= htmlspecialchars($student['lastname']) ?></p>
       <p><strong>Age:</strong> <?= htmlspecialchars($student['age']) ?></p>
       <p><strong>Address:</strong> <?= htmlspecialchars($student['gender']) ?></p>
    </div>
    <div class="card">
        <h3></h3>
      <p><strong>Year:</strong> <?= htmlspecialchars($student['yearlvl']) ?></p>
      <p><strong>Section:</strong> <?= htmlspecialchars($student['section']) ?></p>
      <p><strong>Contact:</strong> <?= htmlspecialchars($student['contact']) ?></p>
      <p><strong>Address:</strong> <?= htmlspecialchars($student['address']) ?></p>
    </div>
    <div class="card">
        <h3>Credentials</h3>
        <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
        
        <p>
            <strong>Password:</strong>
            <span id="passwordField"><?= htmlspecialchars($student['password']) ?></span>
            <button id="togglePassword" style="background: none; border: none; cursor: pointer; margin-left: 10px;">
                <i id="eyeIcon" class="bi bi-eye-fill"></i>
            </button>
        </p>     

    </div>
</div>
<?php
$query = "SELECT p.*, s.firstname, s.lastname, s.image AS profile_image, 
                (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS like_count,
                (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comment_count
          FROM posts p 
          JOIN student s ON p.student_id = s.id 
          WHERE p.student_id = '$student_id' 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($post = mysqli_fetch_assoc($result)) {
        echo '<div class="post">';
        echo '<div class="post-header">';
        echo '<div class="delete-container">';
        echo '<button class="delete-button" onclick="deletePost(' . htmlspecialchars($post['id']) . ')"><i class="bi bi-trash3-fill"></i></button>';
        echo '</div>';
        echo '<img src="images-data/' . htmlspecialchars($post['profile_image']) . '" alt="Profile Image" class="profile-pic">';
        echo '<div class="post-user-info">';
        echo '<strong>' . htmlspecialchars($post['firstname'] . ' ' . $post['lastname']) . '</strong>';
        echo '<span><i class="bi bi-mortarboard-fill"></i> Student</span>';
        
        echo '</div>';
        echo '</div>';

        echo '<div class="post-content">';
        echo '<p>' . htmlspecialchars($post['content']) . '</p>';
        echo '</div>';

        if (!empty($post['media'])) {
            echo '<div class="post-media">';
            echo '<img src="' . htmlspecialchars($post['media']) . '" alt="Post Media">';
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
</main>
<script src="./js/studentProfile.js" ></script>
<script>
    const passwordField = document.getElementById('passwordField');
  const togglePassword = document.getElementById('togglePassword');
  const eyeIcon = document.getElementById('eyeIcon');
  
  togglePassword.addEventListener('click', function () {
    const isHidden = passwordField.textContent.includes('*');
    
    if (isHidden) {
      // Show password
      passwordField.textContent = '<?= $student['password'] ?>';
      eyeIcon.src = '<i class="bi bi-eye-fill"></i>'; // Change icon to "show"
    } else {
      // Hide password
      passwordField.textContent = '*'.repeat('<?= strlen($student['password']) ?>');
      eyeIcon.src = '<i class="bi bi-eye-slash-fill"></i>'; 
    }
  });

  // Initially hide the password
  passwordField.textContent = '*'.repeat('<?= strlen($student['password']) ?>');
    const menuBtn = document.getElementById('menu-btn');
    const navLinks = document.getElementById('nav-links');

    menuBtn.addEventListener('click', () => {
        navLinks.classList.toggle('show');
    });
     function toggleComments(postId) {
    const commentsSection = document.getElementById(`comments-${postId}`);
    if (commentsSection) {
        commentsSection.style.display = commentsSection.style.display === 'block' ? 'none' : 'block';
    }
}
 

</script>

</body>
</html>