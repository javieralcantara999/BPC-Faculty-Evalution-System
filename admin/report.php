<script>
    document.title = "Evaluation Reports (Students) | Administrator";
</script>
<?php

// Include the necessary files
include 'db_connect.php';
$faculty_id = isset($_GET['fid']) ? $_GET['fid'] : '';

// Function to generate ordinal suffix for numbers
function ordinal_suffix($num) {
    $num = $num % 100; // protect against large numbers
    if ($num < 11 || $num > 13) {
        switch ($num % 10) {
            case 1:
                return $num . 'st';
            case 2:
                return $num . 'nd';
            case 3:
                return $num . 'rd';
        }
    }
    return $num . 'th';
}
?>
<h1 class="m-0 text-center"><i class="fas fa-chart-bar"></i>&nbsp;<b>EVALUATION RESULTS (STUDENTS)</b></h1><br><hr>
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
            <div class="callout" style="border:none">
                <div class=" w-10 justify-content-center align-items-center text-center">
                    <label for="faculty" class = "text-center"style = "margin-left:5px;font-size:20px">Select Instructor</label><br>
                    <div class=" mx-0 col-md-12">
                        <select name="" id="faculty_id" class="form-control form-control-sm select2" style = "border:none;width:200px;">
                            <option value=""disabled selected>Please select here...</option>
                            <?php 
                            $faculty = $conn->query("SELECT *, concat(firstname,' ',lastname) 
                            as name FROM faculty_list ORDER BY concat(firstname,' ',lastname) ASC");
                            $f_arr = array();
                            $fname = array();
                            while($row = $faculty->fetch_assoc()):
                                $f_arr[$row['id']] = $row;
                                $fname[$row['id']] = ucwords($row['name']);
                            ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo isset($faculty_id) 
                            && $faculty_id == $row['id'] ? "selected" : "" ?>><?php echo ucwords($row['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                </div>
                <div class="callout" style="border:0px;background:white;">
                    <h5 class="text-center" ><b>Instructor Information</b></h5><hr><br>
                    <div class="text-center">
                        <div id="instructor-avatar"></div> <!-- Display instructor avatar here --><br>
                        <div id="instructor-name" style= "font-weight: bold;font-size:18px;"></div> 
                        <br><hr><!-- Display instructor name here -->
                    </div>
                </div>
            <div class="callout" style="border:0px;">
                <h5 class = "text-center"><b>List of Classes</b></h5><hr>
                <div class="list-group" id="class-list" style = "text-decoration: none">
                </div>
                <hr>
            </div>
            <!-- Feedback Container -->
            <!-- End ng Feedback Container -->
            <script>
                 $(document).ready(function() {
                    $('#faculty_id').change(function() {
                        var facultyId = $(this).val();

                        // Make an AJAX request to fetch instructor information
                        $.ajax({
                            url: 'ajax.php?action=fetch_instructor', // Endpoint to handle the AJAX request
                            method: 'POST',
                            data: { faculty_id: facultyId }, // Send faculty_id as POST data
                            dataType: 'json', // Expect JSON response
                            success: function(response) {
                                if (response.success) {
                                    var instructorInfo = response.data;

                                    // Update the UI with fetched instructor data
                                    // Construct the image tag with the correct source URL
                                    var avatarSrc = 'assets/uploads/' + instructorInfo.avatar;
                                    $('#instructor-avatar').html('<img src="' + avatarSrc + '" alt="Avatar">');

                                    // Display instructor's full name
                                    $('#instructor-name').text(instructorInfo.firstname + ' ' + instructorInfo.lastname);
                                } else {
                                    // Handle error scenario if necessary
                                    console.log('Failed to fetch instructor information');
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle AJAX error
                                console.log('AJAX Error:', error);
                            }
                        });
                    });
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
                    <h4 class="text-center">STUDENTS' EVALUATION REPORT</h4>
                    <h4 class="text-center">SY <?php echo $_SESSION['academic']['year'] ?></h4>
                    <hr>
                    <table width="100%">
                        <tr>
                            <td width="50%"><p><b>Instructor: <span id="fname"></span></b></p></td>
                        </tr>
                        <tr>
                            <td width="50%"><p><b>Section: <span id="classField"></span></b></p></td>
                        </tr>
                            <td width="50%"><p><b>Subject: <span id="subjectField"></span></b></p></td>
                    </table><br>
                    <p class="text-left"><b>No. of Students Evaluated: <span id="tse"></span></b></p>
                </div>
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
                $criteria = $conn->query("SELECT * FROM criteria_list WHERE id IN (SELECT criteria_id FROM 
                question_list WHERE academic_id = {$_SESSION['academic']['id']} ) ORDER BY abs(order_by) ASC ");
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
                $questions = $conn->query("SELECT * FROM question_list WHERE criteria_id = {$crow['id']} 
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
                            Received by: <br><br><br>
                            _____________________________________________
                            <br> (Signature of Faculty)
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
    
    .list-group{
                font-weight: bold;
                border:0px solid red;
                color:black;
            }.list-group-item{
                font-weight: bold;
                background: blue;
                color:white;
            }
            .list-group-item .active{
                font-weight: bold;
                background: darkgreen;
                color:white;
            }
            .list-group-item .active:hover{
                font-weight: bold;
                background: green;
                color:white;
            }
    @media screen and (max-width: 768px) {
        #rt {
            display:flex;
        }
    }
</style>

<script>

$(document).ready(function() {
    $('#faculty_id').change(function() {
        if ($(this).val() > 0) {
            // Update URL to include faculty_id
            window.history.pushState({}, null, './indexx.php?page=report&fid=' + $(this).val());
            // Load class data
            load_class();
        }
    });

    // Trigger load_class() if faculty_id is pre-selected
    if ($('#faculty_id').val() > 0) {
        load_class();
    }
});
    function load_class() {
    start_load();
    $('.rates').text('0');
    $('.rate_total').text('0.00'); 
    $('#tse').text('0');
    $('#print-btn').hide();
    var fname = <?php echo json_encode($fname) ?>;
    $('#fname').text(fname[$('#faculty_id').val()])
    $.ajax({
        url: "ajax.php?action=get_class",
        method: 'POST',
        data: { fid: $('#faculty_id').val() },  // Faculty ID parameter
        error: function(err) {
            console.log(err);
            alert_toast("An error occurred.", "error");
            end_load();
        },
        success:function(resp){
            if(resp){
                computeCriteriaTotalAverage();
                computeCriteriaGeneralAverage();
                resp = JSON.parse(resp)
                if(Object.keys(resp).length <= 0 ){
                    $('#class-list').html('<a href="javascript:void(0)" class="list-group-item list-group-item-action disabled text-center" style = "text-decoration:none">No results found</a>')
                    $('.total_avg').text('0.00');
                    // Reset other elements to default state
                    $('#subjectField').text('');
                    $('#classField').text('');
                    $('.rates').text('0');
                    $('.rate_total').text('0.00'); 
                } else {
                    $('#class-list').html('')
                    Object.keys(resp).map(k=>{
                        $('#class-list').append(' <a href="javascript:void(0)" data-json=\''+JSON.stringify(resp[k])+'\' data-id="'+resp[k].id+'" data-subject-id="'+resp[k].subject_id+'" class="list-group-item list-group-item-action show-result" style = "text-decoration:none;background:whitesmoke;color:black;">'+resp[k].class+' <br> '+resp[k].subj+'</a>')
                    })

                }
            }
        },
        complete:function(){
            end_load()
            anchor_func()
            if('<?php echo isset($_GET['rid']) ?>' == 1){
                $('.show-result[data-id="<?php echo isset($_GET['rid']) ? $_GET['rid'] : '' ?>"]').trigger('click')
            } else {
                $('.show-result').first().trigger('click')
            }
        }
    })
}
    function anchor_func(){
        $('.show-result').click(function(){
            $('.rates').text('0');
            $('.rate_total').text('0.00'); 
            var vars = [], hash;
            var data = $(this).attr('data-json')
                data = JSON.parse(data)
            var _href = location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < _href.length; i++)
                {
                    hash = _href[i].split('=');
                    vars[hash[0]] = hash[1];
                }
            window.history.pushState({}, null, './indexx.php?page=report&fid='+vars.fid+'&rid='+data.id);
            load_report(vars.fid,data.sid,data.id);
            $('#subjectField').text(data.subj)
            $('#classField').text(data.class)
            $('.show-result.active').removeClass('active')
            $(this).addClass('active')
        })
    }
    function load_report(faculty_id, subject_id, class_id) {
    if ($('#preloader2').length <= 0)
        start_load();
    $('.rates').text('0');
    $('.rate_total').text('0.00'); 

    $.ajax({
        url: 'ajax.php?action=get_report',
        method: 'POST',
        data: { faculty_id: faculty_id, subject_id: subject_id, class_id: class_id },
        error: function(err) {
            console.log(err);
            alert_toast("An Error Occurred.", "error");
            end_load();
        },
        success: function(resp) {
            if (resp) {
                resp = JSON.parse(resp);
                if (Object.keys(resp).length <= 0) {
                    // Clear all ratings and display total average
                    $('.rates').text('0');
                    $('.rate_total').text('0.00');
                    $('#tse').text('0');
                    $('#print-btn').hide();
                } else {
                    $('#print-btn').show(); // Show print button
                    $('#tse').text(resp.tse); // Display total number of students evaluated
                    var data = resp.data;
                    Object.keys(data).map(q => {
                        var questionTotalRating = 0;
                        var questionTotalStudents = 0;
                        Object.keys(data[q]).map(r => {
                            $('.rate_'+r+'_'+q).text(data[q][r]); // Display count of ratings
                            questionTotalRating += parseInt(data[q][r]) * parseInt(r);
                            questionTotalStudents += parseInt(data[q][r]);
                        });
                        var questionTotalAverage = questionTotalStudents > 0 ? questionTotalRating / questionTotalStudents : 0;
                        $('.rate_total_'+q).text(questionTotalAverage.toFixed(2)); // Display total average for the question
                    });

                    // Call the function to compute and display criteria total average
                    computeCriteriaTotalAverage();
                    computeCriteriaGeneralAverage();
                }
            } else {
                // If no response, handle it here
                $('.rates').text('0');
                $('.rate_total').text('0.00');
                $('#tse').text('0');
                $('#print-btn').hide();
            }
        },
        complete: function () {
            end_load();
        }
    });
}
$(document).ready(function() {
    $('#print-btn').click(function(){
        start_load();
        var ns = $('noscript').clone();
        var content = $('#printable').html();
        var logoSrc = $('img[src="./assets/uploads/bpc.ico"]').attr('src'); // Get the source of the logo image
        ns.append(content);
        
        // Set the logo source in the cloned content
        ns.find('img[src="./assets/uploads/bpc.ico"]').attr('src', logoSrc); 

        // Remove unwanted lines from the content
        ns.find('p:contains("about:blank")').remove();

        // Open a new window for printing
        var nw = window.open("Report","_blank","width=900,height=700");
        
        // Write the cloned content to the new window
        nw.document.write(ns.html());
        nw.document.close();

        // Ensure the image is loaded before printing
        nw.onload = function() {
            nw.print();
            setTimeout(function(){
                nw.close();
                end_load();
            }, 750);
        }
    }); 
});
function computeCriteriaTotalAverage() {
        $('.table').each(function() {
            var totalRating = 0;
            var totalStudents = 0;
            var $table = $(this);

            // Loop through each tbody row of the table
            $table.find('tbody tr').each(function() {
                var rating = parseFloat($(this).find('.text-center').last().text());
                if (!isNaN(rating)) {
                    totalRating += rating;
                    totalStudents++;
                }
            });

            // Compute the total average for the criteria
            var totalAverage = totalStudents > 0 ? totalRating / totalStudents : 0;
            $table.find('tfoot .text-center span').text(totalAverage.toFixed(2));
        });
    }

    // Function to compute the criteria general average and display badge
    function computeCriteriaGeneralAverage() {
        var totalRating = 0;
        var totalCriteria = 0; // Bagong variable para sa bilang ng mga criteria

        // Loop through each table with the .wborder class
        $('.wborder').each(function() {
            var $table = $(this);
            var criteriaTotalAverage = parseFloat($table.find('tfoot .text-center span').text());
            if (!isNaN(criteriaTotalAverage)) {
                totalRating += criteriaTotalAverage;
                totalCriteria++; // Bilangin ang bawat criteria
            }
        });

        // Compute the total average for all criteria
        var generalAverage = totalCriteria > 0 ? totalRating / totalCriteria : 0;
// Round off the general average to two decimal places
        generalAverage = parseFloat(generalAverage.toFixed(2));
        $('#general_avg').text(generalAverage.toFixed(2)); // Display the rounded general average

        // Add badge based on the rounded general average
        var badgeColor = '';
        var badgeText = '';
        if (generalAverage >= 4.60 && generalAverage <= 5.00) {
            badgeColor = 'badge-success'; // Green badge for Outstanding
            badgeText = 'Outstanding';
        } else if (generalAverage >= 3.60 && generalAverage < 4.60) {
            badgeColor = 'badge-success'; // Green badge for Very Satisfactory
            badgeText = 'Very Satisfactory';
        } else if (generalAverage >= 2.60 && generalAverage <= 3.59) {
            badgeColor = 'badge-secondary'; // Secondary badge for Satisfactory
            badgeText = 'Satisfactory';
        } else if (generalAverage >= 1.60 && generalAverage < 2.60) {
            badgeColor = 'badge-danger'; // Danger badge for Fair
            badgeText = 'Moderately Satisfactory';
        } else if (generalAverage >= 1.00 && generalAverage < 1.60) {
            badgeColor = 'badge-danger'; // Danger badge for Fair
            badgeText = 'Needs Improvement';
        } else {
            badgeColor = 'badge-secondary'; // Default badge
            badgeText = generalAverage;
        } 

        // Append the badge to the general average element
        $('#general_avg').append(' <br> Equivalent: <span style = "font-size:14px"class="badge ' + badgeColor + '">' + badgeText  + '</span>');
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