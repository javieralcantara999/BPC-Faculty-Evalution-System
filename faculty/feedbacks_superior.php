<?php include 'db_connect.php'; ?>

<?php 
function ordinal_suffix1($num){
    $num = $num % 100; // protect against large numbers
    if($num < 11 || $num > 13){
         switch($num % 10){
            case 1: return $num.'st';
            case 2: return $num.'nd';
            case 3: return $num.'rd';
        }
    }
    return $num.'th';
}
$astat = array("Not Yet Started","On-going","Closed");

$academic_info_query = $conn->query("SELECT year, semester, status,restriction FROM academic_list WHERE id = ".$_SESSION['academic']['id']);
$academic_info = $academic_info_query->fetch_assoc();
$year = $academic_info['year'];
$semester = $academic_info['semester'];
$academic_status = $academic_info['status'];
$academic_status_text = $astat[$academic_status];
$restriction = $academic_info['restriction'];

?>
<script>
    $(document).ready(function() {
        // Check the academic status variable obtained from PHP
        var resctrict = <?php echo $restriction; ?>;

        // Check if academic status is 1 (cannot view results yet)
        if (resctrict === 0) {
            // Display SweetAlert notification
            Swal.fire({
                title: 'Announcement',
                text: 'You cannot view the results yet. Please wait for the announcements.',
                icon: 'info',
                confirmButtonText: 'OK'
            }).then((result) => {
                // After user clicks OK, redirect to the homepage
                if (result.isConfirmed || result.isDismissed) {
                    window.location.href = './indexx.php';
                }
            });
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.title = "Superior Feedbacks | Faculty Member";
</script>
<h1 class="m-0 text-center"><i class="fas fa-comments"></i>&nbsp;<b> SUPERIOR FEEDBACKS </b></h1><br>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<div class="container-fluid">
    <hr>
    <div class="row justify-content-center align-items-center text-center">
        <?php
        // Kunin ang impormasyon ng faculty na naka-login
        $faculty_id = isset($_SESSION['login_id']) ? $_SESSION['login_id'] : null;
        $faculty_info = $conn->query("SELECT * FROM faculty_list WHERE id = $faculty_id")->fetch_assoc();
        ?>
        <div class="card"style = "border-radius:20px;" >
            <div class="card-body justify-content-center align-items-center" style="background:white;color:black;border-radius:10px;border:5px solid darkgreen;">
            <h5 class="text center" style = "font-size:25px"></h5>
            <br>
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-10 text-center">
                        <img src="assets/uploads/<?php echo $faculty_info['avatar']; ?>" alt="Faculty Avatar"
                         class="img-fluid" style="width: 150px; height: 150px;border-radius:5px;border:5px solid black;">
                    </div><br>
                    <div class="col-md-9"><br>
                        <h4><b><?php echo ucwords($faculty_info['firstname'] . ' ' . $faculty_info['lastname']); ?></h4></b>
                        <hr style="background-color:black;border:2px solid darkgreen;">
                    </div>
                </div>
            </div>
        </div>
    </div><hr>
        <div class="container-fluid">
    <h2 class="text-center">Sentiment Terminologies Data</h2><hr>
    <div class="row justify-content-first">
        <div class="col-md-4">
            <table class="table table-bordered table-success">
                <thead>
                    <tr>
                        <th class = "text-center"width = "50%">Sentiment Type</th>
                        <th class = "text-center"width = "50%">No. of Words</th>
                    </tr>
                </thead>
                <tbody id="sentimentAnalysisBody" class = "text-center">
                    <tr>
                        <td>Positive</td>
                        <td><span style = "font-size:16px;" class="badge bg-success" id="positiveCount">0</span></td>
                    </tr>
                    <tr>
                        <td>Negative</td>
                        <td><span style = "font-size:16px;" class="badge bg-danger" id="negativeCount">0</span></td>
                    </tr>
                    <tr>
                        <td>Neutral</td>
                        <td><span style = "font-size:16px;" class="badge bg-secondary" id="neutralCount">0</span></td>
                    </tr>
                </tbody>
                        <td colspan="2" class="text-center">Performance Feedback Result: 
                            <span class="badge" style = "font-size:16px;" id="result">
                          
                        </span>
                    </td>
            </table>
        </div>
        <div class="col-md-8">
            <div class="row justify-content-first">
                <div class="col-md-12">
                    <table class="table table-bordered table-success">
                        <thead>
                            <tr>
                                <th width="10%">Type</th>
                                <th>Terms/Words</th>
                            </tr>
                        </thead>
                        <tbody id="sentimentTermsBody">
                            <tr>
                                <td>Positive</td>
                                <td id="positiveTerms"></td>
                            </tr>
                            <tr>
                                <td>Negative</td>
                                <td id="negativeTerms"></td>
                            </tr>
                            <tr>
                                <td>Neutral</td>
                                <td id="neutralTerms"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div><hr>
    <div class="card" style = "border:1px solid darkgreen;">
        <div class="card-body">
            <div class="row justify-content-center align-items-center text-center">
                <div class="col-md-4 offset-md-4">
                    <div class="form-group">
                        <i class = "fas fa-search"></i> &nbsp;<label for="searchComments" class="text-right" style="font-size: 18px;">Filter Comment</label>
                        <input type="text" id="searchComments" class="form-control form-control-sm" style="width: 100%" placeholder="Please type to search..">
                    </div>
                </div>
            </div>
            <table class="table table-hover table-bordered" style = "background:whitesmoke"id="feedbacksTable">
                    <thead style = "background-color: lightgrey;">
                    <tr>
                        <th class="text-center" width="5%">No.</th>
                        <th class="text-left" width="95%">Comments</th>
                    </tr>
                </thead>
                <tbody id="feedbacksBody">
                    <?php
                    $query = "SELECT *,comments FROM evaluation_comments_superior WHERE faculty_id = $faculty_id";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $count = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td class="text-center">' . $count . '</td>';
                            echo '<td>' . $row['comments'] . '</td>';
                            echo '</tr>';
                            $count++;
                        }
                    } else {
                        echo '<tr><td colspan="2" class="text-center">No feedbacks found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        
        var facultyId = '<?php echo isset($_SESSION["login_id"]) ? $_SESSION["login_id"] : ""; ?>';
            fetchFeedbacks(facultyId);
            fetchSentimentTerms(facultyId);
        $('#searchComments').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            filterComments(searchText);
        });
            function fetchSentimentTerms(facultyId) {
            if (!facultyId) {
                return; // Huwag ituloy kung walang napiling faculty
            }

            $.ajax({
                url: 'ajax.php',
                type: 'GET',
                data: { action: 'get_sentiment_terms_superior', faculty_id: facultyId },
                dataType: 'json',
                success: function(response) {
                    displaySentimentTerms(response); // Ipakita ang sentiment terms
                },
            error: function(error) {
                console.error('Error fetching sentiment terms:', error);
            }
        });
    }
    
    function displaySentimentTerms(termsData) {
    // I-update ang UI para ipakita ang sentiment terms
    updateTermDisplay('positiveTerms', termsData.positive, 'success'); // Display positive terms with success badge
    updateTermDisplay('negativeTerms', termsData.negative, 'danger'); // Display negative terms with danger badge
    updateTermDisplay('neutralTerms', termsData.neutral, 'secondary'); // Display neutral terms with secondary badge
}
function displayFeedbacks(feedbacks) {
    var filteredFeedbacks = feedbacks.filter(function(feedback) {
        return feedback.comments.trim() !== ''; // Filter out feedbacks with empty or blank comments
    });

    var html = '';
    if (filteredFeedbacks.length > 0) {
        $.each(filteredFeedbacks, function(index, feedback) {
            html += '<tr>';
            html += '<td class="text-center">' + (index + 1) + '</td>';
            html += '<td>' + feedback.comments + '</td>';
            html += '</tr>';
        });
    } else {
        html = '<tr><td colspan="2" class="text-center">No feedbacks found.</td></tr>';
    }
    $('#feedbacksBody').html(html);

    // Update sentiment analysis based on feedbacks
    updateSentimentAnalysis(filteredFeedbacks);
}
function updateTermDisplay(elementId, terms, badgeClass) {
    // I-clear ang content ng element
    $('#' + elementId).empty();

    // Iterate through each term and display with badge
    terms.forEach(function(term, index) {
        // I-create ang badge element
        var badge = '<span class="badge bg-' + badgeClass + '">' + term + '</span>';

        // I-append ang badge sa element
        $('#' + elementId).append(badge);

        // Add space between terms, but no comma needed
        if (index < terms.length - 1) {
            $('#' + elementId).append(' '); // Add space between terms
        }
    });
}
        // Initial load without faculty selection
        displayFeedbacks([]); // Display empty feedbacks initially

        function fetchFeedbacks(facultyId) {
            if (!facultyId) {
                // Kung walang facultyId, gamitin ang faculty_id ng naka-log in
                facultyId = '<?php echo $faculty_id; ?>';
            }

            $.ajax({
                url: 'ajax.php',
                type: 'GET',
                data: { action: 'get_feedbacks_superior', faculty_id: facultyId },
                dataType: 'json',
                success: function(response) {
                    updateSentimentAnalysis(response); // I-update ang sentiment analysis base sa feedbacks
                    displayFeedbacks(response); // Display feedbacks based on selected faculty
                },
                error: function(error) {
                    console.error('Error fetching feedbacks:', error);
                }
            });
        }

        function filterComments(searchText) {
            var hasVisibleResults = false;

            $('#feedbacksBody tr').each(function() {
                var commentText = $(this).find('td:nth-child(2)').text().toLowerCase();
                var isVisible = commentText.includes(searchText);
                $(this).toggle(isVisible);

                if (isVisible) {
                    hasVisibleResults = true;
                }
            });

            if (!hasVisibleResults && searchText.trim() === '') {
                // If no results and search bar is blank, fetch feedbacks again for the selected instructor
                var facultyId = $('#faculty_id').val();
                fetchFeedbacks(facultyId);
            }
        }
        function updateSentimentAnalysis(feedbacks) {
    let positiveCount = 0;
    let negativeCount = 0;
    let neutralCount = 0;

    // Iterate through feedbacks to count positive, negative, and neutral words
    feedbacks.forEach(function(feedback) {
        positiveCount += parseInt(feedback.positive_count);
        negativeCount += parseInt(feedback.negative_count);
        neutralCount += parseInt(feedback.neutral_count);
    });

    // Update counts in the table
    $('#positiveCount').text(positiveCount);
    $('#negativeCount').text(negativeCount);
    $('#neutralCount').text(neutralCount);

    // Determine result and update badge and result text
    let result = positiveCount - negativeCount;
    let resultBadge = '';

    if (result > 0) {
        resultBadge = 'Positive';
        $('#result').addClass('bg-success').removeClass('bg-danger bg-secondary').text(resultBadge);
    } else if (result < 0) {
        resultBadge = 'Negative';
        $('#result').addClass('bg-danger').removeClass('bg-success bg-secondary').text(resultBadge);
    } else {
        resultBadge = 'Neutral';
        $('#result').addClass('bg-secondary').removeClass('bg-success bg-danger').text(resultBadge);
    }
}
    });
    
</script>
