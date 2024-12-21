<?php
include 'database/dbcon.php';

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $yearlvl = mysqli_real_escape_string($conn, $_POST['yearlvl']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $file_name = '';
 if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
    $file_name = $_FILES['profilePicture']['name'];
    $tempname = $_FILES['profilePicture']['tmp_name'];
    $folder = 'images-data/' . $file_name;

    if (!move_uploaded_file($tempname, $folder)) {
        echo "Failed to upload image.";
        exit();
    }
}
    
    $sql = "INSERT INTO student (firstname, middlename, lastname, age, gender, contact, address, image, yearlvl, section) 
            VALUES ('$firstname', '$middlename', '$lastname', '$age', '$gender', '$contact', '$address', '$file_name', '$yearlvl', '$section')";

    if (mysqli_query($conn, $sql)) {
        $student_id = mysqli_insert_id($conn);

        $credentials_sql = "INSERT INTO credentials (student_id, email, password) 
                            VALUES ('$student_id', '$email', '$password')";

        if (mysqli_query($conn, $credentials_sql)) {
            echo "Student added successfully!";
            header("Location: RegistrationForm.php?id=$id&update=success");
            exit();
        } else {
            echo "Error in credentials: " . mysqli_error($conn);
        }
    } else {
        echo "Error in student table: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Form</title>
    <link rel="stylesheet" href="./css/registration.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <div class="left-section">
            <h1>Please Fill up the Following</h1>
            <img src="images/bsitlogo.png" alt="Image Description" style="width: 200px; height: auto; border-radius: 10px; margin-top: 20px;"><br>
            <a href="index.php">
                <button class="login-btn">Login</button>
            </a>
        </div>
        <div class="right-section">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-container">
                    <div class="form-step active">
                        <div class="form-group">
                            <label for="firstname">Firstname:</label>
                            <input type="text" id="firstname" name="firstname">
                            <label for="middlename">Middlename:</label>
                            <input type="text" id="middlename" name="middlename">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Lastname:</label>
                            <input type="text" id="lastname" name="lastname">
                        </div>
                        <div class="gender">
                            <label for="gender">Gender:</label>
                            <label><input type="radio" name="gender" value="Male"> Male</label>
                            <label><input type="radio" name="gender" value="Female"> Female</label>
                            <label><input type="radio" name="gender" value="Others"> Others</label>
                        </div>
                        <button type="button" class="next-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>

                    <div class="form-step">
                        <div class="form-group">
                            <label for="age">Age:</label>
                            <input type="number" id="age" name="age">
                            <label for="contact">Contact Number:</label>
                            <input type="text" id="contact" name="contact">
                                <label for="yearlvl">Year Level:</label>
                                <select id="yearlvl" name="yearlvl">
                                    <option value="First Year">First Year</option>
                                    <option value="Second Year">Second Year</option>
                                    <option value="Third Year">Third Year</option>
                                    <option value="Fourth Year">Fourth Year</option>
                                </select>
                                <label for="section">Section:</label>
                                <select id="section" name="section">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                </select>
                            
                            <div class="form-group full-width">
                                <label for="address">Address:</label>
                                <input type="text" id="address" name="address">
                            </div>
                        </div>
                        <div class="button-group">
                            <button type="button" class="prev-btn"><i class="fas fa-chevron-left"></i></button>
                            <button type="button" class="next-btn"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>

                    <div class="form-step">
                        <div class="form-group">
                            <div class="profile-pic">
                                <img id="profileImage" src="./images/defaultProfile.jpg" >
                                <label for="profilePicture">Profile Picture:</label>
                                <button type="button" class="edit-btn" id="editButton">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <input type="file" id="profilePicture" name="profilePicture" accept="image/*" style="display: none;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password">
                        </div>
                        <button type="button" class="prev-btn"><i class="fas fa-chevron-left"></i></button>
                        <button type="submit" name="submit" class="register-btn">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <section class="modal-section">
        <span class="overlay"></span>
        <div class="modal-box">
            <i class="fa-regular fa-circle-check" style="font-size: 50px; color:green;"></i>
            <h2>Success</h2>
            <h3>You have successfully registered!</h3>
            <div class="buttons">
                <a href="index.php">
                    <button class="close-btn">OK</button>
                </a>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const steps = document.querySelectorAll('.form-step');
            const nextBtns = document.querySelectorAll('.next-btn');
            const prevBtns = document.querySelectorAll('.prev-btn');
            let currentStep = 0;

            const updateSteps = () => {
                steps.forEach((step, index) => {
                    step.classList.toggle('active', index === currentStep);
                });
            };

            const validateStep = (stepIndex) => {
                const inputs = steps[stepIndex].querySelectorAll('input[required]');
                let isValid = true;

                inputs.forEach((input) => {
                    if (!input.value.trim() || (input.type === 'radio' && !document.querySelector(`input[name="${input.name}"]:checked`))) {
                        input.classList.add('error');
                        isValid = false;
                    } else {
                        input.classList.remove('error');
                    }
                });

                return isValid;
            };

            nextBtns.forEach((btn) => {
                btn.addEventListener('click', () => {
                    if (validateStep(currentStep) && currentStep < steps.length - 1) {
                        currentStep++;
                        updateSteps();
                    }
                });
            });

            prevBtns.forEach((btn) => {
                btn.addEventListener('click', () => {
                    if (currentStep > 0) {
                        currentStep--;
                        updateSteps();
                    }
                });
            });

            updateSteps();
        });

        const profileInput = document.getElementById('profilePicture');
        const profileImage = document.getElementById('profileImage');
        const editButton = document.getElementById('editButton');

        editButton.addEventListener('click', function() {
            profileInput.click();
        });

        profileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const section = document.querySelector(".modal-section"),
                  overlay = document.querySelector(".overlay"),
                  closeBtn = document.querySelector(".close-btn");
            if (urlParams.get('update') === 'success') {
                section.classList.add("active");
            }
            overlay.addEventListener("click", () => section.classList.remove("active"));
            closeBtn.addEventListener("click", () => section.classList.remove("active"));

            window.history.replaceState({}, document.title, window.location.pathname);
        });
    </script>
</body>
</html>