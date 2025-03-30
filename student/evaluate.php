<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<?php include 'db_connect.php'; ?>

<?php 
function ordinal_suffix($num){
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

// Bagong query para makuha ang Year, Semester, at Evaluation Status mula sa database
$academic_info_query = $conn->query("SELECT year, semester, status FROM academic_list WHERE id = ".$_SESSION['academic']['id']);
$academic_info = $academic_info_query->fetch_assoc();
$year = $academic_info['year'];
$semester = $academic_info['semester'];
$academic_status = $academic_info['status'];
$academic_status_text = $astat[$academic_status];

$rid = '';
$faculty_id = '';
$subject_id = '';

if (isset($_GET['rid'])) {
    $rid = $_GET['rid'];
}
if (isset($_GET['fid'])) {
    $faculty_id = $_GET['fid'];
}
if (isset($_GET['sid'])) {
    $subject_id = $_GET['sid'];
}

// Check the status of the student
$status_query = $conn->query("SELECT `status`, `class_id` FROM student_list WHERE id = {$_SESSION['login_id']}");
$status_row = $status_query->fetch_assoc();
$status = $status_row['status'];
$class_id = $status_row['class_id']; // Get the class ID of the student

// Set the appropriate query based on the student's status
if ($status == 0) {
    // Regular Student: Filter evaluations based on the student's class_id
    $restriction_result = $conn->query("
        SELECT r.id, s.id as sid,c.id as cid, f.id as fid, CONCAT(f.firstname,' ',f.lastname) as faculty, s.code, s.subject
        FROM restriction_list r
        INNER JOIN faculty_list f ON f.id = r.faculty_id
        INNER JOIN subject_list s ON s.id = r.subject_id
        INNER JOIN class_list c ON c.id = r.class_id
        WHERE r.academic_id = {$_SESSION['academic']['id']}
        AND r.class_id = {$class_id}  -- Filter by the student's class_id
        AND r.id NOT IN (
            SELECT restriction_id
            FROM evaluation_list
            WHERE academic_id = {$_SESSION['academic']['id']}
            AND student_id = {$_SESSION['login_id']}
        )
    ");
    
} elseif ($status == 1) {
    // Irregular Student: Include evaluations from other sections within the same curriculum and level
    $restriction_result = $conn->query("
        SELECT r.id,s.id as sid, f.id as fid CONCAT(f.firstname,' ',f.lastname) as faculty, s.code, s.subject
        FROM restriction_list r
        INNER JOIN faculty_list f ON f.id = r.faculty_id
        INNER JOIN subject_list s ON s.id = r.subject_id
        INNER JOIN class_list c ON c.id = r.class_id
        WHERE r.academic_id = {$_SESSION['academic']['id']}
        AND c.curriculum = (SELECT curriculum FROM class_list WHERE id = {$class_id})
        AND c.level = (SELECT level FROM class_list WHERE id = {$class_id})
        AND r.id NOT IN (
            SELECT restriction_id
            FROM evaluation_list
            WHERE academic_id = {$_SESSION['academic']['id']}
            AND student_id = {$_SESSION['login_id']}
        )
    ");
} else {
    // Handle other status cases if needed
    // You may want to add additional logic or queries for other status values
}
?>


<h1 class="m-0 text-center"><i class="fas fa-envelope-open-text"></i>&nbsp;<b>EVALUATION FORM</b></h1><br><hr>
<div class="col-lg-12">
    <div class="row">
        <div class="col-md-4">
<div class="callout text-center" style="border: 0px;">
    <h5 class="text-center"><b>List of Evaluations</b></h5><hr>
    <div class="list-group">
    <?php 
if ($restriction_result->num_rows > 0) {
    // Display available evaluations
    while($row = $restriction_result->fetch_array()) {
        if (empty($rid)) {
            $rid = $row['id'];
            $faculty_id = $row['fid'];
            $subject_id = $row['sid'];
            $class_id = $row['cid'];
        }
        $faculty_name = ucwords($row['faculty']);
        $subject_code = $row['code'];
        $subject_name = $row['subject'];
        $class_name_query = $conn->query("SELECT CONCAT(curriculum, ' ', level, ' - ', section) AS class_name FROM class_list WHERE id = {$row['cid']}");
        $class_name_row = $class_name_query->fetch_assoc();
        $class_name = $class_name_row['class_name'];
        ?>
        <a href="./indexx.php?page=evaluate&rid=<?php echo $row['id'] ?>&sid=<?php echo $row['sid'] ?>&fid=<?php echo $row['fid'] ?>&cid=<?php echo $row['cid'] ?>" class="list-group-item list-group-item-action <?php echo isset($rid) && $rid == $row['id'] ? 'active' : '' ?>" style="text-decoration: none;">
            <div><?php echo "{$faculty_name} <br> ({$subject_code}) - {$subject_name}"; ?></div>
            <div><?php echo $class_name; ?></div>
        </a>
        <?php
    }
} else {
    // No available evaluations
    echo '<p>No available evaluations.</p>';
}
?>
<hr>
</div>

</div>
<style>
#instructor-avatar img {
    width: 100px; /* Palitan ang sukat base sa kailangan */
    height: 100px; /* Palitan ang sukat base sa kailangan */
    border-radius: 50%; /* Rounded border para maging bilog */
}
</style>
            <div class="callout" style="border:0px;background:white;"width = "100%;">
            <div class="text-left">
            <h5 class = "text-center"><b>Evaluated Subjects</b></h5>
<hr>
            <?php 
$evaluated_faculty_query = $conn->query("
SELECT CONCAT(f.firstname, ' ', f.lastname) AS name, 
       s.subject, 
       s.code, 
       CONCAT(c.curriculum, ' ', c.level, ' - ', c.section) AS class
FROM evaluation_list el
INNER JOIN faculty_list f ON el.faculty_id = f.id
INNER JOIN subject_list s ON el.subject_id = s.id
INNER JOIN class_list c ON el.class_id = c.id
WHERE el.student_id = {$_SESSION['login_id']}
");if (!$evaluated_faculty_query) {
    echo "Error: " . $conn->error; // Display error message for debugging
}

if ($evaluated_faculty_query->num_rows > 0): ?>
    <ul class="list-group">
        <?php while ($row = $evaluated_faculty_query->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong>Instructor:</strong> <?php echo ucwords($row['name']); ?><br>
                <strong>Subject:</strong> <?php echo $row['subject']; ?><br>
                <strong>Class:</strong> <?php echo formatClassSection($row['class']); ?>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p class = "text-center">No records yet.</p>
<?php endif; ?>

<?php
// Function to format class section for irregular students
function formatClassSection($classSection) {
    // Extract curriculum, level, and section from the concatenated string
    $parts = explode(' - ', $classSection);
    if (count($parts) === 2) {
        $curriculumLevel = $parts[0];
        $section = $parts[1];
        return "{$curriculumLevel} - {$section}";
    } else {
        return $classSection; // Return original string if format is unexpected
    }
}
?>
            </div>
            </div>
        </div>  
        <style>
            #x{
                font-weight: bold;
                border:1px solid darkgreen;
                color:black;
            }
            #x.active{
                font-weight: bold;
                background: darkgreen;
                color:white;
            }
            #x.active:hover{
                font-weight: bold;
                background: green;
                color:white;
            }
        </style>
        
        <br><br>
        <div class="col-md-8" id="cc">
            <div class="card" style="border:none;">
                <div class="card-header text-center" style="background:darkgreen;color:white">
                    <b>Evaluation Form for School Year : 
                        <?php echo $_SESSION['academic']['year'].' '.(ordinal_suffix($_SESSION['academic']['semester'])) ?> 
                    Semester</b>
                     </div>
                <div class="card-body" style="font-family: 'Times New Roman', Times, serif;">
                    <h4>
                    <center>
                                <strong><b>
                                <img src="./images/bpc.ico" alt="BPC Logo" style="width: 100px; height: 100px;"><br><br>
                                BULACAN POLYTECHNIC COLLEGE <br>
                                Bulihan, City of Malolos, Bulacan
                                </b></strong>
                            </center>
                        </h4><br>
                        <h5><center><b>FACULTY EVALUATION F-1: STUDENTS' EVALUATION</b></center></h5>
                        <h5><center><b>FACULTY EVALUATION RESULT</b></center></h5><h5 class = "text-center"><b >
                           SY <?php echo $year.' '.(ordinal_suffix($semester)) ?> Semester 
                        <b></h5><br>
                        <h5>
                            <i style = "font-family: 'Times New Roman', Times, serif; font-size:19px;">
                            <b>
                            Note: </b>This questionnaire gives you an opportunity to express anonymously your views about your instructor.
                                Carefully and honestly rate the performance of your instructor.
                            </i>
                        </h5><br>
                        <h5>
                            <b style = "font-family: 'Times New Roman', Times, serif; font-size:19px;">
                                Instructions: <i>Read each statement carefully and indicate your response by writing your rating on the provided answer sheet.
                                The number rating stands for the following: </i>
                            </b>
                        </h5>
                    <center>
                        <br>
                        <fieldset class="border p-2 w-90 border-success" style="text-align: center;">
                            <legend  class="w-auto" style="font-family: 'Times New Roman', Times, serif;">Rating Scale</legend>
                            <p><b>
                                1 - Rarely <span style="padding-left:25px" id="rtg"></span>
                                2 - Once in a while <span style="padding-left:25px" id="rtg"></span>
                                3 - Sometimes <span style="padding-left:25px" id="rtg"></span>
                                4 - Most of the time <span style="padding-left:25px" id="rtg"></span>
                                5 - Always
                            </b></p>
                        </fieldset>
                    </center>
                    <br>
                        <?php
                            $class_id = isset($_GET['cid']) ? $_GET['cid'] : ''; // Set to empty string if not provided
                        ?>
                        <form id="manage-evaluation" action="" method="POST">
                        <input type="hidden" name="class_id" value="<?php echo $class_id ?>">
                        <input type="hidden" name="faculty_id" value="<?php echo $faculty_id ?>">
                        <input type="hidden" name="restriction_id" value="<?php echo $rid ?>">
                        <input type="hidden" name="subject_id" value="<?php echo $subject_id ?>">
                        <input type="hidden" name="academic_id" value="<?php echo $_SESSION['academic']['id'] ?>">
                        <script>
    // Wait for the DOM to fully load
    document.addEventListener('DOMContentLoaded', function() {
        // Select all hidden input elements
        const hiddenInputs = document.querySelectorAll('input[type="hidden"]');
        
        // Loop through each hidden input element
        hiddenInputs.forEach(input => {
            // Retrieve the name and value of the input
            const name = input.getAttribute('name');
            const value = input.value;
            
            // Log the name and value to the console
            console.log(`Input Name: ${name}, Value: ${value}`);
        });
    });
</script>
                        <div class="clear-fix mt-0"></div>
                        <?php 
                                $q_arr = array();
                            $criteria = $conn->query("SELECT * FROM criteria_list where id in 
                            (SELECT criteria_id FROM question_list where academic_id = {$_SESSION['academic']['id']} ) 
                            order by abs(order_by) asc ");
                            while($crow = $criteria->fetch_assoc()):
                        ?>
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr class="bg-gradient-success text-center" style="background:green;">
                                    <th colspan="1" class="p-2"><b><?php echo $crow['criteria'] ?></b></th>
                                    <th class="text-center">1</th>
                                    <th class="text-center">2</th>
                                    <th class="text-center">3</th>
                                    <th class="text-center">4</th>
                                    <th class="text-center">5</th>
                                </tr>
                            </thead>
                            <tbody class="tr-sortable">
                                <?php 
                                $questions = $conn->query("SELECT * FROM question_list where criteria_id = {$crow['id']} 
                                and academic_id = {$_SESSION['academic']['id']} order by abs(order_by) asc ");
                                while($row=$questions->fetch_assoc()):
                                $q_arr[$row['id']] = $row;
                                ?>
                                <tr class="bg-white">
                                    <td class="p-1" width="40%">
                                        <?php echo $row['question'] ?>
                                        <input type="hidden" name="qid[]" value="<?php echo $row['id'] ?>">
                                    </td>
                                    <?php for($c=1;$c<=5;$c++): ?>
                                    <td class="text-center">
                                        <div class="icheck-success d-inline">
                                            <input type="radio" name="rate[<?php echo $row['id'] ?>]" <?php echo $c == 5 ?  : '' ?> id="qradio<?php echo $row['id'].'_'.$c ?>" value="<?php echo $c ?>">
                                            <label for="qradio<?php echo $row['id'].'_'.$c ?>">
                                            </label>
                                        </div>
                                    </td>
                                    <?php endfor; ?>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <?php endwhile; ?>
                    </form>
                    <br>
                    <div class="form-group">
                        <label for="comments" style="font-size: 22px">COMMENTS</label>
                        <p class="label-question">Please write your comments, suggestions or opinions about the instructor here.</p>
                        <textarea id="comments" name="comments" class="form-control" style="height: 100px;" ></textarea>
                    </div>
                    <label class="text-center" for="comments" style="font-size: 22px">THANK YOU FOR YOU HONEST PARTICIPATION !!! ☺☺☺</label> <br><br>
                    <button id="submit-comments" class="btn btn-sm btn-flat btn-success bg-gradient-success mx-1" style="border-radius: 5px;border:0px solid darkgreen;font-size:18px;" form="manage-evaluation">Submit Evaluation</button>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    @media screen and (max-width: 768px) {
        #rtg {
            display:flex;
        }
        #x {
            margin-bottom: 5px;
        }
        #cc {
            margin-top:50px;
        }
    }
</style>
<script>
   
    $(document).ready(function(){

        // Kapag ang dokumento ay handa na (naka-load), magsisimula ang mga sumusunod na kaganapan
        function removeURLParams() {
        var url = window.location.href;
        url = url.split('?')[0]; // Remove query string
        window.history.replaceState({}, document.title, url);
    }
        // I-check ang estado ng academic session
        if ('<?php echo $academic_status; ?>' == 0) {
        uni_modal("Information", "<?php echo $_SESSION['login_view_folder']; ?>not_started.php");
    } else if ('<?php echo $academic_status; ?>' == 2) {
        Swal.fire({
            icon: 'info',
            title: 'Evaluation Closed',
            text: 'The evaluation for this academic year/semester is already closed.',
            confirmButtonColor: 'green',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = './indexx.php'; // Redirect to homepage
            }
        });
    }
    // Check if there is no selected restriction ID and show appropriate message
    else if (<?php echo empty($rid) ? 1 : 0; ?> == 1) {
        uni_modal("Information", "<?php echo $_SESSION['login_view_folder']; ?>done.php");
        $('#submit-comments').prop('disabled', true);
        end_load(); // End loading state
    }

    // Pagsusumite ng form ng pag-evaluate
    $('#manage-evaluation').submit(function(e) {
    e.preventDefault(); // Pigilan ang default na pag-submit ng form

    // Suriin kung lahat ng radio buttons ay napili
    var isComplete = true;
    $('input[name^="rate["]').each(function() {
        if (!$('input[name="' + this.name + '"]:checked').length) {
            isComplete = false;
            return false; // Lumabas sa loop agad kung may hindi nasagutan na tanong
        }
    });

    if (!isComplete) {
        alert_toast("Please answer all the questions before submitting.", "warning");
        end_load();
        setTimeout(function() {
            $('.alert').remove(); // Alisin ang alert message pagkatapos ng 1 segundo
        }, 1000);
        return;
    }

    var comments = $('#comments').val();
        if (!comments) {
            alert_toast("Please provide comments before submitting.", "warning");
            return;
        }
        
    // Gamitin ang SweetAlert2 para sa confirmation dialog bago mag-submit
    Swal.fire({
        icon: 'question',
        title: 'Are you sure?',
        text: 'Do you want to submit your evaluation form?',
        showCancelButton: true,
        confirmButtonColor: 'green',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm'
    }).then((result) => {
        if (result.isConfirmed) {
            start_load(); // Simulan ang pag-loading
            $.ajax({
    url: 'ajax.php?action=save_evaluation',
    method: 'POST',
    data: $(this).serialize() + '&comments=' + $('#comments').val(),
    success: function(resp) {
        console.log(resp);
        if (resp == 1) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Your evaluation form has been submitted.'
            }).then((result) => {
                if (result.isConfirmed) {
                    removeURLParams();
                    // Instead of direct window.location.href, trigger uni_modal
                    if (<?php echo empty($rid) ? 1 : 0; ?> == 1) {
                        uni_modal("Information", "<?php echo $_SESSION['login_view_folder']; ?>done.php");
                        $('#submit-comments').prop('disabled', true);
                    }else
                    {
                        uni_modal("Information", "<?php echo $_SESSION['login_view_folder']; ?>continue.php");
                    }
                }
            });
        }
        end_load(); // Tapusin ang pag-loading
    },
    error: function(xhr, status, error) {
        console.log(xhr.responseText); // I-log ang error response kung may error
    }
});
        } else {
            // Kung hindi pinindot ang OK sa confirmation dialog, huwag i-submit ang form
            alert_toast("Evaluation form submission canceled.", "info");
            end_load(); // Tapusin ang pag-loading
        }
    });
});
    
});
</script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<?php
// Check if there are no available evaluations
if ($restriction_result->num_rows === 0 && $evaluated_faculty_query->num_rows === 0) {
    // Notify the user using SweetAlert for no available evaluations
    echo "<script>
            Swal.fire({
                icon: 'info',
                title: 'No Available Evaluations',
                text: 'There are no available evaluations as of this moment. Please wait for announcements.',
                confirmButtonColor: 'green',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'my-popup-class' // Custom class for styling the dialog box
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = './indexx.php'; // Redirect to home page
                }
            });
          </script>";
}
?>
<style>
    /* Define custom styles for SweetAlert dialogs and overlay */
    .my-popup-class {
        background-color: white; /* Set background color of the dialog box to white */
        border-radius: 8px; /* Optional: Add border radius for a rounded appearance */
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Optional: Add box shadow for depth */
    }
    .swal-overlay {
        background-color: rgba(255, 255, 255, 0.8) !important; /* Set background color of the overlay to white with opacity */
    }
</style>