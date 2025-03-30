<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM academic_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
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
// Initialize an empty array to hold questions
$q_arr = array();

// Query to retrieve questions from the database
$questions = $conn->query("SELECT * FROM question_list");

// Loop through each row and populate the $q_arr array
while ($row = $questions->fetch_assoc()) {
    $q_arr[$row['id']] = $row;
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<h1 class="m-0 text-center"><i class = "fas fa-file-alt"></i>&nbsp;<b>MANAGE QUESTIONS (STUDENTS)</b></h1><br><hr>
<div class="container-fluid">
    
	<div class="row mt-1">
		<div class="col-md-4">
        <div class="card-tools text-right" style ="margin-top:45px;">
						<button class="btn btn-sm btn-success bg-gradient-success mx-1"style = "display:none" type="button">Available Evaluation Forms</button>
						
					</div>
			<div class="card card-success">
				<div class="card-header" style = "background:darkgreen;color:white;">
					<b>Question Form</b>
				</div>
				<div class="card-body">
					<form action="" id="manage-question">
						<input type="hidden" name="academic_id" value="<?php echo isset($id) ? $id : '' ?>">
						<input type="hidden" name="id" value="">
						<div class="form-group">
							<label for="">Criteria</label>
							<select name="criteria_id" id="criteria_id" class="custom-select custom-select-sm select2">
								<option value=""></option>
							<?php 
								$criteria = $conn->query("SELECT * FROM criteria_list order by abs(order_by) asc ");
								while($row = $criteria->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>"><?php echo $row['criteria'] ?></option>
							<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
                            <label for="">Question</label>
                            <select name="question" id="question_id" class="custom-select custom-select-sm select2">
                                <option value=""></option>
                                <?php 
                                $questions = $conn->query("SELECT * FROM questions_list");
                                while ($row = $questions->fetch_assoc()) {
                                    echo '<option value="' . $row['questions'] . '">' . $row['questions'] . '</option>';
                                }
                                ?>
                            </select>

                        </div>
					</form>
				</div>
				<div class="card-footer">
					<div class="d-flex justify-content-end w-100">
						<button class="btn btn-sm btn-success bg-gradient-success mx-1" form="manage-question">Save</button>
						<button class="btn btn-sm btn-secondary bg-gradient-secondary mx-1" form="manage-question" type="reset">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
        <div class="card-tools text-right" style ="padding-bottom:15px;">
						<button class="btn btn-sm btn-success bg-gradient-success mx-1"
                         id="eval_restrict" type="button">Available Evaluation Forms</button>
						
					</div>
			<div class="card">
                
				<div class="card-header text-center" style = "background-color: darkgreen;color:white;border-bottom:2px solid darkgreen;">
					<b>School Year: <?php echo $year.' '.(ordinal_suffix($semester)) ?> Semester</b>
					
				</div>
				<div class="card-body">
                    
					<h4 >
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
                            <fieldset class="border p-2 w-90 border-success" style = "text-align: center;">
                        <legend  class="w-auto">Rating Scale</legend>
                        <p><b>
                                        1 - Rarely <span style = "padding-left:25px"id = "rtg"></span>
                                        2 - Once in a while <span style = "padding-left:25px"id = "rtg"></span>
                                        3 - Sometimes <span style = "padding-left:25px"id = "rtg"></span>
                                        4 - Most of the time <span style = "padding-left:25px"id = "rtg"></span>
                                        5 - Always
                            </b>
                        </p>
                        </fieldset>
                        </center>
                        <br>
					<form id="order-question">
					<div class="clear-fix mt-2"></div>
					<?php
$criteria = $conn->query("SELECT * FROM criteria_list ORDER BY abs(order_by) ASC");
while ($crow = $criteria->fetch_assoc()) {
    $criteria_id = $crow['id'];
    $questions = $conn->query("SELECT * FROM question_list WHERE criteria_id = $criteria_id AND academic_id = $id ORDER BY abs(order_by) ASC");
    ?>
    <table class="table table-condensed">
        <thead>
            <tr class="bg-gradient-success" style="background:green;">
                <th colspan="2" class="p-1"><b><?php echo $crow['criteria'] ?></b></th>
                <th class="text-center">1</th>
                <th class="text-center">2</th>
                <th class="text-center">3</th>
                <th class="text-center">4</th>
                <th class="text-center">5</th>
            </tr>
        </thead>
        <tbody class="tr-sortable">
            <?php while ($row = $questions->fetch_assoc()): ?>
                <tr class="bg-white">
                    <td class="p-1 text-center" width="5px">
                        <span class="btn-group dropright">
                            <span type="button" class="btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-caret-right"></i>
                            </span>
                            <div class="dropdown-menu">
                                <a class="dropdown-item dropdown-item-success delete_question" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </div>
                        </span>
                    </td>
                    <td class="p-1" width="40%">
                        <?php echo $row['question'] ?>
                        <input type="hidden" name="qid[]" value="<?php echo $row['id'] ?>">
                    </td>
                    <?php for ($c = 0; $c < 5; $c++): ?>
                        <td class="text-center">
                            <div class="icheck-success d-inline">
                                <input type="radio" name="qid[<?php echo $row['id'] ?>][]" id="qradio<?php echo $row['id'].'_'.$c ?>">
                                <label for="qradio<?php echo $row['id'].'_'.$c ?>"></label>
                            </div>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php } ?>
					</form>
                    <button class="btn btn-sm btn-success bg-gradient-success mx-1 text-center" form="order-question">Save Order</button>
            
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
    }
</style>
<script>
    
    $(document).ready(function() {
        $('#list').dataTable({
            "paging": false, // Ito ay nagpapahiwatig na huwag ipakita ang pagination controls
            "searching": true, // Ito ay nagpapahiwatig na ipakita ang search bar
            "info": false, // Ito ay nagpapahiwatig na huwag ipakita ang "Showing [start] to [end] of [total] entries" label
            "ordering": false // Ito ay nagpapahiwatig na huwag payagan ang sorting ng table
        });
    });
    $(document).ready(function(){
    $('.select2').select2({
        placeholder: "Please select here",
        width: "100%"
    });

    $('.delete_question').click(function(){
        var id = $(this).attr('data-id');
            delete_question(id); // Call delete_question function when button is clicked
    });

    $('#eval_restrict').click(function(){
        uni_modal("Evaluation Forms", "<?php echo $_SESSION['login_view_folder'] ?>manage_restriction.php?id=<?php echo $id ?>", "mid-large");
    });

    $('.tr-sortable').sortable()
    
    $('#manage-question').on('reset', function(){
    $(this).find('input[name="id"]').val('');
    $(this).find("[name='criteria_id']").val('').trigger('change');
    $(this).find('select').val('').trigger('change.select2');
    });

    $('#manage-question').submit(function(e){
    e.preventDefault(); // Iwasan ang default form submission
    start_load(); // Simulan ang loading indicator
    // Kunin ang form data
    var formData = new FormData($(this)[0]);
    $.ajax({
    url: 'ajax.php?action=save_question',
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    method: 'POST',
    type: 'POST',
    success: function(resp) {
        console.log(resp); // Debug response
        if (resp == 1) {
            toastr.success('Question successfully saved.');
            setTimeout(function() {
                location.reload(); // I-refresh ang page pagkatapos ng pag-save
                end_load(); // Itigil ang loading indicator
            }, 1000);
        }else if(resp == 2){
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.error('Question already exists at the form.');
        end_load();
                } else {
            toastr.error('Error saving question.');
            
        end_load();
        }
        
    },
    error: function(xhr, textStatus, errorThrown) {
        console.log("Error: " + errorThrown);
        toastr.error('Error: ' + textStatus);
    }
});
});

    $('#order-question').submit(function(e){
        e.preventDefault();
        start_load();

        $.ajax({
            url: 'ajax.php?action=save_question_order',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp){
                if(resp == 1){
                    
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Arrangement successfully saved.');
                    end_load();
                }else {
                    toastr.error('Error saving question.');
                }

            }
        });
    });

    function delete_question(id) {
    start_load();
    $.ajax({
        url: 'ajax.php?action=delete_question',
        method: 'POST',
        data: {id: id},
        success: function(resp) {
            if(resp == 1) {
                toastr.options.positionClass = 'toast-top-center';
                toastr.success('Question successfully deleted.');
                setTimeout(function() {
                    location.reload(); // I-refresh ang page pagkatapos ng pag-delete
                }, 1000);
            } else {
                toastr.error('Error deleting question.');
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log("Error: " + errorThrown);
            toastr.error('Error: ' + textStatus);
            end_load(); // Siguraduhing itigil ang loading indicator sa case ng error
        }
    });
}

});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>