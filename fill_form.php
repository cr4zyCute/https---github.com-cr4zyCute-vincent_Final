<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit;
}

include 'database/dbcon.php';
$student_id = $_SESSION['student_id'];

// Get the form ID from the query string
if (!isset($_GET['form_id']) || empty($_GET['form_id'])) {
    echo "No form selected.";
    exit;
}

$form_id = intval($_GET['form_id']);

// Fetch form name
$form_query = $conn->prepare("SELECT form_name FROM forms WHERE id = ?");
$form_query->bind_param('i', $form_id);
$form_query->execute();
$form_result = $form_query->get_result();

if ($form_result->num_rows === 0) {
    echo "Form not found.";
    exit;
}

$form = $form_result->fetch_assoc();

// Fetch form fields
$fields_query = $conn->prepare("SELECT id, field_name FROM form_fields WHERE form_id = ?");
$fields_query->bind_param('i', $form_id);
$fields_query->execute();
$fields = $fields_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fill Form</title>
</head>
<body>
    <h1>Fill Out Form: <?= htmlspecialchars($form['form_name']); ?></h1>
    <?php if ($fields->num_rows > 0): ?>
      <form action="send_form.php" method="POST">
    <input type="hidden" name="form_id" value="<?= $form_id; ?>">
    <input type="hidden" name="student_id" value="<?= $student_id; ?>"> <!-- Include student ID -->
    <?php while ($field = $fields->fetch_assoc()): ?>
        <div>
            <label for="field_<?= $field['id']; ?>"><?= htmlspecialchars($field['field_name']); ?>:</label>
            <input 
                type="text" 
                id="field_<?= $field['id']; ?>" 
                name="responses[<?= $field['id']; ?>]" 
                required>
        </div>
    <?php endwhile; ?>
    <button type="submit">Submit</button>
</form>

    <?php else: ?>
        <p>No fields available for this form.</p>
    <?php endif; ?>
    <a href="user_profile.php">Back to Dashboard</a>
</body>
</html>
