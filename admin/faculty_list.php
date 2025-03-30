<?php include'db_connect.php' ?>
<script>
    document.title = "Faculty Members / Instructors | Administrator";
    </script>
<h1 class="m-0 text-center"><i class = "fas fa-user-friends"></i>&nbsp;<b>FACULTY MEMBERS / INSTRUCTORS</b></h1><br><hr>
<div class="col-lg-12" id = "tbl">
	<div class="card card-success">
		<div class="card-body">
            <div class="card-tools float-left">
                    <a id="sub" class="btn btn-sm btn-gradient btn-success new_faculty" href="indexx.php?page=new_faculty">
                        <i class="fa fa-plus-circle"></i> Add Faculty Member
                    </a>
                </div>
                <div class="form-group mb-1 d-flex" >
                            <form method="post" id = "import_faculty"enctype="multipart/form-data" class="ml-1">
                                <input type="file" class="form-control-file" name="exceldata" id="exceldata">
                                <button type="submit" name="import" class="btn btn-primary btn-sm mt-1">
                                    <i class = "fas fa-file-excel"></i>&nbsp; Import faculties</button>
                            </form>
                            </div>
			<table class="table table-hover table-bordered" id="list">
				<thead id="cols">
					<tr>
						<th class="text-center">#</th>
						<th>School ID</th>
						<th>Name</th>
						<th>Email</th>
						<th class = "text-center"width = "23%">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM faculty_list order by concat(firstname,' ',lastname) asc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['school_id'] ?></b></td>
						<td><b><?php echo ucwords($row['name']) ?></b></td>
						<td><b><?php echo $row['email'] ?></b></td>
						<td class="text-center">
                        <div class="btn-group">
                                <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'
                                 class="btn btn-sm btn-info btn-gradient view_faculty">
		                          <i class="fas fa-eye"></i> View
		                        </a>&nbsp;
		                        <a href="./indexx.php?page=edit_faculty&id=<?php echo $row['id'] ?>"
                                 class="btn btn-sm btn-success btn-gradient edit_faculty">
		                          <i class="fas fa-cog"></i> Edit
		                        </a>&nbsp;
		                        <button type="button" 
                                class="btn btn-sm btn-danger btn-gradient delete_faculty"
                                 data-id="<?php echo $row['id'] ?>">
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
    /* Apply custom font to the button group */
    .btn-group button {
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif, Geneva, Tahoma, sans-serif;
    }
</style>
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
    $('#import_faculty').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = new FormData(this);
        start_load()
        $.ajax({
            url: 'ajax.php?action=import_faculty',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 1) {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Faculty Members/Instructors imported successfully.');
                    setTimeout(function() {
                        location.reload();// Redirect to class_list.php
            end_load()
                    }, 1000);
                } else {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.error(response);
            end_load()
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
	$('.view_faculty').click(function(){
		uni_modal("Faculty Member Details","<?php echo $_SESSION['login_view_folder'] ?>view_faculty.php?id="+$(this).attr('data-id'))
	})
	$('.delete_faculty').click(function(){
	_conf("Are you sure to delete this faculty?","delete_faculty",[$(this).attr('data-id')])
	})
		$('#list').dataTable()
	})
	function delete_faculty($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_faculty',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					toastr.options.positionClass = 'toast-top-center';
                        toastr.success("Faculty successfully deleted.",'Success')
					setTimeout(function(){
						location.reload()
					},1000)

				}
			}
		})
	}
</script>