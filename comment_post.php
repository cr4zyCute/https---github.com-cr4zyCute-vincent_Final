<?php
session_start();
include 'database/dbcon.php';

if (isset($_POST['post_id'], $_POST['comment']) && isset($_SESSION['student_id'])) {
    $post_id = intval($_POST['post_id']);
    $student_id = intval($_SESSION['student_id']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    if (!empty($comment)) {
        $query = "INSERT INTO comments (post_id, student_id, content) VALUES ($post_id, $student_id, '$comment')";
        mysqli_query($conn, $query);
    }
}

header("Location: home.php");
exit();
?>