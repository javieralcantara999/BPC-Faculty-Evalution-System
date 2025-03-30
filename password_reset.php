<?php
include_once "./db_connect.php";

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    if ($_SERVER["REQUEST_METHOD"] == "POST")   {
        $password = $_POST['sign_password'];

        $encryptedPassword = md5($password);
        $blank = '';

        $sql = "UPDATE student_list SET password = '$encryptedPassword' WHERE email = '$email' AND reset_token = '$token'";
        $sql2 = "UPDATE student_list SET `reset_token` = '$blank' WHERE `email` = '$email' AND `reset_token` = '$token'";

        if ($conn->query($sql) === TRUE  && $conn->query($sql2) === TRUE) {
                        header("Location: ./login.php");

        } else {
            echo "Error updating password: " . $conn->error;
        }
    }
    
} else {
    echo "Email and token parameters are missing in the URL.";
    exit;
}
?>
<script>
    $('form').submit(function (e) {
    e.preventDefault(); // Pigilin ang default form submission

    const password = $('#sign_password').val();

    // AJAX request para i-submit ang form data
    $.ajax({
        type: 'POST',
        url: window.location.href, // Ang URL ng kasalukuyang pahina
        data: { sign_password: password }, // Ang data ng form
        dataType: 'json', // Inaasahan ang JSON response
        success: function (response) {
            if (response.success) {
                // Kung matagumpay ang pag-update, ipakita ang SweetAlert na tagumpay
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = './login.php'; // Bumalik sa login page
                });
            } else {
                // Kung may error, ipakita ang SweetAlert na may error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        },
        error: function () {
            // Kapag may error sa AJAX request
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while processing your request.'
            });
        }
    });
});
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="icon" type="image/png" href="./images/bpc.ico">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.7/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <h1>Password Reset</h1>
        <form action="" method="post">
            
            <div class="field">
                <input type="password" placeholder="Create new password" class="password" name="sign_password" id="sign_password">
                <i class='bx bx-hide eye-icon' onclick="togglePassword('sign_password')"></i>
            </div>
            <div class="field">
                <input type="password" placeholder="Confirm new password" class="confirm_sign_password" name="confirm_sign_password" id="confirm_sign_password">
                <i class='bx bx-hide eye-icon' onclick="togglePassword('confirm_sign_password')"></i>
            </div>
            <div id="password_match_message" class="match-success"></div>
            <div class="content"><br><br>
                <p style = "text-align: left;margin-left:20px;">Password must contain:</p>
                <ul class="requirement-list">
                    <li>
                        <i class="fa-solid fa-circle"></i>
                        <span>At least 1 lowercase letter (a...z)</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-circle"></i>
                        <span>At least 1 uppercase letter (A...Z)</span>
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
            <input type="submit" value="Reset Password" id="resetPasswordBtn" disabled>
        </form>
    </div>
        <script>
            <?php if (!empty($alertMessage)): ?>
                    window.location.href = './login.php';
            <?php endif; ?>
        </script>
        <script>
            function validatePasswordMatch() {
            const password = document.getElementById('sign_password').value;
            const confirmPassword = document.getElementById('confirm_sign_password').value;
            const messageElement = document.getElementById('password_match_message');
            const resetPasswordBtn = document.getElementById('resetPasswordBtn');

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
            const requirementsMet = [...document.querySelectorAll('.requirement-list li.valid')].length === requirements.length;
            resetPasswordBtn.disabled = !requirementsMet || password !== confirmPassword;
        }
        function togglePassword(id) {
            var x = document.getElementById(id);
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        document.getElementById('sign_password').addEventListener('keyup', validatePasswordMatch);
        document.getElementById('confirm_sign_password').addEventListener('keyup', validatePasswordMatch);
            const forms = document.querySelector(".forms"),
            pwShowHide = document.querySelectorAll(".eye-icon"),
            links = document.querySelectorAll(".link");
            pwShowHide.forEach(eyeIcon => {
            eyeIcon.addEventListener("click", () => {
            let pwFields = eyeIcon.parentElement.parentElement.querySelectorAll(".password");
        
            
            })
    })      
        links.forEach(link => {
        link.addEventListener("click", e => {
        e.preventDefault();
        forms.classList.toggle("show-signup");
        })
    })

    const passwordInput = document.querySelector("#sign_password");
    const requirementList = document.querySelectorAll(".requirement-list li");
    const requirements = [
        { regex: /[a-z]/, index: 0 }, // At least one lowercase letter
        { regex: /[A-Z]/, index: 1 }, // At least one uppercase letter
        { regex: /[0-9]/, index: 2 }, // At least one digit
        { regex: /[^A-Za-z0-9]/, index: 3 }, // At least one special character
        { regex: /.{8,}/, index: 4 } // Minimum length of 8 characters
    ];

    passwordInput.addEventListener("keyup", (e) => {
        const password = e.target.value;
        
        requirements.forEach(item => {
            const isValid = item.regex.test(password);
            const requirementItem = requirementList[item.index];
            
            if (isValid) {
                requirementItem.classList.add("valid");
                requirementItem.querySelector("i").className = "fa-solid fa-check";
            } else {
                requirementItem.classList.remove("valid");
                requirementItem.querySelector("i").className = "fa-solid fa-circle";
            }
        });
    });

    let checkedRequirements = 0;

    function checkAllRequirements() {
        if (checkedRequirements === requirements.length) {
            console.log("Lahat ng mga pamantayan ay na-check na.");
        } else {
            console.log("Hindi pa kumpleto ang lahat ng mga pamantayan.");
        }
    }

    requirements.forEach(item => {
        const isValid = item.regex.test(password);
        if (isValid) {
            checkedRequirements++;
        }
    });

    checkAllRequirements();
    </script>
</body>
</html>

<style>
        body {
            background: url('./images/bpcfront.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .wrapper {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        .field {
            position: relative;
            height: 40px;
            width: 100%;
            margin-top: 20px;
            border-radius: 6px;
        }

        .field input {
            height: 100%;
            width: calc(100% - 40px); /* Subtracting the width of the eye icon */
            border: none;
            font-size: 16px;
            font-weight: 400;
            border-radius: 6px;
            padding: 0 15px;
            outline: none;
            border: 1px solid #CACACA;
        }

        .field input:focus {
            border-bottom-width: 2px;
        }

        .eye-icon {
            position: absolute;
            top: 50%;
            right: 25px;
            transform: translateY(-50%);
            font-size: 18px;
            color: #8b8b8b;
            cursor: pointer;
            padding: 5px;
        }

        .content {
            margin-top: -20px;
            font-weight: bold;
        }

        .requirement-list li {
            font-weight: normal;
            margin-top:10px;
            margin-left:15px;
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

        .requirement-list li.valid span {
            color: #999;
        }
        
        #password_match_message {
            font-weight: bold;
            font-size:15px;
            display: none;
        }

        .match-success {
            color: green;
        }

        .match-danger {
            color: red;
        }
        #resetPasswordBtn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>