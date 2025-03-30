<?php 
session_start(); // Start the session
include '../db_connect.php';
?>

<div class="container-fluid">
    <p>No evaluation forms posted yet.</p>
</div>

<div class="modal-footer display p-0 m-0 text-center">
    
    <a href="./indexx.php" class="btn btn-secondary bg-gradient-secondary text-center">Back to Home</a>
</div>

<style>
    #uni_modal .modal-footer {
        display: none;
    }

    #uni_modal .modal-footer.display {
        display: flex;
    }
</style>