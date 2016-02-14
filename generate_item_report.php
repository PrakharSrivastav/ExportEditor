<?php
// proceed only when the post data is set. No one should be able to access the script directly.
if(isset($_POST['po_1'])){
	
	try{
		
		// include PHPExcel library
		include_once dirname(__FILE__) . '/classes/PHPExcel.php';
		include_once(__DIR__.'/utils/class.utility.php');
		
		// variables
		$count_post = count($_POST)-1;
		$iterations = (int)($count_post/10)+1;
		$count = 0;
		$write_data = array();
		$total = 0.00;
		$column=0;
		$row = 0;
		
		// header data
		$write_data[]=array('Order#','Item Code','Unit Price','Qty','Subtotal','Category','Material 1','Material 2');
		
		// fetch the data from the form-post data and  arrange them into an array
		for($index = 0 ; $index < $iterations ; $index++){
			
			// get the data only when the Post data values are set.
			if(isset($_POST['po_'.($index+1)]))			$order_num 	= " ".$_POST['po_'.($index+1)];
			if(isset($_POST['label_'.($index+1)]))		$label		= " ".$_POST['label_'.($index+1)];
			if(isset($_POST['price_'.($index+1)]))		$price 		= '$'.$_POST['price_'.($index+1)];
			if(isset($_POST['qty_'.($index+1)]))		$qty 		= " ".$_POST['qty_'.($index+1)];
			if($_POST['cat_old_'.($index+1)]) 			$cat_old 	= " ".$_POST['cat_old_'.($index+1)];
			if(isset($_POST['material_1_'.($index+1)]))	$mat_1 		= 	" ".$_POST['material_1_'.($index+1)];
			if(isset($_POST['material_2_'.($index+1)]))	$mat_2 		= 	" ".$_POST['material_2_'.($index+1)];
			if(isset($_POST['subtotal_'.($index+1)])){
				$sub_tot=   $_POST['subtotal_'.($index+1)];
				$tot = number_format((float)substr($_POST['subtotal_'.($index+1)],1), 2, '.', '');
				$total += $tot;
			}
			if(isset($_POST['cat_'.($index+1)])){
				$temp_cat = explode('|', $_POST['cat_'.($index+1)]);
				$cat_check = $temp_cat[0];
				if($cat_check !== 'accessory'){
					$cat = " ".$cat_check;
				}
				else
					$cat = " ".$cat_old;
			}		
			
			// store the data into the array
			$write_data[] = array($order_num,$label,str_replace('$$','$',$price),$qty,$sub_tot,$cat,$mat_1,$mat_2);
		}
		$total = number_format((float)$total, 2, '.', '');
		$write_data[]=array('','','','','','','','','','');
		$write_data[]=array('','','','','Total','$'.$total);
		$time_difference = 24;
		// archive the file for reading the data later.
		$csv_filename = Utility::get_archive_folder().date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference))).'item_details.csv';
		$handle = fopen($csv_filename, 'w') or die("could not write file");
		fputcsv($handle, array('Order#','Item Code','Brand Code','Unit Price','Qty','Subtotal'));
		foreach ($write_data as $data) {
			fputcsv($handle, $data);
		}
		fclose($handle);
		
		// create the header style
		$headerStyle = array(
						'font' => array('bold' => true,),
						'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,),
						'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),),
						'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('argb' => 'FFB2B3B8'),),);
		
		// create rest of the data style.
		$dataStyle = array(
						'font' => array('bold' => false,),
						'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,),
						'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),),);
		
		// create PHPExcel object
		$xlsx = new PHPExcel();
		
		// set the default style for sheets
		$xlsx->getDefaultStyle()->getFont()->setName('Arial');
		$xlsx->getDefaultStyle()->getFont()->setSize(10);
		$xlsx->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$xlsx->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$xlsx->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$xlsx->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$xlsx->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$xlsx->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$xlsx->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$xlsx->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		#$xlsx->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		#$xlsx->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		#$xlsx->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		
		// write the data formatted above.
		foreach ($write_data as $data) {
			$column=0;$row++;
			foreach ($data as $value) {
				$xlsx->getActiveSheet()->setCellValueByColumnAndRow($column,$row,$value);
				$column++;
			}
		}
		
		// apply the styles
		$xlsx->getActiveSheet()->getStyle('A1:H1')->applyFromArray($headerStyle);
		$xlsx->getActiveSheet()->getStyle('A2:H'.(1+$iterations))->applyFromArray($dataStyle);//
		$xlsx->getActiveSheet()->getStyle('E'.(3+$iterations).':F'.(3+$iterations))->applyFromArray($headerStyle);//
		
		// creat a writer object
		$excelWriter = new PHPExcel_Writer_Excel2007($xlsx);
		
		// write the file to the server
		$excelWriter->save (Utility::get_reports_folder().date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference))).'item_details.xlsx');
		
		// echo below message on successful execution
		echo Utility::get_item_detail_ajax_msg();			
	}
	catch(Exception $e){
		print_r($e);
	}
}