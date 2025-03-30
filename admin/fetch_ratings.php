<?php
include 'db_connect.php'; // Database connection script

if (isset($_POST['faculty_id'])) {
    $facultyId = $_POST['faculty_id'];

    // Query para kunin ang ratings mula sa database base sa $facultyId
    $query = "SELECT * FROM evaluation_answers_superior WHERE faculty_id = $facultyId";
    $result = $conn->query($query);

    if ($result) {
        $ratingsData = array();

        // Kumpletuhin ang array ng ratings mula sa query result
        while ($row = $result->fetch_assoc()) {
            // Dito mo isama ang mga ratings na kailangan mo mula sa result set
            // Halimbawa, kung mayroon kang 'rating' field sa result:
            $ratingsData[] = $row['rating'];
        }

        // Bilangin ang bilang ng superiors na nag-evaluate
        $queryCount = "SELECT COUNT(DISTINCT superior_id) as total_superiors FROM evaluation_list_superior WHERE faculty_id = $facultyId";
        $resultCount = $conn->query($queryCount);
        $rowCount = $resultCount->fetch_assoc();
        $totalSuperiorsEvaluated = $rowCount['total_superiors'];

        // Kumuha ng pangalan ng instructor
        $queryInstructor = "SELECT CONCAT(firstname, ' ', lastname) AS name FROM faculty_list WHERE id = $facultyId";
        $resultInstructor = $conn->query($queryInstructor);
        $rowInstructor = $resultInstructor->fetch_assoc();
        $instructorName = $rowInstructor['name'];

        // Pagkatapos ng query, i-prepare ang response data
        $response = array(
            'success' => true,
            'ratings' => $ratingsData, // Array ng ratings mula sa database
            'tse' => $totalSuperiorsEvaluated,
            'instructorName' => $instructorName // Pangalan ng instructor
        );

        echo json_encode($response);
    } else {
        // Failed to execute query
        echo json_encode(array('success' => false, 'message' => 'Failed to fetch ratings'));
    }
} else {
    // Invalid request
    echo json_encode(array('success' => false, 'message' => 'Invalid request'));
}
?>