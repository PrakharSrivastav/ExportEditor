<?php

class Utility{
		
	public static function get_invalid_email_id_msg(){
		return "Invalid Email id : ";
	}
	public static function get_blank_email_id_msg(){
		return "Email id is blank";
	}	
	public static function get_short_msg_length_message(){
		return "Message body is too short. Please check again and resend";
	}
	
	public static function get_attachment_missing_message(){
		return "Reports are not available. Please generate the reports before sending the email";
	}
	
	public static function get_archive_folder(){
		return "archive/";
	}
	public static function get_email_configurations(){
		return array(
			'smtp_username'	=>	'srivprakhar@gmail.com',
			'smtp_password'	=>	'Hafslund12',
			'smtp_hostname'	=>	'smtp.gmail.com',
			'smtp_port'		=>	465,
			'sender_email'	=>	'srivprakhar@gmail.com',
			'sender_name'	=>	'prakhar',
			'reply_to_email'=>	'rupuzzled@rediffmail.com',
			'to_email'		=>	'srivprakhar@live.com',
			//'to_name'		=>	'prakhar1',
			'cc_email'		=>	'rupuzzled@rediffmail.com',
			//'cc_name'		=>	'posmaster',
			'subject'		=>	'竹田様【本日出荷分の税関提出用書類】',
			'message_body'	=>'お世話になっております。<br />
								スパイシーハーブの山内です。	<br />
								本日の出荷分の税関提出用書類を添付させて頂きますので<br />								
								こちらの書類を転送して頂くようにお願い致します。<br />
								※万が一何か問題ございましたら、下記の電話番号までに<br />
								いつでも問題ございませんので、必ずご連絡下さい。<br />
								424-750-2012（山内）<br />
								※こちらのメールを転送する際に、ご一報いただく意味で、<br />
								弊社のアドレスをBCC設定していただけましたら安心できますの
								で助かります。<br />
								今日出荷分のインボイスと伝票明細です。<br />
								よろしくお願い致します。<br />
								山内,'
		);
	}
	public static function get_download_falied_msg(){
		return "<h1>download failed</h1>";
	}
	public static function get_download_success_msg(){
		return ' report downloaded successfully';
	}
	public static function get_homepage_link(){
		return "<h1>File not available for download.</h1><a href='home' class='btn btn-warning'>click to go back to Home page</a>";
	}
	public static function get_item_download_parm(){
		return "item";
	}
	public static function get_invoice_download_parm(){
		return "invoice";
	}
	
	public static function get_reports_folder(){
		return "reports/";
	}
	
	public static function get_upload_folder(){
		return "/uploads";
	}
	
	public static function get_upload_success_message(){
		return "  File is uploaded successfully. Please correct the data below";
	}
	
	public static function get_allowed_file_ext(){
		return "csv";
	}
	
	public static function get_database_details(){
		return array('hostname'=>'sql108.byethost33.com','username'=>'b33_15119178','password'=>'Hafslund12','database'=>'b33_15119178_test');
	}
	
	public static function get_dropdownmenu_query(){
		return "SELECT distinct`categories_code`, `material1`, `material2` FROM `cat_new` order by `categories_code`";
	}
	
	public static function get_item_detail_ajax_msg(){
		return "Item details report generated";
	}
	
	public static function get_delete_report_no_msg(){
		return "No more reports to delete";
	}
	
	public static function get_delete_report_success_msg(){
		return "Today's reports deleted";
	}
	
	public static function get_invoice_report_success_msg(){
		return "Invoice report generated";
	}
	
	public static function get_delete_report_post_msg(){
		return "remove";
	}
}
