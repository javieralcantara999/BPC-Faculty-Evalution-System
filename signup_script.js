function togglePassword(id) {
    var x = document.getElementById(id);
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
function capitalizeFirstLetter(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Automatically capitalize the first letter of firstname and lastname
$('.form-group input[name="firstname"], .form-group input[name="lastname"]').on('input', function(){
    var inputValue = $(this).val();
    var capitalizedValue = inputValue.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
    $(this).val(capitalizedValue);
});
// Format the school ID as MA-YY-MM-NNNN

// CHECK THE VALIDITY OF SCHOOL ID
$(document).ready(function(){
    $('#signup_account [name="school_id"]').on('keyup',function(){
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
        } else if(school_id.match(/^MA-\d{2}-\d{2}-\d{4}$/)) {
            // Perform AJAX request for validation
            $.ajax({
                url: 'check.php',
                method: 'POST',
                data: { school_id: school_id }, // Use the original school ID for validation
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
    });
        
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

    // FUNCTION TO VALIDATE PASSWORD MATCH
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
        
    function start_load() {
    // Add loading animation or any other logic here
}


 // Function to capitalize the first letter of a string

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
$(document).ready(function() {
    // Function to validate all required fields
    // Function to validate all required fields
function validateAllFields() {
    // Validation for firstname and lastname
    var firstname = $('.firstname').val();
    var lastname = $('.lastname').val();
    var firstnameValid = /^[a-zA-Z\s\(\),.'-]+$/.test(firstname);
    var lastnameValid = /^[a-zA-Z\s\(\),.'-]+$/.test(lastname);

    // Validation for school ID
    var school_id = $('#signup_account [name="school_id"]').val();
    var schoolIdValid = school_id.match(/^MA-\d{2}-\d{2}-\d{4}$/);

    // Validation for email
    var email = $('[name="email"]').val();
    var emailValid = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email);

    // Validation for section
    var section = $('#class_id').val();
    var sectionValid = section !== ''; // Check if section is not default text

    // Validation for password and confirm password
    var password = $('[name="password"]').val();
    var confirmPassword = $('[name="cpass"]').val();
    var passwordValid = validatePassword();
    var confirmPasswordValid = password === confirmPassword;

    // Return true if all fields are valid, otherwise return false
    return firstnameValid && lastnameValid && schoolIdValid && emailValid && sectionValid && passwordValid && confirmPasswordValid;
}
    // Function to validate password strength
    function validatePassword() {
        const password = $('[name="password"]').val();
        const requirements = [
            /[a-z]/, // 1 LOWERCASE
            /[A-Z]/, // 1 UPPERCASE
            /[0-9]/, // 1 DIGIT
            /.{8,}/, // 8 CHARACTERS
            /[^A-Za-z0-9]/ // 1 SPECIAL CHARACTER
        ];

        // Check if password meets all requirements
        return requirements.every(regex => regex.test(password));
    }
    function start_load() {
        // Add loading animation logic here
        
        $('#loading_indicator').show();
    }
    function end_load() {
        // Hide the loading animation or indicator
        $('#loading_indicator').hide();
    }
    // Call validateAllFields function when 'Create Account' button is clicked
    $('#create_account_btn').click(function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Check if all fields are valid
        if (validateAllFields()) {
            // All fields are valid, proceed with AJAX request
            start_load(); // Show loading animation
        
            // Create FormData object
            var formData = new FormData($('#signup_account')[0]);
            
// Perform AJAX request
            $.ajax({
                url: 'ajax.php?action=signup_account',
                method: 'POST',
                data: formData, // Pass FormData object directly
                processData: false, // Ensure processData is set to false
                contentType: false, // Ensure contentType is set to false
                success: function(resp) {
                    debugger; // Add debugger statement here
                    if (resp == 1) {
                        // Account request sent successfully
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Your request for an account has been sent. Please wait for approval at your email account.',
                            
                            showConfirmButton: false,
                            timer: 3000 // Close alert after 3 seconds
                        });
                        // Redirect to login page after success
                        setTimeout(function() {
                            window.location.href = './login.php';
                        }, 3000);
                    } else if (resp == 2) {
                        // Error occurred
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while processing your request. Please try again later.',
                            showConfirmButton: false,
                            timer: 3000 // Close alert after 3 seconds
                        });
                        end_load(); // Hide loading animation
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('AJAX Error: ' + textStatus);
                    // Display error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'AJAX Error: ' + textStatus,
                        showConfirmButton: false,
                        timer: 3000 // Close alert after 3 seconds
                    });
                    end_load(); // Hide loading animation
                }
            });
        } else {
            // Not all fields are valid, display error message
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please fill in all required fields correctly.',
                showConfirmButton: true // Allow user to close alert
            });
        }
    });
});