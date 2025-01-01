<?php
require '../database/dbcon.php';
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
    header("Location: student.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs and provide fallbacks for missing fields
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname'] ?? $student['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename'] ?? $student['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname'] ?? $student['lastname']);
    $age = mysqli_real_escape_string($conn, $_POST['age'] ?? $student['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender'] ?? $student['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact'] ?? $student['contact']); 
    $yearlvl = mysqli_real_escape_string($conn, $_POST['yearlvl'] ?? $student['yearlvl']); 
    $section = mysqli_real_escape_string($conn, $_POST['section'] ?? $student['section']); 
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? $student['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? $student['email']);
    $password = $_POST['password'] ?? $student['password'];

  $imageQueryPart = ""; 
        $imageName = basename($_FILES['profileImage']['name']);
        $imagePath = '../images-data/' . $imageName;

$imageQueryPart = ""; 
if (!empty($_FILES['profileImage']['name'])) {
    $imageName = basename($_FILES['profileImage']['name']);
    $imagePath = '../images-data/' . $imageName;

    if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
        $imageQueryPart = ", student.image = '$imageName'";
    } else {
        echo "Failed to upload image.";
        exit();
    }
}
    // Construct the update query
    $updateQuery = "
        UPDATE student 
        JOIN credentials ON student.id = credentials.student_id
        SET 
            student.firstname = '$firstname',
            student.middlename = '$middlename',
            student.lastname = '$lastname',
            student.age = '$age',
            student.gender = '$gender',
            student.section = '$section',
            student.contact = '$contact', -- Corrected field
            student.yearlvl = '$yearlvl', -- Corrected field
            student.address = '$address',
            credentials.email = '$email',
            credentials.password = '$password'
               $imageQueryPart
        WHERE student.id = '$student_id'
    ";

    // Execute and check the query
    if (mysqli_query($conn, $updateQuery)) {
        header("Location: ../admin/studentUpdate.php");
        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>User Profile</title>
    <link rel="stylesheet" href="admin-css/studentUpdate.css">
    
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
                 <img src="../images-data/<?= htmlspecialchars($student['image']) ?>" alt="Profile Image" class="profile-pic" >
                <div class="dropdown-content">
                    <a href="#profile">Profile Settings</a>
                    <a href="./includes/logout.php">Log out</a>
                </div>
            </div>
            </a>
        </div>
    </nav>
</header>
 <form action="../admin/studentUpdate.php" method="POST" enctype="multipart/form-data">
<div class="profile-container">
   <div class="profile-picture">
        <?php
        $imagePath = '../images-data/' . htmlspecialchars($student['image']);
        if (!empty($student['image']) && file_exists($imagePath)) {
            echo '<img src="' . $imagePath . '?v=' . time() . '" style="width:120px; height:120px;" alt="Profile Image" id="profileDisplay">';
        } else {
            echo '<img src="../images-data/default-image.png" style="width:120px; height:120px;" alt="Default Image" id="profileDisplay">';
        }
        ?>                    
        <input type="file" id="profileImageUpload" name="profileImage" accept="image/*" onchange="previewImage(event)" hidden>
        <div class="edit-btn" onclick="document.getElementById('profileImageUpload').click()">Edit</div>
    </div>
    <div class="profile-info">
        <h1><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?> </h1>  <button onclick="openPopup()">Open Profile</button>
        <p><strong>ID:</strong> <?= htmlspecialchars($student['id']) ?></p>
        <h2><i class="bi bi-mortarboard-fill"></i>Student</h2> 
    </div>
</div>

<div class="grid-container">
    <div class="card">
        <h3>Information</h3>
         <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($student['firstname']) ?>" required>

       <label for="middlename">Middle Name</label>
        <input type="text" id="middlename" name="middlename" value="<?= htmlspecialchars($student['middlename']) ?>" required>

        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($student['lastname']) ?>" required>

        <label for="age">Age</label>
        <input type="number" id="age" name="age" value="<?= htmlspecialchars($student['age']) ?>" required>

        <label for="gender">Gender</label>
        <select id="gender" name="gender">
          <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
          <option value="Other" <?= $student['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
    </div>
    <div class="card">
        <h3></h3>
      <label for="yearlvl">Year Level</label>
        <select id="yearlvl" name="yearlvl">
          <option value="First Year" <?= $student['yearlvl'] == 'First Year' ? 'selected' : '' ?>>First Year</option>
          <option value="Second Year" <?= $student['yearlvl'] == 'Second Year' ? 'selected' : '' ?>>Second Year</option>
          <option value="Third Year" <?= $student['yearlvl'] == 'Third Year' ? 'selected' : '' ?>>Third Year</option>
          <option value="Fourth Year" <?= $student['yearlvl'] == 'Fourth Year' ? 'selected' : '' ?>>Fourth Year</option>
        </select><br>

      <label for="section">Section</label><br>
        <select id="section" name="section">
          <option value="A" <?= $student['section'] == 'A' ? 'selected' : '' ?>>A</option>
          <option value="B" <?= $student['section'] == 'B' ? 'selected' : '' ?>>B</option>
          <option value="C" <?= $student['section'] == 'C' ? 'selected' : '' ?>>C</option>
          <option value="D" <?= $student['section'] == 'D' ? 'selected' : '' ?>>D</option>
          <option value="E" <?= $student['section'] == 'E' ? 'selected' : '' ?>>E</option>
        </select><br>

       <label for="contact">Contact</label>
        <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($student['contact']) ?>" required>

       <label for="address">Address</label>
        <input type="text" id="address" name="address" value="<?= htmlspecialchars($student['address']) ?>" required>
    </div>
    <div class="card">
      <h3>Credentials</h3>
         <label for="email">Address</label>
        <input type="text" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
  
      <p>
    <label for="password">Password</label>
    <input type="password" id="password" name="password" value="<?= htmlspecialchars($student['password']) ?>" required>
    <button id="togglePassword" style="background: none; border: none; cursor: pointer; margin-left: 10px;">
        <i id="eyeIcon" class="bi bi-eye-fill"></i>
    </button>
    </p>     
    </div>
</div>
<button type="submit" name="updatePersonalInfo" class="btn btn-green">Save Changes</button>
 </form>
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
        echo '<img src="../images-data/' . htmlspecialchars($post['profile_image']) . '" alt="Profile Image" class="profile-pic">';
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
                echo '<img src="../images-data/' . htmlspecialchars($comment['profile_image']) . '" alt="Profile Image" class="profile-pic">';
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
  // Function to open the popup
  function openPopup() {
    const popup = document.getElementById('loginPopup');
    const overlay = document.getElementById('popupOverlay-edit');
    popup.classList.add('active');
    overlay.classList.add('active');
  }

  // Function to close the popup
  function closePopup() {
    const popup = document.getElementById('loginPopup');
    const overlay = document.getElementById('popupOverlay-edit');
    popup.classList.remove('active');
    overlay.classList.remove('active');
  }
document.addEventListener("DOMContentLoaded", () => {
  // Close button functionality
  const closeButton = document.querySelector(".close");
  const overlay = document.getElementById('popupOverlay-edit');

  // Close popup when clicking the close button
  closeButton.addEventListener("click", closePopup);

  // Close popup when clicking the overlay
  overlay.addEventListener("click", closePopup);

  // Password toggle functionality
  const passwordField = document.getElementById('password');
  const togglePassword = document.getElementById('togglePassword');
  const eyeIcon = document.getElementById('eyeIcon');

  togglePassword.addEventListener('click', (e) => {
    e.preventDefault(); // Prevent form submission if button is inside a form
    const isPasswordVisible = passwordField.type === 'text';

    if (isPasswordVisible) {
      // Hide password
      passwordField.type = 'password'; // Change to password input type
      eyeIcon.className = 'bi bi-eye-fill'; // Change icon to open eye
    } else {
      // Show password
      passwordField.type = 'text'; // Change to text input type
      eyeIcon.className = 'bi bi-eye-slash-fill'; // Change icon to closed eye
    }
  });
});
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        const preview = document.getElementById('profileDisplay');
        preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}


</script>

</body>
</html>