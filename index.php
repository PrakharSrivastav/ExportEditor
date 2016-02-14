<?php
require_once('Toro.php');


class HomePageHandler{
	public function get(){
		include("home.php");
	}
}

class PreviewHandler{
	public function post(){
		include("preview.php");
	}
}
class ItemReportHandler{
	public function post(){
		include("generate_item_report.php");
	}
}
class InvoiceReportHandler{
	public function post(){
		include("generate_invoice_report.php");
	}
}
class ClearReportHandler{
	public function post(){
		include("delete_reports.php");
	}
}
class EmailPageHandler{
	public function post(){
		include("get_email_page.php");
	}
}
class DownloadHandler{
	public function get(){
		include("download_reports.php");
	}
}

class ProcessEmailHandler{
	public function post(){
		include("process_emails.php");
	}
}

class ErrorHandler{
	public function get(){
		include("404.php");
	}
}

class PreviousReportHandler{
	public function get(){
		include("get_previous_reports.php");
	}
}

class ShowPreviousReportHandler{
	public function get(){
		include("show_previous_reports.php");
	}
}

ToroHook::add("404", function() {
    include('404.php');
});

Toro::serve(array(
	'get_email_page'=>'EmailPageHandler',
	'home'=>'HomePageHandler',
	'preview'=>'PreviewHandler',
	'generate_item_report'=>'ItemReportHandler',
	'generate_invoice_report'=>'InvoiceReportHandler',
	'clear_reports_today'=>'ClearReportHandler',
	'download_report'=>'DownloadHandler',
	'process_email'=>'ProcessEmailHandler',
	'404'=>'ErrorHandler',
	'previous_reports'=>'PreviousReportHandler',
	'show_previous_reports'=>'ShowPreviousReportHandler'
));
