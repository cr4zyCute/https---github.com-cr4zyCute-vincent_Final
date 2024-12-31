<?php
include '../database/dbcon.php';

$forms = $conn->query("SELECT * FROM forms")->fetch_all(MYSQLI_ASSOC);
$students = $conn->query("SELECT * FROM student")->fetch_all(MYSQLI_ASSOC);
$approved_students = $conn->query("SELECT * FROM student WHERE approved = 1")->fetch_all(MYSQLI_ASSOC);
$new_students = $conn->query("SELECT * FROM student WHERE is_approved = 0 AND admin_notified = 0")->fetch_all(MYSQLI_ASSOC);

$conn->query("UPDATE student SET admin_notified = 1 WHERE is_approved = 0 AND admin_notified = 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard">
        <header>
            <form id="createForm" method="POST" action="create_form.php">
                <a href="admin-dashboard.php" style="color:white;" ><i class="bi bi-arrow-90deg-left"></i></a>
            <div class="form-title">
                 <label for="form_name">Form Name:</label>
                 <input type="text" name="form_name" id="form_name" required><br><br>
            <button type="button" class="add-field-btn" onclick="addField()">Add Field</button>
            <button type="submit" class="create-form-btn">Create Form</button>
             <div id="fields"></div>
            </div>
            </form>
     
        </header>
        <main>
         
            <section class="form-builder">
                <h2>Create a New Form</h2>
              
                    <div id="fields-container">
                        <div class="fields">
                            
                        </div>
                    </div>
            </section>

            <section class="available-forms">
                <h2>Available Forms</h2>
                <div class="search-bar">
                    <input type="text" placeholder="Search Forms">
                </div>
                <ul>
                    <?php foreach ($forms as $form): ?>
                        <li>
                            <?= htmlspecialchars($form['form_name']); ?>
                            <button class="send-btn" onclick="showSendModal(<?= $form['id']; ?>)"><i class="bi bi-send-fill"></i></button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
       
    </div>

    <!-- Modal for Sending Forms -->
    <div id="sendModal" style="display:none;">
        <div class="modal-content">
            <h3>Send Form to Student</h3>
            <form method="POST" action="send_form.php">
                <input type="hidden" name="form_id" id="modalFormId">
                <label for="student_id">Select Student:</label>
                <select name="student_id" id="student_id" required>
                    <?php foreach ($approved_students as $student): ?>
                        <option value="<?= $student['id']; ?>"><?= htmlspecialchars($student['firstname']); ?></option>
                    <?php endforeach; ?>
                </select><br><br>
                <button type="submit">Send Form</button>
                <button type="button" onclick="document.getElementById('sendModal').style.display='none';">Close</button>
            </form>
        </div>
    </div>
 </main>
    <script>
        let fieldCount = 1;

        function addField() {
            const fieldsContainer = document.getElementById('fields-container');
            const fieldHTML = `
                <div class="fields">
                    <label>Field Name:</label>
                    <input type="text" name="fields[${fieldCount}][name]" required>
                    <label>Field Type:</label>
                    <select name="fields[${fieldCount}][type]">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="email">Email</option>
                        <option value="textarea">Textarea</option>
                    </select>
                    <label>Required:</label>
                    <input type="checkbox" name="fields[${fieldCount}][required]">
                </div>
            `;
            fieldsContainer.insertAdjacentHTML('beforeend', fieldHTML);
            fieldCount++;
        }

        function showSendModal(formId) {
            document.getElementById('modalFormId').value = formId;
            document.getElementById('sendModal').style.display = 'block';
        }
    </script>
</body>
</html>


<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
   
    color: #fff;
}
/* Modal container */
#sendModal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
}

/* Modal content box */
#sendModal .modal-content {
    position: relative;
    background-color: #444; /* Dark background for the modal */
    margin: 15% auto; /* Center vertically and horizontally */
    padding: 20px;
    border-radius: 10px;
    width: 400px; /* Set a fixed width */
    color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add a shadow */
}

/* Modal form styles */
#sendModal form {
    display: flex;
    flex-direction: column;
    gap: 15px; /* Add spacing between elements */
}

/* Form label */
#sendModal label {
    font-weight: bold;
}

/* Dropdown styling */
#sendModal select {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #aaa;
    background-color: #fff;
    color: #333;
    font-size: 16px;
}

/* Buttons inside modal */
#sendModal button {
    padding: 10px 15px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

/* Submit button */
#sendModal button[type="submit"] {
    background-color: #28a745; /* Green */
    color: #fff;
}

#sendModal button[type="submit"]:hover {
    background-color: #218838;
}

/* Close button */
#sendModal button[type="button"] {
    background-color: #dc3545; /* Red */
    color: #fff;
}

#sendModal button[type="button"]:hover {
    background-color: #c82333;
}

.dashboard {
    width: 90%;
    height: auto;
    margin: 20px auto;
    background-color: #222;
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #444;
   
}

header .form-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

header .form-title label {
    font-size: 18px;
    font-weight: bold;
}

header .form-title input {
    padding: 5px 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.profile-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #888;
}

main {
background-color: white;
    display: flex;
    padding: 20px;
    gap: 20px;
}

.form-builder {
    flex: 3;
    background-color: #444;
    padding: 20px;
    border-radius: 10px;
}

.fields-container {
    background-color: #ddd;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    color: #333;
}

.field-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.field-row input[type="text"],
.field-row select {
    flex: 1;
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #aaa;
}

.field-row input[type="checkbox"] {
    transform: scale(1.2);
}

.form-buttons {
    display: flex;
    justify-content: space-between;
    gap: 10px;
}

.add-field-btn,
.create-form-btn {
    padding: 10px 20px;
    background-color: #333;
    border: none;
    border-radius: 5px;
    color: #fff;
    cursor: pointer;
}

.add-field-btn:hover,
.create-form-btn:hover {
    background-color: #555;
}

.available-forms {
    flex: 2;
    background-color: #444;
    padding: 20px;
    border-radius: 10px;
}

.search-bar input {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #aaa;
    margin-bottom: 20px;
    color: #333;
}

ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

ul li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background-color: #555;
    margin-bottom: 10px;
    border-radius: 5px;
}

ul li button {
    background-color: #333;
    border: none;
    padding: 5px 15px;
    border-radius: 5px;
    color: #fff;
    cursor: pointer;
}

ul li button:hover {
    background-color: #777;
}


</style>