
<?php include 'db_connect.php'; ?>
<h1 class="m-0 text-center"><i class="fas fa-list-ul"></i>&nbsp;<b>SECTIONS LIST</b></h1><br><hr>
<script>
    document.title = "Sections | Administrator";
    </script>
<div class="container-fluid">
    
    <div class="card card-success">
        
        <div class="card-body">
            <div class="card-tools float-left" style = "margin-right: 10px">
            <a class="btn btn-sm btn-gradient btn-success new_class" href="javascript:void(0)">
                <i class="fa fa-plus-circle"></i> Add Section
            </a>
        </div>
        <div class="form-group mb-1 d-flex" >
        <form method="post" id = "import_section" enctype="multipart/form-data" class="ml-1">
            <input type="file" class="form-control-file" name="exceldata" id="exceldata">
            <button type="submit" name="import" class="btn btn-primary btn-sm mt-1">
                <i class = "fas fa-file-excel"></i>&nbsp; Import Sections</button>
        </form>
        </div>
            <table class="table table-hover table-bordered" id="section_list" style="text-align: left;">
            
                <thead>
                    <tr>
                        <th class="text-center" width = "5%">No.</th>
                        <th width = "75%">Class Section</th>
                        <th class="text-center"width = "20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT *, CONCAT(curriculum,' ', level,'-', section) AS class FROM class_list ORDER BY class ASC");
                    while ($row = $qry->fetch_assoc()) :
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++ ?></td>
                            <td><b><?php echo $row['class'] ?></b></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-sm btn-gradient btn-success 
                                    manage_class">
                                        <i class="fas fa-cog"></i> Edit
                                    </a>&nbsp;
                                    <button type="button" class="btn btn-sm btn-gradient btn-danger delete_class" data-id="<?php echo $row['id'] ?>">
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
    title {
        
        text-transform:"Section List | Faculty Evaluation System"
    }
    .text-center {
        text-align: center !important;
    }
    #sub:hover {
        background:darkgreen !important;
    }
    @media screen and (max-width: 768px) {
        #section_list_wrapper {
            width: 100%;
        }

        #section_list {
            width: 100%;
            overflow-x: auto;
            display: block;
        }

        #section_list_wrapper .dataTables_length,
        #section_list_wrapper .dataTables_info,
        #section_list_wrapper .dataTables_paginate {
            display: none;
        }

        #section_list thead .sorting::after,
        #section_list thead .sorting_desc::after,
        #section_list thead .sorting_asc::after {
            display: none;
        }
    }
</style>

<script>
    $(document).ready(function() {
    $('#import_section').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = new FormData(this);
        start_load()
        $.ajax({
            url: 'ajax.php?action=import_section',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 1) {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Class sections imported successfully.');
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
                console.log(xhr.responseText); // Log any AJAX 
            end_load()
            }
        });
    });
});
    $(document).ready(function() {
        $('#section_list').dataTable({
            "paging": false,
            "searching": true,
            "info": false,
            "ordering": false // Disable sorting
        });

        $('.new_class').click(function() {
            uni_modal("ADD SECTION", "<?php echo $_SESSION['login_view_folder'] ?>manage_class.php");
        });

        $('.manage_class').click(function() {
            uni_modal("EDIT SECTION", "<?php echo $_SESSION['login_view_folder'] ?>manage_class.php?id=" + $(this).attr('data-id'));
        });

        $('.delete_class').click(function() {
            _conf("Are you sure to delete this class?", "delete_class", [$(this).attr('data-id')]);
        });
    });

    function delete_class($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_class',
            method: 'POST',
            data: {
                id: $id
            },
            success: function(resp) {
                if (resp == 1) {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Section successfully deleted.');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }
</script>

