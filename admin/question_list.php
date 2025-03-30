<?php include 'db_connect.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);?>
<script>
    document.title = "Questions (Students) | Administrator";
</script>
<h1 class="m-0 text-center"><i class="fas fa-question-circle"></i>&nbsp;<b> QUESTIONS LIST (STUDENTS)</b></h1><br><hr>
<div class="col-lg-12" id="tbl">
    <div class="card card-success">
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="callout" style="border: 0px;">
                            <?php 
                                $qry = $conn->query("SELECT * FROM questions_list");
                                if($qry->num_rows > 0):
                            ?>
                            <div class="justify-content-center w-40" style="font-size:25px;">
                                <label for=""><b>Questions</b></label>
                                <input type="text" id="searchQuestion" class="form-control mt-2" placeholder="Search question here...">
                            </div>
                            <hr>
                            <ul class="list-group btn col-md-12" id="ui-sortable-list">
                            <?php while($row = $qry->fetch_assoc()): ?>
                                <li class="list-group-item text-left">
                                    <div class="btn-group dropright float-right">
                                        <div class="btn-group">
                                            <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' 
                                            class="btn btn-sm btn-success btn-gradient edit_question"
                                            style="text-decoration: none;color:white;">
                                                <i class="fas fa-cog"></i> Edit
                                            </a>&nbsp;
                                            <button type="button" class="btn btn-sm btn-danger btn-gradient delete_question" 
                                                    data-id="<?php echo $row['id'] ?>">
                                                <i class="fas fa-alt"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                    <?php echo $row['questions'] ?>
                                    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                </li>
                            <?php endwhile; ?>
                            </ul>
                            <?php else: ?>
                                <center>There are no questions in the database yet</center>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group mb-1 d-flex" >
                            <form method="post" id = "import_questions"enctype="multipart/form-data" class="ml-1">
                                <input type="file" class="form-control-file" name="exceldata" id="exceldata"><br>
                                <button type="submit" name="import" class="btn btn-primary btn-sm">
                                    <i class = "fas fa-file-excel"></i>&nbsp; Import questions from excel</button>
                            </form>
                            </div>
                            <br>
                        <div class="card">
                            <div class="card-header text-center" style="background:darkgreen;color:white"><b>Question Information</b></div>
                            <div class="card-body">
                                <form id="manage-question">
                                    <input type="hidden" name="id">
                                    <div class="form-group">
                                        <label for="">Question</label>
                                        <textarea name="question" class="form-control form-control-m" style = "height:100px"></textarea>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-sm btn-success bg-gradient-success mx-1" type="submit">Save</button>
                                        <button class="btn btn-sm btn-secondary bg-gradient-secondary mx-1" type="button" id="clear-question">Clear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
</style>
<script>
    $(document).ready(function() {
    $('#import_questions').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = new FormData(this);
        start_load()
        $.ajax({
            url: 'ajax.php?action=import_questions',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 1) {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success('Questions imported successfully.');
                    setTimeout(function() {
                        location.reload();// Redirect to class_list.php
            end_load()
                    }, 1000);
                } else {
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.error(response); // Display error message
            end_load()
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText); // Log any AJAX 
            end_load()
            }
        });
    });
});
    $(document).ready(function() {
        $('#searchQuestion').on('input', function() {
            var searchText = $(this).val().trim().toLowerCase();
            filterQuestions(searchText);
        });
    });

    function filterQuestions(searchText) {
        $('#ui-sortable-list li').each(function() {
            var questionText = $(this).text().toLowerCase();
            var isVisible = questionText.includes(searchText);
            $(this).toggle(isVisible);
        });

        // I-check kung walang result ang search
        var noQuestionsFound = $('#ui-sortable-list li:visible').length === 0;
        $('#noQuestionsFound').toggle(noQuestionsFound); // Ipakita ang "No questions found" message kung walang result
    }
    $('#searchQuestion').on('input', function() {
        var searchText = $(this).val().trim().toLowerCase();
        filterQuestions(searchText);

        // I-check kung blangko ang searchText, pagkakataon na ipakita ulit ang lahat ng tanong
        if (searchText === '') {
            $('#ui-sortable-list li').show();
        }
        var noQuestionsFound = $('#ui-sortable-list .question-item:visible').length === 0;
        $('#noQuestionsFound').toggle(noQuestionsFound); // Ipakita ang "No questions found" message kung walang result
    });
    $(document).ready(function(){
        // Edit question
        $('.edit_question').click(function(){
            var id = $(this).attr('data-id');
            start_load()
            $.ajax({
                url: 'ajax.php?action=get_question',
                method: 'POST',
                dataType: 'json',
                data: {id: id},
                success: function(response) {
                        end_load()
                    // Handle successful response
                    console.log("Question details:", response);
                    if (response && response.questions) { // Check if response and question data exist
                        $('input[name="id"]').val(response.id);
                        $('textarea[name="question"]').val(response.questions);
                    } else {
                        console.error("Invalid response format or missing question data.");
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors
                    console.error("AJAX error:", status, error);
                    // Log the response text for debugging
                    console.log("Response text:", xhr.responseText);
                }
            });
        });

        // Delete question
        $('.delete_question').click(function(){
            var id = $(this).attr('data-id');
            _conf("Are you sure you want to delete this question?", "delete_question", [id]);
        });

        // Clear button functionality
        $('#clear-question').click(function() {
            $('textarea[name="question"]').val('');
        });
        toastr.options = {
        closeButton: true,
        progressBar: false,
        positionClass: 'toast-top-center', // Position the toast alert at the center
        preventDuplicates: true, // Prevent duplicate toasts
        showDuration: 300,
        hideDuration: 500,
        timeOut: 2000, // Duration the toastr is displayed (in milliseconds)
        extendedTimeOut: 500 // Duration to close the toastr after a user hovers over it (in milliseconds)
    };
        // Form submission
        $('#manage-question').submit(function(e) {
            e.preventDefault();

            // Retrieve the question input value
            var questionText = $('textarea[name="question"]').val().trim();

            // Check if the question is empty
            if (questionText === '') {
                // Display a toastr error message
                toastr.error('Please enter a question before saving.', 'Error');
                return; // Prevent form submission if question is blank
            }

            // Proceed with form submission if the question is not blank
            var formData = $(this).serialize();
            start_load();

            $.ajax({
                url: 'ajax.php?action=save_questions',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response == 1) {
                        toastr.options.positionClass = 'toast-top-center';
                        toastr.success("Question successfully saved.", 'Success');
                        setTimeout(function() {
                            location.reload();
                            end_load();
                        }, 1000);
                    } else if (response == 2) {
                        toastr.options.positionClass = 'toast-top-center';
                        toastr.error("Question already exists.", 'Error');
                        setTimeout(function() {
                            location.reload();
                            end_load();
                        }, 1000);
                    }
                }
            });
        });
    });

    function delete_question(id){
        
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_questions',
            method: 'POST',
            data: {id: id},
            success:function(response){
                if(response == 1){
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.success("Question successfully deleted.", 'Success');
                    setTimeout(function(){
                        location.reload();
                        end_load()
                    },1000);
                }
            }
        });
    }
</script>