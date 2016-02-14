<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta content="text/html; charset=UTF-8" lang="ja" />
		<title>Home : Export Editor</title>
		<link rel='stylesheet' type="text/css" href="resources/css/bootstrap.min.css"/>
		<link rel='stylesheet' type="text/css" href="resources/css/font-awesome.min.css"/>
		<link rel='stylesheet' type="text/css" href="resources/css/style.css"/>		
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1 id='header' class="text-center text-black">Welcome to the Export Editor</h1>
			</div>
			<p>
				<h2>Instructions:</h2>
				<ol>
					<li>Chose the relevant csv file to upload. The uploaded file should have headers (column names) in the first line.</li>
					<li>Click on the the "Upload File" button to upload the file and navigate to the "Preview" page.</li>
					<li>"Preview" page will provide you with the relevant fields from the CSV file.</li>
					<li>Select the Shipper from the dropdown (Yomato AP / Yomato IP).</li>
					<li>Validate the preview data. To adjust material value, chose the correct category from drop-down menu under "Adjust Categore Code" column.</li>
					<li>Click on the "Generate item list" button to generate item details report.</li>
					<li>Click on the "Generate invoice" button to generate current invoice.</li>
					<li>Click on the "Clear today's reports" button to remove the generated files.</li>
					<li>Click on the "Download Invoice report" button to download the generated invoice.</li>
					<li>Click on the "Download Item Details report" button to download the generated item-details.</li>
					<li>Review the downloaded files, if required.</li>
					<li>Once satisfied with the Invoice and the Item-details report, click on the "Go to email configuration" to navigate to "Email Configuration" page.</li>
					<li>On the Email Configuration page enter comma-seperated list for reciepient email-ids.</li>
					<li>Add one or more email-ids in cc field, if required.</li>
					<li>Click on "Send Email" to send the email to the recipients.</li>
				</ol>
			</p>
			<br />
			<form role="form" enctype="multipart/form-data" action="preview" method="post" id="fileInputForm">
			  	<div class="form-group">
			    	<label for="inputFile"><span class="lead">Select file to upload: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
			    	<input type="file" class='input-lg' id="inputFile" name="inputFile"/>
			  	</div>
			   <button type="button" class="col-sm-2 btn btn-default" onclick="validateFile();">Upload File</button>
			   <a href='previous_reports' class="col-sm-2 col-sm-offset-1 btn btn-warning">Previous reports</a>
			</form>
		</div>
		<script src="resources/js/jquery-2.1.1.min.js"></script>
        <script src="resources/js/bootstrap.min.js"></script>
    	<script src="resources/js/script.js"></script>
	</body>
</html>