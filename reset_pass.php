<?php
include_once "./db_connect.php";

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $password = $_POST['sign_password'];

        $encryptedPassword = md5($password);
        $blank = '';

        $sql = "UPDATE student_list SET password = '$encryptedPassword' WHERE email = '$email' AND reset_token = '$token'";
        $sql2 = "UPDATE student_list SET `reset_token` = '$blank' WHERE `email` = '$email' AND `reset_token` = '$token'";

        if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
            echo json_encode(array('success' => true));
            exit;
        } else {
            echo json_encode(array('success' => false, 'error' => 'Error updating password: ' . $conn->error));
            exit;
        }
    }
} else {
    echo json_encode(array('success' => false, 'error' => 'Email and token parameters are missing in the URL.'));
    exit;
}
?>