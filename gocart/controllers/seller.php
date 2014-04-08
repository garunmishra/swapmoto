<?php

/*
*
*/
class seller extends Front_Controller {
	function __construct()
	{
		parent::__construct();
		
		//make sure we're not always behind ssl
		remove_ssl();
	}
	public function index(){		
	}
	/*
	*
	*/
	public function seller_listed_items($id, $order_by = "name", $sort_order = "ASC", $code = 0, $page = 0, $rows = 15){
		$base_url	= $this->uri->segment_array();
		$data['base_url']	= $base_url;
		$post = array('user_id' => $id);
		 $term = json_encode($post);
		 $data['products'] = $this->Product_model->get_my_products(array('term' => $term, 'order_by' => $order_by, 'sort_order' => $sort_order, 'rows' => $rows, 'page' => $page));
		$data['page_title'] = '';		
		$data["file"] = "seller_listed_items";
       $this->load->view('category', $data);
	}
}



?>