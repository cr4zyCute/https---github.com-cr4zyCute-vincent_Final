<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vincent";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Error 404: Database not Found!" . $conn->connect_error);
}


?>