<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    require 'vendor/autoload.php';
    require 'src/PHPMailer.php';
    require 'src/Exception.php';
    require 'src/SMTP.php';

    include 'db_connect.php';

    // Define an array of tables to check
    $tables = ['student_list', 'faculty_list', 'superior_list'];

    // I-check kung ang form ay na-submit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Kunin ang email mula sa form
        $email = $_POST['email'];

        $found = false;

        // Loop through each table and check if email exists
        foreach ($tables as $table) {
            $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $found = true;
                // Email exists in this table, generate and store token
                $token = uniqid(); // Generate unique token

                // Store the token in the database or wherever you prefer
                // For example, you can create a new column 'reset_token' in your table
                $stmt_update = $conn->prepare("UPDATE $table SET reset_token = ? WHERE email = ?");
                $stmt_update->bind_param("ss", $token, $email);
                $stmt_update->execute();
                $stmt_update->close();

                // Send password reset email
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'javieralcantara999@gmail.com'; // Sender email
                    $mail->Password = 'jakohlqtcxkxngid'; // Sender password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;
                    $mail->setFrom('javieralcantara999@gmail.com', 'Faculty Evaluation System');
                    $mail->addAddress($email); // Receiver
                    $mail->addReplyTo('javieralcantara999@gmail.com', 'Information');

                    // Message
                    $mail->isHTML(true);
                    $mail->Subject = 'Reset Password - Faculty Evaluation System BPC';
                    $mail->Body = '
                    <b> Reset Password Link:  </b> <br> <br>
                    Click this link to reset your password -> 
                    <a href="http://localhost/eval/password_reset.php?email=' . $email . '&token=' . $token . '"> Click here.</a>';
                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                    
                    $mail->send();
                    echo '1'; // Success
                } catch (Exception $e) {
                    echo '0'; // Error
                }

                break; // Exit loop once email is found and processed
            }
        }

        if (!$found) {
            echo '0'; // Email not found in any of the specified tables
        }

        // I-close ang MySQL connection
        mysqli_close($conn);
        exit(); // Hindi na natin kailangan magpatuloy sa pag-render ng HTML page pagkatapos ng AJAX request
    }
?>