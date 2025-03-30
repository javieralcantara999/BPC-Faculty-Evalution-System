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
            <a href="./indexx.php?page=result" class="nav-link nav-result" id="sdbr">
            <i class="nav-icon fas fa-chart-bar"></i><i class="fas fa-user-alt"></i>&nbsp;
              <p>
                RESULTS (STUDENTS)
              </p>
            </a>
          </li> 
          <li class="nav-item dropdown">
            <a href="./indexx.php?page=result_superior" class="nav-link nav-result_superior" id="sdbr">
            <i class="nav-icon fas fa-chart-bar"></i><i class="fas fa-user-tie"></i>&nbsp;
              <p>
                RESULTS (SUPERIORS)
              </p>
            </a>
          </li> 
          <li class="nav-item dropdown">
          <a href="./indexx.php?page=feedbacks" class="nav-link nav-feedbacks" id="sdbr">
            <i class="nav-icon fas fa-comments"></i><i class="fas fa-user-alt"></i>&nbsp;
            <p>
                STUDENT FEEDBACKS
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="./indexx.php?page=feedbacks_superior" class="nav-link nav-feedbacks_superior" id="sdbr">
            <i class="nav-icon fas fa-comments"></i><i class="fas fa-user-tie"></i>&nbsp;
            <p>
                SUPERIOR FEEDBACKS
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
        font-size:13px;
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
