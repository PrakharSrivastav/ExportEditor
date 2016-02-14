<?php
// include the libraries
include_once(__DIR__.'/utils/class.utility.php');

if(isset($_POST['data']) && $_POST['data'] === Utility::get_delete_report_post_msg()){
	try{
		// get the files matching the filemask
		$time_difference =24;
		$files_reports = glob(Utility::get_reports_folder().date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference)))."*.xlsx");
		$files_archives = glob(Utility::get_archive_folder().date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference)))."*.csv");
		
		// if files are found delete them one by one
		if(count($files_reports)>0 || count($files_archives)>0){
			if(count($files_reports)>0)
				foreach ($files_reports as $file) 
					unlink($file);
				
			if(count($files_archives)>0)
				foreach ($files_archives as $file) 
					unlink($file);
		}
		else {	
			// messsge when no reports for deleting
			echo Utility::get_delete_report_no_msg();
			exit();
		}
		
		// message when the reports are deleted successfully
		echo Utility::get_delete_report_success_msg();
	}
	catch(Exception $e){
		print_r($e);
	}
}
else {
	exit();
}