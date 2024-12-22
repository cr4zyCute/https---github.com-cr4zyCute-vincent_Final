<?php
session_start();
include 'database/dbcon.php';

if (isset($_POST['post_id']) && isset($_SESSION['student_id'])) {
    $post_id = intval($_POST['post_id']);
    $student_id = intval($_SESSION['student_id']);

    // Check if the user has already liked the post
    $checkQuery = "SELECT * FROM likes WHERE post_id = $post_id AND student_id = $student_id";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        // Add a like
        $query = "INSERT INTO likes (post_id, student_id) VALUES ($post_id, $student_id)";
        mysqli_query($conn, $query);
    } else {
        // Remove the like (toggle functionality)
        $query = "DELETE FROM likes WHERE post_id = $post_id AND student_id = $student_id";
        mysqli_query($conn, $query);
    }
}

header("Location: home.php");
exit();
?>