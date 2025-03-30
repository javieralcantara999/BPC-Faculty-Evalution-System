<script>
    document.title = "Evaluation Results (Superiors) | Faculty Member";
</script>
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
<?php
// Check if user is logged in
if (isset($_SESSION['login_id'])) {
    $user_id = $_SESSION['login_id']; // Use the logged-in user's ID from session

    // Modify your SQL query to fetch data based on the logged-in user
    $faculty_query = $conn->prepare("SELECT id, CONCAT(firstname, ' ', lastname) AS name, avatar FROM faculty_list WHERE id = ?");
    $faculty_query->bind_param('i', $user_id);
    $faculty_query->execute();
    $faculty_result = $faculty_query->get_result();

    if ($faculty_result->num_rows > 0) {
        $faculty_data = $faculty_result->fetch_assoc();
        $faculty_id = $faculty_data['id'];
        $faculty_name = $faculty_data['name'];
        $faculty_avatar = $faculty_data['avatar'];
    }
}
?>
<h1 class="m-0 text-center"><i class="fas fa-chart-bar"></i>&nbsp;<b>EVALUATION RESULTS (SUPERIORS)</b></h1><br><hr>
<div class="col-lg-11">
    
    
    <style>
        #print-btn {
            background:green;
            color:white;
        }
        #print-btn:hover {
            background:red;
        }
        
        #instructor-avatar img {
            width: 120px; /* Set desired width for the avatar image */
            height: 120px; /* Set desired height for the avatar image */
            border-radius: 100%; /* Optional: Create a circular avatar */
        }
    </style>
    <div class="row">
        <div class="col-md-3">
            <!-- Feedback Container -->
            <!-- End ng Feedback Container -->
            
            <div class="callout" style="border:0px;background:white;">
                    <h5 class="text-center" ><b>Information</b></h5><hr><br>
                    <div class="text-center">
                        <div id="instructor-avatar">
                            <?php if (isset($faculty_avatar)) { ?>
                                <img src="assets/uploads/<?php echo $faculty_avatar; ?>" alt="Avatar">
                            <?php } ?>
                        </div><br>
                        <div id="instructor-name" style="font-weight: bold; font-size: 18px;">
                            <?php echo isset($faculty_name) ? $faculty_name : ''; ?>
                        </div>
                    </div><br><hr>
                </div>
                <script>
            $(document).ready(function() {
                // Check if faculty_id is set (from PHP)
                <?php if (isset($faculty_id)) { ?>
                    var facultyId = <?php echo $faculty_id; ?>;

                    // AJAX request to fetch instructor information
                    $.ajax({
                        url: 'ajax.php?action=fetch_instructor',
                        method: 'POST',
                        data: { faculty_id: facultyId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                var instructorInfo = response.data;
                                var avatarSrc = 'assets/uploads/' + instructorInfo.avatar;
                                $('#instructor-avatar').html('<img src="' + avatarSrc + '" alt="Avatar">');
                                $('#instructor-name').text(instructorInfo.firstname + ' ' + instructorInfo.lastname);
                            } else {
                                console.log('Failed to fetch instructor information');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('AJAX Error:', error);
                            console.log('Status:', status);
                            console.log('XHR:', xhr);
                            alert('An error occurred while fetching instructor data.');
                        }
                    });
                <?php } ?>
            });
            </script>
        </div>
        <div class="col-md-9">
            <div class="callout" id="printable" style="border:0px;">
                <div>
                    <div style="text-align: center;">
                        <img src="./images/bpc.ico" alt="BPC Logo" style="width: 100px; height: 100px;"><br>
                        <h3 class="text-center"><br>
                            <b>BULACAN POLYTECHNIC COLLEGE <br> </b>
                            <h4 class="text-center"><b>FACULTY EVALUATION SYSTEM</b></h4>
                        </h3><br>
                    </div>
                    <h4 class="text-center">SUPERIORS' EVALUATION REPORT</h4>
                    <h4 class="text-center">SY <?php echo $_SESSION['academic']['year']?></h4>
                    <br><hr>
                    <table width="100%">
                        <tr>
                            <td width="50%"><p><b>Faculty Member/Instructor: <span>
                                <?php echo $faculty_name ?>
                            </span></b></p></td>
                        </tr>
                    </table><br>
                    <p class="text-left"><b>No. of Superiors Evaluated: <span id="tse"></span></b></p>
                </div><hr>
                <fieldset class="border p-2 w-90 border-success" style="text-align: center;">
                    <legend class="w-auto" style="font-family: 'Times New Roman', Times, serif;">Rating Scale</legend>
                    <p><b>
                        1 - Rarely <span style="padding-left:25px" id="rtg1"></span>
                        2 - Once in a while <span style="padding-left:25px" id="rtg2"></span>
                        3 - Sometimes <span style="padding-left:25px" id="rtg3"></span>
                        4 - Most of the time <span style="padding-left:25px" id="rtg4"></span>
                        5 - Always <span style="padding-left:25px" id="rtg5"></span>
                    </b></p>
                </fieldset>
                <?php 
                $q_arr = array();   
                $criteria = $conn->query("SELECT * FROM criteria_list_superior WHERE id IN (SELECT criteria_id FROM 
                question_list_superior WHERE academic_id = {$_SESSION['academic']['id']} ) ORDER BY abs(order_by) ASC ");
                while($crow = $criteria->fetch_assoc()):
                ?>  <br>
                <table class="table table-condensed wborder table-bordered">
                <thead>
                <tr class="bg-success" style="background:darkgreen;">
                    <th class="pl-3"><b><?php echo $crow['criteria'] ?></b></th>
                    <th width="5%" class="text-center">1</th>
                    <th width="5%" class="text-center">2</th>
                    <th width="5%" class="text-center">3</th>
                    <th width="5%" class="text-center">4</th>
                    <th width="5%" class="text-center">5</th>
                    <th width="15%"class="text-center">Average</th> <!-- New th for Total Average -->
                </tr>
                </thead>
                <tfoot> <!-- New tfoot for Total Average -->
                    <tr class="bg-white">
                        <td colspan="6" class="text-right"><b>Total Average:</b></td>
                        <td class="text-center"><span name = "total_avg"id="total_avg_<?php echo $crow['id'] ?>">0.00</span></td>
                    </tr>
                </tfoot>
                <tbody class="tr-sortable">
                <?php 
                $questions = $conn->query("SELECT * FROM question_list_superior WHERE criteria_id = {$crow['id']} 
                AND academic_id = {$_SESSION['academic']['id']} ORDER BY abs(order_by) ASC ");
                while($row = $questions->fetch_assoc()):
                $q_arr[$row['id']] = $row;
                ?>
                <tr class="bg-white">
                    <td class="p-1" width="40%">
                        <?php echo $row['question'] ?>
                    </td>
                    <?php for ($c = 1; $c <= 5; $c++): ?>
                    <td class="text-center">
                        <span class="rate_<?php echo $c.'_'.$row['id'] ?> rates"></span>
                    </td>
                    <?php endfor; ?>
                    <td class="text-center"> <!-- New td for Total Average -->
                        <span class="rate_total_<?php echo $row['id'] ?> rates"></span>
                    </td>
                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>    
                        <?php endwhile; ?>
                        <div id=""><br>
                            <h4 class = "text-left"style = "font-size:20px;margin-left:20px;"><i><b>Adjectival Equivalent:</i></b></h4>
                            <p  style = "margin-left:20px"class = "text-left">
                                <b>Outstanding (O)</b> > 4.60 - 5.0 <br>
                                <b>Very Satisfactory (VS)</b> > 3.60 - 4.59 <br>
                                <b>Satisfactory (S)</b> > 2.60 - 3.59 <br>
                                <b>Moderately Satisfactory (MS)</b> > 1.60 - 2.59 <br>
                                <b>Needs Improvement (NI)</b> > 1.00 - 1.59
                            </p>
                        </div><hr>
                        <div id="general-average">
                            <h4 class="text-center">General Weighted Average (GWA)</h4><br>
                            <p class="text-center"><b>GWA: <span id="general_avg">0.00</span></b></p>
                        </div>
                        <hr>
                        <div class="text-left ml-4">
                            <br>
                            Prepared by:<br><br><br>
                            _____________________________________________
                            <br><br> 
                            Noted by:<br><br><br>
                            _____________________________________________
                            <br><br>
                        </div>
                        <hr>
                    </div>
        </div>
                    
</div>
            <div class="row">
                <div class="col-md-12 mb-2">
                    <div class="d-flex justify-content-end w-100">
                        <button class="btn btn-sm bg-gradient-success"
                        style="display:none;font-size:18px;" id="print-btn"><i class="fa fa-file-download"></i> Download as PDF </button>
                    </div>
                </div>
            </div>
    </div>=
</div>
<style>
    .list-group-item:hover{
        color: black !important;
        font-weight: 700 !important;
    }
    @media screen and (max-width: 768px) {
        #rt {
            display:flex;
        }
    }
</style>

<script>
   $(document).ready(function() {
     // Use the faculty_id retrieved from PHP (based on session)
     var facultyId = <?php echo isset($faculty_id) ? $faculty_id : 'null'; ?>;

    if (facultyId !== null) {
        load_report(facultyId); // Call load_report function with the retrieved faculty_id
    }

    $('#print-btn').click(function() {
        start_load();
        var ns = $('noscript').clone();
        var content = $('#printable').html();
        var logoSrc = $('img[src="./assets/uploads/bpc.ico"]').attr('src');

        ns.append(content);
        ns.find('img[src="./assets/uploads/bpc.ico"]').attr('src', logoSrc);

        var nw = window.open("Report", "_blank", "width=900,height=700");
        nw.document.write(ns.html());
        nw.document.close();

        nw.onload = function() {
            nw.print();
            setTimeout(function() {
                nw.close();
                end_load();
            }, 750);
        };
    });
});
function load_report(faculty_id) {
    start_load();
    $('.rates').text('0');
    $('.rate_total').text('0.00');
    $('#tse').text('0');
    $('#print-btn').hide();

    $.ajax({
        url: "ajax.php?action=get_report_superior",
        method: 'POST',
        data: { fid: faculty_id },
        error: function(err) {
            console.log(err);
            alert_toast("An error occurred.", "error");
            end_load();
        },
        success: function(resp) {
            console.log("Response from server:", resp);
            if (resp.total_superiors_evaluated) {
                $('#tse').text(resp.total_superiors_evaluated);
            }
            if (resp) {
                try {
            resp = JSON.parse(resp); // Subukan i-parse ang response bilang JSON
            // Iba pang logic dito...
        } catch (error) {
            console.error("Error parsing JSON:", error);
        }
                if (Object.keys(resp).length <= 0) {
                    $('.rates').text('0');
                    $('.rate_total').text('0.00');
                    $('#tse').text('0');
                    $('#print-btn').hide();
                } else {
                    $('#print-btn').show();
                    $('#tse').text(resp.tse);

                    Object.keys(resp.data).forEach(function(q) {
                        var questionTotalRating = 0;
                        var questionTotalSuperior = 0;

                        Object.keys(resp.data[q]).forEach(function(r) {
                            var rating = parseInt(r);
                            var count = parseInt(resp.data[q][r]);

                            // Update rating count for each scale
                            $('.rate_' + rating + '_' + q).text(count);

                            // Calculate total rating for the question
                            questionTotalRating += rating * count;
                            questionTotalSuperior += count;
                        });

                        // Calculate average rating for the question
                        var questionTotalAverage = questionTotalSuperior > 0 ? questionTotalRating / questionTotalSuperior : 0;
                        $('.rate_total_' + q).text(questionTotalAverage.toFixed(2));
                    });

                    // Compute total and general averages
                    computeCriteriaTotalAverage();
                    computeCriteriaGeneralAverage();
                }
            } else {
                // Handle empty or invalid response
                $('.rates').text('0');
                $('.rate_total').text('0.00');
                $('#tse').text('0');
                $('#print-btn').hide();
            }
        },
        complete: function() {
            end_load();
        }
    });

}

function computeCriteriaTotalAverage() {
    $('.table').each(function() {
        var totalRating = 0;
        var totalStudents = 0;
        var $table = $(this);

        $table.find('tbody tr').each(function() {
            var rating = parseFloat($(this).find('.text-center').last().text());
            if (!isNaN(rating)) {
                totalRating += rating;
                totalStudents++;
            }
        });

        var totalAverage = totalStudents > 0 ? totalRating / totalStudents : 0;
        $table.find('tfoot .text-center span').text(totalAverage.toFixed(2));
    });
}

function computeCriteriaGeneralAverage() {
    var totalRating = 0;
    var totalCriteria = 0;

    $('.wborder').each(function() {
        var $table = $(this);
        var criteriaTotalAverage = parseFloat($table.find('tfoot .text-center span').text());
        if (!isNaN(criteriaTotalAverage)) {
            totalRating += criteriaTotalAverage;
            totalCriteria++;
        }
    });

    var generalAverage = totalCriteria > 0 ? totalRating / totalCriteria : 0;
    generalAverage = parseFloat(generalAverage.toFixed(2));
    $('#general_avg').text(generalAverage.toFixed(2));

    var badgeColor = '';
    var badgeText = '';
    if (generalAverage >= 4.60 && generalAverage <= 5.00) {
        badgeColor = 'badge-success';
        badgeText = 'Outstanding';
    } else if (generalAverage >= 3.60 && generalAverage < 4.60) {
        badgeColor = 'badge-success';
        badgeText = 'Very Satisfactory';
    } else if (generalAverage >= 2.60 && generalAverage <= 3.59) {
        badgeColor = 'badge-secondary';
        badgeText = 'Satisfactory';
    } else if (generalAverage >= 1.60 && generalAverage < 2.60) {
        badgeColor = 'badge-danger';
        badgeText = 'Moderately Satisfactory';
    } else if (generalAverage >= 1.00 && generalAverage < 1.60) {
        badgeColor = 'badge-danger';
        badgeText = 'Needs Improvement';
    } else {
        badgeColor = 'badge-secondary';
        badgeText = generalAverage;
    }

    $('#general_avg').append('<br> Equivalent: <span style="font-size:14px" class="badge ' + badgeColor + '">' + badgeText  + '</span>');
}
</script>
<style>
    /* CSS for hiding title when printing */
    @media print {
        .print-title {
            display: none;
        }
    }
</style>
<style>
                .feedbacks-container {
                    margin-top: 20px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    padding: 10px;
                    background-color: #f9f9f9;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }
                
                .feedback {
                    border-bottom: 1px solid #ddd;
                    padding-bottom: 10px;
                    margin-bottom: 10px;
                }
                
                .feedback:last-child {
                    border-bottom: none;
                    margin-bottom: 0;
                    padding-bottom: 0;
                }
                
                .feedback .student-name {
                    font-weight: bold;
                    color: #333;
                }
                
                .feedback .comment {
                    color: #666;
                    margin-top: 5px;
                }
                
                .feedback .date {
                    font-size: 12px;
                    color: #888;
                }
            </style>
            <script>
            </script>
            <style>
                /* Add your CSS styles for the feedbacks modal here */
                #feedbacks-modal .modal-dialog {
                    max-width: 600px;
                }

                #feedbacks-modal .modal-content {
                    padding: 20px;
                }

                #feedbacks-modal .modal-body ul {
                    list-style-type: disc;
                    padding-left: 20px;
                }

                #feedbacks-modal .modal-body p {
                    margin-bottom: 10px;
                }
            </style>
            <noscript>
    <style>
        table{
            width:100%;
            border-collapse: collapse;
        }
        table.wborder tr,table.wborder td,table.wborder th{
            border:1px solid green;
            padding: 3px
        }
        table.wborder thead tr{
            background: green;
            color: #fff;
        }
        .text-center{
            text-align:center;
        } 
        .text-right{
            text-align:right;
        } 
        .text-left{
            text-align:left;
        } 
    </style>
</noscript> 