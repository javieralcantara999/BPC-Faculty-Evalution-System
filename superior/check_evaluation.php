<?php
// Include your database connection file
include 'db_connect.php';

// Check if selectedFacultyId is set in the POST data
if (isset($_POST['selectedFacultyId'])) {
    $selectedFacultyId = $_POST['selectedFacultyId'];
    
    // Ensure that the session variable is set and not empty
    if (isset($_SESSION['login_id']) && !empty($_SESSION['login_id'])) {
        $superiorId = $_SESSION['login_id'];
        
        // Query to check if the faculty member has been evaluated by the superior
        $check_evaluation_query = "SELECT CONCAT(firstname, ' ', lastname) AS name
                                   FROM evaluation_list_superior
                                   INNER JOIN faculty_list ON evaluation_list_superior.faculty_id = faculty_list.id
                                   WHERE faculty_list.id = $selectedFacultyId
                                         AND evaluation_list_superior.superior_id = $superiorId";

        // Execute the query
        $check_evaluation_result = $conn->query($check_evaluation_query);

        // Check if the query was successful
        if ($check_evaluation_result) {
            // Check if there is a result
            if ($check_evaluation_result->num_rows > 0) {
                // Faculty member has been evaluated by the superior
                $evaluatedFacultyName = ucwords($check_evaluation_result->fetch_assoc()['name']);
                echo json_encode(['evaluated' => true, 'facultyName' => $evaluatedFacultyName]);
            } else {
                // Faculty member has not been evaluated by the superior
                echo json_encode(['evaluated' => false]);
            }
        } else {
            // Error in executing the query
            echo json_encode(['error' => 'Query execution failed: ' . $conn->error]); // Add more details for debugging
        }
    } else {
        // Session variable not set or empty
        echo json_encode(['error' => 'Invalid session']);
    }
} else {
    // selectedFacultyId not set in the POST data
    echo json_encode(['error' => 'selectedFacultyId is required']);
}
?>