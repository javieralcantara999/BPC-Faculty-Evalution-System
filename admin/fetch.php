<?php
// Include the database connection file
include 'db_connect.php';

// Check if the school_id parameter is set in the POST request
if (isset($_POST['school_id'])) {
    $school_id = $_POST['school_id'];

    // Prepare and execute a query to fetch student data based on the school ID
    $stmt = $conn->prepare("SELECT firstname, lastname, email, avatar FROM student_list WHERE school_id = ?");
    $stmt->bind_param("s", $school_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a student record is found
    if ($result->num_rows > 0) {
        // Fetch student data
        $row = $result->fetch_assoc();

        // Prepare response data as an associative array
        $response = [
            'success' => true,
            'data' => [
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'email' => $row['email'],
                'avatar' => $row['avatar']
            ]
        ];
    } else {
        // No student found with the provided school ID
        $response = [
            'success' => false,
            'message' => 'Student not found'
        ];
    }

    // Close prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request (school_id parameter not provided)
    $response = [
        'success' => false,
        'message' => 'Invalid request'
    ];
}

// Output response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>