<?php
	include_once(__DIR__.'/utils/class.utility.php');
	$files = glob(Utility::get_archive_folder()."*.csv");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta content="text/html; charset=UTF-8" lang="ja" />
		<title>Previous Reports : Export Editor</title>
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
				<h2>List of previous reports:</h2>
				<ol>
					<?php
						foreach ($files as $file) {
							$file_name = str_replace(Utility::get_archive_folder(), '', $file);
							echo "<li><a href='show_previous_reports?report=$file' style='color:white'>$file_name</a></li>";
						}
					?>
				</ol>
			</p>
			<br /><br /><br /><br />
			   <a href='home' class="col-sm-2 btn btn-warning">Go to Home page</a>
		</div>
		<script src="resources/js/jquery-2.1.1.min.js"></script>
        <script src="resources/js/bootstrap.min.js"></script>
    	<script src="resources/js/script.js"></script>
	</body>
</html>