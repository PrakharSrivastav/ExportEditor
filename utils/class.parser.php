<?php

class Parser {
	
	private $file_name;
	private $return_array;
	
	function __construct($file_name) {
		$this->file_name = $file_name;
		#echo $this->file_name;
		$this->return_array = array();
	}
	
	function parse_csv(){
		try{
			$count = 0;
			$file_handler = fopen($this->file_name, 'r');
			if ($file_handler === FALSE) {
				throw new Exception("Problems opening the uploaded file", 1);
			}
			else{
				while (!feof($file_handler)) {
					$data = fgetcsv($file_handler);
					$po = $data[3];
					$brand_code = $data[4];
					$label_3 = $data[11];
					$unit_price = $data[17];
					$qty = $data[19];
					if($count === 0){
						//$this->return_array[] = array($po ,$label_3, $brand_code,  $unit_price, $qty,'Sub Total');
						$this->return_array[] = array($po ,$label_3, $unit_price, $qty,'Sub Total');
						$count ++;
					}
					else{
						if ((int)$brand_code <70 &&(int)$qty >0) {
							$unit_price = round(floatval($unit_price)/200,2);
							$sub_total = floatval(((int)$qty) * ($unit_price));
							//$this->return_array[] = array($po ,$label_3, $brand_code, $unit_price, $qty,$sub_total);	
							$this->return_array[] = array($po ,$label_3, $unit_price, $qty,$sub_total);	
						} 
						else {
							continue;
						}
					}
				}
				fclose($file_handler);
			}
			
			return $this->return_array;
		}
		catch(Exception $e){
			throw new Exception("Problems while parsing csv files:".$e->getMessage());
		}
	}
}
