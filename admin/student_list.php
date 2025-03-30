
<?php include'db_connect.php' ?>
<script>
    document.title = "Students | Administrator";
    </script>
<h1 class="m-0 text-center"><i class = "fas fa-users"></i>&nbsp;<b>STUDENTS LIST</b></h1><br><hr>
<div class="col-lg-12" id = "tbl">
	<div class="card card-success">
		<div class="card-body">
            <div class="card-tools float-left">
                    <a class="btn btn-sm btn-gradient btn-success new_student"
                     style="margin-right:10px;"
                     href="./indexx.php?page=new_student">
                        <i class="fa fa-plus-circle"></i> Add Student
                    </a>
                </div> 
                <div class="form-group mb-1 d-flex" >
                            <form method="post" id = "import_student"enctype="multipart/form-data" class="ml-1">
                                <input type="file" class="form-control-file" name="exceldata" id="exceldata">
                                <button type="submit" name="import" class="btn btn-primary btn-sm mt-1">
                                    <i class = "fas fa-file-excel"></i>&nbsp; Import students</button>
                            </form>
                            </div>
			<table class="table table-hover table-bordered" id="list">
				<thead id="cols">
					<tr>
						<th class="text-center">No.</th>
						<th>School ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Section</th>
						<th class = "text-center"width = "23%">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$class= array();
					$classes = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as `class` FROM class_list");
					while($row=$classes->fetch_assoc()){
						$class[$row['id']] = $row['class'];
					}
					$qry = $conn->query("SELECT *,status,concat(firstname,' ',lastname) as name FROM student_list order by concat(firstname,' ',lastname) asc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['school_id'] ?></b></td>
						<td><b><?php echo ucwords($row['name']) ?></b></td>
						<td><b><?php echo $row['email'] ?></b></td>
						<td><b><?php echo isset($class[$row['class_id']]) ? $class[$row['class_id']] : "N/A" ?></b></td>
						<td class="text-center">
                        <div class="btn-group">
                                <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' 
                                class="btn btn-sm btn-info btn-gradient view_student">
		                          <i class="fas fa-eye"></i> View
		                        </a>&nbsp;
		                        <a href="./indexx.php?page=edit_student&id=<?php echo $row['id'] ?>" 
                                class="btn btn-sm btn-success btn-gradient edit_student">
		                          <i class="fas fa-cog"></i> Edit
		                        </a>&nbsp;
		                        <button type="button" class="btn btn-sm btn-gradient btn-danger delete_student" 
                                data-id="<?php echo $row['id'] ?>"> <i class="fas fa-trash-alt"></i> Delete
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
.btn-group .btn {
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif, Geneva, Tahoma, sans-serif;/* Palitan ang sukat ng font base sa iyong preference */
    font-weight: 100; /* Palitan ang bigat ng font base sa iyong preference */
}
    /* Center text in the table cells */
    .text-center {
        text-align: center !important;
    }

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
     $(document).ready(function() {
    $('#import_student').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = new FormData(this);
        start_load()
        $.ajax({
            url: 'ajax.php?action=import_student',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 1) {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Students imported successfully.');
                    
                    setTimeout(function() {
                        location.reload();// Redirect to class_list.php
                    end_load()
                    }, 1000);
                } else {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.error(response);
                    
                    setTimeout(function() {
                        location.reload();// Redirect to class_list.php
                    end_load()
                    }, 1000);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            end_load()
            }
        });
    });
});
    $(document).ready(function() {
        $('#list').dataTable({
            "paging": false, // Ito ay nagpapahiwatig na huwag ipakita ang pagination controls
            "searching": true, // Ito ay nagpapahiwatig na ipakita ang search bar
            "info": false, // Ito ay nagpapahiwatig na huwag ipakita ang "Showing [start] to [end] of [total] entries" label
            "ordering": false // Ito ay nagpapahiwatig na huwag payagan ang sorting ng table
        });
    });
	$(document).ready(function(){
	$('.view_student').click(function(){
		uni_modal("Student Details","<?php echo $_SESSION['login_view_folder'] ?>view_student.php?id="+$(this).attr('data-id'))
	})
	$('.delete_student').click(function(){
	_conf("Are you sure to delete this student?","delete_student",[$(this).attr('data-id')])
	})
		$('#list').dataTable()
	})
	function delete_student($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_student',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Student successfully deleted.');
					setTimeout(function(){
						location.reload()
					},1000)

				}
			}
		})
	}
</script>

<div id="importForm" style="display: none;">
    <form id="fileUploadForm" method="post" enctype="multipart/form-data">
        <input type="file" name="excelFile" id="excelFile" accept=".xls,.xlsx" required>
        <button type="submit" class="btn btn-success">Upload</button>
    </form>
</div>