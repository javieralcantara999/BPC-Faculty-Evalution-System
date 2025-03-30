<?php include('db_connect.php'); ?>

<style>
    .user-img {
        border-radius: 50%;
        height: 25px;
        width: 25px;
        object-fit: cover;
    }
    #topbar{
        background: darkgreen;
    }
</style>

<nav class="main-header navbar navbar-expand navbar-success navbar-dark " id="topbar">
    <ul class="navbar-nav">
        <?php if(isset($_SESSION['login_id'])): ?>
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="" role="button" style="color:white"><i class="fas fa-bars"></i></a>
            </li>
        <?php endif; ?>
        <li>
            <img src="./images/bpc.ico" id="sl" alt="Logo" style="width:30px;height:30px;display:none;">
            <a class="nav-link text-white" role="button">
                <large><b style="color:yellow" id="topb">BULACAN POLYTECHNIC COLLEGE - FACULTY EVALUATION SYSTEM</b></large>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto"></li>
        <?php if(isset($_SESSION['login_id'])): ?>
            <li class="nav-item dropdown">
                
                <a class="nav-link"  data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
                    <span>
                        
                        <div class="d-felx badge-pill" id="name">
                            <span class=""><img src="assets/uploads/<?php echo $_SESSION['login_avatar'] ?>" alt="" class="user-img border "></span>
                            <span><b>&nbsp;<?php echo ucwords($_SESSION['login_firstname']), " ", ucwords($_SESSION['login_lastname']) ?></b></span>
                            <span class="fa fa-angle-down ml-2"></span>
                        </div>
                    </span>
                    
                </a>
                
                <div class="dropdown-menu" aria-labelledby="account_settings" style="left: 20px;">
                    <a class="dropdown-item" href="javascript:void(0)" id="manage_account">
                        <i class="fa fa-cog"></i>&nbsp; Settings
                    </a>
                    <a class="dropdown-item" href="ajax.php?action=logout">
                        <i class="fa fa-power-off"></i>&nbsp; Logout
                    </a>
                </div>
                
            
            
        <?php endif; ?>
    </ul>
</nav>

<style>
    #name {
        font-size: 18px;
        color: whitesmoke;
    }

    #name:hover {
        color: lightgray;
    }

    @media screen and (max-width: 768px) {
        #topb,
        #fc {
            display: none;
        }

        #sl {
            display: block;
            position: absolute;
            margin-top: 5px;
        }
    }
</style>

<script>
    $('#manage_account').click(function(){
        <?php if(isset($_SESSION['login_id'])): ?>
            uni_modal('Manage Account','manage_user.php?id=<?php echo $_SESSION['login_id'] ?>');
        <?php endif; ?>
    })
</script>