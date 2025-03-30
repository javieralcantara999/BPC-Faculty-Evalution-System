    <script>
        document.title = "Evaluation Form | Superior";
    </script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <?php
    include 'db_connect.php';

    function ordinal_suffix($num) {
        $num = $num % 100;
        if ($num < 11 || $num > 13) {
            switch ($num % 10) {
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

    // Query to fetch all faculty members for evaluation
    $faculty_result = $conn->query("SELECT id, CONCAT(firstname, ' ', lastname) AS name FROM faculty_list");

    ?>

    <style>
        #instructor-avatar img {
                width: 120px; /* Set desired width for the avatar image */
                height: 120px; /* Set desired height for the avatar image */
                border-radius: 100%; /* Optional: Create a circular avatar */
            }

    </style>

    <h1 class="m-0 text-center"><i class="fas fa-envelope-open-text"></i>&nbsp;<b>EVALUATION FORM (SUPERIOR)</b></h1><br><hr>
    <div class="col-lg-12">
        <div class="row">
        <div class="col-md-4">
        <div class="callout" style="border:0px;background:white;" width="100%;">
            <h5 class="text-center"><b>Details</b></h5>
            <hr>
            <div class="text-center">
                <div id="instructor-avatar"></div> <!-- Display instructor avatar here -->
                <div id="instructor-name" style="font-weight: bold; font-size: 18px;"></div> <!-- Display instructor name here -->
            </div><br class = "status"style="display: none;">
            <!-- Show status based on active faculty -->
            <div class="status text-center" style="display: none;"></div> <!-- Initially hidden -->
            <?php if (empty($activeFacultyId)): ?>
                <p id="noDetailsMessage" class="text-center showDetails">No details found.</p>
            <?php endif; ?>
            <hr>
        </div>
                <div class="container">
                    <div class="form-group text-center">
                        <label for="faculty" class="text-center" style="font-size: 18px">Faculty Member/Instructor</label><hr>
                        <select id="faculty_id" class="form-control form-control-sm select2" style="border: none; width: 100%;">
                            
                            <?php
                            // Check if there are results
                            if ($faculty_result->num_rows > 0) {
                                // Loop through each faculty member and populate the dropdown
                                
                                while ($row = $faculty_result->fetch_assoc()) {
                                    echo '<option value="' . $row['id'] . '">' . ucwords($row['name']) . '</option>';
                                }
                            } else {
                                echo '<option value="" disabled>No Faculty Available</option>';
                            }

                            ?>
                        </select>
                    </div>
                    <div class="text-center">
                        <button id="addToList" class="btn btn-sm btn-gradient btn-success mt-3">Add to List</button>
                    </div>
                    <hr>
                    <!-- Selected Faculty List -->
                    <h4 class="text-center mt-4" style="font-size: 19px; font-weight: bold;">Evaluation List</h4>
                    <hr>
                    <?php if ($faculty_result->num_rows > 0): ?>
                        <ul id="selectedFacultyList" class="list-group">
                            <!-- Loop through selected faculty -->
                            <?php while ($row = $faculty_result->fetch_assoc()): ?>
                                <li class="list-group-item" data-faculty-id="<?php echo $row['id']; ?>">
                                    <?php echo ucwords($row['name']); ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                    <?php endif; ?>
                    <hr>
                </div><br>
                <div class="callout" style="border:0px;background:white;"width = "100%;">
                <div class="text-center">
                    <h5><b>Evaluated Faculty Members</b></h5>
                    <?php 
                        $evaluated_faculty_result = $conn->query("SELECT CONCAT(firstname, ' ', lastname) AS name
                        FROM evaluation_list_superior
                        INNER JOIN faculty_list ON evaluation_list_superior.faculty_id = faculty_list.id
                        WHERE evaluation_list_superior.superior_id = {$_SESSION['login_id']}");
                    if ($evaluated_faculty_result->num_rows > 0): ?>
                        <ul class="list-group">
                            <?php while ($row = $evaluated_faculty_result->fetch_assoc()): ?>
                                <li class="list-group-item"><?php echo ucwords($row['name']); ?></li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No records yet.</p>
                    <?php endif; ?>
                </div>
                </div>
            </div>
            <!-- Right Column (Evaluation Form) -->
            <br><br>
            <div class="col-md-8" id="cc">
                
                <div class="card" style="border:none;">
                    <div class="card-header text-center" style="background:darkgreen;color:white">
                        <b>Evaluation Form for School Year : 
                            <?php echo $_SESSION['academic']['year'].' '.(ordinal_suffix($_SESSION['academic']['semester'])) ?> 
                        Semester</b>
                        </div>
                    <div class="card-body" style="font-family: 'Times New Roman', Times, serif;">
                    <h4 >
                                <center>
                                    <strong><b>
                                    <img src="./images/bpc.ico" alt="BPC Logo" style="width: 100px; height: 100px;"><br><br>
                                    BULACAN POLYTECHNIC COLLEGE <br>
                                    Bulihan, City of Malolos, Bulacan
                                    </b></strong>
                                </center>
                            </h4><br>
                            <h5><center><b>EVALUATION ON THE DUTIES OF DUTIES AND RESPONSIBILITIES OF THE FACULTY</b></center></h5>
                            <h5><center><b>FACULTY EVALUATION F-2: SUPERIORS' EVALUATION</b></center></h5>
                            <h5 class = "text-center"><b >
                            SY <?php echo $year?>
                            <b></h5><br>
                            <h5 style = "font-size:19px">
                                <b><br><hr>
                                    Instructions: </b> <i>Read each statement carefully and indicate your response by writing your rating on the provided answer sheet.
                                    The number rating stands for the following: </i>
                            
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
                        <form id="manage-evaluation" action="" method="POST">
                            <input type="hidden" name="faculty_id" value="<?php echo $faculty_id ?>">
                            <input type="hidden" name="academic_id" value="<?php echo $_SESSION['academic']['id'] ?>">
                            
                            <div class="clear-fix mt-0"></div>
                            <?php 
                                    $q_arr = array();
                                $criteria = $conn->query("SELECT * FROM criteria_list_superior where id in 
                                (SELECT criteria_id FROM question_list_superior where academic_id = {$_SESSION['academic']['id']} ) 
                                order by abs(order_by) asc ");
                                while($crow = $criteria->fetch_assoc()):
                            ?>
                            <table class="table table-condensed table-bordered table-success"style="border-radius:15px">
                                <thead>
                                    <tr class="bg-gradient-success text-center" >
                                        <th colspan="1" class="p-1 text-center align-items-center"><b><?php echo $crow['criteria'] ?></b></th>
                                        <th class="text-center">1</th>
                                        <th class="text-center">2</th>
                                        <th class="text-center">3</th>
                                        <th class="text-center">4</th>
                                        <th class="text-center">5</th>
                                    </tr>
                                </thead>
                                <tbody class="tr-sortable">
                                    <?php 
                                    $questions = $conn->query("SELECT * FROM question_list_superior where criteria_id = {$crow['id']} 
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
                                                <input type="radio" name="rate[<?php echo $row['id'] ?>]" <?php echo $c == 5 ?  : '' ?> 
                                                id="qradio<?php echo $row['id'].'_'.$c ?>" value="<?php echo $c ?>">
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
                            <p class="label-question">Please write your comments, suggestions or opinions about the faculty member/instructor here.</p>
                            <textarea id="comments" name="comments" class="form-control" style="height: 100px;"></textarea>
                        </div>
                        <label class="text-center" for="comments" style="font-size: 22px">THANK YOU FOR YOUR PARTICIPATION! </label> <br><br>
                        <button id="submit-comments" class="btn btn-sm btn-flat btn-success bg-gradient-success mx-1" style="border-radius: 5px;border:0px solid darkgreen;font-size:18px;" form="manage-evaluation">Submit Evaluation</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
        if('<?php echo $academic_status; ?>' == 0){
            uni_modal("Information", "<?php echo $_SESSION['login_view_folder']; ?>not_started.php");
            } else if('<?php echo $academic_status; ?>' == 2){
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
        if(<?php echo empty($rid) ? 1 : 0; ?> == 1) {
            // uni_modal("Information","<?php echo $_SESSION['login_view_folder']; ?>done.php");
        }
    });

    $(document).ready(function() {

        function showNoDetailsMessage() {
            $('#noDetailsMessage').show();
            
            $(this).find('.showDetails').show();
        }

        // Function to hide the 'No details found' message
        function hideNoDetailsMessage() {
            $('#noDetailsMessage').hide();
            $('.showDetails').hide();
        }

    var activeFacultyId = null;
    var activeFacultyName = null; // Variable to store active faculty name

    function toggleEvaluationButtons(facultyId) {
        // Iterate through each faculty item
        $('#selectedFacultyList .list-group-item').each(function() {
            var itemId = $(this).data('faculty-id');

            if (itemId === facultyId) {
                // Toggle buttons for the selected faculty
                $(this).find('.selectFaculty, .removeFaculty').hide();
                $(this).find('.cancelEvaluation').show();
            } else {
                // Reset buttons for other faculties
                $(this).find('.selectFaculty, .removeFaculty').show();
                $(this).find('.cancelEvaluation').hide();        
                $('.showDetails').hide();

            }
        });

        // Set the active faculty
        activeFacultyId = facultyId;

        // Get active faculty name
        activeFacultyName = $('#selectedFacultyList').find('[data-faculty-id="' + facultyId + '"]').text().trim();
    }

    function saveSelectedFacultyList() {
        var selectedFacultyList = [];

        $('#selectedFacultyList .list-group-item').each(function() {
            var facultyId = $(this).data('faculty-id');
            var facultyName = $(this).text().trim();
            selectedFacultyList.push({ id: facultyId, name: facultyName });
        });
    }

    // Event handler for adding faculty to the list
    $('#addToList').click(function() {
        var selectedFacultyId = $('#faculty_id').val();
        var selectedFacultyName = $('#faculty_id option:selected').text();

        if (selectedFacultyId && selectedFacultyName) {
            // Check if faculty is already in the list
            var isInList = $('#selectedFacultyList').find('[data-faculty-id="' + selectedFacultyId + '"]').length > 0;

            if (!isInList) {
                // Check if faculty is already evaluated by the superior
                $.ajax({
                    url: 'ajax.php?action=check_evaluation',
                    method: 'POST',
                    data: { selectedFacultyId: selectedFacultyId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.error) {
                            toastr.error('Error checking evaluationss: ' + response.error);
                        } else {
                            if (response.evaluated) {
                                // Faculty has already been evaluated by the superior
                                toastr.error('You have already evaluated this faculty member/instructor.');
                            } else {
                                
                                $('.showRes').hide();
                                // Add selected faculty to the list
                                $('#selectedFacultyList').append(
                                    '<li class="list-group-item d-flex justify-content-between align-items-center" data-faculty-id="' 
                                    + selectedFacultyId + '">' +
                                    selectedFacultyName +
                                    '<div>' +
                                    '<button class="btn btn-success btn-sm selectFaculty mr-1">Evaluate</button>' +
                                    '<button class="btn btn-danger btn-sm removeFaculty">Remove</button>' +
                                    '<button class="btn btn-primary btn-sm cancelEvaluation" style="display:none;float:right;">Cancel</button>' +
                                    '</div>' +
                                    '</li>'
                                );

                                // Save updated selected faculty list to localStorage
                                saveSelectedFacultyList();

                                // Clear selected faculty dropdown
                                $('#faculty_id').val('');

                                // Bind click event for select and cancel evaluation actions
                                bindSelectCancelActions();
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error checking evaluations: ' + error);
                    }
                });
            } else {
                toastr.error('This faculty is already selected for evaluation.');
            }
        }
    });

    function bindSelectCancelActions() {
        $('#selectedFacultyList').on('click', '.removeFaculty', function() {
            $(this).closest('li').remove();
            saveSelectedFacultyList(); // Save updated list after removal
            showNoDetailsMessage();
            $('.showRes').show();
        });

        $('#selectedFacultyList .selectFaculty').off('click').on('click', function() {
            var facultyId = $(this).closest('li').data('faculty-id');
            toggleEvaluationButtons(facultyId);

            // Fetch instructor information for the selected faculty
            fetchInstructorInformation(facultyId);
            showNoDetailsMessage();
        });

        $('#selectedFacultyList .cancelEvaluation').off('click').on('click', function() {
            var facultyId = $(this).closest('li').data('faculty-id');
            toggleEvaluationButtons(null); // Reset active faculty

            // Clear instructor information and hide status
            $('#instructor-avatar').empty();
            $('#instructor-name').empty();
            $('.status').hide();
            $('.showDetails').show();
        });
    }

    // Function to fetch instructor information via AJAX
    function fetchInstructorInformation(facultyId) {
        $.ajax({
            url: 'ajax.php?action=fetch_instructor',
            method: 'POST',
            data: { faculty_id: facultyId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var instructorInfo = response.data;
                    var avatarSrc = 'assets/uploads/' + instructorInfo.avatar;

                    // Update instructor information
                    $('#instructor-avatar').html('<img src="' + avatarSrc + '" alt="Avatar">');
                    $('#instructor-name').text(instructorInfo.firstname + ' ' + instructorInfo.lastname);

                    // Show status
                    $('.status').show().html('<h6 class="text-center">Status: <span class="badge badge-success">Evaluation active</span></h6>');
                    
                    $('.showDetails').hide();
                } else {
                    console.log('Failed to fetch instructor information');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', error);
            }
        });
    }

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

        var comments = $('#comments').val().trim();
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
        url: 'ajax.php?action=save_evaluation_superior',
        method: 'POST',
        data: $(this).serialize() + '&comments=' + $('#comments').val() + '&faculty_id=' + activeFacultyId  ,
        success: function(resp) {
            console.log(resp);
            if (resp == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Thank you! Your evaluation form has been submitted.'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Instead of direct window.location.href, trigger uni_modal
                        uni_modal("Information", "<?php echo $_SESSION['login_view_folder']; ?>continue.php");
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
        /* CSS styles for layout adjustments */
        #selectedFacultyInfo {
            margin-top: 20px;
        }
        #selectedFacultyInfo img {
            margin-bottom: 10px;
        }
        @media screen and (max-width: 768px) {
            #rtg {
                display: flex;
            }
            #x {
                margin-bottom: 5px;
            }
            #cc {
                margin-top: 50px;
            }
        }
        /* CSS styles for layout adjustments */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .container {
            width: 100%; /* Adjust width as needed */
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .text-center {
            text-align: center;
        }

        #selectedFacultyInfo {
            margin-top: 20px;
        }

        #selectedFacultyInfo img {
            margin-bottom: 10px;
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 100%; /* Full width on smaller screens */
            }
            #selectedFacultyList {
                margin-top: 20px;
            }
        }
    </style>