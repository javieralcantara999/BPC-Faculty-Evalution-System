<script>
    document.title = "School Year | Administrator";
</script>

<?php 
include 'db_connect.php';

// Initialize or retrieve session variables
$restriction_id = isset($_SESSION['restriction_id']) ? $_SESSION['restriction_id'] : '';
$current_academic_id = isset($_SESSION['current_academic_id']) ? $_SESSION['current_academic_id'] : '';

// Retrieve the current academic year ID from the database
$current_academic_year = $conn->query("SELECT * FROM academic_list WHERE is_default = 1")->fetch_assoc();
$current_academic_id = $current_academic_year['id'];

// Assign the retrieved academic ID to the session variable
$_SESSION['current_academic_id'] = $current_academic_id;
?>

<h1 class="m-0 text-center"><i class="fas fa-calendar-alt"></i>&nbsp;<b>SCHOOL YEAR LIST</b></h1>
<br>
<hr>

<div class="col-lg-12" id="tbl">
	<div class="card card-success">
		<div class="card-body">
			<div class="card-tools float-left">
                <a class="btn btn-sm btn-gradient btn-success new_academic" href="javascript:void(0)">
                    <i class="fa fa-plus-circle"></i> Add School Year
                </a>
			</div>
			<table class="table table-hover table-bordered" id="list" style="text-align: left;">
				<colgroup id="cols">
					<col width="1%">
					<col width="30%">
					<col width="5%">
					<col width="5%">
					<col width="4%">
					<col width="2%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">No.</th>
						<th>School Year & Semester</th>
						<th class="text-center">School Year Status</th>
						<th class="text-center">Evaluation Status</th>
						<th class="text-center">Results Restriction</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT * FROM academic_list ORDER BY ABS(year) DESC, ABS(semester) DESC");
                    while ($row = $qry->fetch_assoc()) :
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td><b><?php echo $row['year'] . " " . ($row['semester'] == 1 ? '1st' : '2nd') ?> Semester</b></td>
                        <td class="text-center">
                            <?php if ($row['is_default'] == 0) : ?>
                                <button type="button" class="btn btn-secondary make_default" data-id="<?php echo $row['id'] ?>">Inactive</button>
                            <?php elseif ($row['is_default'] == 1) : ?>
                                <button type="button" class="btn btn-success">Active</button>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row['status'] == 2) : ?>
                                <button type="button" class="btn btn-danger start_evaluation" data-id="<?php echo $row['id'] ?>">Ended</button>
                            <?php elseif ($row['status'] == 1) : ?>
                                <button type="button" class="btn btn-success close_evaluation" data-id="<?php echo $row['id'] ?>">Started</button>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row['restriction'] == 0) : ?>
                                <button type="button" class="btn btn-danger unrestrict" data-id="<?php echo $row['id'] ?>">Closed</button>
                            <?php elseif ($row['restriction'] == 1) : ?>
                                <button type="button" class="btn btn-success restrict" data-id="<?php echo $row['id'] ?>">Opened</button>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-danger btn-gradient delete_academic" data-id="<?php echo $row['id'] ?>">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
			</table>
		</div>
	</div>
</div>
<style>
    @media screen and (max-width: 768px) {
    /* Adjust table styles for smaller screens */
    #tbl {
        width: 100%;
    }
    #cols {
        width: 5px;
    }
    table {
        overflow-x: auto;
        display: block;
    }
    }

    /* Center text in the table cells */
    .text-center {
        text-align: center !important;
    }

    /* Style for the "Add Academic Year" button on hover */
    #sub:hover {
        background: darkgreen !important;
    }

    @media screen and (max-width: 768px) {
        /* Adjust DataTable styles for smaller screens */
        #list_wrapper {
            width: 100%;
        }
        #list {
            width: 100%;
            overflow-x: auto;
            display: block;
        }

        /* Hide unnecessary DataTable controls */
        #list_wrapper .dataTables_length,
        #list_wrapper .dataTables_info,
        #list_wrapper .dataTables_paginate {
            display: none;
        }
        
        }
</style>

<script>
    // Use jQuery document ready function to ensure DOM is fully loaded
    $(document).ready(function() {
        
        // Retrieve current academic ID from PHP
        var currentActiveAcademicID = <?php echo $current_academic_id; ?>;

        // Loop through each row in the academic list table
        $('#list tbody tr').each(function() {
            var row = $(this);
            var academicID = row.find('.make_default').attr('data-id'); // Get academic ID from the row

            // Check if the current academic ID matches the row's academic ID
            if (academicID == currentActiveAcademicID) {
                // Disable appropriate evaluation buttons based on school year status
                if (row.find('.btn-success').length > 0) {
                    row.find('.start_evaluation').prop('disabled', true); // Disable Start Evaluation button
                    row.find('.restrict').prop('disabled', true); // Disable Start Evaluation button
                } else if (row.find('.btn-secondary').length > 0) {
                    row.find('.close_evaluation').prop('disabled', true); // Disable Close Evaluation button
                } else if (row.find('.btn-danger').length > 0) {
                    row.find('.unrestrict').prop('disabled', true); // Disable Close Evaluation button
                }
            }
        });

        // Add event handlers for various button clicks
        $('.new_academic').click(function() {
            uni_modal("ADD ACADEMIC YEAR", "<?php echo $_SESSION['login_view_folder'] ?>manage_academic.php");
        });

        $('.delete_academic').click(function() {
            _conf("Are you sure you want to delete this school year?", "delete_academic", [$(this).attr('data-id')]);
        });

        $('.make_default').click(function() {
            var academic_id = $(this).attr('data-id');
            // Call function to activate the selected school year as default
            _conf("Are you sure you want to activate this school year?", "make_default", [academic_id]);
        });

        $('.start_evaluation').click(function() {
            var academic_id = $(this).attr('data-id');
            // Call function to start evaluation for the selected school year
            _conf("Are you sure you want to start this evaluation?", "start_evaluation", [academic_id]);
        });

        $('.close_evaluation').click(function() {
            var academic_id = $(this).attr('data-id');
            // Call function to close evaluation for the selected school year
            _conf("Are you sure you want to end this evaluation?", "close_evaluation", [academic_id]);
        });
        $('.unrestrict').click(function() {
            var academic_id = $(this).attr('data-id');
            // Call function to start evaluation for the selected school year
            _conf("Are you sure you want to unrestrict the evaluation results?", "unrestrict", [academic_id]);
        });

        $('.restrict').click(function() {
            var academic_id = $(this).attr('data-id');
            // Call function to close evaluation for the selected school year
            _conf("Are you sure you want to restrict the evaluation results?", "restrict", [academic_id]);
        });

        // Initialize DataTable
        $('#list').dataTable({
            "paging": false,
            "searching": true,
            "info": false,
            "ordering": false
        });
    });

    // Functions for AJAX operations
    function start_evaluation(academic_id) {
        
        start_load()
        $.ajax({
            url: 'ajax.php?action=start_evaluation',
            method: 'POST',
            data: { id: academic_id },
            success: function(resp) {
                if (resp == 1) {
                    toastr.success("Successfully started the evaluation.", 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else if(resp == 2)
                {
                    toastr.error("Cannot start the inactive school year. Please try again.", 'Failed');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else{
                    toastr.error("An error has been occured. Please try again.", 'Failed');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }

    function close_evaluation(academic_id) {
        
        start_load()
        $.ajax({
            url: 'ajax.php?action=close_evaluation',
            method: 'POST',
            data: { id: academic_id },
            success: function(resp) {
                if (resp == 1) {
                    toastr.success("Successfully closed evaluation.", 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else if(resp == 2)
                {
                    toastr.error("An error has been occured. Please try again.", 'Failed');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else{
                    toastr.error("An error has been occured. Please try again.", 'Failed');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }

    function unrestrict(academic_id) {
        
        start_load()
        $.ajax({
            url: 'ajax.php?action=unrestrict',
            method: 'POST',
            data: { id: academic_id },
            success: function(resp) {
                if (resp == 1) {
                    toastr.success("Successfully unrestricted the results for faculty members/instructors.", 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else if(resp == 2)
                {
                    toastr.error("Cannot modify the resctrictions for inactive school year.", 'Failed');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else{
                    toastr.error("An error has been occured. Please try again.", 'Failed');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }

    function restrict(academic_id) {
        
        start_load()
        $.ajax({
            url: 'ajax.php?action=restrict',
            method: 'POST',
            data: { id: academic_id },
            success: function(resp) {
                if (resp == 1) {
                    toastr.success("Successfully restricted the results.", 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else if(resp == 2)
                {
                    toastr.error("Action cannot be done.", 'Failed');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else{
                    toastr.error("An error has been occured. Please try again.", 'Failed');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }

    function delete_academic(id) {
        
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_academic',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    toastr.success("Successfully deleted.", 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }
    function make_default(id) {
        
    start_load()
        $.ajax({
            url: 'ajax.php?action=make_default',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    toastr.success("Successfully updated.", 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }
</script>