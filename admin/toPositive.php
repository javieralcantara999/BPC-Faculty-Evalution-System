<?php
if(isset($_GET['term_id'])) {
    include 'db_connect.php';  // Siguruhing tama ang pangalan ng database connection file
    $term_id = $_GET['term_id'];
    $update_query = "UPDATE sentiment_terms SET term_type = 'Positive' WHERE term_id = $term_id";
    
    if ($conn->query($update_query) === TRUE) {
        header("Location: indexx.php");  // I-redirect sa main page matapos ang pag-update
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>