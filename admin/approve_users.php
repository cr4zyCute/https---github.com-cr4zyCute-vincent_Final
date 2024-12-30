<?php
session_start();
include 'database/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_users'])) {
    $approved_user_ids = $_POST['approve_users'];

    // Sanitize input
    $approved_user_ids = array_map('intval', $approved_user_ids);

    foreach ($approved_user_ids as $student_id) {
        // Approve the student
        $update_query = $conn->prepare("UPDATE student SET approved = 1 WHERE id = ?");
        $update_query->bind_param('i', $student_id);
        if (!$update_query->execute()) {
            die("Error updating student approval: " . $update_query->error);
        }

        // Check if the notification already exists
        $check_query = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE student_id = ? AND message = ?");
        $message = "Your account has been approved by the admin.";
        $check_query->bind_param('is', $student_id, $message);
        if (!$check_query->execute()) {
            die("Error checking notification existence: " . $check_query->error);
        }
        $check_query->bind_result($exists);
        $check_query->fetch();
        $check_query->close();

        // Add the notification if it doesn't exist
        if ($exists == 0) {
            $insert_query = $conn->prepare("INSERT INTO notifications (student_id, message, created_at, is_read) VALUES (?, ?, NOW(), 0)");
            $insert_query->bind_param('is', $student_id, $message);
            if (!$insert_query->execute()) {
                die("Error inserting notification: " . $insert_query->error);
            }
            $insert_query->close();
        }
    }

    // Redirect after processing
    header('Location: admin_notifications.php');
    exit;
}
?>
