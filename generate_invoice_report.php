<?php
if(isset($_POST['po_1'])){
	try{
		
		// include libraries
		include_once(__DIR__.'/utils/class.utility.php');
		include_once dirname(__FILE__) . '/classes/PHPExcel.php';
		
		// set the database configuration parameters.
		$database_config = Utility::get_database_details();
		$username = $database_config['username'];
		$password = $database_config['password'];
		$hostname = $database_config['hostname'];
		$database = $database_config['database'];
		
		// create a connection object
		$con = mysqli_connect($hostname, $username, $password);
		
		// select the database that this connection object interacts with
		mysqli_select_db($con, $database);
		$curr_year = (int) date('Y');
		$curr_month = (int) date('m');
		$current_day = (int)date('d');
		$query = "select max(count) from invoice_report_table where month = $curr_month and year = $curr_year";
		$result = mysqli_query($con, $query);
		$count = $result -> num_rows;
		$next_count = 0;
		//$this_count = 0;
		
		//print_r($result);
		while ($row = mysqli_fetch_row($result)) {
			if($row[0] === '' ||$row[0] === 0){
				$next_count = 1 ;
				//$this_count = 0 ;
			}
			else{
				$next_count = (int)$row[0]+1;
				//$this_count = (int)$row[0];
			}
		}
		
		$len = strlen((string)$next_count);
		$invoice_num = '';
		if($len === 1){
			$invoice_num = '0'."".$next_count;
		}
		else {
			$invoice_num = "".$next_count;	
		}
		
		
		$sql_insert = "INSERT INTO `invoice_report_table` (`year`, `month`,`day`, `count`) VALUES ($curr_year, $curr_month,$current_day, $next_count);";
		mysqli_query($con,$sql_insert);
		
		// release the resultset.
		mysqli_free_result($result);
		mysqli_close($con);
		
		
		// variables
		$count_post = count($_POST)-1;
		$iterations = (int)($count_post/10)+1;
		$write_data = array();
		$total = 0.00;
		$shipper = '';
		$column=0;
		$row = 0;
		if(isset($_POST['shipper'])) $shipper = $_POST['shipper'];
		if(isset($_POST['shipping_number'])) $invoice_num = $_POST['shipping_number'];
		// count the total
		for($index = 0 ; $index < $iterations ; $index++){
			if(isset($_POST['subtotal_'.($index+1)]))	{
				$tot = number_format((float)substr($_POST['subtotal_'.($index+1)],1), 2, '.', '');
				$total += $tot;
			}
		}
		$total = number_format((float)$total, 2, '.', '');
		$time_difference = 14;
		// prepare the report data
		$write_data[]=array('','','','','','','Date','Invoice #');
		$write_data[]=array('','','','','','',date('m/d/y',strtotime(sprintf("-%d hours", $time_difference))),date('Ymd',strtotime(sprintf("-%d hours", $time_difference))).'-ship'.$invoice_num);
		$write_data[]=array('Spicy Herb, Inc./Shop LA Walker','','','','','','','Reference #');
		$write_data[]=array('400 Corporate Pointe, #300','','','','','','',date('Ymd',strtotime(sprintf("-%d hours", $time_difference))).'-ship'.$invoice_num);
		$write_data[]=array('Culver City, CA 90230 USA','','','','','','','');
		$write_data[]=array('Phone:310-590-4579(USA)','','','','','','','');
		$write_data[]=array('FAX:310-590-4577(USA)','','','','','','','');
		$write_data[]=array('Phone:050-5539-9951(Japan)','','','','','','','');
		$write_data[]=array('Fax:020-4667-9139(Japan)','','','','','','','');
		$write_data[]=array('','','','','','','','');
		$write_data[]=array('Bill To','','','','','Ship To','','');
		$write_data[]=array('Spicy Herb, Inc.','','','','','Spicy Herb, Inc. C/O OTS','','');
		$write_data[]=array('Tokyo to Taitouku Torigoe 1-2-3','','','','','Tokyo to Edogawa-ku','','');
		$write_data[]=array('111-0054 Japan','','','','','Minamikasai 5-16-1','','');
		$write_data[]=array('03-3851-3333','','','','','134-0085 Japan','','');
		$write_data[]=array('','','','','','03-5605-5511','','');
		$write_data[]=array('','','','','','','','');
		$write_data[]=array('','','','','','','','');
		$write_data[]=array('','','','','','','SHIP DATE','Ship via');
		$write_data[]=array('','','','','','',date('m/d/y',strtotime(sprintf("-%d hours", $time_difference))),$shipper);
		$write_data[]=array('Item #','Description','','','','Qty','Rate','Total');
		$write_data[]=array('Babydollship','Apparel Sales','','','','1','$'.$total,'$'.$total);
		$write_data[]=array('','','','','','','','');
		$write_data[]=array('','','','','','','','');
		$write_data[]=array('','','','','','','','');
		$write_data[]=array('','','','','','','','');
		$write_data[]=array('','','','','','','','');
		$write_data[]=array('','','','','','','Total','$'.$total);
		
		
		// archive the file for reading the data later.
		$csv_filename = Utility::get_archive_folder().date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference))).'invoice.csv';
		$handle = fopen($csv_filename, 'w') or die("could not write file");
		fputcsv($handle, array('Order#','Item Code','Brand Code','Unit Price','Qty','Subtotal'));
		foreach ($write_data as $data) {
			fputcsv($handle, $data);
		}
		fclose($handle);
		
		
		// prepare outline style
		$outline = array(
						'font' => array('bold' => true,),
						'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,),
						'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),),);
		// prepare border style
		$border = array(
						'font' => array('bold' => false,),
						'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,),
						'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),),);
		
		// create PHPExcel object
		$xlsx = new PHPExcel();
		
		// set default styles
		$xlsx->getDefaultStyle()->getFont()->setName('Arial');
		$xlsx->getDefaultStyle()->getFont()->setSize(10);
		$xlsx->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$xlsx->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$xlsx->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$xlsx->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$xlsx->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$xlsx->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$xlsx->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$xlsx->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		
		// write the data to cells
		foreach ($write_data as $data) {
			$column=0;$row++;
			foreach ($data as $value) {
				$xlsx->getActiveSheet()->setCellValueByColumnAndRow($column,$row,$value);
				$column++;
			}
		}
		
		// provide selective styles to the cellls
		$xlsx->getActiveSheet()->getStyle('G1:H2')->applyFromArray($outline);
		$xlsx->getActiveSheet()->getStyle('H3:H4')->applyFromArray($outline);
		$xlsx->getActiveSheet()->getStyle('A11:D11')->applyFromArray($outline);
		$xlsx->getActiveSheet()->getStyle('F11:H11')->applyFromArray($outline);
		$xlsx->getActiveSheet()->getStyle('A12:D16')->applyFromArray($outline);
		$xlsx->getActiveSheet()->getStyle('F12:H16')->applyFromArray($outline);
		$xlsx->getActiveSheet()->getStyle('G19:H20')->applyFromArray($border);
		$xlsx->getActiveSheet()->getStyle('A21:H28')->applyFromArray($border);
		
		// create write object
		$excelWriter = new PHPExcel_Writer_Excel2007($xlsx);
		
		// save the report to the server
		$excelWriter->save(Utility::get_reports_folder().date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference))).'invoice.xlsx');
		
		// echo the success message for the ajax request
		echo Utility::get_invoice_report_success_msg();
	}
	catch(Exception $e){
		print_r($e);
	}
}
else exit();