
<?php 
include 'db_connect.php';
$faculty_id = isset($_GET['fid']) ? $_GET['fid'] : '';

function ordinal_suffix($num){
    $num = $num % 100; // protektahan laban sa malalaking numero
    if($num < 11 || $num > 13){
         switch($num % 10){
            case 1: return $num.'st';
            case 2: return $num.'nd';
            case 3: return $num.'rd';
        }
    }
    return $num.'th';
}
?>

<h1 class="m-0 text-center"><i class="fas fa-chart-bar"></i>&nbsp;<b>STUDENT EVALUATION REPORT</b></h1><br><hr>
<div class="col-lg-11">
    
    
    <style>
        #print-btn {
            background:green;
            color:white;
        }
        #print-btn:hover {
            background:red;
        }
    </style>
    <div class="row">
        <div class="col-md-3">
        <div class="callout" style="border:none">
        <div class=" w-10 justify-content-center align-items-center text-center">
            <label for="faculty" class = "text-center"style = "margin-left:5px;font-size:20px">Faculty Member</label><br>
            <div class=" mx-0 col-md-12">
                <select name="" id="faculty_id" class="form-control form-control-sm select2" style = "border:none;width:200px;">
                    <option value=""disable selected>Select Faculty</option>
                    <?php 
                    $faculty = $conn->query("SELECT *, concat(firstname,' ',lastname) 
                    as name FROM faculty_list ORDER BY concat(firstname,' ',lastname) ASC");
                    $f_arr = array();
                    $fname = array();
                    while($row = $faculty->fetch_assoc()):
                        $f_arr[$row['id']] = $row;
                        $fname[$row['id']] = ucwords($row['name']);
                    ?>
                    <option value="<?php echo $row['id'] ?>" 
                    <?php echo isset($faculty_id) && $faculty_id == $row['id'] ? "selected" : "" ?>
                    ><?php echo ucwords($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </div>
            <!-- Feedback Container -->
            <div class="callout mt-4 p-3" style="border:none;">
                <h5 class = "text-center"><b>Feedbacks & Comments</b></h5>
                <div id="feedbacks-container">
                <div class="d-flex justify-content-center w-100">
                    <button class="btn btn-sm btn-success bg-gradient-success" id="feedbacks-btn"><i class="fa fa-comment"></i> View Feedbacks</button>
                </div>
                </div>
            </div>
            <!-- End ng Feedback Container -->
            
        </div>
        <div class="col-md-9">
            <div class="callout" id="printable" style="border:0px;">
                <div>
                    <div style="text-align: center;">
                        <img src="./images/bpc.ico" alt="BPC Logo" style="width: 75px; height: 75px;"><br>
                        <h3 class="text-center">
                            <b>BULACAN POLYTECHNIC COLLEGE <br> </b>
                            <h4 class="text-center"><b>FACULTY EVALUATION SYSTEM</b></h4>
                        </h3><br>
                    </div>
                    <h4 class="text-center">EVALUATION REPORT</h4>
                    <hr>
                    <table width="100%">
                        <tr>
                            <td width="50%"><p><b>Instructor: <span id="fname"></span></b></p></td>
                            <td width="50%"><p><b>School Year: <span id="ay">
                                <?php echo $_SESSION['academic']['year'].' '.(ordinal_suffix($_SESSION['academic']['semester'])) ?>
                                Semester</span></b></p></td>
                        </tr>
                        <tr>
                            <td width="50%"><p><b>Section: <span id="classField"></span></b></p></td>
                            <td width="50%"><p><b>Subject: <span id="subjectField"></span></b></p></td><br>
                        </tr>
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
                $criteria = $conn->query("SELECT * FROM criteria_list WHERE id IN (SELECT criteria_id FROM question_list WHERE academic_id = {$_SESSION['academic']['id']} ) ORDER BY abs(order_by) ASC ");
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
    @media screen and (max-width: 768px) {
        #rt {
            display:flex;
        }
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
<script>
    $(document).ready(function(){
        $('#faculty_id').change(function(){
            if($(this).val() > 0)
                window.history.pushState({}, null, './indexx.php?page=report&fid='+$(this).val());
            load_class()
        })
        if($('#faculty_id').val() > 0)
            load_class()
    })
    function load_class(){
        start_load()
        $('.rates').text('0');
        $('.rate_total').text('0');
        $('#tse').text('0');
        $('#print-btn').hide();
        var fname = <?php echo json_encode($fname) ?>;
        $('#fname').text(fname[$('#faculty_id').val()])
        $.ajax({
            url:"ajax.php?action=get_class",
            method:'POST',
            data:{fid:$('#faculty_id').val()},
            error:function(err){
                console.log(err)
                alert_toast("An error occured",'error')
                end_load()
            },
            success:function(resp){
                if(resp){
                    resp = JSON.parse(resp)
                    if(Object.keys(resp).length <= 0 ){
                        $('#class-list').html('<a href="javascript:void(0)" class="list-group-item list-group-item-action disabled text-center" style = "text-decoration:none">No results found</a>')
                        $('.total_avg').text('0.00');
                    }else{
                        $('#class-list').html('')
                        Object.keys(resp).map(k=>{
                        $('#class-list').append('<a href="javascript:void(0)" data-json=\''+JSON.stringify(resp[k])+'\' data-id="'+resp[k].id+'" class="list-group-item list-group-item-action show-result" style = "text-decoration:none;background:white;color:black;">'+resp[k].class+' - '+resp[k].subj+'</a>')
                        })

                    }
                }
            },
            complete:function(){
                end_load()
                anchor_func()
                if('<?php echo isset($_GET['rid']) ?>' == 1){
                    $('.show-result[data-id="<?php echo isset($_GET['rid']) ? $_GET['rid'] : '' ?>"]').trigger('click')
                }else{
                    $('.show-result').first().trigger('click')
                }
            }
        })
    }
    function anchor_func(){
        $('.show-result').click(function(){
            $('.rates').text('0');
            $('.rate_total').text('0');
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

</script>
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
                $(document).ready(function() {
                $('#feedbacks-btn').click(function() {
                    // Dito mo isasagawa ang AJAX request para kumuha ng mga feedbacks
                    // Halimbawa:
                    $.ajax({
                        url: 'get_feedbacks.php', // Palitan ito ng tamang URL para sa iyong AJAX request
                        method: 'GET',
                        success: function(response) {
                            // Kapag natanggap mo na ang mga feedbacks, ilagay ito sa modal body
                            $('#feedbacks-list').html(response);
                            // Pagkatapos, buksan ang modal
                            $('#feedbacksModal').modal('show');
                        },
                        error: function(err) {
                            console.error('Error fetching feedbacks:', err);
                            // Kung may error, maaaring ipakita mo ang isang mensahe sa user
                            $('#feedbacks-list').html('<p>Error fetching feedbacks. Please try again later.</p>');
                            $('#feedbacksModal').modal('show');
                        }
                    });
                });
            });
            </script>