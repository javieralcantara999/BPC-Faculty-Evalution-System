<?php include 'db_connect.php'
?>
<script src="https://cdn.jsdelivr.net/npm/@iconscout/unicons"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
<h1 class="m-0 text-center"><i class="fas fa-users"></i>&nbsp;<b>STUDENT INFORMATION</b></h1><br><hr>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form action="" id="manage_student">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <div class="form-group">
                            <label for="" class="control-label">School ID</label>
                            <input type="text" name="school_id" placeholder="Enter your school ID (e.g., ma12345678)"
                            class="form-control form-control-sm" required value="<?php echo isset($school_id) ? $school_id : '' ?>">
                            <small id="school_id_msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">First Name</label>
                            <input type="text" name="firstname" placeholder="Enter your firstname (e.g., Juan)" 
                            class="form-control form-control-sm" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
                            <small id="firstname_msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Last Name</label>
                            <input type="text" name="lastname" placeholder="Enter your lastname (e.g., Dela Cruz)" 
                            class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
                            <small id="lastname_msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Section</label>
                            <select name="class_id" id="class_id" class="form-control form-control-sm select2">
                                <option value=""disabled selected> Select your Class Section</option>
                                <?php 
                                $classes = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as class FROM class_list");
                                while($row=$classes->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['id'] ?>" <?php echo isset($class_id) && $class_id == $row['id'] ? "selected" : "" ?>><?php echo $row['class'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Avatar</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this,$(this))">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center align-items-center">
                            <img src="<?php echo isset($avatar) ? 'assets/uploads/'.$avatar :'' ?>" alt="Avatar" id="cimg" class="img-fluid img-thumbnail ">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input type="email" placeholder="Enter a valid email address" class="form-control form-control-sm" name="email" required value="<?php echo isset($email) ? $email : '' ?>">
                            <small id="email_msg"></small>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Password</label>
                            <div class="input-group">
                                <input type="password" placeholder="Create a strong password" class="form-control form-control-sm" id="password" name="password" <?php echo !isset($id) ? "required" : '' ?>>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class='bx bx-hide eye-icon' onclick="togglePassword('password')"></i>
                                    </span>
                                </div>
                            </div>
                            <small><i><?php echo isset($id) ? "" : '' ?></i></small>
                            <small id="pass_match" data-status=''></small>
                        </div>
                        <div class="form-group">
                            <label class="label control-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" placeholder="Confirm your password" class="form-control form-control-sm" id="cpass" name="cpass" <?php echo !isset($id) ? 'required' : '' ?>>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class='bx bx-hide eye-icon' onclick="togglePassword('cpass')"></i>
                                    </span>
                                </div>
                            </div>
                            <small id="pass_match" data-status=''></small>
                        </div>
                        <div id="password_match_message"></div>
                        <div class="content">
                            <p style="text-align: left;margin-left:20px;">Password must contain:</p>
                            <ul class="requirement-list">
                                <li>
                                    <i class="fa-solid fa-circle"></i>
                                    <span id="sp">At least 1 lowercase letter (a...z)</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle"></i>
                                    <span id="sp">At least 1 uppercase letter (A...Z)</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle"></i>
                                    <span id="sp">At least 1 number (0..9)</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle"></i>
                                    <span id="sp">At least 8 characters length</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle"></i>
                                    <span id="sp">At least 1 special symbol (!...$)</span>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                <hr>
                <div class="col-lg-12 text-right justify-content-center d-flex">
                    <button class="btn btn-success mr-2">Save</button>
                    <button class="btn btn-secondary" type="button" onclick="location.href = 'indexx.php?page=student_list'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .input-group-text {
    cursor: pointer;
    }

    .eye-icon {
        cursor: pointer;
    }

    /* Adjust eye icon position */
    .input-group-text {
        padding: 0;
    }

    .input-group .input-group-text {
        border-left: 0;
        border-radius: 0 4px 4px 0;
        background-color: #ffffff;
        border-color: #ccc;
    }
        img#cimg{
        height: 15vh;
        width: 15vh;
        object-fit: cover;
        border-radius: 100% 100%;
    }
</style>

<script>
    

    function togglePassword(id) {
        var x = document.getElementById(id);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
    function validatePassword() {
    const password = $('[name="password"]').val();
    const requirements = [
        /[a-z]/,   // 1 LOWERCASE
        /[A-Z]/,   // 1 UPPERCASE
        /[0-9]/,   // 1 DIGIT
        /.{8,}/,   // 8 CHARACTERS
        /[^A-Za-z0-9]/  // 1 SPECIAL CHARACTER
    ];

    let isValid = true;

    // CHECK EFFECT FOR PASSWORD REQUIREMENTS
    requirements.forEach((regex, index) => {
        const requirementItem = $('.requirement-list li').eq(index);
        const isRequirementMet = regex.test(password);

        if (isRequirementMet) {
            requirementItem.addClass('valid');
            requirementItem.find('i').removeClass('fa-circle').addClass('fa-check');
        } else {
            requirementItem.removeClass('valid');
            requirementItem.find('i').removeClass('fa-check').addClass('fa-circle');
            isValid = false; // Set isValid to false if any requirement is not met
        }
    });

    return isValid;
}
$(document).ready(function() {
        $('[name="password"]').keyup(validatePassword);
    
        $('[name="password"], [name="cpass"]').keyup(function() {
        var pass = $('[name="password"]').val();
        var cpass = $('[name="cpass"]').val();
        var messageElement = $('#pass_match');

        if (cpass !== '' && pass !== '') {
            if (cpass === pass) {
                messageElement.attr('data-status', '1').html('<i class="text-success">Password Matched.</i>').show();
            } else {
                messageElement.attr('data-status', '2').html('<i class="text-danger">Password does not match.</i>').show();
            }
        } else {
            messageElement.hide();
        }
    });
});
    
        $('#manage_student [name="school_id"]').on('keyup',function(){
        var school_id = $(this).val();
        
        // Check if the input matches the format ma20011385
        if(school_id.match(/^ma\d{8}$/)){
            // Format the school ID as MA-20-01-1385
            var formattedSchoolID = school_id.replace(/^ma(\d{2})(\d{2})(\d{4})$/, 'MA-$1-$2-$3');
            $(this).val(formattedSchoolID);
            
            // Perform AJAX request for validation
            $.ajax({
                url: 'check.php',
                method: 'POST',
                data: { school_id: formattedSchoolID }, // Use the formatted school ID for validation
                success: function (resp) {
                    console.log(resp);
                    if (resp.trim() == 'existing') {
                        $('#school_id_msg').html('<small style="color:red;">School ID already exists.</small>');
                        $('#manage_student [name="school_id"]').addClass('border-danger');
                    } 
                    else if(resp.trim() == 'not_existing') {
                        $('#school_id_msg').html('');
                        $('#manage_student [name="school_id"]').removeClass('border-danger');
                    }else {
                        $('#manage_student [name="school_id"]').removeClass('border-danger');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('AJAX Error: ' + textStatus);
                    $('#school_id_msg').html('<small style="color:red;">AJAX Error: ' + textStatus + '</small>');
                    $('#manage_student [name="school_id"]').removeClass('border-danger');
                }
            });
        } else if(school_id.match(/^MA-\d{2}-\d{2}-\d{4}$/)) {
            // Perform AJAX request for validation
            $.ajax({
                url: 'check.php',
                method: 'POST',
                data: { school_id: formattedSchoolID }, // Use the formatted school ID for validation
                success: function (resp) {
                    console.log(resp);
                    if (resp.trim() == 'existing') {
                        $('#school_id_msg').html('<small style="color:red;">School ID already exists.</small>');
                        $('#manage_student [name="school_id"]').addClass('border-danger');
                    } 
                    else if(resp.trim() == 'not_existing') {
                        $('#school_id_msg').html('');
                        $('#manage_student [name="school_id"]').removeClass('border-danger');
                    }else {
                        $('#manage_student [name="school_id"]').removeClass('border-danger');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('AJAX Error: ' + textStatus);
                    $('#school_id_msg').html('<small style="color:red;">AJAX Error: ' + textStatus + '</small>');
                    $('#manage_student [name="school_id"]').removeClass('border-danger');
                }
            });
        } else {
            $('#school_id_msg').html('<small style="color:red;">Invalid school ID format.</small>');
            $('#manage_student [name="school_id"]').addClass('border-danger');
        }
    });

        // Function to validate email format
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
                 // Function to validate password match
    $('[name="password"], [name="cpass"]').keyup(function(){
        var pass = $('[name="password"]').val()
        var cpass = $('[name="cpass"]').val()
        if(cpass == '' || pass == ''){
            $('#pass_match').attr('data-status','')
        } else {
            if(cpass == pass){
                $('#pass_match').attr('data-status','1').html('<i class="text-success">Password Matched.</i>')
            } else {
                $('#pass_match').attr('data-status','2').html('<i class="text-danger">Password does not match.</i>')
            }
        }
    });

    // Function to validate school ID format
    $('[name="school_id"]').on('keyup',function(){
        var school_id = $(this).val();

        // Check if the input matches the format MA-20-01-1385
        if(school_id.match(/^MA-\d{2}-\d{2}-\d{4}$/)){
            $('#school_id_msg').html('');
            $(this).removeClass('border-danger');
        } else {
            $('#school_id_msg').html('<small style="color:red;">Invalid school ID format.</small>');
            $(this).addClass('border-danger');
        }
    });

    // Function to capitalize the first letter of firstname and lastname
    $('.firstname, .lastname').on('keyup', function(){
        var inputValue = $(this).val();
        var capitalizedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1);
        $(this).val(capitalizedValue);
    });

function displayImg(input,_this) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#cimg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$('.form-group input[name="firstname"], .form-group input[name="lastname"]').on('input', function(){
    var inputValue = $(this).val();
    var capitalizedValue = inputValue.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
    $(this).val(capitalizedValue);
});

$(document).ready(function() {
        $('#manage_student').submit(function(e){
    e.preventDefault()
    const isPasswordValid = validatePassword();
    if (!isPasswordValid) {
        alert_toast("Password does not meet the requirements. Please check the password requirements.", "error");
        return;
    }

    // If password is valid, proceed with form submission
    $('input').removeClass("border-danger");
    $('#msg').html('');

    if($('[name="password"]').val() != '' && $('[name="cpass"]').val() != ''){
        if($('#pass_match').attr('data-status') != 1){
            if($("[name='password']").val() !=''){
                $('[name="password"],[name="cpass"]').addClass("border-danger")
                return false;
            }
        }
    }
    start_load()
    $.ajax({
        url:'ajax.php?action=save_student',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success:function(resp){
            if(resp == 1){
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Student successfully saved.');
                setTimeout(function(){
                    location.replace('indexx.php?page=student_list')
                },750)
            }else{
                $('#msg').html("<div class='alert alert-danger'>An error has been occured. Please try again.</div>");
                $('[name="email"]').addClass("border-danger")
                end_load()
            }
        }
    })
});
});

</script>
<style>
    #pass_match {
    margin-top: 5px;
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

/* Update the CSS selectors for the password requirement list */
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

/* Update the CSS selectors for the password strength indicators */
.password-strength-indicator {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
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
