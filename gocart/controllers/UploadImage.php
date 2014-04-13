<?php

class UploadImage extends Front_Controller {
	
	var $customer;
	
	function __construct()
	{
		parent::__construct();
		
		force_ssl();
		
		$this->load->model(array('location_model'));
		
		$this->customer = $this->go_cart->customer();
	}
	
	function index()
	{
		show_404();
		echo "hello";
	}
	
	public function upload_Image(){
		$targetFolder = 'uploads/images/full'; // Relative to the root
		$verifyToken = md5('unique_salt' . $_POST['timestamp']);
		if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
	 		$targetPath =  $targetFolder;
			$rand = rand(22222,333333333);
	// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($_FILES['Filedata']['name']);
			$targetFile = rtrim($targetPath,'/') . '/'.$verifyToken .$rand.'.'.$fileParts['extension'];// $_FILES['Filedata']['name'];
			if (in_array($fileParts['extension'],$fileTypes)) {
				move_uploaded_file($tempFile,$targetFile);
				// crop image 
				$upload_data['file_name'] = $verifyToken.$rand.'.'.$fileParts['extension'];//$_FILES['Filedata']['name'];
				$this->load->library('image_lib');
			/*
			
			I find that ImageMagick is more efficient that GD2 but not everyone has it
			if your server has ImageMagick then you can change out the line
			
			$config['image_library'] = 'gd2';
			
			with
			
			$config['library_path']		= '/usr/bin/convert'; //make sure you use the correct path to ImageMagic
			$config['image_library']	= 'ImageMagick';
			*/			
			
			//this is the larger image
			$config['image_library'] = 'gd2';
			$config['source_image'] = 'uploads/images/full/'.$upload_data['file_name'];
			$config['new_image']	= 'uploads/images/medium/'.$upload_data['file_name'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 600;
			$config['height'] = 500;
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
			$this->image_lib->clear();

			//small image
			$config['image_library'] = 'gd2';
			$config['source_image'] = 'uploads/images/medium/'.$upload_data['file_name'];
			$config['new_image']	= 'uploads/images/small/'.$upload_data['file_name'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 235;
			$config['height'] = 235;
			$this->image_lib->initialize($config); 
			$this->image_lib->resize();
			$this->image_lib->clear();

			//cropped thumbnail
			$config['image_library'] = 'gd2';
			$config['source_image'] = 'uploads/images/small/'.$upload_data['file_name'];
			$config['new_image']	= 'uploads/images/thumbnails/'.$upload_data['file_name'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 150;
			$config['height'] = 150;
			$this->image_lib->initialize($config); 	
			$this->image_lib->resize();	
			$this->image_lib->clear();
			echo $upload_data['file_name'] ;
				// end crop image
				} else {
				echo 'Invalid file type.';
				}
		}
		}
		
	public function remove_image(){
		$image_name = $this->input->post('img_name');
		unlink('uploads/images/small/'.$image_name);
		unlink('uploads/images/thumbnails/'.$image_name);
		unlink('uploads/images/medium/'.$image_name);
		unlink('uploads/images/full/'.$image_name);
		echo 1;
		
	}
}