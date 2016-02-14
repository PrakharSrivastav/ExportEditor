<?php
$message = '';
if(isset($_COOKIE['error_message']) && strlen($_COOKIE['error_message'])>0){
	$message = $_COOKIE['error_message'];
	setcookie("page_number",'2',time()-1200,$domain,FALSE);
	setcookie("error_message",'2',time()-1200,$domain,FALSE);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta content="text/html; charset=UTF-8" lang="ja" />
		<title>404 : Export Editor</title>
		<link rel='stylesheet' type="text/css" href="resources/css/bootstrap.min.css"/>
		<link rel='stylesheet' type="text/css" href="resources/css/font-awesome.min.css"/>
		<link rel='stylesheet' type="text/css" href="resources/css/style.css"/>		
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1 id='header' class="text-center text-black">Welcome to the Export Editor</h1>
			</div>
			<div class="text-center">
				<br /><br /><br /><br /><br />
				<p>
					<h3>
						<?php
							if($message !== '' && strlen($message)>0){
								echo $message;
							}
							else
								echo "The requested page does not exist";
						?>
					</h3>
					<br /><br /><br />
					<a href='home' class='btn btn-info btn-sm'>click to go back to Home page</a>
				</p>
			</div>
			<br />
		</div>
	</body>
</html>