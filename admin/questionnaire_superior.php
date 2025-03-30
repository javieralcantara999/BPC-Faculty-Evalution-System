<?php include 'db_connect.php' ?>
<script>
    document.title = "Manage Questions (Superiors) | Administrator";
</script>
<h1 class="m-0 text-center"><i class="fas fa-envelope-open-text"></i>&nbsp;<b>MANAGE QUESTION FORM (SUPERIOR)</b></h1><br><hr>
<div class="col-lg-12" id="tbl">
    <div class="card card-success">
        <div class="card-body">
            <table class="table table-hover table-bordered" id="list" style="text-align: left;padding-top:0px;">
                <colgroup id="cols">
                    <col width="5%">
                    <col width="35%">
                    <col width="5%">
                    <col width="8%">
                    <col width="5%" class="text-center">
                </colgroup>
                <thead>
                    <tr class="text-center">
                        <th class="text-center">No.</th>
                        <th class="text-left">School Year & Semester</th>
                        <th>Questions</th>
                        <th>Submitted Answers</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT * FROM academic_list ORDER BY abs(year) DESC, abs(semester) DESC ");
                    while ($row = $qry->fetch_assoc()) :
                        $academic_year_semester = $row['year'] . ' ' . ($row['semester'] == 1 ? '1st' : '2nd') . ' Semester';
                        $questions = $conn->query("SELECT * FROM question_list_superior WHERE academic_id = {$row['id']}")->num_rows;
                        $answers = $conn->query("SELECT * FROM evaluation_list_superior WHERE academic_id = {$row['id']}")->num_rows;
                    ?>
                        <tr class="text-left">
                            <th class="text-center"><?php echo $i++ ?></th>
                            <td class="text-left"><b><?php echo $academic_year_semester ?></b></td>
                            <td class="text-center"><b><?php echo number_format($questions) ?></b></td>
                            <td class="text-center"><b><?php echo number_format($answers) ?></b></td>
                            <td class="text-center">
                                <a href="indexx.php?page=manage_questionnaire_superior&id=<?php echo $row['id'] ?>" class="btn btn-sm btn-success btn-gradient-success manage_questionnaire">
                                    <i class="fas fa-cogs"></i> Manage
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    @media screen and (max-width: 768px) {
        #tbl {
            width: 100%;
        }
        #cols {
            width: 5px;
        }
        table {
            overflow-x: auto;
            display: block;
        }

        /* Style for the "Add Academic Year" button on hover */
        #sub:hover {
            background: darkgreen !important;
        }
    }
</style>
<script>
    $(document).ready(function () {
        $('#list').dataTable({
            "paging": false, // Hide pagination controls
            "searching": true, // Show search bar
            "info": false, // Hide "Showing [start] to [end] of [total] entries" label
            "ordering": false // Disable sorting
        });
    });

</script>
