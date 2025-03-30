

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<link rel="icon" type="image/png" href="images/uploads/bpc.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
</head>
<body style="background:url('images/uploads/bpcfront.jpg'); background-size:cover;background-repeat:no-repeat;">
<div class="wrapper">
    <header><b>Faculty Evaluation System</b></header>
    <div class="forgot-password-container"><br><br>
        <h3 class="text-center">FORGOT PASSWORD</h3>
        <form id="forgot" action="" method="post">
            <div class="field email">
                <div class="input-area">
                    <input type="email" name="email" required placeholder="Enter your account email">
                    <i class="icon fas fa-envelope"></i>
                    <i class="error error-icon fas fa-exclamation-circle"></i>
                </div>
                <div class="error error-txt">Email can't be blank</div>
            </div>
            <div class="submit-btn-container">
                <input type="submit" value="Submit" class="submit-btn">
            </div>
        </form>
        <div class="text-center">
            <button onclick="goBack()" style="padding:10px;" class="back-btn">Back</button>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    function goBack() {
        window.location.href = './login.php';
    }
</script>
<script>
    $(document).ready(function() {
        $('#forgot').submit(function(e) {
            e.preventDefault();

            var form = $(this);
            var email = form.find('input[name="email"]').val();

            $.ajax({
                url: 'send_password_reset_email.php', // PHP script para mag-validate ng email
                method: 'POST',
                data: {email: email},
                success: function(response) {
                    if (response == '1') {
                        // Success message using SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Password reset link has been sent to your email.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.href = './login.php';
                            }
                        });
                    } else if (response == '0') {
                        // Error message using SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Email not found.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        // Warning message using SweetAlert2
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: 'Email not found.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        });
    });
</script>
</body>
</html>
