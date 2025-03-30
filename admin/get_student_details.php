<?php
// Include database connection
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['school_id'])) {
    $school_id = $_POST['school_id'];

    // Prepare and execute a query to get student details based on school ID
    $query = "SELECT * FROM student_list WHERE school_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $school_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    // Return student details as JSON response
    if ($student) {
        echo json_encode($student);
    } else {
        echo json_encode(['error' => 'Student not found']);
    }
}
?>