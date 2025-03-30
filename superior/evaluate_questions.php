<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Online Quiz</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    #quiz {
        width: 60%;
        margin: auto;
        text-align: center;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    #options {
        margin-bottom: 20px;
    }

    #nextButton, #prevButton, #submitButton {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 0 10px;
    }

    #nextButton:hover, #prevButton:hover, #submitButton:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>

<div id="quiz">
    <h2 id="question">Ano ang pambansang ibon ng Pilipinas?</h2>
    <div id="options">
        <input type="radio" name="choice" value="A"> A. Maya<br>
        <input type="radio" name="choice" value="B"> B. Lawin<br>
        <input type="radio" name="choice" value="C"> C. Kalapati<br>
        <input type="radio" name="choice" value="D"> D. Tukô<br>
    </div>
    <button id="prevButton" onclick="prevQuestion()">Previous Question</button>
    <button id="nextButton" onclick="nextQuestion()">Next Question</button>
</div>

<button id="submitButton" onclick="submitAnswers()">Submit Answers</button>

<script>
    var questions = [
        {
            question: "Ano ang pambansang ibon ng Pilipinas?",
            choices: ["A. Maya", "B. Lawin", "C. Kalapati", "D. Tukô"]
        },
        {
            question: "Sino ang pambansang bayani ng Pilipinas?",
            choices: ["A. Andres Bonifacio", "B. Jose Rizal", "C. Emilio Aguinaldo", "D. Manuel Quezon"]
        },
        {
            question: "Ano ang kabisera ng Pilipinas?",
            choices: ["A. Manila", "B. Cebu", "C. Davao", "D. Quezon City"]
        }
    ];
    var currentQuestion = 0;

    // Load saved answer from localStorage if available
    var savedAnswer = localStorage.getItem('savedAnswer');
    if (savedAnswer !== null) {
        document.querySelector('input[name="choice"][value="' + savedAnswer + '"]').checked = true;
    }

    function nextQuestion() {
        currentQuestion++;
        updateQuestion();
    }

    function prevQuestion() {
        currentQuestion--;
        updateQuestion();
    }

    function updateQuestion() {
        document.getElementById("question").innerHTML = questions[currentQuestion].question;
        var optionsHTML = "";
        for(var i = 0; i < questions[currentQuestion].choices.length; i++) {
            optionsHTML += "<input type='radio' name='choice' value='" + String.fromCharCode(65 + i) + "'> " + questions[currentQuestion].choices[i] + "<br>";
        }
        document.getElementById("options").innerHTML = optionsHTML;

        // Hide or show previous button based on current question
        if (currentQuestion === 0) {
            document.getElementById("prevButton").style.display = "none";
        } else {
            document.getElementById("prevButton").style.display = "inline-block";
        }

        // Hide or show next button based on current question
        if (currentQuestion === questions.length - 1) {
            document.getElementById("nextButton").style.display = "none";
            document.getElementById("submitButton").style.display = "inline-block";
        } else {
            document.getElementById("nextButton").style.display = "inline-block";
            document.getElementById("submitButton").style.display = "none";
        }

        // Save selected answer to localStorage
        var selectedAnswer = document.querySelector('input[name="choice"]:checked');
        if (selectedAnswer !== null) {
            localStorage.setItem('savedAnswer', selectedAnswer.value);
        }
    }

    // Initial update to display first question
    updateQuestion();

    // Function to save answer to the database
    function saveAnswerToDatabase(answer) {
        // Here you can make an AJAX call or use another method to save the answer to your database
        // For demonstration purposes, I'm just logging the answer to the console
        console.log("Answer saved to database: " + answer);

        // Add your database saving logic here
        var user_id = 1; // You need to get the user ID
        var question_number = currentQuestion + 1;
        // Example AJAX call
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "save_answer.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log("Answer saved successfully!");
            }
        };
        var data = JSON.stringify({user_id: user_id, question_number: question_number, answer: answer});
        xhr.send(data);
    }

    // Function to submit answers
    function submitAnswers() {
        // Here you can submit all answers to the server
        for (var i = 0; i < questions.length; i++) {
            var answer = localStorage.getItem('answer_' + i);
            if (answer !== null) {
                saveAnswerToDatabase(answer, i + 1);
            } else {
                // Handle case where no answer is found for a question
                console.log("Answer for question " + (i + 1) + " is missing.");
            }
        }
        // After submitting answers, you might want to clear all saved answers from localStorage
        localStorage.clear();
        // Optionally, you can redirect the user or perform any other action after submitting answers
        console.log("All answers submitted successfully!");
    }
    
</script>

</body>
</html>