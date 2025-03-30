<?php include('db_connect.php'); ?>

<?php 

$astat = array("Not Yet Started","On-going","Closed");

// Bagong query para makuha ang Year, Semester, at Evaluation Status mula sa database
$academic_info_query = $conn->query("SELECT year, semester, status FROM academic_list WHERE id = ".$_SESSION['academic']['id']);
$academic_info = $academic_info_query->fetch_assoc();
$year = $academic_info['year'];
$semester = $academic_info['semester'];
$academic_status = $academic_info['status'];
$academic_status_text = $astat[$academic_status];
?>

<aside class="main-sidebar sidebar-green-primary elevation-4" style = "background-color: darkgreen;color:white;">
  <div class="dropdown">
    <a href="./indexx.php" class="brand-link">
      <!-- Palitan ang src at alt ng <img> tag base sa iyong logo -->
      <img src="./images/bpc.ico" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <h3 class="text-center p-0 m-0"><b></b></h3>
    </a>
  </div>
    <div class="sidebar" style = "color:white;">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
         <li class="nav-item dropdown">
            <a href="./indexx.php" class="nav-link nav-home" id="sdbr">
              <i class="nav-icon fas fa-home"></i>
              <p>
                DASHBOARD
              </p>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a href="./indexx.php?page=evaluate" class="nav-link nav-evaluate" id="sdbr">
              <i class="nav-icon fas fa-envelope-open-text"></i>
              <p>
                EVALUATE
              </p>
            </a>
          </li> 
          
        </ul>
      </nav>
    </div>
  </aside>
  <style>
    #sdbr{
        background:green;
        color:whitesmoke;
        padding:10px;
        font-size:16px;
        margin:2px 5px 2px 5px;
        border-radius: 5px;
        font-weight: bold;
        text-align: left;
        padding-top:15px;
        padding-bottom:15px;
    }
    #sdbr.active,
    #sdbr:hover {
        background: lightgreen;
        color: black;
    }
    #sdbr:hover{
        background:lightgreen;
        color:black;
    }
    .nav-link.nav-evaluate.disabled {
    pointer-events: none;
    color: #6c757d; /* Set the disabled color */
    opacity: 0.5;
    }
  </style>
  <script>
    $(document).ready(function () {
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';

    if (s != '')
        page = page + '_' + s;

    if ($('.nav-link.nav-' + page).length > 0) {
        $('.nav-link.nav-' + page).addClass('active')

        if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
            $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
            $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
        }

        if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
            $('.nav-link.nav-' + page).parent().addClass('menu-open')
        }
    }

    // Palitan ang text sa sidebar depende sa kondisyon ng sidebar
    updateSidebarText();

    // Event listener para sa pag-expand o pag-minimize ng sidebar
    $('[data-widget="treeview"]').on('expanded.treeview collapsed.treeview', function () {
        updateSidebarText();
    });

    // Function para i-update ang laman ng #sidebar-title
    function updateSidebarText() {
        if ($('.main-sidebar').hasClass('sidebar-collapse')) {
            // Minimized
            $('#sidebar-title img').show();
            $('#sidebar-title span').hide();
        } else {
            // Expanded
            $('#sidebar-title img').hide();
            $('#sidebar-title span').show();
        }

        // Check ang evaluation status at i-enable o i-disable ang nav-evaluate
        var evaluationStatus = '<?php echo $academic_status_text; ?>';
        if (evaluationStatus === 'On-going') {
            $('.nav-link.nav-evaluate').removeClass('disabled');
        } else {
            $('.nav-link.nav-evaluate').addClass('disabled');
        }
    }
});
</script>

<style>
    #sidebar-title img {
  display: inline;
}

#sidebar-title span {
  display: none;
}

#sidebar-title.collapsed img {
  display: none;
}

#sidebar-title.collapsed span {
  display: inline;
}
</style>
