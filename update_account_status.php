<?php
// update_account_status.php
require 'db_connect.php';
require 'vendor/autoload.php';
require 'src/PHPMailer.php';
require 'src/Exception.php';
require 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
ini_set('display_errors', 1);
function decryptPassword($encryptedPassword) {
    // Implement your decryption logic here
    // For example, if the password was encrypted using base64 encoding:
    return base64_decode($encryptedPassword);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $school_id = $_POST['school_id'];
    $action = $_POST['action'];

    if ($action == 'accept') {
        
        $response = 1;
        $accountQuery = $conn->prepare("SELECT * FROM account_request WHERE school_id = ?");
        $accountQuery->bind_param("s", $school_id);
        if ($accountQuery->execute()) {
            $accountResult = $accountQuery->get_result();
            $accountRow = $accountResult->fetch_assoc();    
            
            $decryptedPassword = decryptPassword($accountRow['password']);

            $classQuery = $conn->prepare("SELECT *,CONCAT(curriculum, ' ', level, ' - ', section) FROM class_list WHERE id = ?");
            $classQuery->bind_param("i", $accountRow['class_id']);
            if ($classQuery->execute()) {
                $classResult = $classQuery->get_result();
                $classRow = $classResult->fetch_assoc();
                $class_id = $accountRow['class_id'];
                
                $insertQuery = $conn->prepare("INSERT INTO student_list 
                    (school_id, firstname, lastname, email, password, class_id, avatar) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insertQuery->bind_param("sssssss", $accountRow['school_id'],
                    $accountRow['firstname'], $accountRow['lastname'], $accountRow['email'],
                    $accountRow['password'], $class_id, $accountRow['avatar']);

                if ($insertQuery->execute()) {
                    $deleteQuery = $conn->prepare("DELETE FROM account_request WHERE school_id = ?");
                    $deleteQuery->bind_param("s", $school_id);
                    
                    $response = 1;
                    if ($deleteQuery->execute()) {
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
                                $mail->addAddress($accountRow['email']); // Receiver
                                $mail->addReplyTo('your_email@gmail.com', 'Information');

                                // Message
                                $mail->isHTML(true);
                                $mail->Subject = 'Account Verification - Faculty Evaluation System';
                                $mail->Body = '<b> Account Request Verification   </b> <br> <br>
                                                Hello BPCian! 
                                                We would like to inform you that your request for an account has been successfully verified.
                                                You can now able to access the Faculty Evaluation System by clicking on this link -> 
                                                <a href="http://localhost/eval/login.php">Click here.</a> <br>
                                                Login using your account: <br><br>
                                                email: ' . $accountRow['email'].'<br>
                                                password: ' . $accountRow['password'];
                                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                                $mail->send();
                                $response = 1;
                            } catch (Exception $e) {
                                // Log error
                                error_log('Email sending error: ' . $mail->ErrorInfo);
                                $response = 1;
                                $response = 0; // Error
                            }
                    } else {
                        $response = 1;
                        error_log('Delete query execution failed: ' . $conn->error);
                    }
                } else {
                    $response = 1;
                    error_log('Insert query execution failed: ' . $conn->error);
                }
            } else {
                $response = 1;
                error_log('Class query execution failed: ' . $conn->error);
            }
        } else {
            $response = 1;
            error_log('Account query execution failed: ' . $conn->error);
        }
    }else {
        // Decline the account request
        $deleteQuery = $conn->prepare("DELETE FROM account_request WHERE school_id = ?");
        $deleteQuery->bind_param("s", $school_id);
        if ($deleteQuery->execute()) {
            
            $response = 2;
        } else {
            error_log('Delete query execution failed: ' . $conn->error);
        }
    }
}
?>