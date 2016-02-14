<?php

$report_path = $_GET['report'];
$report_name = str_replace("archive/", "", $report_path);
$csv_data = array();
if(file_exists($report_path)){
	$handler = fopen($report_path, 'r');
	if($handler !== false){
		while(!feof($handler)){
			$data = fgetcsv($handler);
			$csv_data[]=$data;
		}
	}
	array_shift($csv_data);
	//print_r($csv_data);
	//print_r (file_exists("reports/".str_replace('csv', 'xlsx', $report_name)));
}
else{
	header("Location: 404");
}


?>
<!DOCTYPE html>
<html>
	<head>		
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" lang="ja" />
		<title>Previous Reports: Export Editor</title>
		<link rel='stylesheet' type="text/css" href="resources/css/bootstrap.min.css"/>
		<link rel='stylesheet' type="text/css" href="resources/css/font-awesome.min.css"/>
		<link rel='stylesheet' type="text/css" href="resources/css/style.css"/>	
		<style>
			table tr th{
				background-color:white;color:black;
			}
			h3{
				margin-bottom: 20px;
			}
			#home{
				margin-top : 20px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1 id='header' class="text-center text-black">Welcome to the Export Editor</h1>
			</div>
			<div class="row">
				<h3 class="col-sm-6">Report name : <?php echo $report_name ?></h3>
				<a href='home' id='home' class="col-sm-5 pull-right btn btn-warning">Go to Home page</a>
			</div>
			<table class='table table-condensed table-bordered table-responsive'>
				<?php
					$count = 0;
					foreach ($csv_data as $data) {
						$data_0 = (isset($data[0])) ? $data[0] : '' ;
						$data_1 = (isset($data[1])) ? $data[1] : '' ;
						$data_2 = (isset($data[2])) ? str_replace("$$", '$', $data[2]) : '' ;
						$data_3 = (isset($data[3])) ? $data[3] : '' ;
						$data_4 = (isset($data[4])) ? str_replace("$$", '$', $data[4]) : '' ;
						$data_5 = (isset($data[5])) ? $data[5] : '' ;
						$data_6 = (isset($data[6])) ? $data[6] : '' ;
						$data_7 = (isset($data[7])) ? $data[7] : '' ;
						if($count === 0){
							$count++;
							echo "<tr><th>$data_0</th><th>$data_1</th><th>$data_2</th><th>$data_3</th><th>$data_4</th><th>$data_5</th><th>$data_6</th><th>$data_7</th></tr>";
						}
						else
							echo "<tr><td>$data_0</td><td>$data_1</td><td>$data_2</td><td>$data_3</td><td>$data_4</td><td>$data_5</td><td>$data_6</td><td>$data_7</td></tr>";
					}
				?>
			</table>
			<button id='download_report' onclick="download_reports('<?php echo "reports/".str_replace('csv', 'xlsx', $report_name); ?>');" class="col-sm-5 pull-left btn btn-danger">Download this report</button>
			<br /><hr />
		</div>
		<script src="resources/js/jquery-2.1.1.min.js"></script>
        <script src="resources/js/bootstrap.min.js"></script>
    	<script src="resources/js/script.js"></script>
	</body>
</html>
