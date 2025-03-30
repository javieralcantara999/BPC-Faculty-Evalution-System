<?php
if(isset($_POST['input'])) {
    include 'db_connect.php';  // Siguruhing tama ang pangalan ng database connection file
    $input = $_POST['input'];
    $sql = "SELECT term_id, term FROM sentiment_terms WHERE term_type = 'Positive' AND term LIKE '{$input}%' ORDER BY term";
    $result = $conn->query($sql);
    $count = $result->num_rows;
    while ($row = $result->fetch_assoc()) {
        $term_id = $row["term_id"];
        $term = $row["term"];
        echo "
        <tr>
            <td>$term_id</td>
            <td>$term</td>
            <td>
                <a href='./toNegative.php?term_id=$term_id' class='negative'><i class='fas fa-minus'></i><span> Negative</span></a>
                <a href='./toNeutral.php?term_id=$term_id' class='neutral'><i class='fas fa-genderless'></i><span> Neutral</span></a>
            </td>
        </tr>
        ";
    }
    if ($count == 0) {
        echo "
        <tr>
            <td></td>
            <td colspan='2'>No results found.</td>
        </tr>
        ";
    }
}
?>