<?php
if(!isset($_POST['email']) || !$_POST['email'] === 'page'){
	exit();
	//redirect to error page;
}
else{
	include_once(__DIR__.'/utils/class.utility.php');
	$email_config = Utility::get_email_configurations();
	
	
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" lang="ja" />
		<title>Email Configuration: Export Editor</title>
		<link rel='stylesheet' type="text/css" href="resources/css/bootstrap.min.css"/>
		<link rel='stylesheet' type="text/css" href="resources/css/font-awesome.min.css"/>
		<link rel='stylesheet' type="text/css" href="resources/css/style.css"/>	
	</head>
	<body>
		<div class='container'>
			<div class="page-header">
				<h1 id='header' class="text-center text-black">Welcome to the Export Editor</h1>
			</div>
			
			<form role='form' id='email_form' method="post" enctype="multipart/form-data" class='form-horizontal' role='form'>
				<div class="form-group">
			    	<label for="toEmail" class="col-sm-2 control-label">To-Email</label>
			    	<div class="col-sm-10">
			      		<input type="email" class="form-control" name="toEmail" id="toEmail" value='<?php echo $email_config['to_email'];?>' >
			    	</div>
			  	</div>
			  	<div class="form-group">
			    	<label for="ccEmail" class="col-sm-2 control-label">CC-Email</label>
			    	<div class="col-sm-10">
			      		<input type="email" class="form-control" name='ccEmail' id="ccEmail" value='<?php echo $email_config['cc_email'];?>'>
			    	</div>
			  	</div>
			  	<p class="col-sm-10 col-sm-offset-2 help-block" style="color:white">To use multiple emails, please seperate them by comma(,).</p>
			  	
			  	<div class="form-group">
			    	<label for="emailSubject" class="col-sm-2 control-label">Email Subject</label>
			    	<div class="col-sm-10">
			      		<input type="text" class="form-control pull-left" name='emailSubject' id="emailSubject" value='<?php echo $email_config['subject'];?>'>
			    	</div>
			  	</div>
			  	
			  	<div class="form-group">
			    	<label for="message" class="col-sm-2 control-label">Email Message</label>
			    	<div class="col-sm-10">
			      		<textarea class="form-control" name='message' id="message">
			      			<?php echo $email_config['message_body'];?>
			      		</textarea>
			      		<p class="help-block" style="color:white">Edit above to change the email body</p>
			    	</div>
			  	</div>
			  	
			  	
			  	<div class="form-group">
			  		<label for="butt" class="col-sm-2 control-label">Click to send emails</label>
			    	<div class="col-sm-10">
			      		<button type="button" onclick='get_email_response();' id="butt" class="pull-left btn btn-danger">Send Email</button>
			      		<a href='home' class='btn col-sm-4 pull-right col-sm-offset-2 btn-warning'>click to go back to Home page</a>
			
			    	</div>
			  	</div>
			</form>
		</div>
		<div class='row'>
			<div id='email_result'class='col-sm-10 pull-left well well-lg' style='visibility:hidden; width:80%;margin-left:18%'>
				<span style="margin-top:-.4em;font-size:1.5em;float:left;color:black;">Email status</span>
			</div>
		</div>
		<script src="resources/js/jquery-2.1.1.min.js"></script>
        <script src="resources/js/bootstrap.min.js"></script>
        <script src="resources/js/tinymce/tinymce.min.js"></script>
    	<script src="resources/js/script.js"></script>
    	<script>
    		tinymce.init({
			    selector: 'textarea',
				plugins : 'autosave preview paste lists contextmenu',
				paste_data_images: true
			});
    	</script>
	</body>
</html>