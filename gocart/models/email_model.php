<?php
/**
 * this is common model All common method are avilabe here
 * @author Garun Mishra <mishragkmishra@gmail.com> 
 */
Class Email_model extends CI_Model
{
	function __construct()
	{
            parent::__construct();
			$this->load->library('email');
	}
	function changePassword($newPassword,$customer)
	{
		$template = $this->getEmailTemplate('change_password');
		$subject = $template[0]['subject'];
		$message=$template[0]['content'];
		
		$this->email->from(ADMIN_FROME_EMAIL, ADMIN_FROME_NAME);
		$this->email->to($customer['email']);
		$this->email->subject($subject);
		$message=str_replace('{customer_name}',$customer['firstname'],$message);
		$message=str_replace('{password}',$newPassword,$message);
		$this->email->message($message);
		if($this->email->send())
			return true;
		else
			return false;		
	}
	function getEmailTemplate($name)
	{
		$sqlteml="select * from prefix_canned_messages where name ='$name'";
		$queryteml=$this->db->query($sqlteml);
		return $queryteml->result_array();
	}
	
        
}