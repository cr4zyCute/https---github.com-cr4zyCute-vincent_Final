<?php
include '../database/dbcon.php';

if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']);

    // Fetch student details
    $student_query = $conn->prepare("
        SELECT student.*, credentials.email 
        FROM student
        LEFT JOIN credentials ON student.id = credentials.student_id
        WHERE student.id = ?
    ");
    $student_query->bind_param('i', $student_id);
    $student_query->execute();
    $student_result = $student_query->get_result();

    if ($student_result->num_rows > 0) {
        $student = $student_result->fetch_assoc();
    } else {
        die("Student not found.");
    }

    // Fetch forms assigned to the student
    $forms_query = $conn->prepare("
        SELECT f.id AS form_id, f.form_name 
        FROM student_forms sf
        JOIN forms f ON sf.form_id = f.id
        WHERE sf.student_id = ?
    ");
    $forms_query->bind_param('i', $student_id);
    $forms_query->execute();
    $forms_result = $forms_query->get_result();
} else {
    die("No student ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Student Profile</title>
    <link rel="stylesheet" href="../css/studentProfile.css">
</head>
<body>
<header>
    <nav>
        <a href="./admin-dashboard.php"><i class="bi bi-arrow-90deg-left"></i></a>
        <div class="logo">BSIT</div>
    </nav>
</header>

<div class="profile-container">
    <div class="profile-picture">
        <img src="<?= file_exists('../images-data/' . htmlspecialchars($student['image'])) && !empty($student['image']) ? '../images-data/' . htmlspecialchars($student['image']) : '../images-data/default-image.png'; ?>" 
             alt="Profile Image" class="profile-image" style="width: 120px; height: 120px;">
    </div>
    <div class="profile-info">
        <h1><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></h1>
        <p><strong>ID:</strong> <?= htmlspecialchars($student['id']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($student['email']); ?></p>
    </div>
</div>

<div class="forms-container">
    <h2>Assigned Forms</h2>
    <?php if ($forms_result->num_rows > 0): ?>
        <?php while ($form = $forms_result->fetch_assoc()): ?>
            <div class="form-section">
                <h3><?= htmlspecialchars($form['form_name']); ?></h3>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Response</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $responses_query = $conn->prepare("
                            SELECT fr.id as response_id, ff.field_name, fr.response 
                            FROM form_responses fr 
                            JOIN form_fields ff ON fr.field_id = ff.id 
                            WHERE fr.student_id = ? AND fr.form_id = ?
                        ");
                        $responses_query->bind_param('ii', $student_id, $form['form_id']);
                        $responses_query->execute();
                        $responses_result = $responses_query->get_result();

                        if ($responses_result->num_rows > 0):
                            while ($response = $responses_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($response['field_name']); ?></td>
                                    <td><?= htmlspecialchars($response['response']); ?></td>
                                    <td>
                                        <a href="edit_response.php?response_id=<?= $response['response_id']; ?>" class="btn-edit">Edit</a>
                                        <form action="delete_response.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="response_id" value="<?= $response['response_id']; ?>">
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this response?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; 
                        else: ?>
                            <tr>
                                <td colspan="3">No responses found for this form.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No forms assigned to this student.</p>
    <?php endif; ?>
</div>

</body>
</html>
