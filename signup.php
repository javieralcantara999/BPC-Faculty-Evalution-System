<?php include 'db_connect.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/uploads/bpc.ico">
    <link rel="stylesheet" href="login.css">
    <title>Signup Account | Faculty Evaluation System</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.7/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- DataTables -->
  <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
   <!-- Select2 -->
  <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
   <!-- Jquery-UI -->
  <link rel="stylesheet" href="assets/plugins/jquery-ui/jquery-ui.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
</head>


<body id="bd" style="background:url('./images/bpcfront.jpg');background-size:cover;background-repeat:no-repeat;">
    <div class="container mt-5" style="border:0px;">
        <div class="row justify-content-center">
            <div class="col-md-8" id="scard" >
                <div class="card" style="border:0px;top:2%;">
                    <div class="card-header text-center" style="background-color: darkgreen;border:0px;color:white;font-size:20px;font-weight:bold">CREATE AN ACCOUNT</div>

                    <div class="card-body">
                                    <div id="loading_overlay">
                    <div id="loading_indicator">
                        <!-- Loading animation or indicator -->
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                    <form action="" id="signup_account">
				    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                            <div class="row">
                                <div class="col-md-6 border-right">
                                    <div class="form-group">
                                        <label for="school_id" class="control-label">School ID</label>
                                        <input type="text" placeholder="Enter your school ID (e.g., ma12345678)" 
                                        name="school_id" class="form-control form-control-sm" 
                                            required value="<?php echo isset($school_id) ? $school_id : '' ?>">
                                        <small id="school_id_msg"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="firstname" class="control-label">First Name</label>
                                        <input type="text" placeholder="Enter your firstname (e.g., Juan)" 
                                        name="firstname" class="form-control form-control-sm firstname" required 
                                        value="<?php echo isset($firstname) ? $firstname : '' ?>">
                                        <small id="firstname_msg"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="lastname" class="control-label">Last Name</label>
                                        <input type="text" placeholder = "Enter your lastname (e.g., Dela Cruz)" 
                                        name="lastname" class="form-control form-control-sm lastname" 
                                        required value="<?php echo isset($lastname) ? $lastname : '' ?>">
                                        <small id="lastname_msg"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="class_id" class="control-label">Section</label>
                                        <select name="class_id" id="class_id" class="form-control form-control-sm select2">
                                            <option value="" disabled selected required>Select your current Section</option>
                                            <?php 
                                            $classes = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as class FROM class_list");
                                            while($row=$classes->fetch_assoc()):
                                            ?>
                                            <option value="<?php echo $row['id'] ?>" <?php echo isset($class_id) && $class_id == $row['id'] ? "selected" : "" ?>><?php echo $row['class'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                    <label for="img" class="control-label">Avatar</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="img" 
                                        onchange="displayImg(this,$(this))">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                    <!-- Add error message display area for file upload errors -->
                                    <small id="avatar_error" class="text-danger"></small>
                                </div>
                                <div class="form-group d-flex justify-content-center align-items-center">
                                    <!-- Display the uploaded image or default avatar -->
                                    <img src="<?php echo isset($avatar) ? './assets/uploads/'.$avatar : 'assets/uploads/avatar.jpg' ?>"
                                    id="cimg" class="img-fluid img-thumbnail"alt = "Avatar">
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for = "email"class="control-label">Email</label>
                                        <input type="email" placeholder="Enter a valid email address" 
                                        class="form-control form-control-sm" name="email" 
                                        required value="<?php echo isset($email) ? $email : '' ?>">
                                        <small id="email_msg"></small>
                                    </div>
                                    <div class="field">
                                        <label class="control-label">Password</label>
                                        <div class="position-relative">
                                            <input type="password" placeholder="Create a strong password" 
                                            class="password form-control form-control-sm" id="pass" name="password" 
                                            <?php echo !isset($id)?> required>
                                            <i class='bx bx-hide eye-icon' onclick="togglePassword('pass')"></i>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="control-label">Confirm Password</label>
                                        <div class="position-relative">
                                            <input type="password" placeholder="Confirm your password" 
                                            class="confirm_password form-control form-control-sm" id="cpass" name="cpass"
                                            <?php echo !isset($id)?> required>
                                            <i class='bx bx-hide eye-icon' onclick="togglePassword('cpass')"></i>
                                        </div>
                                    <div id="password_match_message" class="match-success text-center"></div>
                                    </div>
                                    
                                    <div class="content">
                                        <p style="text-align: left;margin-left:20px;">Password must contain:</p>
                                        <ul class="requirement-list">
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span id = "sp">At least 1 lowercase letter (a...z)</span>
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span id = "sp">
                                                At least 1 uppercase letter (A...Z)
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span id = "sp">At least 1 number (0..9)</span>
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span id = "sp">At least 8 characters length</span>
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span id = "sp">At least 1 special symbol (!...$)</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- terms of services -->
                            <div class="form-group form-check text-center">
                                
                                <label class="form-check-label" for="agree_to_terms"> 
                                    I agree to the 
                                    <a href="javascript:void(0);" id="terms_link">Terms of Services</a>
                                    
                                    <input type="checkbox" class="form-check" id="agree_to_terms">
                                </label>
                            </div>

                            <div class="col-lg-12 text-right justify-content-center d-flex">
                                <!-- Disabled Create Account button -->
                                <button class="btn btn-success mr-2" id="create_account_btn"disabled>Create Account</button>
                            </div>
                            <br>
                            
                            <script src = "signup_script.js"></script>
                            <center>
                                <div class="sign-txt">Already have an account? <a href="./login.php" style="color:darkgreen"><strong>Log in</strong></a></div>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include 'signup_terms.php'; ?>
</html>
    <style>
        .form-check-input {
            margin-top: 0.3rem; /* Adjust margin-top as needed */
            margin-bottom: 0.3rem; /* Adjust margin-bottom as needed */
        }

        .form-check-label {
            margin-left: 5px; /* Adjust margin-left as needed */
        }
        img#cimg {
        height: 15vh;
        width: 15vh;
        object-fit: cover;
        border-radius: 100% 100%;
        }
        @media screen and (max-width: 768px) {
            #bd {
                height:fit-content;
            }
            #scard {
                margin-bottom:20px;
            }
        }
        #password_match_message {
            margin-top:5px;
            font-weight: bold;
            font-size: 15px;
            display: none;
        }

        .match-success {
            color: green;
        }

        .match-danger {
            color: red;
        }

        .requirement-list li {
            font-weight: normal;
            margin-top: 10px;
            margin-left: 15px;
            font-size: 15px;
            list-style: none;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .requirement-list li i {
            width: 20px;
            color: #aaa;
            font-size: 0.6rem;
        }

        .requirement-list li.valid i {
            font-size: 14px;
            color: darkgreen;
        }
        #sp {
            margin-left: 10px;
            color: #333;
        }

        .eye-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .position-relative {
            position: relative;
        }
    </style>
    
    

    <style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<style>
    #scard {
        bottom:5%;
    }
    #loading_overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Background color with reduced opacity */
    z-index: 9998; /* Ensure the overlay appears below the loading indicator */
    }

    #loading_indicator {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
</style>