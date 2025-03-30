<?php 
include '../db_connect.php';
?>

<div class="container-fluid">
    <p>Do you wish to continue with the evaluation?</p>
</div>

<div class="modal-footer display p-0 m-0 text-center">
    <a href="./indexx.php?page=evaluate" class="btn btn-success bg-gradient-success text-center">Continue</a>
    
    <a href="./indexx.php" class="btn btn-secondary bg-gradient-secondary text-center">No</a>
</div>
<style>
    #uni_modal .modal-footer {
        display: none;
    }

    #uni_modal .modal-footer.display {
        display: flex;
    }
</style>