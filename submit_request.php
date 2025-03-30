<?php
include 'db_connect.php'; // Include database connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $school_id = $_POST['school_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // MD5 hash the password
    $section_name = $_POST['class_id']; // Assuming you have a field for section name in your form

    // Check if all fields are filled
    if (empty($school_id) || empty($firstname) || empty($lastname) || empty($email) || empty($password)
     || empty($section_name)) {
        echo $school_id ,' ';
        echo $firstname ,' ';
        echo $lastname ,' ';
        echo $email ,' ';
        echo $password ,' ';
        echo $section_name ,' ';
        // If any required field is empty, return error
        echo " All fields are required.";
    } else {
        // Handle file upload
        $avatar_name = $_FILES['avatar']['name'];
        $avatar_tmp = $_FILES['avatar']['tmp_name'];
        $avatar_type = $_FILES['avatar']['type'];

        // Check file type
        if ($avatar_type == "image/jpeg" || $avatar_type == "image/png" || $avatar_type == "image/jpg") {
            // Set upload directory
            $avatar_path = "./images/uploads/" . basename($avatar_name);

            // Move uploaded file to specified directory
            if (move_uploaded_file($avatar_tmp, $avatar_path)) {
                // File uploaded successfully, proceed with database insertion
                // Query to retrieve the id based on curriculum, level, and section
                $query = "SELECT id FROM class_list WHERE CONCAT(curriculum, ' ', level, ' - ', section) = '$section_name'";
                $result = $conn->query($query);

                // Check if there is a matching section name
                if ($result->num_rows > 0) {
                    // Retrieve the id from the result
                    $row = $result->fetch_assoc();
                    $class_id = $row['id'];

                    // Insert data into account_request table
                    $insert_query = "INSERT INTO account_request (school_id, firstname, lastname, email, password, class_id, avatar, status) 
                                     VALUES ('$school_id', '$firstname', '$lastname', '$email', '$password', $class_id, '$avatar_path', '$status')";
                    if ($conn->query($insert_query) === TRUE) {
                        // Data inserted successfully, show success message
                        echo "success";
                    } else {
                        // Error occurred while inserting data
                        echo "Error: " . $conn->error;
                    }
                } else {
                    // Section name not found, handle accordingly
                    echo "Section $section_name not found.";
                }
            } else {
                // Error occurred while uploading file
                echo "Error uploading file.";
            }
        } else {
            // Invalid file type
            echo "Invalid file type. Only JPEG and PNG images are allowed.";
        }
    }
}
?>