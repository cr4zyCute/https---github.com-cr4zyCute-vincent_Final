<?php

include '../database/dbcon.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "SELECT * FROM student WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        echo "Student not found.";
        exit;
    }
} else {
    echo "No student ID provided.";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs and provide fallbacks for missing fields
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname'] ?? $student['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename'] ?? $student['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname'] ?? $student['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? $student['email']);
    $password = $_POST['password'] ?? $student['password'];

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
    $updateQuery = "
        UPDATE student 
        JOIN credentials ON student.id = credentials.student_id
        SET 
            student.firstname = '$firstname',
            student.middlename = '$middlename',
            student.lastname = '$lastname',

            credentials.email = '$email',
            credentials.password = '$password'
               $imageQueryPart
        WHERE student.id = '$student_id'
    ";
    if (mysqli_query($conn, $updateQuery)) {
        header("Location: studentProfile.php");
        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
$student_query = $conn->prepare("SELECT approved FROM student WHERE id = ?");
$student_query->bind_param('i', $student_id);
$student_query->execute();
$student_query->bind_result($approved);
$student_query->fetch();
$student_query->close();

$notifications_query = $conn->prepare("SELECT message FROM notifications WHERE student_id = ? AND is_read = 0");
$notifications_query->bind_param('i', $student_id);
$notifications_query->execute();
$notifications_result = $notifications_query->get_result();

$mark_read_query = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE student_id = ? AND is_read = 0");
$mark_read_query->bind_param('i', $student_id);
$mark_read_query->execute();

$forms_query = $conn->prepare("SELECT f.id AS form_id, f.form_name FROM student_forms sf
                               JOIN forms f ON sf.form_id = f.id
                               WHERE sf.student_id = ?");

$forms_query->bind_param('i', $student_id);
$forms_query->execute();
$forms_result = $forms_query->get_result();
$forms_query->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/studentProfile.css">
</head>
<body>
<main>
<header>
    <nav>
        <div class="logo">BSIT</div>
       
    </nav>
</header>

<div class="profile-container">
    <img src="../images-data/<?= htmlspecialchars($student['image']) ?>" alt="Profile Image" class="profile-image">
    <div class="profile-info">
        <h1><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></h1>
        <button onclick="openPopup()">Open Profile</button>
        <p><strong>ID:</strong> <?= htmlspecialchars($student['id']) ?></p>
        <h2><i class="bi bi-mortarboard-fill"></i> Student</h2><br>
        <?php if ($student['approved']): ?>
    <p>This Account has Been Approved.</p>
<?php elseif ($student['rejected']): ?>
    <p>This Account has been Rejected</p>
<?php else: ?>
    <p>This Account is Waiting For Approval</p>
<?php endif; ?>

    </div>
</div>

<div class="grid-container">
    <div class="card">
        <h3>Information</h3>
        <p><strong>First Name:</strong> <?= htmlspecialchars($student['firstname']) ?></p>
        <p><strong>Middle Name:</strong> <?= htmlspecialchars($student['middlename']) ?></p>
        <p><strong>Last Name:</strong> <?= htmlspecialchars($student['lastname']) ?></p>

    </div>
    <div class="card">
        <?php
if (!empty($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    $query = "
        SELECT student.*, credentials.email
        FROM student
        JOIN credentials ON student.id = credentials.student_id
        WHERE student.id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        echo "Student profile not found.";
        exit();
    }

    $forms_query = $conn->prepare("
        SELECT f.id AS form_id, f.form_name 
        FROM student_forms sf
        JOIN forms f ON sf.form_id = f.id
        WHERE sf.student_id = ?
    ");
    $forms_query->bind_param('i', $student_id);
    $forms_query->execute();
    $forms_result = $forms_query->get_result();

    if ($forms_result->num_rows > 0) {
        while ($form = $forms_result->fetch_assoc()) {
            $form_id = $form['form_id'];

            $responses_query = $conn->prepare("
                SELECT fr.response, ff.field_name 
                FROM form_responses fr
                JOIN form_fields ff ON fr.field_id = ff.id
                WHERE fr.form_id = ? AND fr.student_id = ?
            ");
            $responses_query->bind_param('ii', $form_id, $student_id);
            $responses_query->execute();
            $responses_result = $responses_query->get_result();

            if ($responses_result->num_rows > 0) {
                
              echo "<a href='view_responses.php?form_id=" . $form['form_id'] . "'><i class='bi bi-pencil-square'></i></a>";

                
                while ($response = $responses_result->fetch_assoc()) {
                    echo "<li style = 'list-style-type: none;'> " . htmlspecialchars($response['field_name']) . ":  " . htmlspecialchars($response['response']) . "</li>";
                }
                echo "</ul>";
            } else {
            }
        }
    } else {
        echo "<p>No forms found for this student.</p>";
    }
} 
?>
        <h3></h3>
    
    </div>
    <div class="card">
        <h3>Credentials</h3>
        <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>


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

    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <?php if ($forms_result->num_rows > 0): ?>
                <ul>
                    <?php
                    // Reset result pointer to reuse $forms_result
                    $forms_result->data_seek(0);
                    while ($form = $forms_result->fetch_assoc()): ?>
                        <li>
                            <?= htmlspecialchars($form['form_name']); ?>
                            <a href="fill_form.php?form_id=<?= $form['form_id']; ?>">Fill Form</a>
                            <a href="view_responses.php?form_id=<?= $form['form_id']; ?>">View Responses</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No Message Yet.</p>
            <?php endif; ?>
        </div>
    </div>


<div class="popup-overlay-edit" id="popupOverlay-edit"></div>
<div class="popup-edit" id="loginPopup">
       <span class="close">&times;</span>
  <div class="grid-container">

    <div class="card">
        <form action="studentProfile.php" method="POST" enctype="multipart/form-data">
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
      <h3>Information</h3>
     
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($student['firstname']) ?>" required>

        <label for="middlename">Middle Name</label>
        <input type="text" id="middlename" name="middlename" value="<?= htmlspecialchars($student['middlename']) ?>" required>

        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($student['lastname']) ?>" required>
    </div>
    <div class="card">
      <h3>Additional Information</h3>

    </div>

    <div class="card">
      <h3>Credentials</h3>
         <label for="email">Address</label>
        <input type="text" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
  
      <p>
    <label for="password">Password</label>
<input type="password" id="password" name="password" value="<?php echo htmlspecialchars($student['password']); ?>" required>
    <button id="togglePassword" style="background: none; border: none; cursor: pointer; margin-left: 10px;">
        <i id="eyeIcon" class="bi bi-eye-fill"></i>
    </button>
</p>     
    </div>
</div>
<button type="submit" name="updatePersonalInfo" class="btn btn-green">Save Changes</button>
 </form>
</div>

<script src="./js/studentProfile.js" ></script>
<script>

const modal = document.getElementById("messageModal");
const openBtn = document.getElementById("openModalBtn");
const closeBtn = document.getElementById("closeModalBtn");

openBtn.addEventListener("click", () => {
  modal.style.display = "block";
});

closeBtn.addEventListener("click", () => {
  modal.style.display = "none";
});

  function openPopup() {
    const popup = document.getElementById('loginPopup');
    const overlay = document.getElementById('popupOverlay-edit');
    popup.classList.add('active');
    overlay.classList.add('active');
  }

  function closePopup() {
    const popup = document.getElementById('loginPopup');
    const overlay = document.getElementById('popupOverlay-edit');
    popup.classList.remove('active');
    overlay.classList.remove('active');
  }
document.addEventListener("DOMContentLoaded", () => {
  const closeButton = document.querySelector(".close");
  const overlay = document.getElementById('popupOverlay-edit');
  closeButton.addEventListener("click", closePopup);
  overlay.addEventListener("click", closePopup);

  const passwordField = document.getElementById('password');
  const togglePassword = document.getElementById('togglePassword');
  const eyeIcon = document.getElementById('eyeIcon');

  togglePassword.addEventListener('click', (e) => {
    e.preventDefault(); 
    const isPasswordVisible = passwordField.type === 'text';

    if (isPasswordVisible) {
      passwordField.type = 'password'; 
      eyeIcon.className = 'bi bi-eye-fill';
    } else {
      passwordField.type = 'text';
      eyeIcon.className = 'bi bi-eye-slash-fill';
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