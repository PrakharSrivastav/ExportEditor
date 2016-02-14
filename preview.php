<?php
try {

	// include the required libraries
	include_once (__DIR__ . '/utils/class.upload.php');
	include_once (__DIR__ . '/utils/class.parser.php');
	include_once (__DIR__ . '/utils/class.utility.php');

	// variables
	$basepath = dirname(__FILE__);
	$upload_dir = $basepath . Utility::get_upload_folder();
	$message = '';
	$file_path = '';
	$csv_data = array();
	$temp_csv_data = array();
	$drop_down_array = array();
	
	// If the file details are correct, upload the file
	$file_upload_object = new FileUpload($basepath, $upload_dir, $_FILES['inputFile']);
	$uploaded_file_details = $file_upload_object -> getFileDetails();
	$file_path = $uploaded_file_details['path'];

	if (isset($uploaded_file_details['ext']) && $uploaded_file_details['ext'] === Utility::get_allowed_file_ext()) {
		if ($file_upload_object -> validate(array(Utility::get_allowed_file_ext()))) {
			if ($file_upload_object -> uploadFile()) {
				$message = Utility::get_upload_success_message();
			}
		}
	}

	if ($file_path !== '') {

		// variables
		$temp_count = 0;
		
		// creat a parser object to parse the csv file.
		$parser = new Parser($file_path);
		$csv_data = $parser -> parse_csv();

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

		// iterate for each row in the uploaded csv - file
		foreach ($csv_data as $data) {

			// if it is the first row, set the table header data
			if ($temp_count === 0) {
				$temp_csv_data[] = array_merge($data, array('category_code', 'material1', 'material2'));
				$temp_count++;
				continue;
			}

			// strip the last 9 degits from the item_code
			$len = strlen($data[1]) - 9;
			$item_code = mysqli_real_escape_string($con, substr($data[1], 0, $len));

			// get category_code and the materials from the database.
			$query = "select a.categories_code, a.material1, a.material2 from cat_new a, item_categories b, item c where c.item_code = '$item_code' and c.item_id = b.item_id and b.categories_id = a. categories_id;";

			// get the results from the database
			$result = mysqli_query($con, $query);
			$count = $result -> num_rows;

			// if no rows are found for an item_code then add "" in the array. These records will be adjusted from the web-interface

			if ($count === 0) {
				$temp_csv_data[] = array_merge($data, array('', '', ''));
			} else {
				while ($row = mysqli_fetch_row($result)) {
					// variables

					$categ = '';
					$mat_1 = '';
					$mat_2 = '';

					if (isset($row[0]))						$categ = $row[0];
					if (isset($row[1]))						$mat_1 = $row[1];
					if (isset($row[2]))						$mat_2 = $row[2];

					// if the category is shoe, then add Upper and Bottom to the materil 1 and 2.

					if ($mat_2 !== "" && strlen($mat_2) > 0) {
						if ($mat_1 !== '')	$mat_1 = 'Upper:' . $mat_1;
						$mat_2 = 'Bottom:' . $mat_2;
					}

					// add the updated values to the array
					$temp_csv_data[] = array_merge($data, array($categ, $mat_1, $mat_2));

					// only fetch the first row in-case there are multiple matches.
					break;
				}
			}
			// release the resultset.
			mysqli_free_result($result);
		}

		// query the database to get the data for the drop down menu.
		// create an array for dropdown menu	
		$result_drop_down = mysqli_query($con, Utility::get_dropdownmenu_query());
		while ($row = mysqli_fetch_row($result_drop_down)) {
			$drop_down_array[] = $row;
		}

		// free the resultset
		mysqli_free_result($result_drop_down);

		// close the database connection
		mysqli_close($con);

		// sort the dropdown array
		ksort($temp_csv_data);
		
		// unset the csv array as we do not need it any more.
		unset($csv_data);
	} else
		echo "File path is null";
} catch(Exception $e) {
	print_r($e);
}
?>

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
		<div class="container">
			<div class="page-header">
				<h1 id='header' class="text-center text-black">Welcome to the Export Editor</h1>
			</div>
			<div id='table_div' class='row'>
				<h2 class="text-center"><?php echo $message; ?></h2><hr />
				<form id='item_form'>
					<!-- The drop-down menu for Shipper -->
					<div class="row">
						<label for="shipper" class="pull-left col-sm-3"><h4>Select the shipper name for the items</h4></label>
						<div class="pull-left col-sm-3">
							<select class="form-control" name='shipper' onchange='check_shipper_status();' id='shipper' style="color:black">
								<option value="Yamato AP" selected="selected">Yamato AP</option>
								<option value="Yamato IP">Yamato IP</option>
							</select>
						</div>
						Shipper Number: <input type = "text" class="col-sm-1" style="margin:3px;color: black;" name='shipping_number' id='shipping_number' placeholder="Shipping #">
						<a href='home' class='pull-left col-sm-3 col-sm-offset-1 btn btn-warning'>click to go back to Home page</a>
					</div>
					<table id='data_table' class="table table-bordered table-condensed">
						<tr style="background-color: white;color: black"><th>
						<?php $count = 0;
						foreach ($temp_csv_data as $data) {
							if ($count === 0) {
								echo "Order_Number</th><th>Item_Code</th><th>Unit_price</th><th>Quantity</th><th>" . $data[4] . "</th><th>Category code</th><th>Adjust Category_code</th><th>" . $data[6] . "</th><th>" . $data[7] . "</th></tr>";
							} 
							else {
								$options = '';
								foreach ($drop_down_array as $drop_data) {
									$cat_code = trim($drop_data[0]);
									$list = $cat_code . "|" . trim($drop_data[1]) . "|" . trim($drop_data[2]);
									$options .= "<option value='$list'>$cat_code</option>";
								}
								echo "<tr>" . "<td><input class='input-sm' type='text' name='po_$count' name='po_$count' value='" 
								. $data[0] . "'/></td>" . "<td><input class='input-sm' type='text' name='label_$count' value='" 
								. $data[1] . "'/></td>" . "<td><input class='input-sm' type='text' name='price_$count' value='$" 
								. $data[2] . "'/></td>" . "<td><input class='input-sm' type='text' name='qty_$count' value='" 
								. $data[3] . "'/></td>" . "<td><input class='input-sm' type='text' name='subtotal_$count' value='$" 
								. $data[4] . "'/></td>" . "<td>" 
								. $data[5] . "</td>" . "<input type='hidden' name='cat_old_$count' value='$data[5]'/>" 
								. "<td><select class='form-control input-sm' name='cat_$count' id='cat_$count' onchange='populate_materials($count);'>" 
								. $options . "</select></td>" . "<td><input class='input-sm' type='text' name='material_1_$count' id='material_1_$count' value='" 
								. $data[6] . "'/></td>" . "<td><input class='input-sm' type='text' name='material_2_$count' id='material_2_$count'value='" 
								. $data[7] . "'/></td>" . "</tr>";
							}
							$count++;
						}
						?>
					</table>
				</form>
				<hr />
				<div class="row" id = 'preview_buttons'>
					<div class='row'>
						<input type='button' onclick='submit_item_data(<?php echo $count; ?>);' value = 'Generate item list' class="col-sm-offset-1 col-sm-2 pull-left btn btn-default"/>
						<input type='button' onclick="download_reports('item_detail');" value = "Download Item Details report" class="col-sm-offset-2 col-sm-2 pull-left btn btn-default"/>
						<input type='button' onclick='clear_data();' value = "Clear today's reports" class="col-sm-offset-2 col-sm-2 pull-left btn btn-default"/>
					</div>
					<br />

					<div class='row'>	
						<input type='button' onclick='generate_invoice_report(<?php echo $count; ?>);' value = 'Generate invoice' class="col-sm-offset-1 col-sm-2 pull-left btn btn-default"/>				
						<input type='button' onclick="download_reports('invoice');" value = 'Download Invoice report' class="col-sm-offset-2 col-sm-2 pull-left btn btn-default"/>
						<!--<input type='button' onclick='get_email_page();'value="Go to email configurations" class='col-sm-offset-2 col-sm-2 pull-left btn btn-danger' />-->
						<form action='get_email_page' id='preview_email_calling_form' method='post'>
							<input type="hidden" name='email' value="page"/>
							<button type="button" onclick='validate_table_data(<?php echo $count; ?>);' class='col-sm-offset-2 col-sm-2 pull-left btn btn-danger' >Go to email configurations</button>
						</form>
					</div>

					<br />
					<div class='row'>
						<div id='result'class='col-sm-10 col-sm-offset-1 input-lg alert alert-success' role="alert">
							<p id='para' style="margin-top:-.4em;font-size:1.5em;text-align:center">Click the buttons to perform the intended operations.</p>
						</div>
					</div>
					<br /><br />
				</div>
			</div>
		</div>
		<script src="resources/js/jquery-2.1.1.min.js"></script>
        <script src="resources/js/bootstrap.min.js"></script>
    	<script src="resources/js/script.js"></script>
	</body>
</html>