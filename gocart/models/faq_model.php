<?php
Class faq_model extends CI_Model
{
		
	// we will store the group discount formula here
	// and apply it to product prices as they are fetched 
	var $group_discount_formula = false;
	
	function __construct()
	{
		parent::__construct();
		
		// check for possible group discount 
		$customer = $this->session->userdata('customer');
		
	}
	
	public function save_faq($data){
		$this->db->insert('faq',$data);
		$id	= $this->db->insert_id();
		if($id) return $id ;  else return false;
	}
	
	public function get_faq_count($condetion=null){	
	return $totalconversetion = $this->db->select('count(id) totalrow')->from('faq')->count_all_results();
	}
	public function get_faq_list(){
	
	$this->customer = $this->go_cart->customer();
	$return['unread_result'] = $this->db->query('select m1.id, m1.title, m1.timestamp, count(m2.id) as reps, users.id as userid, users.firstname from prefix_faq as m1, prefix_faq as m2,prefix_customers users where ((m1.user1="'.$this->customer['id'].'" and m1.user1read="no" and users.id=m1.user2) or (m1.user2="'.$this->customer['id'].'" and m1.user2read="no" and users.id=m1.user1)) and m1.id2="1" and m2.id=m1.id group by m1.id order by m1.id desc')->result();
	
	$return['read_result'] = $this->db->query('select m1.id, m1.title, m1.timestamp, count(m2.id) as reps, users.id as userid, users.firstname from prefix_faq as m1, prefix_faq as m2,prefix_customers users where ((m1.user1="'.$this->customer['id'].'" and m1.user1read="yes" and users.id=m1.user2) or (m1.user2="'.$this->customer['id'].'" and m1.user2read="yes" and users.id=m1.user1)) and m1.id2="1" and m2.id=m1.id group by m1.id order by m1.id desc')->result();
	return $return;
	}
	
	public function get_faq_by_id($id){
		 $this->db->select('title, user1, user2,product_id');
		return $this->db->get_where('faq',array('id'=>$id,'id2'=>1))->row();
		
	
	}
	public function save($data,$condetion=array()){
	if(!empty($condetion)){ // check for update query
			$this->db->where($condetion);
			$this->db->update('faq', $data);
			return true;
	
	} else{
	// is use to insert data in faq
			$this->db->insert('faq', $data);
			$id	= $this->db->insert_id();
			return $id;
	}	
	}
	public function get_message_by_id($id){
	
	return $this->db->query('select pm.timestamp, pm.message, users.id as userid, users.firstname username from prefix_faq pm, prefix_customers users where pm.id="'.$id.'" and users.id=pm.user1 order by pm.id2')->result();
	}
	
	public function save_feedback($data){
			$this->db->insert('feedback', $data);
			$id	= $this->db->insert_id();
			return $id;
	}
}