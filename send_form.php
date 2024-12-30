<?php
include 'database/dbcon.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_id = filter_input(INPUT_POST, 'form_id', FILTER_VALIDATE_INT);
    $student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);

    if (!$form_id || !$student_id) {
        die("Invalid form_id or student_id.");
    }

    // Handle responses
    if (isset($_POST['responses']) && is_array($_POST['responses'])) {
        foreach ($_POST['responses'] as $field_id => $response) {
            $field_id = intval($field_id);
            $response = $conn->real_escape_string($response);

            // Insert into a table to save responses (e.g., form_responses)
            $stmt = $conn->prepare("INSERT INTO form_responses (form_id, field_id, student_id, response) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $form_id, $field_id, $student_id, $response);

            if (!$stmt->execute()) {
                echo "Error saving response for field $field_id: " . $stmt->error;
            }
        }
    }

    // Send email (existing code)
    $student_result = $conn->query("SELECT email FROM credentials WHERE id = $student_id");
    if ($student_result && $student = $student_result->fetch_assoc()) {
        $to = $student['email'];
        $subject = "New Form Assigned";
        $message = "You have been assigned a new form. Please log in to your dashboard to view it.";
        $headers = "From: admin@yourdomain.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "Form has been sent to the student.";
        } else {
            echo "Error sending email.";
        }
    } else {
        echo "good!!";
    }
}

?>
