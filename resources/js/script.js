function validateFile() {
	var file_data = document.getElementById("inputFile").value;
	var file_length = file_data.length;
	var file_array = file_data.split('.');
	var file_ext = file_array[file_array.length - 1];
	if (file_data === '')
		alert('Please chose a file to upload');
	else if (file_data !== '' && file_length > 0 && file_ext !== 'csv')
		alert("Please upload a csv file only");
	else
		document.getElementById("fileInputForm").submit();
}

function populate_materials(count) {

	//alert(count);
	var item = document.getElementById('cat_' + count).value;
	var vals_array = item.split('|');
	var cat = vals_array[0];
	var mat_1 = vals_array[1];
	var mat_2 = vals_array[2];
	var material_1 = document.getElementById('material_1_' + count);
	var material_2 = document.getElementById('material_2_' + count);
	if (mat_2 === '') {
		material_1.value = mat_1;
		material_2.value = mat_2;
	} else {
		material_1.value = 'Upper:' + mat_1;
		material_2.value = 'Lower:' + mat_2;
	}
	//alert(material_1.value);
}

var ajaxRequest;

function ajaxFunction() {
	try {
		ajaxRequest = new XMLHttpRequest();
	} catch (e) {
		try {
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				alert("Problem with the browser");
			}
		}
	}
}

function generate_invoice_report(count) {
	message = '';
	for(var i = 1 ; i<(count-1) ; i++){
		var mat_1 = document.getElementById('material_1_'+i);
		var mat_2 = document.getElementById('material_2_'+i);
		temp_message = '';
		if(mat_1.value === '' && mat_2.value === ''){
			temp_message = 'Row '+i+' is empty';		
		}
		if(temp_message !== '')
			message += temp_message+'\n';
		//document.write(temp_message);
	}	
	var shipping_number = document.getElementById('shipping_number');
	if(shipping_number.value === ''){
			message += "The shipping number is blank, please provide a value"+'\n';		
	}
	if(message.length >0 )
		alert(message);
	else{
		var formData = new FormData(document.getElementById('item_form'));
		ajaxFunction();
		ajaxRequest.open('post', 'generate_invoice_report');
		ajaxRequest.send(formData);
		ajaxRequest.onreadystatechange = function () {
			if (this.readyState === 4 && this.status === 200) {
				el = document.getElementById('result');
				//el.style.visibility = 'visible';
				el.innerHTML = "<p style='margin-top:-.4em;font-size:1.5em;text-align:center;'>" + this.responseText + "</p>";
			}
		};
	}
}

function get_email_page() {
	var myform = document.createElement('form');
	var input1 = document.createElement('input');
	input1.setAttribute('name', 'email');
	input1.setAttribute('value', 'page');
	input1.setAttribute('type', 'hidden');
	myform.appendChild(input1);
	myform.setAttribute('action', 'get_email_page');
	myform.setAttribute('method', 'post');
	myform.submit();
}

function clear_data() {
	
	if (confirm("Do you really want to delete today's reports?") === true) {
        ajaxFunction();
		ajaxRequest.open('post', 'clear_reports_today');
		ajaxRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		ajaxRequest.send('data=remove');
		ajaxRequest.onreadystatechange = function () {
			if (this.readyState === 4 && this.status === 200) {
				el = document.getElementById('result');
				el.innerHTML = "<p style='margin-top:-.4em;font-size:1.5em;text-align:center'>" + this.responseText + "</p>";
			}
		};
    } else {
        return false;
    }
    
	
}

function download_reports(report_type) {
	var report_name = '';
	if (report_type === 'invoice') {
		report_name = 'invoice';
	} else if (report_type === 'item_detail') {
		report_name = 'item';
	}
	else 
		report_name = report_type;
	ajaxFunction();
	ajaxRequest.open('get', 'download_report');
	//ajaxRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	ajaxRequest.send('report_type=' + report_name);
	ajaxRequest.onreadystatechange = function () {
		if (this.readyState === 4 && this.status === 200) {
			window.location = "download_report?report_type=" + report_name;
		}
	};
}

function get_email_response() {

	//alert(1);
	var formData = new FormData(document.getElementById('email_form'));
	ajaxFunction(); //alert(2);
	ajaxRequest.open('post', 'process_email', false); //alert(3);
	ajaxRequest.send(formData);
	el = document.getElementById('email_result');
	el.style.visibility = 'visible';
	el.style.color = 'black';
	el.innerHTML = "<p style='margin-top:0.3em;font-size:1.5em;text-align:left;'>" + ajaxRequest.responseText + '</p>';
}

function submit_item_data(count) {
	message = '';
	for(var i = 1 ; i<(count-1) ; i++){
		var mat_1 = document.getElementById('material_1_'+i);
		var mat_2 = document.getElementById('material_2_'+i);
		temp_message = '';
		if(mat_1.value === '' && mat_2.value === ''){
			temp_message = 'Row '+i+' is empty';		
		}
		if(temp_message !== '')
			message += temp_message+'\n';
		//document.write(temp_message);
	}
	
	if(message.length >0 )
		alert(message);
	else{
		var formData = new FormData(document.getElementById('item_form'));
		ajaxFunction();
		ajaxRequest.open('post', 'generate_item_report');
		ajaxRequest.send(formData);
		ajaxRequest.onreadystatechange = function () {
			if (this.readyState === 4 && this.status === 200) {
				el = document.getElementById('result');
				//el.style.visibility = 'visible';
				el.innerHTML = "<p style='margin-top:-.4em;font-size:1.5em;text-align:center;'>" + this.responseText + '</p>';
			}
		};
	}
}

function validate_table_data(count){	
	//alert(count);
	message = '';
	for(var i = 1 ; i<(count-1) ; i++){
		var mat_1 = document.getElementById('material_1_'+i);
		var mat_2 = document.getElementById('material_2_'+i);
		temp_message = '';
		if(mat_1.value === '' && mat_2.value === ''){
			temp_message = 'Row '+i+' is empty';		
		}
		if(temp_message !== '')
			message += temp_message+'\n';
	}	
	
	if(message.length >0 )
		alert(message);
	else{
		var form = document.getElementById('preview_email_calling_form');
		form.submit();
	}
}

function check_shipper_status(){
	//alert(1);
	var shipper = document.getElementById('shipper').value;
	if(shipper === 'Yamato IP'){
		if(confirm('Do you want to check Yamato IP as shipper?') === true){
			return true;
		}
		else return false;
	}
	return true;
}
