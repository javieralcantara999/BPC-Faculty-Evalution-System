<?php
include 'db_connect.php';

// Assuming you have a function to fetch data from the database
function getSentimentData() {
    global $conn;
    $data = array('positive' => array(), 'negative' => array(), 'neutral' => array());

    $positiveQuery = "SELECT * FROM sentiment_terms WHERE term_type = 'Positive'";
    $positiveResult = $conn->query($positiveQuery);

    if ($positiveResult) {
        while ($row = $positiveResult->fetch_assoc()) {
            $data['positive'][] = array('word' => $row['term']);
        }
    } else {
        echo 'Error executing positive query: ' . $conn->error;
    }

    $negativeQuery = "SELECT * FROM sentiment_terms WHERE term_type = 'Negative'";
    $negativeResult = $conn->query($negativeQuery);

    if ($negativeResult) {
        while ($row = $negativeResult->fetch_assoc()) {
            $data['negative'][] = array('word' => $row['term']);
        }
    } else {
        echo 'Error executing negative query: ' . $conn->error;
    }

    $neutralQuery = "SELECT * FROM sentiment_terms WHERE term_type = 'Neutral'";
    $neutralResult = $conn->query($neutralQuery);

    if ($neutralResult) {
        while ($row = $neutralResult->fetch_assoc()) {
            $data['neutral'][] = array('word' => $row['term']);
        }
    } else {
        echo 'Error executing neutral query: ' . $conn->error;
    }

    return $data;
}

// Output JSON format
header('Content-Type: application/json');
echo json_encode(getSentimentData());
?>