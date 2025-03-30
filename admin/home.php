<?php include('db_connect.php'); ?>
<script>
    document.title = "Home | Administrator";
    </script>
<?php 
function ordinal_suffix1($num){
    $num = $num % 100; // protect against large numbers
    if($num < 11 || $num > 13){
         switch($num % 10){
            case 1: return $num.'st';
            case 2: return $num.'nd';
            case 3: return $num.'rd';
        }
    }
    return $num.'th';
}

$astat = array("Not Yet Started","On-going","Closed");

// Bagong query para makuha ang Year, Semester, at Evaluation Status mula sa database
$academic_info_query = $conn->query("SELECT year, semester, status FROM academic_list WHERE id = ".$_SESSION['academic']['id']);
$academic_info = $academic_info_query->fetch_assoc();
$year = $academic_info['year'];
$semester = $academic_info['semester'];
$academic_status = $academic_info['status'];
$academic_status_text = $astat[$academic_status];
?>
<div class="container-fluid">
    <h1 class="m-0 text-left"><i class="fas fa-home"></i> ADMINISTRATOR DASHBOARD</h1><br><hr>
    <div class="row">
        <!-- Academic Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-white" style = "background: green;">
                    <h3 class="card-title">Academic Information</h3>
                </div>
                <div class="card-body">
                <p class="text-left">
                <b>School Year:</b>
                <span class="badge badge-primary" style = "font-size: 15px">
                     <?php echo $year.' '.(ordinal_suffix1($semester)) ?> Semester</p><hr>
                </span>
                    <p class="text-left"><b>Evaluation Status:</b> 
                        <?php if($academic_status == 0): ?>
                            <span class="badge badge-danger" style = "font-size: 15px">Not Yet Started</span>
                        <?php elseif($academic_status == 1): ?>
                            <span class="badge badge-success" style = "font-size: 15px">Started</span>
                        <?php elseif($academic_status == 2): ?>
                            <span class="badge badge-danger" style = "font-size: 15px">Ended</span>
                        <?php endif; ?>
                    </p><hr>
                    
                    <a id="evaluateButton" class="btn btn-gradient btn-secondary text-center" 
                        href="./indexx.php?page=academic_list" style="text-decoration: none;" disabled>
                        <i class = "fas fa-cog"></i> Manage School Year & Status </a><hr>
                </div>
            </div>
        </div>
        <div class="col-md-3">
                <div class="card">
                    <div class="card-header card-success text-white text-center" style = "background: green;">
                        <h3 class="card-title ">Information</h3>
                        
            <?php 
                            $name = $conn->query("SELECT *,CONCAT(firstname , ' ' , lastname) as name from users
                            WHERE id = ".$_SESSION['login_id']);
                            $info = $name->fetch_assoc();
                        ?> 
                    </div>
                    <div class="card-body">
                    <p class="text-center"style = "font-size: 17px;font-weight:bold;">
                    <img class = "text-center mb-2"src = "assets/uploads/<?php echo $info['avatar'];?>" 
                    style = "width:100px;height:100px;border-radius:50%;"><br>
                    <b><span class="" > <?php echo $info['name'];?></span></b><br>
                    <b><span class="" > <?php echo $info['email'];?></span></b>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header card-success text-white text-center" style = "background: green;">
                        <h3 class="card-title ">Date & Time</h3>
                    </div>
                    <div class="card-body">
                    <p class="text-center" id="currentDateTime" style="font-size: 17px; font-weight: bold;">
                            <b>Time:</b> <?php echo date('F j, Y, h:i:s A'); ?><hr>
                        </p>
                    </div>
                </div>
            </div>
            <script>
                function updateDateTime() {
                    var currentDate = new Date();
                    var options = { 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric', 
                        hour: 'numeric', 
                        minute: 'numeric', 
                        second: 'numeric', 
                        hour12: true 
                    };
                    var formattedDateTime = currentDate.toLocaleString('en-US', options);
                    document.getElementById('currentDateTime').innerHTML = '<hr> ' + formattedDateTime;
                    requestAnimationFrame(updateDateTime); // Continuously update using requestAnimationFrame
                }

                // Initial call to start updating the date and time
                updateDateTime();
            </script>
        <!-- Statistics -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header text-white" style = "background: green">
                    <h3 class="card-title">Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fa fa-book"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Subjects</span>
                                    <span class="info-box-number"><?php echo $conn->query("SELECT * FROM subject_list")->num_rows; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fa fa-list-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Sections</span>
                                    <span class="info-box-number"><?php echo $conn->query("SELECT * FROM class_list")->num_rows; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fa fa-user-friends"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Faculties</span>
                                    <span class="info-box-number"><?php echo $conn->query("SELECT * FROM faculty_list ")->num_rows; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Students</span>
                                    <span class="info-box-number"><?php echo $conn->query("SELECT * FROM student_list")->num_rows; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fa fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Users</span>
                                    <span class="info-box-number"><?php echo $conn->query("SELECT * FROM users")->num_rows; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fa fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pending Accounts</span>
                                    <span class="info-box-number"><?php echo $conn->query("SELECT * FROM account_request")->num_rows; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>