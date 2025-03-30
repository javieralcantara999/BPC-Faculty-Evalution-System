<?php
session_start();
include('./db_connect.php');

$email = $_POST['email'];

$stmt = $conn->prepare("SELECT * FROM student_list WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo 'valid'; // Email exists
} else {
    echo 'invalid'; // Email does not exist
}

$stmt->close();
$conn->close();
?>