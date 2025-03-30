<?php
include '../db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM subject_list where id={$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>

<div class="container-fluid">
	<form action="" id="manage-subject">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div id="msg" class="form-group"></div>
		<div class="form-group">
			<label for="subject" class="control-label">Subject Code</label>
			<input type="text" placeholder="(e.g., IS-MIS-113)" class="form-control form-control-sm"
             name="code" id="code" value="<?php echo isset($code) ? strtoupper($code) : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="subject" class="control-label">Subject Name</label>
			<input type="text" placeholder="Enter subject name (e.g., Management Information Systems)" 
            class="form-control form-control-sm" name="subject" id="subject"
             value="<?php echo isset($subject) ? ucwords($subject) : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="description" class="control-label">Description</label>
			<textarea placeholder="Subject Description (Optional)" name="description" id="description" cols="30" rows="4" class="form-control" required><?php echo isset($description) ? $description : '' ?></textarea>
		</div>
	</form>
</div>
<script>
$(document).ready(function(){
    // Function to auto-correct the format of the subject code and display live error detection
    $('#code').on('input', function() {
        var val = this.value.toUpperCase(); // Convert input to uppercase
        val = val.replace(/[^A-Z0-9]/g, ''); // Remove non-alphanumeric characters
        if (val.length > 2) {
            val = val.substring(0, 2) + '-' + val.substring(2); // Insert hyphen after the first two characters
        }
        if (val.length > 6) {
            val = val.substring(0, 6) + ' ' + val.substring(6); // Insert space after the sixth character
        }
        this.value = val;

        // Check if the format is correct
        if (!validateSubjectCode(val)) {
            $('#code').addClass('border border-danger'); // Add red border
            $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Invalid subject code format. Please follow the format XX-XXX XXX.</div>'); // Display error message
        } else {
            $('#code').removeClass('border border-danger'); // Remove red border
            $('#msg').html(''); // Clear error message
        }
        checkFormValidity(); // Check form validity after input change
    });

    // Function to validate the subject code format
    function validateSubjectCode(code) {
        // Regular expression pattern to validate the subject code format
        var code_pattern = /^[A-Z0-9]{2}-[A-Z0-9]{3}\s[A-Z0-9]{3}$/;
        return code_pattern.test(code);
    }

    // Function to capitalize the first letter of each word in the subject name and check for invalid characters
    $('#subject').on('input', function() {
    var subjectName = $(this).val();
    // Check for invalid characters
    if (!/^[a-zA-Z0-9\s]+$/.test(subjectName)) {
        // Show warning message
        $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Invalid characters detected.</div>');
        // Add red border to input field
        $(this).addClass('border border-danger');
    } else {
        // Remove warning message
        $('#msg').html('');
        // Remove red border
        $(this).removeClass('border border-danger');
    }

    // Capitalize the first letter of each word in the subject name
    this.value = this.value.replace(/\b\w/g, function(char) {
        return char.toUpperCase();
    });
    checkFormValidity(); // Check form validity after input change
});

    $('#description').on('input', function() {
        this.value = this.value.replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    });

    // Function to check form validity and disable/enable save button accordingly
    function checkFormValidity() {
        var code = $('#code').val().trim(); // Get subject code value
        var subject = $('#subject').val().trim(); // Get subject name value

        // Check if subject code or name is blank or if there are error messages
        if (code === '' || subject === '' || $('#msg').html() !== '') {
            $('#save-btn').prop('disabled', true); // Disable save button
        } else {
            $('#save-btn').prop('disabled', false); // Enable save button
        }
    }

    // Call checkFormValidity on document ready
    checkFormValidity();

    $('#manage-subject').submit(function(e){
        e.preventDefault(); // Pigilan ang default behavior ng form submission
    
        var code = $('#code').val().trim(); // Get subject code value
        var subject = $('#subject').val().trim(); // Get subject name value
    
        // Check if subject code or name is blank
        if (code === '' || subject === '') {
            $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Subject code and name are required.</div>');
            return; // Exit submission process
        }

        // Check if there are errors detected by live validation
        if ($('#msg').html() !== '') {
            return; // Exit submission process
        }

        start_load()
        $('#msg').html('')
        $.ajax({
            url:'ajax.php?action=save_subject',
            method:'POST',
            data:$(this).serialize(),
            success:function(resp){
                if(resp == 1){
                    toastr.options.positionClass = 'toast-top-center';// Set position to middle center
                    toastr.success('Subject successfully added.');
                    setTimeout(function(){
                        location.reload()    
                    },1000)
                }else if(resp == 2){
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Subject Code already exist.</div>')
                    end_load()
                }
            }
        })
    });
});
</script>
<style>
    .form-group {
    margin-bottom: 20px;
}

.form-control {
    border: 1px solid #ced4da;
    border-radius: .25rem;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.container-fluid {
    top:0;
}
textarea.form-control {
    height: auto;
}

.alert {
    margin-bottom: 0;
}

#msg {
    margin-top: 10px;
}
</style>