<?php
if(isset($_POST['input'])) {
    include 'db_connect.php';
    $input = $_POST['input'];
    $sql = "SELECT term_id, term FROM sentiment_terms WHERE term_type = 'Neutral' AND term LIKE '{$input}%' ORDER BY term";
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
                <a href='./toPositive.php?term_id=$term_id' class='positive'><i class='fas fa-plus'></i><span> Positive</span></a>
                <a href='./toNegative.php?term_id=$term_id' class='negative'><i class='fas fa-minus'></i><span> Negative</span></a>
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