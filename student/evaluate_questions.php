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

$rid = '';
$faculty_id = '';
$subject_id = '';
if(isset($_GET['rid']))
    $rid = $_GET['rid'];
if(isset($_GET['fid']))
    $faculty_id = $_GET['fid'];
if(isset($_GET['sid']))
    $subject_id = $_GET['sid'];

$restriction_result = $conn->query("SELECT r.id,s.id as sid,f.id as fid,concat(f.firstname,' ',f.lastname) as faculty,s.code,s.subject FROM restriction_list r inner join faculty_list f on f.id = r.faculty_id inner join subject_list s on s.id = r.subject_id where academic_id = {$_SESSION['academic']['id']} and class_id = {$_SESSION['login_class_id']} and r.id not in (SELECT restriction_id from evaluation_list where academic_id = {$_SESSION['academic']['id']} and student_id = {$_SESSION['login_id']} ) ");

?>


<h1 class="m-0 text-center"><i class="fas fa-envelope-open-text"></i>&nbsp;<b>EVALUATION FORM</b></h1><br><hr>
<div class="col-lg-12">
    <div class="row">
        <div class="col-md-4">
        <div class="callout" style="border:0px;background:white;"width = "10    0%;">
                <h5 class="text-center" ><b>Details</b></h5><hr><br>
                <div class="text-center">
                    <div id="instructor-avatar"></div> <!-- Display instructor avatar here -->
                    <div id="instructor-name" style= "font-weight: bold;font-size:20px;"></div><br>
                    <div id="instructor-subject" style= "font-weight: bold;font-size:18px;"></div>
                    <div id="instructor-section" style= "font-weight: bold;font-size:18px;"></div>
                </div><br>
                <div class="status" style = "display:none">
                    <h6 class="text-center">Status: <span id="status-span"class="badge badge-success">Evaluation active</span></h6>
                </div>
                <hr>
            </div>
            <script>
    // Kunin ang mga kinakailangang elemento ng HTML
    const instructorAvatar = document.getElementById('instructor-avatar');
    const instructorName = document.getElementById('instructor-name');
    const instructorSubject = document.getElementById('instructor-subject');
    const instructorSection = document.getElementById('instructor-section');

    // Kunin ang lahat ng mga link sa listahan ng evaluasyon
    const evaluationLinks = document.querySelectorAll('.list-group-item');

    // Iterasyon sa bawat link at magdagdag ng event listener para sa pagkuha ng detalye ng instructor
    evaluationLinks.forEach(link => {
    link.addEventListener('click', (event) => {
        event.preventDefault();

        // I-clear ang lahat ng mga active na klase mula sa mga links
        evaluationLinks.forEach(item => {
            item.classList.remove('active');
        });

        link.classList.add('active');

        const rid = link.getAttribute('data-rid');
        const sid = link.getAttribute('data-sid');
        const fid = link.getAttribute('data-fid');

        // Gumawa ng AJAX request para kumuha ng detalye ng instructor
        $.ajax({
            url: 'ajax.php?action=fetch_evaluation_information',
            method: 'POST',
            data: { faculty_id: fid },
            dataType: 'json',
            success: function(response) {
                instructorAvatar.innerHTML = ''; // Ilagay ang larawan ng instructor dito
                instructorName.textContent = response.name;
                instructorSubject.textContent = response.subject;
                instructorSection.textContent = response.section;
                $('.status').show().html('<h6 class="text-center">Status: <span class="badge badge-success">Evaluation active</span></h6>');
                $('#evaluateNowBtn').html('<button class="text-center btn btn-secondary mt-3" disabled>&lt;X&gt;</button>');
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });
});
</script>
<div class="callout text-center" style="border: 0px;">
    <h5 class="text-center"><b>List of Evaluations</b></h5>
    <select id="evaluationSelect" class="form-control text-center">
        <?php while($row = $restriction_result->fetch_array()): 
            if(empty($rid)){
                $rid = $row['id'];
                $faculty_id = $row['fid'];
                $subject_id = $row['sid'];
            }?>
            <option value="<?php echo $row['id']; ?>" data-fid="<?php echo $row['fid']; ?>" 
            data-sid="<?php echo $row['sid']; ?>" data-classid="<?php echo $_SESSION['login_class_id']; ?>"
             data-academicid="<?php echo $_SESSION['academic']['id']; ?>">
                <?php echo ucwords($row['faculty']).' - ('.$row["code"].') '.$row['subject']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button id="evaluateNowBtn" 
    href="./indexx.php?page=evaluate&rid=<?php echo $row['id'] ?>&sid=<?php echo $row['sid'] ?>&fid=<?php echo $row['fid'] ?>"
    class="text-center btn btn-success mt-3 selectFaculty">Evaluate Now</button> <!-- Evaluate now button -->
</div>
<script>
$(document).ready(function() {
    $(document).ready(function() {
    $('#evaluationSelect').change(function() {
        var rid = $(this).val();
        var fid = $(this).find('option:selected').attr('data-fid');
        var sid = $(this).find('option:selected').attr('data-sid');
        var class_id = $(this).find('option:selected').attr('data-classid');
        var academic_id = $(this).find('option:selected').attr('data-academicid');

        $.ajax({
            url: 'ajax.php?action=fetch_evaluation_information',
            method: 'POST',
            data: { faculty_id: fid, subject_id: sid, class_id: class_id, academic_id: academic_id },
            dataType: 'json',
            success: function(response) {
                
            $('#instructor-avatar').html('<img src="' + response.avatar + '" alt="Instructor Avatar">');
            $('#instructor-name').text(response.name);
            $('#instructor-subject').text(response.subject);
            $('#instructor-section').text(response.section);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });

    $('#evaluateNowBtn').click(function() {
        var selectedOption = $('#evaluationSelect option:selected');

        var rid = selectedOption.val();
        var fid = selectedOption.attr('data-fid');
        var sid = selectedOption.attr('data-sid');
        var class_id = selectedOption.attr('data-classid');
        var academic_id = selectedOption.attr('data-academicid');

        // Dito pwede mong gamitin ang mga variables (fid, sid, rid, class_id, academic_id) 
        // para mag-update ng detalye ng instructor at subject sa iyong form
        // Halimbawa, magagamit mo ito sa AJAX request para kumuha ng karagdagang impormasyon
        // o para mag-set ng tamang mga hidden input fields sa form mo.

        // Dito mo na rin maari ipasa ang mga kinakailangang data para sa pag-evaluate.

        // Halimbawa ng pag-update ng mga detalye sa instructor na nakuha mula sa selection:
        $.ajax({
            url: 'ajax.php?action=fetch_evaluation_information',
            method: 'POST',
            data: { faculty_id: fid, subject_id: sid, class_id: class_id, academic_id: academic_id },
            dataType: 'json',
            success: function(response) {
                // Update instructor details based on the selected option
                $('#instructor-avatar').html('<img src="' + response.avatar + '" alt="Instructor Avatar">');
                $('#instructor-name').text(response.name);
                $('#instructor-subject').text(response.subject);
                $('#instructor-section').text(response.section);
                $('.status').show().html('<h6 class="text-center">Status: <span class="badge badge-success">Evaluation Active</span></h6>');
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });

        // Dito pwede mong gamitin ang mga variables na ito para sa iba pang mga functions o pagkuha ng mga detalye.
    });
});
});
</script>
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
        <?php 
            $evaluated_faculty_result = $conn->query("SELECT CONCAT(f.firstname, ' ', f.lastname) AS name, s.subject, s.code, 
            CONCAT(c.curriculum, ' ', c.level , ' - ', c.section) AS class
                FROM evaluation_list el
                INNER JOIN faculty_list f ON el.faculty_id = f.id
                INNER JOIN subject_list s ON el.subject_id = s.id
                INNER JOIN class_list c ON el.class_id = c.id
                WHERE el.student_id = {$_SESSION['login_id']}");
        
        if ($evaluated_faculty_result->num_rows > 0): ?>
            <ul class="list-group">
                <?php while ($row = $evaluated_faculty_result->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <strong>Instructor:</strong> <?php echo ucwords($row['name']); ?><br>
                        <strong>Subject:</strong> <?php echo $row['subject']; ?><br>
                        <strong>Section:</strong> <?php echo $row['class']; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No records yet.</p>
        <?php endif; ?>
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
                    <form id="manage-evaluation" action="evaluate_questions.php" method="POST">
                        <input type="hidden" name="class_id" value="<?php echo $_SESSION['login_class_id'] ?>">
                        <input type="hidden" name="faculty_id" value="<?php echo $faculty_id ?>">
                        <input type="hidden" name="restriction_id" value="<?php echo $rid ?>">
                        <input type="hidden" name="subject_id" value="<?php echo $subject_id ?>">
                        <input type="hidden" name="academic_id" value="<?php echo $_SESSION['academic']['id'] ?>">
                        
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
                        <p class="label-question">Do you have any comments, suggestions or opinions? Write your feedback.</p>
                        <textarea id="comments" name="comments" class="form-control" style="height: 100px;" required></textarea>
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

        // I-check ang estado ng academic session
        if ('<?php echo $_SESSION['academic']['status']; ?>' == 0) {
            uni_modal("Information", "<?php echo $_SESSION['login_view_folder']; ?>not_started.php");
        } else if ('<?php echo $_SESSION['academic']['status']; ?>' == 2) {
            uni_modal("Information", "<?php echo $_SESSION['login_view_folder']; ?>closed.php");
        }

        // I-check kung walang piniling restriction ID
        if (<?php echo empty($rid) ? 1 : 0; ?> == 1) {
            uni_modal("Information", "<?php echo $_SESSION['login_view_folder']; ?>done.php");
        }
    });

    // Pagsusumite ng form ng pag-evaluate
    $('#manage-evaluation').submit(function(e){
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
            setTimeout(function(){
                $('.alert').remove(); // Alisin ang alert message pagkatapos ng 1 segundo
            }, 1000);
            return;
        }

        start_load(); // Simulan ang pag-loading

        // Gumawa ng AJAX request para i-submit ang form ng evaluation
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
                        text: 'Your evaluation form has been submitted.',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = './indexx.php?page=evaluate'; // I-redirect sa evaluate page pagkatapos ng pagsusumite
                        }
                    });
                }
                end_load(); // Tapusin ang pag-loading
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText); // I-log ang error response kung may error
            }
        });
    });
</script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>