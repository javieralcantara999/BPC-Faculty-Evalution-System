<?php 
session_start(); // Start the session
include '../db_connect.php';
?>

<div class="container-fluid">
    <p>Do you wish to continue with the evaluation?</p>
</div>

<div class="modal-footer display p-0 m-0 text-center">
    <a href="./indexx.php?page=evaluate" class="btn btn-success bg-gradient-success text-center">Continue</a>
    
    <a href="./indexx.php" class="btn btn-secondary bg-gradient-secondary text-center">No</a>
</div>
<script>
    // Function to reload the current tab
    function reloadCurrentTab() {
        location.reload(); // Reload the current tab
    }

    // Add event listener to the "Continue" button
    document.getElementById('continueBtn').addEventListener('click', function() {
        reloadCurrentTab(); // Call the reloadCurrentTab function on button click
    });
</script>
<style>
    #uni_modal .modal-footer {
        display: none;
    }

    #uni_modal .modal-footer.display {
        display: flex;
    }
</style>