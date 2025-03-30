<?php include 'db_connect.php'?>
<script>
    document.title = "Evaluation Criteria (Superiors) | Administrator";
    </script>
<h1 class="m-0 text-center"><i class="fas fa-clipboard-list"></i>&nbsp;<b>EVALUATION CRITERIA LIST (SUPERIOR)</b></h1><br><hr>
<div class="col-lg-12" id="tbl">
    <div class="card card-success">
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="callout" style="border: 0px;">
                            <?php 
                                $qry = $conn->query("SELECT * FROM criteria_list_superior order by abs(order_by) asc ");
                                if($qry->num_rows > 0):
                            ?>
                            <div class="justify-content-center w-40"style = "font-size:25px;">
                                <label for=""><b>Criteria for Evaluation</b></label>
                                <input type="text" id="searchCriteria" class="form-control mt-2" placeholder="Search criteria here...">
                            </div>
                            <hr>
                            <form action="" id="order-criteria">
                                <ul class="list-group btn col-md-12 " id="ui-sortable-list">
                                    <?php
                                    $criteria = array();
                                    while($row= $qry->fetch_assoc()):
                                        $criteria[$row['id']] = $row; 
                                    ?>
                                    <li class="list-group-item text-left">
                                        <span class="btn-group dropright float-right">
                                          <div class="btn-group" >
                                            <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' 
                                            class="btn btn-success btn-sm btn-gradient edit_criteria_superior"
                                            style = "text-decoration: none;color:white;">
                                                <i class="fas fa-cog"></i> Edit
                                            </a>&nbsp;
                                            <button type="button" class="btn btn-sm btn-danger btn-gradient delete_criteria_superior" 
                                            data-id="<?php echo $row['id'] ?>">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                        </span>
                                        <i class="fas fa-greater-than justify-content-center"style="margin-right:20px;">
                                        &nbsp;</i> <?php echo ucwords($row['criteria']) ?>
                                        <input type="hidden" name="criteria_id[]" value="<?php echo $row['id'] ?>">
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                            </form>
                            <?php else: ?>
                                <center>There's no criteria in the database yet</center>
                            <?php endif; ?>
                            
                            <h6 class = "text-center"><i>Please drag the criteria item to change orders.</i></h6>

                                <button class="btn btn-sm btn-success bg-gradient-success mx-6 text-right"
                                 form="order-criteria"style="font-size:15px;">Save List</button>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                    <div class="form-group mb-1 d-flex" >
                            <form method="post" id = "import_criteria_superior"enctype="multipart/form-data" class="ml-1">
                                <input type="file" class="form-control-file" name="exceldata" id="exceldata"><br>
                                <button type="submit" name="import" class="btn btn-primary btn-sm">
                                    <i class = "fas fa-file-excel"></i>&nbsp; Import criteria from excel</button>
                            </form>
                            </div>
                            <br>
                        <div class="card">
                            <div class="card-header text-center"style = "background:darkgreen;color:white">
                                <b>Criteria Information</b>
                            </div>
                            <div class="card-body">
                                <form action="" id="manage-criteria">
                                    <input type="hidden" name="id">
                                    <div class="form-group">
                                        <label for="">Criteria Name</label>
                                        <input type="text" name="criteria" class="form-control form-control-sm">
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer "style="background:white;">
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-sm btn-success bg-gradient-success mx-1"
                                     form="manage-criteria">Confirm</button>
                                    <button class="btn btn-sm btn-secondary bg-gradient-secondary mx-1"
                                     form="manage-criteria" type="reset">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
</style>
<script>
    $(document).ready(function() {
        $('#searchCriteria').on('input', function() {
            var searchText = $(this).val().trim().toLowerCase();
            filterCriteria(searchText);
        });
    });
    function filterCriteria(searchText) {
            $('#ui-sortable-list li').each(function() {
                var criteriaText = $(this).text().toLowerCase();
                var isVisible = criteriaText.includes(searchText);
                $(this).toggle(isVisible);
            });

            // Show "No criteria found" message if no items are visible
            var noCriteriaFound = $('#ui-sortable-list li:visible').length === 0;
            $('#noCriteriaFound').toggle(noCriteriaFound);
        }
    
    $('#searchCriteria').on('input', function() {
        var searchText = $(this).val().trim().toLowerCase();
        filterCriteria(searchText);

        // I-check kung blangko ang searchText, pagkakataon na ipakita ulit ang lahat ng tanong
        if (searchText === '') {
            $('#ui-sortable-list li').show();
        }
        var noCriteriaFound = $('#ui-sortable-list .question-item:visible').length === 0;
        $('#noCriteriaFound').toggle(noCriteriaFound); // Ipakita ang "No questions found" message kung walang result
    });
     $(document).ready(function() {
    $('#import_criteria_superior').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = new FormData(this);
        start_load()
        $.ajax({
            url: 'ajax.php?action=import_criteria_superior',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 1) {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Criterias imported successfully.');
                    setTimeout(function() {
                        location.reload();// Redirect to class_list.php
            end_load()
                    }, 1000);
                } else {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.error(response); // Display error message
            end_load()
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText); // Log any AJAX errors
            end_load()
            }
        });
    });
});
	$(document).ready(function() {
        $('#ui-sortable-list').sortable({
            update: function(event, ui) {
                // Triggered when sorting has stopped
                var criteriaOrder = $(this).sortable('toArray', { attribute: 'data-id' });

                // Update the order of criteria on the server
                $.ajax({
                    url: 'ajax.php?action=save_criteria_order_superior',
                    method: 'POST',
                    data: { criteriaOrder: criteriaOrder },
                    success: function(resp) {
                        if (resp == 1) {
                            toastr.options.positionClass = 'toast-top-center';
                            toastr.success("Criteria successfully ordered.", 'Success');
                        }
                    }
                });
            }
        }); 
        $('#manage-criteria').on('reset', function() {
            $(this).find('input:hidden').val('');
        });

        $('.edit_criteria_superior').click(function() {
            var id = $(this).attr('data-id');
            var criteria = <?php echo json_encode($criteria); ?>;
            $('#manage-criteria').find("[name='id']").val(criteria[id].id);
            $('#manage-criteria').find("[name='criteria']").val(criteria[id].criteria);
        });

        $('.delete_criteria_superior').click(function() {
            _conf("Are you sure to delete this criteria?", "delete_criteria", [$(this).attr('data-id')]);
        });
        
        toastr.options = {
        closeButton: true,
        progressBar: false,
        positionClass: 'toast-top-center', // Position the toast alert at the center
        preventDuplicates: true, // Prevent duplicate toasts
        showDuration: 300,
        hideDuration: 500,
        timeOut: 2500, // Duration the toastr is displayed (in milliseconds)
        extendedTimeOut: 500 // Duration to close the toastr after a user hovers over it (in milliseconds)
    };
        $('#manage-criteria').submit(function(e) {
            e.preventDefault();

            var criteriaName = $('input[name="criteria"]').val().trim();

            // Check if the criteria name is empty
            if (criteriaName === '') {
                // Display a toastr error message
                toastr.error('Please enter a criteria name before saving.', 'Error');
                return; // Prevent form submission if criteria name is blank
            }
            start_load();
            $('#msg').html('');
            $.ajax({
                url: 'ajax.php?action=save_criteria_superior',
                method: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp == 1) {
                        toastr.options.positionClass = 'toast-top-center';
                        toastr.success("Criteria successfully saved.", 'Success');
                        setTimeout(function() {
                            location.reload();
            end_load()
                        }, 1000);
                    } else if (resp == 2) {
                        $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Criteria already exists.</div>');
                      
            end_load()
                    }
                }
            });
        });

        $('#order-criteria').submit(function(e) {
            e.preventDefault();
            start_load();
            $.ajax({
                url: 'ajax.php?action=save_criteria_order_superior',
                method: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp == 1) {
                        toastr.options.positionClass = 'toast-top-center';
                        toastr.success("Criteria successfully ordered.", 'Success');
                        setTimeout(function() {
                            location.reload();
            end_load()
                        }, 1000);
                    }
                }
            });
        });
    });
    function delete_criteria(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_criteria_superior',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success("Criteria successfully deleted", 'Success');
                    setTimeout(function() {
                        location.reload();
            end_load()
                    }, 1000);
                }
            }
        });
    }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>