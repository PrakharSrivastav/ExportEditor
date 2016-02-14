<?php

/************************************************************************************************************
* Author		: Prakhar
* Class			: FileUpload
* Purpose 		: Performs basic fileupload operation
* Validations	: Checks performed are mentioned below. More checks can be added if needed in validate method
* 				: $returnMessage adds up all the validation errors and sends them back to callee for auditing.
* usage 		: $fileUpload = new FileUpload($webroot,$uploaddir, $_FILES['filename']);
				  $returnMessage = $fileUpload->validate($allowed_extensions);
				  if(count($returnMessage) === 0) 
				  		$returnMessage = $fileUpload->uploadFile();
*************************************************************************************************************/

class FileUpload {

	# class variables.
	private $basePath;							  							// webroot 
	private $uploadDir;							  							// file upload directory
	private $uploadFile;						  							// file ($_FILES['filename'])
	private $fileExt;							  							// file extension
	private $uploadPath;						  							// upload directory + file name
	private $returnMessage; 					  							// message for auditing
	
	# constructor
	public function __construct ($basePath, $dir, $file)
	{
		try{
			$this->basePath = $basePath;
			$this->returnMessage = array();
			$this->uploadDir = $dir;
			$this->uploadFile = $file;
			$this->uploadPath = $this->uploadDir.'/'.date("M_D_Y_").$this->uploadFile['name'];		// target file name. change the target file name here
			$temp = (explode('.' , $this->uploadFile['name']));
			$this->fileExt = strtolower($temp[count($temp)-1]);
		}
		catch(Exception $exception){
			throw new Exception ("Exception while creating the FileUpload instance: \t".$exception->getMessage());
		}
	}
	
	# return the file path and location.
	public function getFileDetails()
	{
		return array('path'=>$this->uploadPath,'ext'=>$this->fileExt);
	}

	# perform below validations before uploading the file
 	# 	1. file format
 	#	2. file upload error
 	#	3. target directory not found
 	# 	4. duplicate file
 	# add more validations as per your usecase.
	public function validate($allowedExts)
	{
		try
		{
			if (!in_array($this->fileExt,$allowedExts)) 
				throw new Exception ("Invalid format for uploaded file. Please upload an image file");
			if ($this->uploadFile['error'] > 0) 
				throw new Exception ("File Error :". $this->uploadFile['error']);
			if (!is_dir($this->uploadDir))	
				throw new Exception ("Upload directory not found. Please create the directory and try again");
			//if (file_exists($this->uploadPath))	
				//throw new Exception ("Duplicate file with same name exists. Please upload another file");
			return true;
		}
		catch (Exception $exception) 
		{
			throw new Exception ("Exception while validating the uploaded file: \t".$exception->getMessage());
		}
		
	}
	
	# upload files when there are no validation errors
	public function uploadFile()
	{
		try 
		{
			if (count($this->returnMessage) === 0) 
				move_uploaded_file($this->uploadFile['tmp_name'],$this->uploadPath);
			return true;
		}
		catch (Exception $exception) 
		{
			throw new Exception ("Exception while uploading the file: \t".$exception->getMessage());
		}
	}
	
	# destructor
	public function __destructor(){
		$this->basePath = null ;		unset($this->basePath);
		$this->uploadDir = null ;		unset($this->uploadDir);
		$this->uploadFile = null ;		unset($this->uploadFile);
		$this->fileExt = null ;			unset($this->fileExt);
		$this->uploadPath = null ;		unset($this->uploadPath);
		$this->returnMessage = null ;	unset($this->returnMessage);
	}
}