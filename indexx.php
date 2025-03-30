<!DOCTYPE html>
<html lang="en">
    
<link rel="icon" type="image/png" href="images/uploads/bpc.ico">
<?php session_start() ?>
<?php 
	if(!isset($_SESSION['login_id']))
	    header('location:login.php');
    include 'db_connect.php';
    ob_start();
  if(!isset($_SESSION['system'])){

    $system = $conn->query("SELECT * FROM system_settings")->fetch_array();
    foreach($system as $k => $v){
      $_SESSION['system'][$k] = $v;
    }
  }
  ob_end_flush();

	include 'header.php' 
?>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <?php include 'topbar.php' ?>
  <?php include $_SESSION['login_view_folder'].'sidebar.php' ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="ct">
  	 <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
	    <div class="toast-body text-white">
	    </div>
	  </div>
    <div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            
          </div><!-- /.col -->

        </div><!-- /.row -->
            
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content"id="ct">
      <div class="container-fluid"id="ct">
         <?php 
            $page = isset($_GET['page']) ? $_GET['page'] : 'home';
            if(!file_exists($_SESSION['login_view_folder'].$page.".php")){
                include '404.html';
            }else{
            include $_SESSION['login_view_folder'].$page.'.php';

            }
          ?>
      </div><!--/. container-fluid -->
    </section>
    <style>
    @media screen and (max-width: 768px) 
    {
        #ct{
            
            width:100%;
        }
    }
</style>
    <!-- /.content -->
    <div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #4CAF50; color: white;">
                <h5 class="modal-title">Custom Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Isama ang nilalaman ng modal body dito -->
                <p>Dito mo ilalagay ang nilalaman ng iyong modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Kameron:wght@400;700&display=swap');
    *{
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif, Geneva, Tahoma, sans-serif;
    }
    /* Custom styles for modal */
.modal-header {
    background-color: darkgreen;
    color: white;
    border-bottom: none; /* Remove bottom border */
}
.modal-dialog {
    position: relative;
    width:100%;
    top: 10%;
}
.modal-title {
    font-weight: bold;
}

.modal-body {
    padding: 20px; /* Add padding */
}

.modal-footer {
    border-top: none; /* Remove top border */
}

.modal-footer .btn {
    padding: 8px 20px; /* Adjust button padding */
}

/* Close button for viewer modal */
.btn-close {
    position: absolute;
    top: 10px;
    right: 10px;
    color: darkgreen;
    font-size: 24px;
    background-color: transparent;
    border: none;
}

/* Viewer modal image */
#viewer_modal img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
}   
</style>
  <div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-right"></span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
              <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
              <img src="" alt="">
      </div>
    </div>
  </div>
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<!-- Bootstrap -->
<?php include 'footer.php' ?>
</body>
</html>
