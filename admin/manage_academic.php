<div class="container-fluid">
    <form action="" id="manage-academic">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class="form-group"></div>
        <div class="form-group">
            <label for="academic_period" class="control-label">Academic Period</label>
            <select class="form-control form-control-sm" name="academic_period" id="academic_period" required>
                <option value="" disabled>Select Academic Period</option>
                <?php
                    // Loop through academic periods
                    $current_year = date('Y');
                    for ($i = $current_year; $i <= $current_year + 50; $i++) {
                        $start_year = $i;
                        $end_year = $i + 1;
                        $academic_period_1st = "$start_year-$end_year 1st Semester";
                        $academic_period_2nd = "$start_year-$end_year 2nd Semester";

                        // Create option tags for both 1st and 2nd semesters
                        echo "<option value='$start_year-$end_year-1'>$academic_period_1st</option>";
                        echo "<option value='$start_year-$end_year-2'>$academic_period_2nd</option>";
                    }
                ?>
            </select>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#academic_period').change(function() {
    var academic_period = $(this).val();
    var year = academic_period.split('-')[0];
    var nextYear = parseInt(year) + 1; // Calculate the next year
    var yearRange = year + '-' + nextYear; // Create the year range (e.g., 2024-2025)
    var semester = academic_period.split('-')[1];
    // Update the hidden input fields with the correct year range and semester
    $('#year').val(yearRange); // Set year as '2024-2025'
    $('#semester').val(semester); // Set semester (e.g., '2')
    });
    $('#manage-academic').submit(function(e) {
        e.preventDefault();
        start_load();
        $('#msg').html('');
        $.ajax({
            url: 'ajax.php?action=save_academic',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                if (resp == 1) {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success("Successfully added.", 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1750);
                } else if (resp == 2) {
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Academic Code already exists.</div>');
                    end_load();
                }
            }
        });
    });
});
</script>