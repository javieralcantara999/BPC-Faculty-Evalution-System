<?php include 'db_connect.php'; ?>
<script>
    document.title = "Subjects | Administrator";
    </script>
<h1 class="m-0 text-center"><i class="fas fa-scroll"></i>&nbsp;<b>SUBJECTS LIST</b></h1><br><hr>

<div class="container-fluid">
    <div class="card card-success">
        <div class="card-body">
            <div class="card-tools float-left">
            <a class="btn btn-sm btn-gradient btn-success new_subject"
                     style="margin-right:10px;" href="javascript:void(0)">
                    <i class="fa fa-plus-circle"></i> Add Subject
                </a>
            </div>
            <div class="form-group mb-1 d-flex" >
            <form method="post" id = "import_subject"nctype="multipart/form-data" class="ml-1">
                <input type="file" class="form-control-file" name="exceldata" id="exceldata">
                <button type="submit" name="import" class="btn btn-primary btn-sm mt-1">
                    <i class = "fas fa-file-excel"></i>&nbsp; Import Subjects</button>
            </form>
            </div>
            <table class="table table-hover table-bordered" id="subject_list" style="text-align: left;">
                <thead>
                    <tr>
                        <th width = "5%"class="text-center">No.</th>
                        <th width = "10%">Subject Code</th>
                        <th width = "20%">Subject Name</th>
                        <th width = "30%">Description</th>
                        <th width = "11%"class = "text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT * FROM subject_list ORDER BY subject ASC");
                    while ($row = $qry->fetch_assoc()) :
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++ ?></td>
                            <td><b><?php echo $row['code'] ?></b></td>
                            <td><b><?php echo $row['subject'] ?></b></td>
                            <td><b><?php echo $row['description'] ?></b></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-success btn-sm btn-gradient manage_subject">
                                        <i class="fas fa-cog"></i> Edit
                                    </a>&nbsp;
                                    <button type="button" class="btn btn-danger btn-sm btn-gradient delete_subject" data-id="<?php echo $row['id'] ?>">
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
    .text-center {
        text-align: center !important;
    }
    #sub:hover {
        background:darkgreen !important;
    }
    @media screen and (max-width: 768px) {
        #subject_list_wrapper {
            width: 100%;
        }

        #subject_list {
            width: 100%;
            overflow-x: auto;
            display: block;
        }

        #subject_list_wrapper .dataTables_length,
        #subject_list_wrapper .dataTables_info,
        #subject_list_wrapper .dataTables_paginate {
            display: none;
        }

        #subject_list thead .sorting::after,
        #subject_list thead .sorting_desc::after,
        #subject_list thead .sorting_asc::after {
            display: none;
        } 
        #subject_list thead .sorting_asc::after {
        display: none !important;
    }
        
    }

    .container-fluid .card {
        transition: opacity 0.3s ease;
    }
</style>

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
     $(document).ready(function() {
    $('#import_subject').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = new FormData(this);
        start_load()
        $.ajax({
            url: 'ajax.php?action=import_subject',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 1) {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Subjects imported successfully.');
                    setTimeout(function() {
                        location.reload();// Redirect to class_list.php
                        
                    end_load()
                    }, 1000);
                } else {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.error(response); // Display error 
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
        $('#subject_list').dataTable({
            "paging": false,
            "searching": true,
            "info": false,
            "ordering": false // Disable sorting
        });

        $('.new_subject').click(function() {
            var container = $(".container-fluid .card");
            container.css("opacity", 0);
            uni_modal("Create new Subject", "<?php echo $_SESSION['login_view_folder'] ?>manage_subject.php", function() {
                container.css("opacity", 1);
            });
        });

        $('.manage_subject').click(function() {
            var container = $(".container-fluid.card");
            container.css("opacity", 0);
            uni_modal("Update Subject", "<?php echo $_SESSION['login_view_folder'] ?>manage_subject.php?id=" + $(this).attr('data-id'), function() {
                container.css("opacity", 1);
            });
        });

        $('.delete_subject').click(function() {
            _conf("Do you want to delete this subject?", "delete_subject", [$(this).attr('data-id')]);
        });
    });

    function delete_subject($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_subject',
            method: 'POST',
            data: {
                id: $id
            },
            success: function(resp) {
                if (resp == 1) {
                    toastr.options.positionClass = 'toast-top-center';// Set position to middle center
                    toastr.success('Subject has been deleted.');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }
</script>