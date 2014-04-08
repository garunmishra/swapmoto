<?php
/**
 * this is common model All common method are avilabe here
 * @author Garun Mishra <mishragkmishra@gmail.com> 
 */
Class Common_model extends CI_Model
{

	//this is the expiration for a non-remember session
	var $session_expire	= 7200;
	function __construct()
	{
            parent::__construct();
	}
        
     function setMessage($type = NULL ,$message = NULL)
	{
		if($type != NULL && $message != NULL)
		{
			$this->session->set_flashdata('message_type', $type);//message 
			$this->session->set_flashdata('message', $message);
		}
	
	}
	
	function getMessage()
	{
		if($this->session->flashdata('message_type') != ''){
		$type = $this->session->flashdata('message_type');
		} else {
		$type = NULL;
		}
		if($this->session->flashdata('message') != ''){
		$message = $this->session->flashdata('message');
		} else {
		$message = NULL;
		}
		
		if($type != NULL && $message != NULL)
		{
			if($type == 1) {
			echo '<div id="closeMeRapper" class="alert alert-success">'.$message.'<a id="closeMe" class="close" data-dismiss="alert">x</a></div>';
			} elseif($type == 2) {
			echo '<div id="closeMeRapper" class="alert alert-warning">'.$message.'<a id="closeMe" class="close" data-dismiss="alert">x</a></div>';
			} elseif($type == 3){
			echo '<div id="closeMeRapper" class="alert alert-danger">'.$message.'<a id="closeMe" class="close" data-dismiss="alert">x</a></div>';
			}
		}
	}  
        
	
}