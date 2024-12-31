<?php
include '../database/dbcon.php';

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_id = $_POST['form_id'];
    $student_id = $_POST['student_id'];

    // Insert the form assignment into the student_forms table
    $stmt = $conn->prepare("INSERT INTO student_forms (student_id, form_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $student_id, $form_id);

    if ($stmt->execute()) {
        // After assigning, send an email to the student
        $student_result = $conn->query("SELECT email FROM credentials WHERE id = $student_id");
        $student = $student_result->fetch_assoc();

        if ($student) {
            $to = $student['email'];
            $subject = "New Form Assigned";
            $message = "You have been assigned a new form. Please log in to your dashboard to view it.";
            $headers = "From: admin@yourdomain.com";

            if (mail($to, $subject, $message, $headers)) {
                echo "Form has been sent to the student.";
            } else {
                echo "Error sending email.";
            }
        }
    } else {
        echo "Error assigning form to student.";
    }
}
?>
