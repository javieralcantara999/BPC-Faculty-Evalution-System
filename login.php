<!DOCTYPE html>
<html lang="en">
<link rel="icon" type="image/png" href="images/uploads/bpc.ico">
<link rel="stylesheet" href="login.css">
<?php
session_start();
include('./db_connect.php');
ob_start();

$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
foreach ($system as $k => $v) {
    $_SESSION['system'][$k] = $v;
}

ob_end_flush();
?>
<?php
if (isset($_SESSION['login_id']))
    header("location:indexx.php?page=home");
?>
<?php include 'header.php' ?>
<body style="background:url('images/uploads/bpcfront.jpg'); background-size:cover;background-repeat:no-repeat;">
    <div class="wrapper">
        <header><b>Faculty Evaluation System</b></header>
        <form id="login-form" action="" method="post">
            <div class="field email">
                <div class="input-area">
                    <input type="email" name="email" required placeholder="Email Address">
                    <i class="icon fas fa-envelope"></i>
                    <i class="error error-icon fas fa-exclamation-circle"></i>
                </div>
                <div class="error error-txt">Email can't be blank</div>
            </div>
            <div class="field password">
                <div class="input-area">
                    <input type="password" name="password" id="passwordInput" required placeholder="Password">
                    <i class="icon fas fa-lock"></i>
                    <i class="bx bx-hide show-hide"></i>
                    <i class="error error-icon fas fa-exclamation-circle"></i>
                </div>
                <div class="error error-txt">Password can't be blank</div>
            </div>
            <div class="pass-txt"><a href="./forgotpassword.php">Forgot password?</a></div>
            <input type="submit" value="Login">
            </form>
        <div class="sign-txt">Doesn't have an account? <a href="./signup.php" style="color:darkgreen"><strong>Sign up</strong></a></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="script.js"></script>
    <script>
    $(document).ready(function() {
        const form = $("#login-form");

        form.submit(function(e) {
            e.preventDefault();

            const emailInput = $("input[name='email']");
            const passwordInput = $("input[name='password']");

            // Validation
            if (!emailInput.val().trim()) {
                showError(emailInput, "Email can't be blank");
                return;
            }

            if (!passwordInput.val().trim()) {
                showError(passwordInput, "Password can't be blank");
                return;
            }

            // Clear errors
            clearError(emailInput);
            clearError(passwordInput);

            // AJAX
            start_load();
            if (form.find('.alert-danger').length > 0)
                form.find('.alert-danger').remove();
            
            $.ajax({
                url: 'ajax.php?action=login',
                method: 'POST',
                data: form.serialize(),
                error: err => {
                    console.log(err);
                    end_load();
                },
                success: function(resp) {
                    if (resp == 1) {
                        location.href = 'indexx.php?page=home';
                    } else {
                        form.prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
                        end_load();
                    }
                }
            });
        });

        function showError(input, message) {
            const field = input.closest(".field");
            const errorTxt = field.find(".error-txt");

            field.addClass("shake error");
            errorTxt.text(message);
        }

        function clearError(input) {
            const field = input.closest(".field");
            field.removeClass("shake error");
        }

        const eyeIcons = $(".show-hide");
        eyeIcons.click(function() {
            const pInput = $(this).parent().find("#passwordInput");
            if (pInput.attr("type") === "password") {
                $(this).removeClass("bx-hide").addClass("bx-show");
                pInput.attr("type", "text");
            } else {
                $(this).removeClass("bx-show").addClass("bx-hide");
                pInput.attr("type", "password");
            }
        });
    });
</script>
    <?php include 'footer.php' ?>
</body>
</html>

