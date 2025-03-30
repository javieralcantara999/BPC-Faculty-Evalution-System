<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Participation</title>
    <link rel="icon" type="image/png" href="../images/uploads/bpc.ico">

    <style type="text/css">
        * {
            font-family: 'Times New Roman', Times, serif;
        }
        body, html {
            margin: 0;
            padding: 0;
            height: 100%; /* Set body and html height to 100% */
        }
        body {
            color: black;
            display: flex; /* Use flexbox for vertical and horizontal centering */
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            font-size: 24px;
            text-align: center;
            background: url('../images/bpcfront.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
        .container {
            border: 10px solid darkgreen;
            width: 750px;
            height: 563px;
            background-color: white; /* Change background color to white */
            display: flex; /* Use flexbox for vertical and horizontal centering */
            flex-direction: column; /* Stack child elements vertically */
            align-items: center; /* Center horizontally */
            justify-content: center; /* Center vertically */
            padding: 20px;
            position: relative; /* Position relative for button positioning */
            border-radius:10px;
        }
        .logo {
            margin-top:40px;
            color: darkgreen;
        }
        .marquee {
            color: green;
            font-size: 48px;
            margin: 15px;
        }
        .assignment {
            margin: 15px;
        }
        .person {
            border-bottom: 2px solid black;
            font-size: 32px;
            font-style: italic;
            margin: 15px auto;
            width: 400px;
        }
        .reason {
            margin: 20px;
            font-size: 18px;
        }
        .logs {
            width: 70px;
            height: 70px;
        }
        .print-button {
            background-color: #28a745; 
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s; 
            margin-top: 50px;
        } 
        .print-button:hover {
            background-color: #218838; /* Change background color on hover */
        }
        @media print {
            /* Hide unnecessary elements when printing */
            .print-button,
            .container::after,
            .url-bar {
                display: none;
            }
            title {
                display: none;
            }
            .url-bar::after {
                content: "For Academic Purposes";
                display: block;
                font-size: 12px;
                margin-top: 0px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <br>
            <img src="../images/uploads/bpc.ico" class="logs" alt="" style="width:90px;height:90px;margin-top:10px;">
            <br><br>
            BULACAN POLYTECHNIC COLLEGE<br><br>
            Faculty Evaluation System
        </div>
        <div class="marquee">
            Certificate of Participation
        </div>
        <div class="assignment">
            This is to certify that
        </div>
        <br>
        <div class="person">
            <?php
            // Retrieve student's name from URL parameter
            $studentName = isset($_GET['name']) ? $_GET['name'] : '';
            echo $studentName;
            ?>
        </div>
        <div class="reason">
            has actively participated in the Faculty Evaluation System for the <br>
            School Year:
            <?php
                // Retrieve academic year from URL parameter
                $academicYear = isset($_GET['year']) ? $_GET['year'] : '';
                echo $academicYear;

                // Retrieve semester from URL parameter
                $semester = isset($_GET['semester']) ? $_GET['semester'] : '';

                // Convert semester number to a more descriptive format
                $semesterText = '';
                switch ($semester) {
                    case '1':
                        $semesterText = '1st Semester';
                        break;
                    case '2':
                        $semesterText = '2nd Semester';
                        break;
                    case '3':
                        $semesterText = '3rd Semester';
                        break;
                    default:
                        $semesterText = $semester . 'th Semester'; // Handles other cases (e.g., 4th, 5th, etc.)
                        break;
                }

                echo ' ' . $semesterText;
                ?>
            .<br> The feedback and insights provided by
            <?php echo $studentName; ?> contribute significantly to the continuous improvement of our faculty members.
        </div>
        <button onclick="printCertificate()" class="print-button">Download Certificate</button>
        <!-- Placeholder for academic purposes text -->
        <div class="academic-purpose-text"></div>
    </div>

    <script>
        function printCertificate() {
            // Retrieve necessary data from the page
            var studentName = '<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>';
            var academicYear = '<?php echo isset($_GET['year']) ? $_GET['year'] : ''; ?>';
            var semester = '<?php echo isset($_GET['semester']) ? $_GET['semester'] : ''; ?>';

            // Construct the clean URL with parameters
            var certificateURL = `certificate.php?name=${encodeURIComponent(studentName)}&year=${encodeURIComponent(academicYear)}&semester=${encodeURIComponent(semester)}`;

            // Open a new window with the certificate URL for printing
            var printWindow = window.open(certificateURL);
            if (printWindow) {
                printWindow.print(); // Print the opened window
                
            } else {
                alert('Please allow pop-ups to print the certificate.'); // Display error if pop-up was blocked
            }
        }
    </script>