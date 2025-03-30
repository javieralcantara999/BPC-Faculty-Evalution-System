    <?php include 'db_connect.php'; ?>
    <script>
    document.title = "Pending Accounts | Administrator";
    </script>
    <h1 class="m-0 text-center"><i class="fas fa-users"></i>&nbsp;<b>PENDING ACCOUNTS - STUDENTS</b></h1>
    <hr>
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-body">
                <table class="table table-hover table-bordered" id="account_list" style="text-align: left;">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No.</th>
                            <th width="15%">School ID</th>
                            <th width="15%">Name</th>
                            <th width="20%">Email</th>
                            <th width="10%">Section</th>
                            <th class="text-center" width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $class = array();
                        $classes = $conn->query("SELECT id, CONCAT(curriculum,' ', level,' - ', section) AS `class` FROM class_list");
                        while ($row = $classes->fetch_assoc()) {
                            $class[$row['id']] = $row['class'];
                        }
                        $qry = $conn->query("SELECT *, CONCAT(firstname,' ', lastname) 
                        AS name FROM account_request ORDER BY CONCAT(firstname,' ',lastname) ASC");
                        while ($row = $qry->fetch_assoc()) :
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++ ?></td>
                                <td><b><?php echo $row['school_id'] ?></b></td>
                                <td><b><?php echo ucwords($row['name']) ?></b></td>
                                <td><b><?php echo $row['email'] ?></b></td>
                                <td><b><?php echo isset($class[$row['class_id']]) ? $class[$row['class_id']] : "N/A" ?></b></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-success btn-gradient-success accept_pending"
                                         data-id="<?php echo $row['school_id'] ?>">
                                            <i class="fas fa-check"></i> Accept
                                        </button>
                                        <button type="button" class="btn btn-danger btn-gradient-danger decline_pending"
                                         data-id="<?php echo $row['school_id'] ?>">
                                            <i class="fas fa-ban"></i> Decline
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

    <!-- Account Status Modal -->
    <div class="modal fade" id="accountStatusModal" tabindex="-1" role="dialog" aria-labelledby="accountStatusLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accountStatusLabel">Confirm Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="confirmationText"></p>
                    <input type="hidden" id="school_id">
                    <input type="hidden" id="action">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-gradient-success" onclick="performAction()">Confirm</button>
                    <button type="button" class="btn btn-danger btn-gradient-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        
        $(document).ready(function() {
            
            $('#account_list').dataTable({
                "paging": false,
                "searching": true,
                "info": false,
                "ordering": false // Disable sorting
            });
            $('.accept_pending').click(function() {
                var school_id = $(this).data('id');
                var confirmationText = "Are you sure you want to accept this account?";
                $('#school_id').val(school_id);
                $('#action').val('accept');
                $('#confirmationText').text(confirmationText);
                $('#accountStatusModal').modal('show');
            });

            $('.decline_pending').click(function() {
                var school_id = $(this).data('id');
                var confirmationText = "Are you sure you want to decline this account?";
                $('#school_id').val(school_id);
                $('#action').val('decline');
                $('#confirmationText').text(confirmationText);
                $('#accountStatusModal').modal('show');
            });
        });
        function performAction() {
            var school_id = $('#school_id').val();
            var action = $('#action').val();
            
            start_load()
            $.ajax({
                url: 'update_account_status.php',
                method: 'POST',
                data: {
                    school_id: school_id,
                    action: action
                },
                
                success: function(response) {
                    $('#responseMessage').text(response);
                    
                    if (response == 2) {
                        toastr.options.positionClass = 'toast-top-center';
                        toastr.success('Student account successfully deleted.');
                        location.reload();
                    }else {
                            toastr.options.positionClass = 'toast-top-center';
                            toastr.success('Student account successfully activated.');
                            setTimeout(function(){
                                location.reload();
                            }, 1500); // 1000 milliseconds na pag-antala bago mag-refresh
                            
                        }
                },
                error: function() {
                    
                }
            });
        }
        end_load()
    </script>
    <style>
        .text-center {
            text-align: center !important;
        }

        @media screen and (max-width: 768px) {
            #account_list_wrapper {
                width: 100%;
            }

            #account_list {
                width: 100%;
                overflow-x: auto;
                display: block;
            }

            #account_list_wrapper .dataTables_length,
            #account_list_wrapper .dataTables_info,
            #account_list_wrapper .dataTables_paginate {
                display: none;
            }

            #account_list thead .sorting::after,
            #account_list thead .sorting_desc::after,
            #account_list thead .sorting_asc::after {
                display: none;
            }
        }
    </style>