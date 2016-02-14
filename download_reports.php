<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" lang="ja" />
		<title>Preview: Export Editor</title>
		<link rel='stylesheet' type="text/css" href="resources/css/bootstrap.min.css"/>
		<link rel='stylesheet' type="text/css" href="resources/css/font-awesome.min.css"/>
		<link rel='stylesheet' type="text/css" href="resources/css/style.css"/>	
	</head>
	<body>
		<div class='container-fluid'>
<?php
if((isset($_GET['report_type']) && $_GET['report_type'] !="" && !is_null($_GET['report_type'])))
{
	try{
		
		// include libraries
		include_once(__DIR__.'/utils/class.utility.php');
		
		// variables
		$temp_name = $_GET['report_type'];
		$file_name = '';
		$time_difference = 24;
		// select the report name based on the post parameter
		if($temp_name === Utility::get_item_download_parm())
			$file_name = Utility::get_reports_folder().date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference))).'item_details.xlsx';
		else if ($temp_name === Utility::get_invoice_download_parm())
			$file_name = Utility::get_reports_folder().date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference))).'invoice.xlsx';
		else
			$file_name = $_GET['report_type'];
		
		if (file_exists($file_name)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($file_name));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file_name));
			ob_clean();
			flush();
			readfile($file_name);
			flush();
			echo ucfirst($temp_name).Utility::get_download_success_msg();
			exit();
		}
		else if (!file_exists($file_name)){
			echo Utility::get_homepage_link();
			exit();
		}
	}	
	catch(Exeption $e){
		print_r ($e);
	}
}
else
{
	echo Utility::get_download_falied_msg();
	exit();
}
?>
		</div>
		<script src="resources/js/jquery-2.1.1.min.js"></script>
        <script src="resources/js/bootstrap.min.js"></script>
    	<script src="resources/js/script.js"></script>
	</body>
</html>