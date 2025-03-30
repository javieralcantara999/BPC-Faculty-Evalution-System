<?php include'db_connect.php' ?>
<script>
    document.title = "Administrator Users | Administrator";
    </script>
<h1 class="m-0 text-center"><i class = "fas fa-chalkboard-teacher"></i>&nbsp;<b>ADMIN USERS LIST</b></h1><br><hr>
<div class="col-lg-12" id = "tbl">
	<div class="card card-success">
		<div class="card-body">
            <div class="card-tools float-left">
				<a id="sub" class="btn btn-sm btn-success btn-gradient btn-success new_user"  href="./indexx.php?page=new_user">
					<i class="fa fa-plus-circle"></i> Add User
				</a>
			</div>
            <div class="form-group mb-1 d-flex" >
                            <form method="post" id = "import_admin"enctype="multipart/form-data" class="ml-1">
                                <input type="file" class="form-control-file" name="exceldata" id="exceldata">
                                <button type="submit" name="import" class="btn btn-primary btn-sm mt-1">
                                    <i class = "fas fa-file-excel"></i>&nbsp; Import admin users</button>
                            </form>
                            </div>
			<table class="table table-hover table-bordered" id="list">
				<thead id="cols">
					<tr>
						<th class="text-center"width = "5%">No.</th>
						<th width = "35%">Name</th>
						<th width = "25%">Email</th>
						<th class = "text-center" width = "15%">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users order by concat(firstname,' ',lastname) asc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords($row['name']) ?></b></td>
						<td><b><?php echo $row['email'] ?></b></td>
						<td class="text-center">
                        <div class="btn-group">
		                        <a href="./indexx.php?page=edit_user&id=<?php echo $row['id'] ?>" class="btn btn-sm btn-success btn-gradient edit_user">
		                          <i class="fas fa-cog"></i> Edit
		                        </a>&nbsp;
		                        <button type="button" class="btn btn-danger btn-gradient btn-sm delete_user" 
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
    @media screen and (max-width: 768px) {
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
</style>
<style>
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
    $('#import_admin').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = new FormData(this);
        start_load()
        $.ajax({
            url: 'ajax.php?action=import_admin',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 1) {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Admin users imported successfully.');
                    setTimeout(function() {
                        location.reload();// Redirect to class_list.php
                    }, 1000);
                } else {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.error(response); // Display error message
                    setTimeout(function() {
                        location.reload();// Redirect to class_list.php
                    }, 1000);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText); // Log any AJAX errors
                    setTimeout(function() {
                        location.reload();// Redirect to class_list.php
                    }, 1000);
            }
        });
    });
            end_load()
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
	$('.view_user').click(function(){
		uni_modal("Admin User Details","view_user.php?id="+$(this).attr('data-id'))
	})
	$('.delete_user').click(function(){
	_conf("Are you sure to delete this user?","delete_user",[$(this).attr('data-id')])
	})
		$('#list').dataTable()
	})
	function delete_user($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_user',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
                    toastr.options.positionClass = 'toast-top-center';// Set position to middle center
                    toastr.success("Successfully deleted.",'Success')
					setTimeout(function(){
						location.reload()
					},1000)

				}
			}
		})
	}
</script>