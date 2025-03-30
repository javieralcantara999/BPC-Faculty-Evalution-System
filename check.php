<?php
// Include the database connection file
include 'db_connect.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if the email exists in the student_list table
    $query = $conn->query("SELECT id, status FROM student_list WHERE email = '$email'");
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        if ($row['status'] == 0) {
            echo 'existing';
        } else {
            echo 'not_existing';
        }
    } else {
        // Check if the email exists in the account_request table
        $query1 = $conn->query("SELECT * FROM account_request WHERE email = '$email'");
        if ($query1->num_rows > 0) {
            echo 'existing';
        } else {
            // Check if the email exists in the users table
            $query2 = $conn->query("SELECT * FROM users WHERE email = '$email'");
            if ($query2->num_rows > 0) {
                echo 'existing';
            } else {
                echo 'not_existing';
            }
        }
    }
}
?>
<?php
// Include the database connection file
include 'db_connect.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if the school ID exists in the student_list table
    $query = $conn->query("SELECT id, status FROM student_list WHERE email = '$email'");
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        if ($row['status'] == 0) {
            echo 'existing';
        } else {
            echo 'not_existing';
        }
    } else {
        // Check if the school ID exists in the account_request table
        $query1 = $conn->query("SELECT * FROM account_request WHERE email = '$email'");
        if ($query1->num_rows > 0) {
            echo 'existing';
        } else {
            echo 'not_existing';
        }
    }
}
?>