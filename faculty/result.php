<script>
    document.title = "Evaluation Results (Students) | Faculty Member";
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
$faculty_id = isset($_SESSION['login_id']) ? $_SESSION['login_id'] : null;

// Check if faculty_id is set
if($faculty_id) {
    // Include database connection
    include './db_connect.php';
    
    // Query to fetch evaluation results for the faculty member
    $query = "SELECT * FROM evaluation_list WHERE faculty_id = $faculty_id";
    $result = mysqli_query($conn, $query);

    // Check if there are results
    if(mysqli_num_rows($result) > 0) {
        // Loop through results and display them
        while($row = mysqli_fetch_assoc($result)) {
            // Display evaluation results here
            // You can use $row to access individual evaluation results
        }
    } else {
        // No evaluation results found
        echo "No evaluation results found.";
    }

    // Get the current active academic year and semester
    $current_academic_query = $conn->query("SELECT year, semester FROM academic_list WHERE is_default = 1");
    $current_academic = $current_academic_query->fetch_assoc();
    $year = $current_academic['year'];
    $semester = $current_academic['semester'];

    // Close database connection
    mysqli_close($conn);
} else {
    // If faculty_id is not set in the session, prompt user to login
    echo "Please login to view evaluation results.";
}

function ordinal_suffix($num) {
    $num = $num % 100; // protect against large numbers
    if ($num < 11 || $num > 13) {
        switch ($num % 10) {
            case 1: return $num.'st';
            case 2: return $num.'nd';
            case 3: return $num.'rd';
        }
    }
    return $num.'th';
}
?>
<h1 class="m-0 text-center"><i class = "fas fa-chart-bar"></i>&nbsp;<b>EVALUATION RESULTS (STUDENTS)</b></h1><br><hr>
<div class="col-lg-12">
	<div class="row">
		<div class="col-md-3">
			<div class="callout" style = "border:0px;">
            <h5 class="text-center"><b>List of Subjects</b></h5><hr>
				<div class="list-group" id="class-list"style ="background:darkgreen;text-decoration:none;">
					
				</div>
                <hr>
			</div>
            <div class="callout" style="border:0px;background:white;">
                    <h5 class="text-center" ><b>Instructor Information</b></h5><hr> <br>
                    <div class="text-center">
                        <div id="instructor-avatar"><?php 
                            include './db_connect.php';
                            $q = "SELECT *,avatar,firstname,lastname FROM `faculty_list` WHERE `id` = $faculty_id";
                            $r = mysqli_query($conn,$q);
                            $row = mysqli_fetch_array($r);
                            ?> <img src = "assets/uploads/<?php echo $row['avatar'];?>" style = "height:150px;width:150px;border-radius:50%;">
                            </div> <!-- Display instructor avatar here --><br>
                        <div id="instructor-name" style= "font-weight: bold;font-size:18px;">
                            <?php echo $row['firstname'] , " ", $row['lastname'];?></div> <!-- Display instructor name here -->
                        </div>
                        <hr>
                </div>
            <!-- <div class="callout mt-4 p-3">
                <h5 class = "text-center"><b>Student Feedbacks</b></h5>
                <div id="feedbacks-container">
                <div class="d-flex justify-content-center w-100">
                    <button class="btn btn-sm btn-success bg-gradient-success" id="feedbacks-btn"><i class="fa fa-comment"></i> View Feedbacks</button>
                </div>
                </div>
            </div>   -->
		</div>
        
		<div class="col-md-9">
			<div class="callout" id="printable"style = "border:0px;">
			
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
                            <td width="50%"><p><b>Instructor: <span id="fname"><?php 
                            include './db_connect.php';
                            $q = "SELECT *,firstname,lastname FROM `faculty_list` WHERE `id` = $faculty_id";
                            $r = mysqli_query($conn,$q);
                            $row = mysqli_fetch_array($r);

                            echo $row['firstname'] , " ", $row['lastname'];
                            
                            ?></span></b></p></td>
                        </tr>
                        <tr>
                            <td width="50%"><p><b>Section: <span id="classField"></span></b></p></td>
                        </tr>
                            <td width="50%"><p><b>Subject: <span id="subjectField"></span></b></p></td>
                    </table><br>
                    <p class="text-left"><b>No. of Students Evaluated: <span id="tse"></span></b></p>
                </div>
			<table width="100%">
			</div>
                        <fieldset class="border p-2 w-90 border-success" style = "text-align: center;">
                        <legend  class="w-auto" style = "font-family: 'Times New Roman', Times, serif;">Rating Scale</legend>
                        <p><b>
                        1 - Rarely <span style="padding-left:25px" id="rtg1"></span>
                2 - Once in a while <span style="padding-left:25px" id="rtg2"></span>
                3 - Sometimes <span style="padding-left:25px" id="rtg3"></span>
                4 - Most of the time <span style="padding-left:25px" id="rtg4"></span>
                5 - Always <span style="padding-left:25px" id="rtg5"></span>
                            </b>
                        </p>
                        </fieldset>
				<?php 
							$q_arr = array();
						$criteria = $conn->query("SELECT * FROM criteria_list where id in (SELECT criteria_id FROM question_list where academic_id = {$_SESSION['academic']['id']} ) order by abs(order_by) asc ");
						while($crow = $criteria->fetch_assoc()):
					?>  <br>
					<table class="table table-condensed wborder table-bordered">
						<thead >
                        <tr class="bg-success" style="background:darkgreen;">
                    <th class="pl-3"><b><?php echo $crow['criteria'] ?></b></th>
                    <th width="5%" class="text-center">1</th>
                    <th width="5%" class="text-center">2</th>
                    <th width="5%" class="text-center">3</th>
                    <th width="5%" class="text-center">4</th>
                    <th width="5%" class="text-center">5</th>
                    <th width="15%" class="text-center">Average</th> <!-- New th for Total Average -->
                </tr>
                    </tr>
                    </thead>
            <tfoot> <!-- New tfoot for Total Average -->
                <tr class="bg-white">
                    <td colspan="6" class="text-right"><b>Total Average:</b></td>
                    <td class="text-center"><span id="total_avg_<?php echo $crow['id'] ?>">0.00</span></td>
                </tr>
            </tfoot>
            <tbody class="tr-sortable">
                <?php 
                $questions = $conn->query("SELECT * FROM question_list WHERE criteria_id = {$crow['id']} AND academic_id = {$_SESSION['academic']['id']} ORDER BY abs(order_by) ASC ");
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
                    <div id=""><br><hr>
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
</div>
<style>
	.list-group-item:hover{
		color: black !important;
		font-weight: 700 !important;
	}
</style>
<noscript>
<style>
		table{
            width:100%;
            border-collapse: collapse;
        }
        table.wborder tr, table.wborder td, table.wborder th {
            border: 1px solid green;
            padding: 3px;
        }
                th, td {
            border: 1px solid black; /* I-set ang border para sa mga cell */
            padding: 8px; /* I-set ang padding */
            text-align: left; /* I-set ang text alignment */
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; /* I-set ang background color para sa mga even rows */
        }

        /* I-set ang background color para sa header ng table */
        th {
            background-color: #4CAF50;
            color: white;
        }
        /* I-set ang background color ng header ng table */
        table.wborder thead tr {
            background: darkgreen;
            color: black;
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
        .active {
            background:green;
            color:white;
        }
        #act {
            background:white;
            color:black;
            text-decoration:none;
            border:1px darkgreen solid;
        }
        #act:hover {
            background:lightgreen;
            color:white;
        }
        #act.active {
            background:darkgreen;
            color:white;
        }
        @media print {
    /* Ipanatili ang table border kapag nagpriprint */
    table.wborder tr, table.wborder td, table.wborder th {
        border: 1px solid black;
    }
    .list-group-item.active {
        z-index: 2;
        color: #fff;
        background:black;
        border-color: darkgreen;
     }
}
    #act.active {
        background:darkgreen;
        color:white;
    }
	</style>
</noscript>
<script>
	$(document).ready(function () {
        computeCriteriaGeneralAverage();
        computeCriteriaTotalAverage();
    load_class(<?php echo $faculty_id; ?>);
});

function load_class(facultyId) {
    start_load();
    $.ajax({
        url: "ajax.php?action=get_class",
        method: 'POST',
        data: { fid: facultyId },
        error: function (err) {
            console.log(err);
            alert_toast("An error occurred", 'error');
            end_load();
        },
        success: function (resp) {
            if (resp) {
                resp = JSON.parse(resp);
                if (Object.keys(resp).length <= 0) {
                    $('#class-list').html('<a href="javascript:void(0)" class="list-group-item list-group-item-action disabled" >No results found.</a>')
                } else {
                    $('#class-list').html('');
                    Object.keys(resp).map(k => {
                        $('#class-list').append('<a href="javascript:void(0)" data-json=\'' + 
                        JSON.stringify(resp[k]) + '\' data-id="' + resp[k].id 
                        + '" class="list-group-item list-group-item-action show-result" style = "text-decoration:none;background:white;color:black;"id = "act" >'
                         + resp[k].class + ' - ' + resp[k].subj + '</a>');
                    });

                    // Fetch and set faculty name
                    
                }
            }
        },
        complete: function () {
            end_load();
            anchor_func();
            if ('<?php echo isset($_GET['rid']) ?>' == 1) {
                $('.show-result[data-id="<?php echo isset($_GET['rid']) ? $_GET['rid'] : '' ?>"]').trigger('click');
            } else {
                $('.show-result').first().trigger('click');
            }
        }
    });
}


    
function anchor_func() {
    $('.show-result').click(function () {
        var vars = [], hash;
        var data = $(this).attr('data-json')
        data = JSON.parse(data)
        var _href = location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < _href.length; i++) {
            hash = _href[i].split('=');
            vars[hash[0]] = hash[1];
        }
        window.history.pushState({}, null, './indexx.php?page=result&rid=' + data.id);
        load_report(<?php echo $_SESSION['login_id'] ?>, data.sid, data.id);
        $('#subjectField').text(data.subj)
        $('#classField').text(data.class)
        $('.show-result.active').removeClass('active')
        $(this).addClass('active')
    })
}

function load_report($faculty_id, $subject_id, $class_id) {
    if ($('#preloader2').length <= 0)
        start_load();
        $('.rates').text('0');
        $('.rate_total').text('0');
    $.ajax({
        url: 'ajax.php?action=get_report',
        method: "POST",
        data: { faculty_id: $faculty_id, subject_id: $subject_id, class_id: $class_id },
        error: function (err) {
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
                    $('.rate_total').text('0');
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
                }
            } else {
                // If no response, handle it here
                $('.rates').text('0');
                $('.rate_total').text('0');
                $('#tse').text('0');
                $('#print-btn').hide();
            }
        },
        complete: function () {
            end_load();
        }
    });
}

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
    computeCriteriaGeneralAverage()
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
            badgeColor = 'badge-danger'; // Dager badge for Fair
            badgeText = 'Needs Improvement';
        } else {
            badgeColor = 'badge-secondary'; // Default badge
            badgeText = generalAverage;
        } 

        // Append the badge to the general average element
        $('#general_avg').append(' <br> Equivalent: <span style = "font-size:14px"class="badge ' + badgeColor + '">' + badgeText  + '</span>');
    }
$('#print-btn').click(function(){
    start_load();
    var ns = $('noscript').clone();
    var content = $('#printable').html();
    var logoSrc = $('img[src="./images/bpc.ico"]').attr('src'); // Get the source of the logo image
    ns.append(content);
    ns.find('img[src="./images/bpc.ico"]').attr('src', logoSrc); // Set the logo source in the cloned content
    var nw = window.open("Report","_blank","width=900,height=700");
    nw.document.write(ns.html());
    nw.document.close();
    nw.print();
    setTimeout(function(){
        nw.close();
        end_load();
    }, 750);
});
</script>

    