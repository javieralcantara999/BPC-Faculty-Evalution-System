<div class="container-fluid">
	<p>Evaluation is closed.</p>
</div>
<div class="modal-footer display p-0 m-0">
        <a href="./indexx.php" class="btn btn-primary bg-gradient-primary">Home</a>
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: flex
	}
</style>

<div class="container-fluid mt-1">
    <h2 class="text-center">Sentiment Terms Analysis</h2>
    <div class="row justify-content-center align-items-center">
        <div class="col-md-4">
            <table class="table table-bordered table-success">
                <thead>
                    <tr>
                        <th class = "text-center"width = "50%">Sentiment Type</th>
                        <th class = "text-center"width = "50%">No. of Words</th>
                    </tr>
                </thead>
                <tbody id="sentimentAnalysisBody" class = "text-center">
                    <tr>
                        <td>Positive</td>
                        <td><span class="badge bg-success" id="positiveCount">0</span></td>
                    </tr>
                    <tr>
                        <td>Negative</td>
                        <td><span class="badge bg-danger" id="negativeCount">0</span></td>
                    </tr>
                    <tr>
                        <td>Neutral</td>
                        <td><span class="badge bg-secondary" id="neutralCount">0</span></td>
                    </tr>
                </tbody>
                        <td colspan="2" class="text-center">Feedback Result: <span class="badge bg-//depende sa result" id="result">
                        </span>
                    </td>
            </table>
        </div>
        <div class="col-md-4">
            <table class="table table-bordered table-success">
                <thead>
                    <tr>
                        <th class = "text-center"colspan = "2"width = "50%">Detected Terms/Words</th>
                    </tr>
                </thead>
                <tbody id="sentimentWords" class = "text-center">
                    <tr>
                        <td><span class="badge bg-success" id="positiveWords">//list of words na naka badge success per word</span></td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-danger" id="negativeWords">//list of words na naka badge danger per word</span></td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-secondary" id="neutralWords">//list of words na naka badge neutral per word</span></td>
                    </tr>
                </tbody>
                        <td colspan="2" class="text-center"> Performance
                            <span class="badge bg-//depende sa result" id="result2">
                                //badge success -  check icon/mark - if result is Positive
                                //badge danger -  cross icon/mark - if result is Negative
                                //badge secondary -  negative icon/mark - if result is Neutral

                            </span>
                    </td>
            </table>
        </div>
    </div>
</div>