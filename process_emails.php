<?php
error_reporting(E_ERROR | E_PARSE);
//echo "string";
    if(isset($_POST['message']) && strlen($_POST['message'])>0 ){
    	$success_email_count= 0;
		try{
			// include the libraries
			include_once(__DIR__.'/classes/swiftmailer/swift_required.php');
			include_once(__DIR__.'/utils/class.utility.php');
			
			// variables
			$email_config = Utility::get_email_configurations();
			$message 			= '';
			$to_address 		= '';
			$cc_address 		= '';
			$msg_body 			= '';
			$msg_subject		= '';
			$time_difference = 24;
			$file_item_details 	= Utility::get_reports_folder().date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference))).'item_details.xlsx';
			$file_invoice 		= Utility::get_reports_folder().date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference))).'invoice.xlsx';
			
			
			// hidden configurations
			$username 			= $email_config['smtp_username'];
			$password 			= $email_config['smtp_password'];
			$smtphost 			= $email_config['smtp_hostname'];
			$smtpport 			= $email_config['smtp_port'];
			$useremail 			= $email_config['sender_email'];
			$name 				= $email_config['sender_name'];
			$reply_to_email 	= $email_config['reply_to_email'];
			
			// set variables form the post parameters
			if(isset($_POST['toEmail']) && strlen($_POST['toEmail']) > 0) 			$to_address = trim($_POST['toEmail']);
			if(isset($_POST['ccEmail']) && strlen($_POST['ccEmail']) > 0) 			$cc_address = trim($_POST['ccEmail']);
			if(isset($_POST['message'])) 								  			$msg_body = $_POST['message'];
			if(isset($_POST['emailSubject']) && strlen($_POST['emailSubject']) > 0) $msg_subject = $_POST['emailSubject'];
			
			// validate to address
			if($to_address === '' || strlen($to_address)<6){
				$to_email 	= array($email_config['to_email']);
			}
			else {
				$temp_email_array = explode(',', $to_address);
				foreach ($temp_email_array as $email) {
					if($email === ''){
						echo Utility::get_blank_email_id_msg();
						exit();
					}
					else{
						if(filter_var($email,FILTER_VALIDATE_EMAIL) === FALSE){
							echo Utility::get_invalid_email_id_msg().'To: '.$email;
							exit();
						}
					}
				}
				$to_email = $temp_email_array;
			}
			
			// validate cc address
			if($cc_address === '' || strlen($cc_address)<6){
				$cc_email 	= array($email_config['cc_email']);
			}
			else {
				$temp_email_array = explode(',', $cc_address);
				foreach ($temp_email_array as $email) {
					if($email === ''){
						echo Utility::get_blank_email_id_msg();
						exit();
					}
					else{
						if(filter_var($email,FILTER_VALIDATE_EMAIL) === FALSE){
							echo Utility::get_invalid_email_id_msg().'CC: '.$email;
							exit();
						}
					}
				}
				$cc_email = $temp_email_array;
			}
			
			// validate the subject line
			if($msg_subject === '')	$subject = $email_config['subject'];
			else $subject = $msg_subject;
			
			// validate the message body
			if($msg_body !== "" && strlen($msg_body) > 10){
				$messageBody = $_POST['message'];
			}
			else{
				echo Utility::get_short_msg_length_message();
			}
			
			// set the message transport
				// smtphost
				// smtpport
				// username (smtp-username)
				// password (smtp-password)
			$transport = Swift_SmtpTransport::newInstance($smtphost, $smtpport,'ssl')->setUsername($username)->setPassword($password);
			
			// create a new instance of mailer
			$mailer = Swift_Mailer::newInstance($transport);
			
			// create the email message and set the parameters
				// usermail (email-id)
				// reply_to_email
				// $to_email array of email addresses
			foreach ($to_email as $trget_email) {
				$message = Swift_Message::newInstance($subject)
                                  ->setFrom     ($useremail		, 	$name)
    							  ->setReplyTo	($reply_to_email	)
                                  ->setTo       ($trget_email	,	'')
                                  ->setBody     ($messageBody		)
    							  ->setCc		($cc_email		,	'')
    							  ->setContentType("text/html");
				
				// add the attachments
				if(file_exists($file_invoice) && file_exists($file_item_details)){
					$attachment1 = Swift_Attachment::newInstance(file_get_contents($file_item_details), date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference))).'item_details.xlsx');
					$attachment2 = Swift_Attachment::newInstance(file_get_contents($file_invoice), date("m_d_Y_",strtotime(sprintf("-%d hours", $time_difference))).'invoice.xlsx');
					$message->attach($attachment1);
					$message->attach($attachment2);
					$numSent = $mailer->send($message);
					$success_email_count += $numSent;
				}
				else {
					echo Utility::get_attachment_missing_message();
					exit();
				}
			}
			printf("Successfully sent %d emails", $success_email_count);
			exit();
		}
		catch(Exception $e){
			$message = $e->getMessage();
			if($message === 'Expected response code 250 but got code "", with message ""'){
				printf("Successfully sent %d emails", $success_email_count);
				exit();
			}
			//Expected response code 250 but got code
			echo $message;
			exit();
		}
    }
	else{
		echo "Direct access to this page is not allowed";
		exit();
	}
?>