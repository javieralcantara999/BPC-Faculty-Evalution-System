<?php include 'db_connect.php';?>

<script>
    document.title = "Sentiment Terminologies | Administrator";
    </script>

<!-- HTML Content -->
<h1 class="m-0 text-center"><i class="fas fa-comment"></i>&nbsp;<b>SENTIMENT TERMINOLOGIES</b></h1><br><hr>
<div class="container-fluid ">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-success">
                <div class="card-header text-center" style="background:darkgreen;color:white;">
                    <b>Sentiment Terms</b>
                </div>
                <div class="card-body">
                    <form action="" id="manage-sentiments" method="post">
                        <input type="hidden" name="sentiment_id" value="">
                        <div class="form-group">
                            <label for="">Term / Word</label>
                            <textarea name="term" id="term" cols="30" rows="4" class="form-control"
                                required=""></textarea>
                        </div>

                        <button id="submit-term" class="btn btn-sm btn-success mx-1"
                            style="border-radius: 5px;font-size:15px;">Add Term</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
                    &nbsp;<div class="main-search1 ml-5">
						<i class="fas fa-search"></i>&nbsp;
                        <input type="text" style = "border-radius:5px;border:1px solid grey;text-decoration:none;height:35px;"
                        id="search_records" placeholder="Search term here" class="main-search">
                    </div>
<!-- Positive Words Table -->
<div class="sentiment-container">
    <div class="container-table-words">
        <table class="table-words table-bordered" id="positiveTable">
            <thead class="table-success" style="background:green;color:white;">
                <tr>
                    <th width="12%">Positive Words</th>
                    <th width="18%">Action</th>
                </tr>
            </thead>
            <tbody id="positive_results">
            </tbody>
        </table>
    </div>
    <div class="container-table-words">
        <table class="table-words table-bordered" id="negativeTable">
            <thead class="table-danger" style="background:darkred;color:white;">
                <tr>
                    <th width="12%">Negative Words</th>
                    <th width="18%">Action</th>
                </tr>
            </thead>
            <tbody id="negative_results">
            </tbody>
        </table>
    </div>
    <div class="container-table-words">
        <table class="table-words table-bordered" id="neutralTable">
            <thead class="table-secondary" style="background:gray;color:white;">
                <tr>
                    <th width="12%">Neutral Words</th>
                    <th width="18%">Action</th>
                </tr>
            </thead>
            <tbody id="neutral_results">
            </tbody>
        </table>
    </div>
</div>
<script>
function termExistsInOtherTable(term) {
    var exists = false;

    // Check if term exists in positiveTable
    if ($('#positive_results').find('td:contains(' + term + ')').length > 0) {
        exists = true;
    } else if ($('#negative_results').find('td:contains(' + term + ')').length > 0) {
        exists = true;
    }

    return exists;
}
 $(document).ready(function() {
    fetchSentimentTerms();

    // Button click handler para sa pagdaragdag ng bagong term
    $('#submit-term').click(function(e) {
        e.preventDefault(); // Pigilan ang default form submission behavior

        // Kunin ang term na nasa textarea
        var term = $('#term').val().trim();

        if (termExistsInOtherTable(term)) {
        alert_toast("Term already exists in another table.", 'bg-danger');
        return; // Exit function if term exists in another table
    }
        // Gumawa ng AJAX request para idagdag ang term sa database
        $.ajax({
            url: 'ajax.php?action=add_term',
            type: 'POST',
            data: {
                term: term,
                term_type: 'Neutral', // Default na term_type
                value: 0 // Default value
            },
            success: function(response) {
                // Ipakita ang response message sa toast
                alert_toast(response, 'bg-success');
                // I-reload ang neutral table pagkatapos ng pagdaragdag ng term
                fetchSentimentTerms();
                // Clear ang textarea
                $('#term').val('');
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });

    // Function para mag-fetch ng sentiment terms mula sa server
    function fetchSentimentTerms() {
        $.ajax({
            url: 'ajax.php?action=sentiment_terms',
            type: 'GET',
            success: function(response) {
                var data = JSON.parse(response);

                // Populate tables based on fetched data
                populateTable('positiveTable', data.positive, 'Positive');
                populateTable('negativeTable', data.negative, 'Negative');
                populateTable('neutralTable', data.neutral, 'Neutral');
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }

    // Function para i-populate ang table base sa data
    function populateTable(tableId, terms, termType) {
        var table = $('#' + tableId);
        var tbody = table.find('tbody');
        var thead = table.find('thead');

        // Clear existing rows
        tbody.empty();

        if (terms && terms.length > 0) {
            // Show thead if there are terms to display
            thead.show();

            terms.forEach(function(term) {
                var row = $('<tr>');
                row.append($('<td>').text(term.term));
                var actionBtns = $('<td class="text-center">');

                // Button to move term
                if (termType !== 'Positive') {
                    actionBtns.append($('<button>').text('Positive').addClass('btn-move btn-sm btn-gradient btn-success m-1 b-0 text-center').attr('data-termid', term.term_id).attr('data-action', 'Positive'));
                }
                if (termType !== 'Negative') {
                    actionBtns.append($('<button>').text('Negative').addClass('btn-move btn-sm btn-gradient btn-danger m-1 b-0 text-center').attr('data-termid', term.term_id).attr('data-action', 'Negative'));
                }
                if (termType !== 'Neutral') {
                    actionBtns.append($('<button>').text('Neutral').addClass('btn-move btn-sm btn-gradient btn-secondary m-1 b-0 text-center').attr('data-termid', term.term_id).attr('data-action', 'Neutral'));
                }

                // Add trash icon for delete action
                var trashIcon = $('<i>').addClass('fas fa-trash text-danger ml-1 delete-term').attr('data-termid', term.term_id).attr('title', 'Delete');
                actionBtns.append(trashIcon);

                row.append(actionBtns);
                tbody.append(row);
            });
        } else {
            // Hide thead if there are no terms to display
            thead.show();
        }
    }
    // Button click handler para sa pag-delete ng term
    $(document).on('click', '.delete-term', function() {
    var termId = $(this).attr('data-termid');

    // Ipakita ang SweetAlert prompt para sa confirmation
    Swal.fire({
        icon: 'error',
        title: 'Are you sure?',
        text: 'You are about to delete this term.',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        reverseButtons: false
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request para idelete ang term
        
            $.ajax({
                url: 'ajax.php?action=delete_term&term_id=' + termId,
                type: 'GET',
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Term successfully deleted.'
                    });
                    fetchSentimentTerms(); // Reload terms after deletion
                    
                    end_load()
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete term. Please try again.'
                    });
                    
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
        }
    });
});

    // Button click handler para sa paglipat ng term
    $(document).on('click', '.btn-move', function() {
        var termId = $(this).attr('data-termid');
        var action = $(this).attr('data-action');
        moveTerm(action, termId);
    });

    // Function para mag-move ng term
    function moveTerm(action, termId) {
        $.ajax({
            url: 'ajax.php?action=' + action + '&term_id=' + termId,
            type: 'GET',
            success: function(response) {
                alert_toast(response, 'bg-success');
                fetchSentimentTerms(); // Reload terms after move
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }

});
$(document).ready(function() {
    // Function to handle search input change
    $('#search_records').on('input', function() {
        var searchText = $(this).val().trim().toLowerCase();

        // Filter terms and display results
        filterTable('positiveTable', searchText);
        filterTable('negativeTable', searchText);
        filterTable('neutralTable', searchText);
    });

    // Function to filter table rows based on search text
    function filterTable(tableId, searchText) {
        var table = $('#' + tableId);
        var tbody = table.find('tbody');
        var rows = tbody.find('tr');
        var noResultsRow = table.find('.no-results-row');

        rows.hide(); // Hide all rows initially

        // Filter and show rows based on search text
        rows.each(function() {
            var termText = $(this).text().toLowerCase();

            if (termText.includes(searchText)) {
                $(this).show(); // Show row if it matches search text
            }
        });

        // Show "No results found" message if no matching rows
        if (tbody.find('tr:visible').length === 0) {
            if (noResultsRow.length === 0) {
                noResultsRow = $('<tr class="no-results-row"><td colspan="2" class="text-center">No results found</td></tr>');
                tbody.append(noResultsRow);
            } else {
                noResultsRow.show();
            }
        } else {
            noResultsRow.hide(); // Hide the message if there are matching rows
        }
    }
});
</script>
<script>
    
</script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
    .alert-toast {
    position: fixed;
    top: 50%; /* I-set ang top position sa 50% ng screen */
    left: 50%; /* I-set ang left position sa 50% ng screen */
    transform: translate(-50%, -50%); /* I-center horizontally at vertically */
    z-index: 9999;
    padding: 15px;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center; /* I-center horizontally */
    align-items: center; /* I-center vertically */
}
    .container-fluid {
        margin-top: 5px;
    }

    .card {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .sentiment-container {
        display: flex;
        margin: 0 auto;
        flex-wrap: wrap; /* I-wrap ang mga card kapag nagiging masyadong maliit na ang screen */
        justify-content: center; /* I-center ang mga card sa horizontal axis */
    }

    .container-table-words {
        width: calc(33.33% - 1em); /* Baguhin ang width para maging 1/3 ng screen width */
        margin: 0.5em;
    }

    .table-words {
        border-collapse: collapse;
        font-size: 1rem;
        border-radius: 5px 5px 0 0;
        width: 100%;
        margin: 0.5em;
        height: max-content;
        overflow-x: auto;
    }

    tbody {
        display: table-row-group;
        vertical-align: middle;
        border-color: inherit;
    }

    .table-words th {
        padding: 8px;
        text-align: center;
    }

    .table-words td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .card-header {
        padding: 10px;
        font-size: 18px;
    }

    .card-body,
    .card-footer {
        padding: 20px;
    }

    .btn-sm {
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .table-words th,
        .table-words td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-header {
            font-size: 14px;
        }

        .btn-sm {
            font-size: 12px;
        }

        .sentiment-container {
            flex-direction: column; /* I-stack ang mga card kapag maliit na ang screen */
            align-items: center; /* I-center ang mga card sa vertical axis */
        }

        .container-table-words {
            width: calc(100% - 1em); /* I-adjust ang width para maging buong screen width */
        }
    }

    #term {
        width: 100%;
        max-width: 100%;
    }
</style>
