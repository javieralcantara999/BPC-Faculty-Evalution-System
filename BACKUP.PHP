<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/uploads/bpc.ico">
    <link rel="stylesheet" href="login.css">
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.7/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    #scard {
        bottom:10%;
    }
</style>
<body id="bd" style="background:url('images/uploads/bpcfront.jpg');background-size:cover;background-repeat:no-repeat;">
    <div class="container mt-5" style="border:0px;">
        <div class="row justify-content-center">
            <div class="col-md-8" id="scard">
                <div class="card" style="border:0px;">
                    <div class="card-header text-center" style="background-color: darkgreen;border:0px;color:white;font-size:20px;font-weight:bold">CREATE AN ACCOUNT</div>

                    <div class="card-body">
                            
                        <form action="" id="signup_account"method = "post">
                            <div class="row">
                                <div class="col-md-6 border-right">
                                    <div class="form-group">
                                        <label for="school_id" class="control-label">School ID</label>
                                        <input type="text" placeholder="Enter your school ID (e.g., ma12345678)
                                        " name="school_id" class="form-control form-control-sm" 
                                        required value="">
                                        <small id="school_id_msg"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="firstname" class="control-label">First Name</label>
                                        <input type="text" placeholder="Enter your firstname (e.g., Juan)" 
                                        name="firstname" class="form-control form-control-sm firstname" required 
                                        value="">
                                        <small id="firstname_msg"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="lastname" class="control-label">Last Name</label>
                                        <input type="text" placeholder="Enter your lastname (e.g., Dela Cruz)" 
                                        name="lastname" class="form-control form-control-sm lastname" 
                                        required value="">
                                        <small id="lastname_msg"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="class_id" class="control-label">Section</label>
                                        <select name="class_id" id="class_id" class="form-control form-control-sm select2">
                                            <option value="" disabled selected>Select your current Section</option>
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
                                            <input type="file" class="custom-file-input" id="customFile" name="avatar" onchange="displayImg(this)">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="form-group d-flex justify-content-center align-items-center">
                                        <img src="" id="cimg" class="img-fluid img-thumbnail">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Email</label>
                                        <input type="email" placeholder="Enter a valid email address" 
                                        class="form-control form-control-sm" name="email" required value="<?php echo isset($email) ? $email : '' ?>">
                                        <small id="email_msg"></small>
                                    </div>
                                    <div class="field">
                                        <label class="control-label">Password</label>
                                        <div class="position-relative">
                                            <input type="password" placeholder="Create a strong password" 
                                            class="password form-control form-control-sm" id="pass" name="password" required>
                                            <i class='bx bx-hide eye-icon' onclick="togglePassword('pass')"></i>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="control-label">Confirm Password</label>
                                        <div class="position-relative">
                                            <input type="password" placeholder="Confirm your password" 
                                            class="confirm_password form-control form-control-sm" id="cpass" name="cpass" required>
                                            <i class='bx bx-hide eye-icon' onclick="togglePassword('cpass')"></i>
                                        </div>
                                    <div id="password_match_message" class="match-success text-center"></div>
                                    </div>
                                    
                                    <div class="content">
                                        <p style="text-align: left;margin-left:20px;">Password must contain:</p>
                                        <ul class="requirement-list">
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span>At least 1 lowercase letter (a...z)</span>
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span>
                                                At least 1 uppercase letter (A...Z)
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span>At least 1 number (0..9)</span>
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span>At least 8 characters length</span>
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span>At least 1 special symbol (!...$)</span>
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
                                    
                                    <input type="checkbox" class="form-check" name="agree_to_terms"id="agree_to_terms">
                                </label>
                            </div>

                            <div class="col-lg-12 text-right justify-content-center d-flex">
                                <!-- Disabled Create Account button -->
                                <button type = "submit" class="btn btn-success mr-2" id="create_account_btn"disabled>Create Account</button>
                            </div>
                            <br>
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

        .requirement-list li span {
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
    
    <script src="signup_script.js"></script>


    <script>
        // CHECK THE VALIDITY OF SCHOOL ID
$(document).ready(function(){
    $('#signup_account [name="school_id"]').on('keyup',function(){
        var school_id = $(this).val();
        if(school_id.match(/^ma\d{8}$/)){
            $.ajax({
                url: 'check.php',
                method: 'POST',
                data: { school_id: school_id },
                success: function (resp) {
                    console.log(resp);
                    if (resp.trim() == 'existing') {
                        $('#school_id_msg').html('<small style="color:red;">School ID already exists.</small>');
                        $('#signup_account [name="school_id"]').addClass('border-danger');
                    } 
                    else if(resp.trim() == 'not_existing') {
                        $('#school_id_msg').html('');
                        $('#signup_account [name="school_id"]').removeClass('border-danger');
                    } else {
                        $('#signup_account [name="school_id"]').removeClass('border-danger');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('AJAX Error: ' + textStatus);
                    $('#school_id_msg').html('<small style="color:red;">AJAX Error: ' + textStatus + '</small>');
                    $('#signup_account [name="school_id"]').removeClass('border-danger');
                }
            });
        } else {
            $('#school_id_msg').html('<small style="color:red;">Invalid school ID format.</small>');
            $('#signup_account [name="school_id"]').addClass('border-danger');
        }
        })
        
    // CHECK THE VALIDITY OF FIRSTNAME AND LASTNAME FIELD
        $('.firstname, .lastname').on('keyup', function(){
            var inputValue = $(this).val();
            var fieldName = $(this).attr('name');
            var isValid = true;
            var errorMsg = '';

            // Regular expression pattern para sa pag-validate ng pangalan
            var namePattern = /^[a-zA-Z\s\(\),.'-]+$/;

            // Suriin kung ang nilalaman ng field ay sumusunod sa pattern
            if(!namePattern.test(inputValue)){
                isValid = false;
                errorMsg = 'Invalid characters. Only letters and (.),(,),(-) are allowed.';
            }

            // I-update ang mensahe ng pag-validate base sa resulta
            if(isValid){
                $('#' + fieldName + '_msg').html('');
            }else{
                $('#' + fieldName + '_msg').html('<small style="color:red;">' + errorMsg + '</small>');
            }
        });
    });

    $(document).ready(function(){
        
    });

    // SHOW/HIDE PASSSWORD
    function togglePassword(id) {
        var x = document.getElementById(id);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }


    // CALL VALIDATEPASSWORDMATCH FOR PASSWORD AND CONFIRM PASSWORD
    $(document).ready(function(){

        // VALIDATE PASSWORD STRENGTH
        function validatePassword() {
            const password = $('[name="password"]').val();
            const requirements = [
                /[a-z]/, // 1 LOWERCASE
                /[A-Z]/, // 1 UPPERCASE
                /[0-9]/, // 1 DIGIT
                /.{8,}/, // 8 CHARACTERS
                /[^A-Za-z0-9]/ // 1 SPECIAL CHARACTER
            ];

            // CHECK EFFECT FOR PASSWORD REQUIREMENTS
            requirements.forEach((regex, index) => {
                const isValid = regex.test(password);
                const requirementItem = $('.requirement-list li').eq(index);

                if (isValid) {
                    requirementItem.addClass('valid');
                    requirementItem.find('i').removeClass('fa-circle').addClass('fa-check');
                } else {
                    requirementItem.removeClass('valid');
                    requirementItem.find('i').removeClass('fa-check').addClass('fa-circle');
                }
            });
        }

        // Call the validatePassword function on keyup event for password field
        $('[name="password"]').keyup(validatePassword);
    });

    // FUNCTION TO VALIDATE PASSWORD MATCH
    $(document).ready(function(){
        function validatePasswordMatch() {
            const password = document.getElementById('pass').value;
            const confirmPassword = document.getElementById('cpass').value;
            const messageElement = document.getElementById('password_match_message');

            if (confirmPassword !== '' && password === confirmPassword) {
                messageElement.textContent = 'Password matched.';
                messageElement.classList.remove('match-danger');
                messageElement.classList.add('match-success');
                messageElement.style.display = 'block';
            } else if (confirmPassword !== '' && password !== confirmPassword) {
                messageElement.textContent = 'Passwords do not match.';
                messageElement.classList.remove('match-success');
                messageElement.classList.add('match-danger');
                messageElement.style.display = 'block';
            } else {
                messageElement.style.display = 'none';
            }
        }

        $('[name="password"], [name="cpass"]').keyup(validatePasswordMatch);
    });

    $(document).ready(function(){
        function checkConditions() {
            var allConditionsMet = true;
    
            // Check if checkbox for terms of services is checked
            if (!$('#agree_to_terms').prop('checked')) {
                allConditionsMet = false;
            }
    
            return allConditionsMet;
        }
    
        // Call function to check conditions on checkbox change and input keyup
        $('#agree_to_terms').change(function() {
            $('#create_account_btn').prop('disabled', !checkConditions());
        });
    
    });
    function displayImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }
    
            reader.readAsDataURL(input.files[0]);
        }
    }
        
$(document).ready(function(){
    $('[name="email"]').on('keyup',function(){
        var email = $(this).val();
        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if(emailPattern.test(email)){
            // Valid email format
            $.ajax({
                url: 'check.php',
                method: 'POST',
                data: { email: email },
                success: function (resp) {
                    if (resp.trim() == 'existing') {
                        $('#email_msg').html('<small style="color:red;">Email already exists.</small>');
                        $('[name="email"]').addClass('border-danger');
                    } else {
                        $('#email_msg').html('');
                        $('[name="email"]').removeClass('border-danger');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('AJAX Error: ' + textStatus);
                    $('#email_msg').html('<small style="color:red;">AJAX Error: ' + textStatus + '</small>');
                    $('[name="email"]').removeClass('border-danger');
                }
            });
        } else {
            // Invalid email format
            $('#email_msg').html('<small style="color:red;">Invalid email format.</small>');
            $('[name="email"]').addClass('border-danger');
        }
    });

    $('#signup_account').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data

        // Perform AJAX request to process_signup.php
        $.ajax({
            url: 'submit_request.php',
            method: 'POST',
            data: formData,
            success: function(response) {
                // Handle the response from the server
                if (response.trim() == 'success') {
                    // If data insertion is successful, redirect to login page
                    window.location.href = './login.php';
                } else {
                    // If there's an error, display the error message
                    console.log('Error: ' + response);
                    // You can display the error message on the page if needed
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle AJAX error
                console.log('AJAX Error: ' + textStatus);
                // You can display the error message on the page if needed
            }
        });
    });

});
    </script>

//<script src="signup_script.js"></script>