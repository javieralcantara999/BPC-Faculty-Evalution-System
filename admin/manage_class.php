<?php
include '../db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM class_list where id={$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
    <form action="" id="manage-class">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class="form-group"></div>
        <div class="form-group">
            <label for="curriculum" class="control-label">Program</label>
            <input type="text" placeholder="Enter program name (e.g., BSIS)"class="form-control form-control-sm" name="curriculum" id="curriculum" value="<?php echo isset($curriculum) ? $curriculum : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="level" class="control-label">Year</label>
            <select class="form-control form-control-sm" name="level" id="level" required>
                <option value="" disabled selected>Select Year</option>
                <option value="1" <?php if(isset($level) && $level == '1') echo 'selected'; ?>>1st</option>
                <option value="2" <?php if(isset($level) && $level == '2') echo 'selected'; ?>>2nd</option>
                <option value="3" <?php if(isset($level) && $level == '3') echo 'selected'; ?>>3rd</option>
                <option value="4" <?php if(isset($level) && $level == '4') echo 'selected'; ?>>4th</option>
            </select>
        </div>
        <div class="form-group">
            <label for="section" class="control-label">Section</label>
            <input type="text" placeholder="Enter section (A-Z)"class="form-control form-control-sm" name="section" id="section" value="<?php echo isset($section) ? $section : '' ?>" required>
        </div>
        
    </form>
</div>
<script>
    $(document).ready(function () {
    // Bind input event handlers to detect errors live
    $('#curriculum').on('input', function () {
        autoCapitalizeProgram();
        detectProgramError();
    });

    $('#section').on('input', function () {
        autoCapitalizeSection();
        detectSectionError();
    });

    $('#manage-class').submit(function (e) {
        e.preventDefault();
        start_load();
        $('#msg').html('');

        // Check if program name, section, and year level are filled out
        var programValue = $('#curriculum').val().trim().toUpperCase();
        var sectionValue = $('#section').val().trim().toUpperCase();
        var levelValue = $('#level').val();
        if (programValue === '' || sectionValue === '' || levelValue === null) {
            $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Please fill out all fields.</div>');
            end_load();
            return;
        }

        // Validation for Program and Section
        if (!isValidProgram(programValue)) {
            $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Invalid Program. Please enter a valid program name.</div>');
            end_load();
            return;
        }

        if (!isValidSection(sectionValue)) {
            $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Invalid Section. Please enter a single character (A-Z) for the section.</div>');
            end_load();
            return;
        }

        // Proceed to AJAX if all validations pass
        $.ajax({
            url: 'ajax.php?action=save_class',
            method: 'POST',
            data: $(this).serialize(),
            success: function (resp) {
                if (resp == 1) {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Section successfully saved.');
                    setTimeout(function () {
                        location.reload()
                    }, 1000)
                } else if (resp == 2) {
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Class already exists.</div>');
                    end_load();
                }
            }
        });
    });

    // Function to auto-capitalize the program name
    function autoCapitalizeProgram() {
        var programValue = $('#curriculum').val().toUpperCase();
        $('#curriculum').val(programValue);
    }

    // Function to auto-capitalize the section
    function autoCapitalizeSection() {
        var sectionValue = $('#section').val().toUpperCase();
        $('#section').val(sectionValue);
    }

    // Function to detect Program error live
    function detectProgramError() {
        var programValue = $('#curriculum').val().toUpperCase();
        if (!isValidProgram(programValue)) {
            $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Invalid Program. Please enter a valid program name.</div>');
        } else {
            $('#msg').html('');
        }
    }

    // Function to detect Section error live
    function detectSectionError() {
        var sectionValue = $('#section').val().toUpperCase();
        if (!isValidSection(sectionValue)) {
            $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Invalid Section. Please enter a single character (A-Z) for the section.</div>');
        } else {
            $('#msg').html('');
        }
    }

    // Function to validate Program
    function isValidProgram(program) {
        var regex = /^[a-zA-Z\s]+$/;
        return regex.test(program);
    }

    // Function to validate Section
    function isValidSection(section) {
        var regex = /^[A-Za-z]$/;
        return regex.test(section);
    }
});
</script>