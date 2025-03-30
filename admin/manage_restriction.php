<?php
include '../db_connect.php';
?>
<div class="container-fluid">
	<form action="" id="manage-restriction">
		<div class="row">
			<div class="col-md-12">
				<input type="hidden" name="academic_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
				<div id="msg" class="form-group"></div>
				<div class="form-group">
					<label for="" class="control-label">Instructor</label>
					<select name="" id="faculty_id" class="form-control form-control-sm select2">
						<option value=""></option>
						<?php 
						$faculty = $conn->query("SELECT *,concat(firstname,' ',lastname) 
                        as name FROM faculty_list order by concat(firstname,' ',lastname) asc");
						$f_arr = array();
						while($row=$faculty->fetch_assoc()):
							$f_arr[$row['id']]= $row;
						?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($faculty_id) && $faculty_id == $row['id'] ? 
                        "selected" : "" ?>><?php echo ucwords($row['name']) ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="" class="control-label">Section</label>
					<select name="" id="class_id" class="form-control form-control-sm select2">
						<option value=""></option>
						<?php 
						$classes = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as class FROM class_list");
						$c_arr = array();
						while($row=$classes->fetch_assoc()):
							$c_arr[$row['id']]= $row;
						?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($class_id) && $class_id == $row['id'] ? "selected" : "" ?>><?php echo $row['class'] ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="" class="control-label">Subject</label>
					<select name="" id="subject_id" class="form-control form-control-sm select2">
						<option value=""></option>
						<?php 
						$subject = $conn->query("SELECT id,concat(code,' - ',subject) as subj FROM subject_list");
						$s_arr = array();
						while($row=$subject->fetch_assoc()):
							$s_arr[$row['id']]= $row;
						?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($subject_id) && $subject_id == $row['id'] ? "selected" : "" ?>><?php echo $row['subj'] ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<div class="form-group">
					<div class="d-flex w-100 justify-content-center">
						<button class="btn btn-sm  btn-success bg-gradient-success" id="add_to_list" type="button">Activate</button><br>
					</div>
				</div>
			</div><hr>
			<div class="col-md-12"><hr>
            <h5 class="text-center"><b>Available Evaluations</b></h5><br>
				<table class="table table-condensed table-bordered" id="r-list">
					<thead>
						<tr>
							<th width="30%">Subject Name</th>
							<th width="10%">Section</th>
							<th width="20%">Instructor</th>
							<th width="10%"class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$restriction = $conn->query("SELECT * FROM restriction_list where academic_id = {$_GET['id']} order by id asc");
						while($row=$restriction->fetch_assoc()):
						?>
						<tr>
							<td>
								<b><?php echo isset($s_arr[$row['subject_id']]) ? $s_arr[$row['subject_id']]['subj'] : '' ?></b>
								<input type="hidden" name="subject_id[]" value="<?php echo $row['subject_id'] ?>">
							</td>
							<td>
								<b><?php echo isset($c_arr[$row['class_id']]) ? $c_arr[$row['class_id']]['class'] : '' ?></b>
								<input type="hidden" name="class_id[]" value="<?php echo $row['class_id'] ?>">
							</td>
							<td>
								<b><?php echo isset($f_arr[$row['faculty_id']]) ? $f_arr[$row['faculty_id']]['name'] : '' ?></b>
								<input type="hidden" name="rid[]" value="<?php echo $row['id'] ?>">
								<input type="hidden" name="faculty_id[]" value="<?php echo $row['faculty_id'] ?>">
							</td>
							<td class="text-center">
								<button class="btn btn-sm btn-danger btn-gradient-danger" 
                                onclick="$(this).closest('tr').remove()" type="button"><i class="fa fa-trash"> Delete</i></button>
							</td>
						</tr>
					<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>
<script>
	$(document).ready(function(){
		$('.select2').select2({
		    placeholder:"Please select here",
		    width: "100%"
		  });
		$('#manage-restriction').submit(function(e){
			e.preventDefault();
			start_load()
			$('#msg').html('')
			$.ajax({
				url:'ajax.php?action=save_restriction',
				method:'POST',
				data:$(this).serialize(),
				success:function(resp){
					if(resp == 1){
                        toastr.options.positionClass = 'toast-top-center';
                        toastr.success('Successfuly saved.');
						setTimeout(function(){
							location.reload()	
						},1000)
					}else if(resp == 2){
						$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Class already exist.</div>')
						end_load()
					}
				}
			})
		})
		$('#add_to_list').click(function(){
    start_load();
    var frm = $('#manage-restriction');
    var cid = frm.find('#class_id').val();
    var fid = frm.find('#faculty_id').val();
    var sid = frm.find('#subject_id').val();
    var f_arr = <?php echo json_encode($f_arr) ?>;
    var c_arr = <?php echo json_encode($c_arr) ?>;
    var s_arr = <?php echo json_encode($s_arr) ?>;
    
    // Check if the selected subject_id and class_id combination already exists in the list
    var exists = false;
    $('#r-list tbody tr').each(function(){
        var row_subject_id = $(this).find('input[name="subject_id[]"]').val();
        var row_class_id = $(this).find('input[name="class_id[]"]').val();
        
        if(row_subject_id == sid && row_class_id == cid){
            exists = true;
            return false; // Exit loop if match is found
        }
    });
    
    if(exists){
        // Display error message if subject_id and class_id combination already exists
                        toastr.options.positionClass = 'toast-top-center';
                        toastr.error('Section and subject already exists.');
						setTimeout(function(){
						},2000)
        end_load();
        return;
    }

    // Add the new entry to the list if not already existing
    var tr = $("<tr></tr>");
    tr.append('<td><b>'+s_arr[sid].subj+'</b><input type="hidden" name="subject_id[]" value="'+sid+'"></td>');
    tr.append('<td><b>'+c_arr[cid].class+'</b><input type="hidden" name="class_id[]" value="'+cid+'"></td>');
    tr.append('<td><b>'+f_arr[fid].name+'</b><input type="hidden" name="rid[]" value=""><input type="hidden" name="faculty_id[]" value="'+fid+'"></td>');
    tr.append('<td class="text-center"><span class="btn btn-sm btn-danger btn-gradient-danger" onclick="$(this).closest(\'tr\').remove()" type="button"><i class="fa fa-trash"> Delete</i></span></td>');
    $('#r-list tbody').append(tr);

    // Reset form fields after adding entry
    frm.find('#class_id').val('').trigger('change');
    frm.find('#faculty_id').val('').trigger('change');
    frm.find('#subject_id').val('').trigger('change');

    end_load();
});
	})

</script>