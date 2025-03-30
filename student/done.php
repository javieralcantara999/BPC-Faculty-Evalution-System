<?php 
session_start(); // Start the session
include '../db_connect.php';
?>

<div class="container-fluid">
    <p>Congratulations! You have already finished the evaluation. Thank you so much for your participation!</p>
</div>

<div class="modal-footer display p-0 m-0 text-center">
    <a href="<?php echo $_SESSION['login_view_folder']; ?>certificate.php?name=<?php 
        echo urlencode($_SESSION['login_name']); ?>&year=<?php 
        echo isset($_SESSION['academic']['year']) ? $_SESSION['academic']['year'] : ''; ?>
        &semester=<?php echo isset($_SESSION['academic']['semester']) ? $_SESSION['academic']['semester'] : ''; 
        ?>" class="btn btn-success bg-gradient-success" target="_blank">Download Certificate</a>
    
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