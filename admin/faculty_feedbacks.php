<script>
    document.title = "Feedbacks (Students) | Administrator";
    </script>
    
<?php include 'db_connect.php'; ?>
        <h1 class="m-0 text-center"><i class="fas fa-comments"></i>&nbsp;<b>FEEDBACKS (STUDENTS)</b></h1><br>
<div class="container-fluid">
        <hr>
        <div class="row justify-content-center align-items-center text-center">
            <div class="col-md-5"style = "background: green;color:white;border-radius:5px;"><br>
                <div class="container" > <!-- Enclose the select instructor section in a container -->
                    <label for="faculty" class="text-center" style="font-size: 20px;">Select Instructor / Faculty Member</label>
                    <select name="faculty_id" id="faculty_id" class="form-control form-control-sm" style="font-size: 18px;">
                        <option class = "text-center"style="color:gray;"value="" disabled selected>Please select here...</option>
                        <?php 
                        $faculty = $conn->query("SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM faculty_list ORDER BY firstname ASC");
                        while ($row = $faculty->fetch_assoc()) :
                        ?>
                            <option value="<?php echo $row['id'] ?>"><?php echo ucwords($row['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <br>
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
        <br>
        
        <div class="card">
                        <br>
            <div class="row justify-content-center align-items-center text-center">
                <div class="col-md-4 offset-md-4"> <!-- Use offset to push the search section to the right -->
                    <div class="form-group">
                        <label for="searchComments" class="text-right" style="font-size: 18px;">Filter Comment</label>
                        <input type="text" id="searchComments" class="form-control form-control-sm" 
                        style = "width: 100%" placeholder="Please type to search..">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered" style = "background:whitesmoke"id="feedbacksTable">
                    <thead style = "background-color: lightgrey;">
                        <tr>
                            <th class="text-center" width="5%">No.</th>
                            <th class="text-left" width="95%">Comments</th>
                        </tr>
                    </thead>
                    <tbody id="feedbacksBody">
                        <!-- Table rows will be populated via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        
     $(document).ready(function() {
    $('#faculty_id').change(function() {
        var facultyId = $(this).val();
        fetchFeedbacks(facultyId);
        fetchSentimentTerms(facultyId); 
    });
    function fetchSentimentTerms(facultyId) {
        if (!facultyId) {
            return; // Huwag ituloy kung walang napiling faculty
        }

        $.ajax({
            url: 'ajax.php',
            type: 'GET',
            data: { action: 'get_sentiment_terms', faculty_id: facultyId },
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
    $('#searchComments').on('input', function() {
        var searchText = $(this).val().toLowerCase();
        filterComments(searchText);
    });

    // Initial load without faculty selection
    displayFeedbacks([]); // Display empty feedbacks initially

    function fetchFeedbacks(facultyId) {
    if (!facultyId) {
        // Reset sentiment analysis table if no faculty selected
        $('#positiveCount').text('0');
        $('#negativeCount').text('0');
        $('#neutralCount').text('0');
        $('#result').removeClass('bg-success bg-danger bg-secondary').text('');

        displayFeedbacks([]); // Display empty feedbacks
        return;
    }

    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        data: { action: 'get_feedbacks', faculty_id: facultyId },
        dataType: 'json',
        success: function(response) {
            displayFeedbacks(response); // Display feedbacks based on selected faculty
            updateSentimentAnalysis(response); // Update sentiment analysis based on feedbacks
        },
        error: function(error) {
            console.error('Error fetching feedbacks:', error);
        }
    });
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

        // Display "No feedbacks found" message if no visible results and search text is not empty
        if (!hasVisibleResults && searchText.trim() !== '') {
            var html = '<tr><td colspan="2" class="text-center">No feedbacks found.</td></tr>';
            $('#feedbacksBody').html(html);
        } else {
            // If search bar is cleared, fetch all feedbacks for the selected instructor
            if (searchText.trim() === '') {
                var facultyId = $('#faculty_id').val();
                fetchFeedbacks(facultyId);
            }
        }
    }
});
    </script>