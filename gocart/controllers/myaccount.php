<?php

class Myaccount extends Front_Controller {

    var $customer;

    function __construct() {
        parent::__construct();

        force_ssl();

        $this->load->model(array('location_model'));

        $this->customer = $this->go_cart->customer();
    }

    function index($offset = 0) {
        //make sure they're logged in
        $this->Customer_model->is_logged_in('secure/login/');

        $data['gift_cards_enabled'] = $this->gift_cards_enabled;

        $data['customer'] = (array) $this->Customer_model->get_customer($this->customer['id']);

        $data['addresses'] = $this->Customer_model->get_address_list($this->customer['id']);

        $data['page_title'] = 'Welcome ' . $data['customer']['firstname'] . ' ' . $data['customer']['lastname'];
        $data['customer_addresses'] = $this->Customer_model->get_address_list($data['customer']['id']);

        // load other page content 
        //$this->load->model('banner_model');
        $this->load->model('order_model');
        $this->load->helper('directory');
        $this->load->helper('date');

        //if they want to limit to the top 5 banners and use the enable/disable on dates, add true to the get_banners function
        //	$data['banners']	= $this->banner_model->get_banners();
        //	$data['ads']		= $this->banner_model->get_banners(true);
        $data['categories'] = $this->Category_model->get_categories_tierd(0);


        // paginate the orders
        $this->load->library('pagination');

        $config['base_url'] = site_url('secure/my_account');
        $config['total_rows'] = $this->order_model->count_customer_orders($this->customer['id']);
        $config['per_page'] = '15';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['full_tag_open'] = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data['orders_pagination'] = $this->pagination->create_links();

        $data['orders'] = $this->order_model->get_customer_orders($this->customer['id'], $offset);


        //if they're logged in, then we have all their acct. info in the cookie.


        /*
          This is for the customers to be able to edit their account information
         */

        $this->load->library('form_validation');
        $this->form_validation->set_rules('company', 'Company', 'trim|max_length[128]');
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]|callback_check_email');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('email_subscribe', 'Subscribe', 'trim|numeric|max_length[1]');


        if ($this->input->post('password') != '' || $this->input->post('confirm') != '') {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|sha1');
            $this->form_validation->set_rules('confirm', 'Confirm Password', 'required|matches[password]');
        } else {
            $this->form_validation->set_rules('password', 'Password');
            $this->form_validation->set_rules('confirm', 'Confirm Password');
        }


        if ($this->form_validation->run() == FALSE) {

            $data["title"] = "";
            $data["file"] = "my_account";
            $this->load->view('myaccount_template', $data);
        } else {
            $customer = array();
            $customer['id'] = $this->customer['id'];
            $customer['company'] = $this->input->post('company');
            $customer['firstname'] = $this->input->post('firstname');
            $customer['lastname'] = $this->input->post('lastname');
            $customer['email'] = $this->input->post('email');
            $customer['phone'] = $this->input->post('phone');
            $customer['email_subscribe'] = intval((bool) $this->input->post('email_subscribe'));
            if ($this->input->post('password') != '') {
                $customer['password'] = $this->input->post('password');
            }

//			$this->go_cart->save_customer($this->customer);
            $this->Customer_model->save($customer);

            //$this->session->set_flashdata('message', 'Profile has been updated');
            $this->common_model->setMessage(1, 'Profile has been updated');

            redirect('myaccount');
        }
    }

    public function list_item($order_by = "name", $sort_order = "ASC", $code = 0, $page = 0, $rows = 15) {
        //make sure they're logged in
        $this->Customer_model->is_logged_in('secure/login/');

        $this->load->model('Product_model');
        $this->load->helper('form');
        $this->lang->load('product');

        $data['page_title'] = lang('products');

        $data['code'] = $code;
        $term = false;
        $category_id = false;

        //get the category list for the drop menu
        //$data['categories']	= $this->Category_model->get_categories_tierd();
        //$post				= $this->input->post(null, false);
        $customer = $this->go_cart->customer();
        $post = array('user_id' => $customer['id']);
        $this->load->model('Search_model');
        if ($post) {
            $term = json_encode($post);
            $code = $this->Search_model->record_term($term);
        }
        //store the search term
        $data['term'] = $term;
        $data['order_by'] = $order_by;
        $data['sort_order'] = $sort_order;

        $data['products'] = $this->Product_model->get_my_products(array('term' => $term, 'order_by' => $order_by, 'sort_order' => $sort_order, 'rows' => $rows, 'page' => $page));


        //total number of products
        $data['total'] = $this->Product_model->get_my_products(array('term' => $term, 'order_by' => $order_by, 'sort_order' => $sort_order), true);


        $this->load->library('pagination');

        $config['base_url'] = site_url($this->config->item('admin_folder') . '/products/index/' . $order_by . '/' . $sort_order . '/' . $code . '/');
        $config['total_rows'] = $data['total'];
        $config['per_page'] = $rows;
        $config['uri_segment'] = 7;
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['full_tag_open'] = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data["title"] = "";
        $data["file"] = "list_item";
        $this->load->view('myaccount_template', $data);
    }

    function thanks() {
        echo "Product Has been saved successfully";
        echo "<br>This page is under cunstruction";
        echo "<br><a href='" . base_url() . "secure/sale'>Go to Sale Page</a>";
    }

    public function remove_item($product_id = NULL) {

        if (empty($product_id)) {
            //$this->session->set_flashdata('error', lang('error_not_found'));
            $this->common_model->setMessage(3,"Item not exist");
        }
        $customer = $this->go_cart->customer();
        $product = $this->Product_model->get_user_product($product_id, $customer['id']);

        if ($product == false) {
            //$this->session->set_flashdata('error', lang('error_not_found'));
            $this->common_model->setMessage(3, lang('error_not_found'));
			$this->common_model->setMessage(3,"Item not exist");
        } else {
            $this->Product_model->delete_product($product_id);
            //$this->session->set_flashdata('message', lang('message_deleted_product'));
            $this->common_model->setMessage(1, "Item successfully removed");
        }

        redirect('/myaccount/list_item');
    }

    function order($offset = 0) {
        //make sure they're logged in
        $this->Customer_model->is_logged_in('secure/login/');
        $this->load->model('order_model');
        $this->load->helper('directory');
        $this->load->helper('date');
        $this->load->library('pagination');

        $config['base_url'] = site_url('secure/my_account');
        $config['total_rows'] = $this->order_model->count_customer_orders($this->customer['id']);
        $config['per_page'] = '10';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['full_tag_open'] = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data['orders_pagination'] = $this->pagination->create_links();
        $data['orders'] = $this->order_model->get_customer_orders($this->customer['id'], $offset);
        $data["title"] = "";
        $data["file"] = "customer_order";
        $this->load->view('myaccount_template', $data);
    }

    public function address() {
        $this->Customer_model->is_logged_in('myaccount/my_account/');
        $data['gift_cards_enabled'] = $this->gift_cards_enabled;
        $data['customer'] = (array) $this->Customer_model->get_customer($this->customer['id']);
        $data['addresses'] = $this->Customer_model->get_address_list($this->customer['id']);
        $data["title"] = "";
        $data["file"] = "addresses";
        $this->load->view('myaccount_template', $data);
    }

    public function add_address($id = 0) {
        $customer = $this->go_cart->customer();
        //grab the address if it's available
        $data['id'] = false;
        $data['company'] = $customer['company'];
        $data['firstname'] = $customer['firstname'];
        $data['lastname'] = $customer['lastname'];
        $data['email'] = $customer['email'];
        $data['phone'] = $customer['phone'];
        $data['address1'] = '';
        $data['address2'] = '';
        $data['city'] = '';
        $data['country_id'] = '';
        $data['zone_id'] = '';
        $data['zip'] = '';
        if ($id != 0) {
            $a = $this->Customer_model->get_address($id);
            if ($a['customer_id'] == $this->customer['id']) {
                $data = $a['field_data'];
                $data['id'] = $id;
            } else {
                redirect('/'); // don't allow cross-customer editing
            }
            $data['zones_menu'] = $this->location_model->get_zones_menu($data['country_id']);
        }
        //get the countries list for the dropdown
        $data['countries_menu'] = $this->location_model->get_countries_menu();
        if ($id == 0) {
            //if there is no set ID, the get the zones of the first country in the countries menu
            $data['zones_menu'] = $this->location_model->get_zones_menu(array_shift(array_keys($data['countries_menu'])));
        } else {
            $data['zones_menu'] = $this->location_model->get_zones_menu($data['country_id']);
        }
        $data["title"] = "";
        $data["file"] = "add_address";
        $this->load->view('myaccount_template', $data);
    }

    public function change_password() {
        //echo "<pre>";print_r($this->customer);
        // user authantication
        $this->Customer_model->is_logged_in('secure/login/');
        if ('update' == 'update') {
            $haserror = true;
            $customer = $this->go_cart->customer();
            if ($this->input->post('current_password') != '') {
                if ($customer['password'] === sha1($this->input->post('current_password'))) {
                    $haserror = false;
                } else {
					$this->common_model->setMessage(3,'Password not match.');
					redirect('myaccount/change_password');
                }
            } elseif(isset($_POST['current_password']) && $_POST['current_password']==''){
			$this->common_model->setMessage(3,'Enter current password.');
			redirect('myaccount/change_password');
            }
            if ($haserror == false) {
                if (!empty($_REQUEST['password'])) {
                    $password = $this->input->post('password');
                    $confirm = $this->input->post('confirm');
                    if ($password === $confirm) {
                        $customer = array();
                        $customer['id'] = $this->customer['id'];
                        $customer['password'] = sha1($this->input->post('password'));
                        $this->go_cart->save_customer($this->customer);
                        if ($this->Customer_model->save($customer)) {
                            $this->common_model->setMessage(1, 'Password has been updated');
                            //$this->email_model->changePassword($this->input->post('password'),$this->customer);
                        }
						$this->common_model->setMessage(3,'Sorry ! try again.');
						redirect('myaccount/change_password');
                    }
                } else {
					$this->common_model->setMessage(3,'Please enter password.');
					redirect('myaccount/change_password');
                   
                }
            }
        }
        $data["title"] = "";
        $data["file"] = "change_pass";
        $this->load->view('myaccount_template', $data);
    }
    
    function address_form($id = 0)
	{
		
		$customer = $this->go_cart->customer();
		
		//grab the address if it's available
		$data['id']			= false;
		$data['company']	= $customer['company'];
		$data['firstname']	= $customer['firstname'];
		$data['lastname']	= $customer['lastname'];
		$data['email']		= $customer['email'];
		$data['phone']		= $customer['phone'];
		$data['address1']	= '';
		$data['address2']	= '';
		$data['city']		= '';
		$data['country_id'] = '';
		$data['zone_id']	= '';
		$data['zip']		= '';
		

		if($id != 0)
		{
			$a	= $this->Customer_model->get_address($id);
			if($a['customer_id'] == $this->customer['id'])
			{
				//notice that this is replacing all of the data array
				//if anything beyond this form data needs to be added to
				//the data array, do so after this portion of code
				$data		= $a['field_data'];
				$data['id']	= $id;
			} else {
				redirect('/'); // don't allow cross-customer editing
			}
			
			$data['zones_menu']	= $this->location_model->get_zones_menu($data['country_id']);
		}
		
		//get the countries list for the dropdown
		$data['countries_menu']	= $this->location_model->get_countries_menu();
		
		if($id==0)
		{
			//if there is no set ID, the get the zones of the first country in the countries menu
			$data['zones_menu']	= $this->location_model->get_zones_menu(array_shift(array_keys($data['countries_menu'])));
		} else {
			$data['zones_menu']	= $this->location_model->get_zones_menu($data['country_id']);
		}

		$this->load->library('form_validation');	
		//$this->form_validation->set_rules('company', 'Company', 'trim|max_length[128]');
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('address1', 'Address', 'trim|required|max_length[128]');
		$this->form_validation->set_rules('address2', 'Address', 'trim|max_length[128]');
		$this->form_validation->set_rules('city', 'City', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('country_id', 'Country', 'trim|required|numeric');
		$this->form_validation->set_rules('zone_id', 'State', 'trim|required|numeric');
		$this->form_validation->set_rules('zip', 'Zip', 'trim|required|max_length[32]');
		
		
		if ($this->form_validation->run() == FALSE)
		{
			if(validation_errors() != '')
			{
				echo validation_errors();
			}
			else
			{
				$this->load->view('address_form', $data);
			}
		}
		else
		{
			$a = array();
			$a['id']						= ($id==0) ? '' : $id;
			$a['customer_id']				= $this->customer['id'];
			$a['field_data']['company']		= $this->input->post('company');
			$a['field_data']['firstname']	= $this->input->post('firstname');
			$a['field_data']['lastname']	= $this->input->post('lastname');
			$a['field_data']['email']		= $this->input->post('email');
			$a['field_data']['phone']		= $this->input->post('phone');
			$a['field_data']['address1']	= $this->input->post('address1');
			$a['field_data']['address2']	= $this->input->post('address2');
			$a['field_data']['city']		= $this->input->post('city');
			$a['field_data']['zip']			= $this->input->post('zip');
			
			// get zone / country data using the zone id submitted as state
			$country = $this->location_model->get_country(set_value('country_id'));	
			$zone    = $this->location_model->get_zone(set_value('zone_id'));		
			if(!empty($country))
			{
				$a['field_data']['zone']		= $zone->code;  // save the state for output formatted addresses
				$a['field_data']['country']		= $country->name; // some shipping libraries require country name
				$a['field_data']['country_code']   = $country->iso_code_2; // some shipping libraries require the code 
				$a['field_data']['country_id']  = $this->input->post('country_id');
				$a['field_data']['zone_id']		= $this->input->post('zone_id');  
			}
			
			$this->Customer_model->save_address($a);
			
                        $this->common_model->setMessage(1, 'Address has been saved');
			echo 1;
		}
	}
        
        function delete_address()
	{
		$id = $this->input->post('id');
		// use the customer id with the addr id to prevent a random number from being sent in and deleting an address
		$customer = $this->go_cart->customer();
		$this->Customer_model->delete_address($id, $customer['id']);
		echo $id;
	}
        /*
		@auther : Rameshwar Jaiswal
		@DAte : 4-april- 2014
		@desc : save query asked by visiter or user and send notfication to seller, called using ajax
		@input : message, product_id
		
		*/
        public function save_faq(){	
		$this->load->model(array('faq_model'));
	$message = $this->input->post('faq');
	$product_id = $this->input->post('product_id');
	$customer = $this->go_cart->customer();		
	$condetion = array('id'=>$product_id);
	$product	= $this->Product_model->get_similar_product($condetion,1);
	$tatalfaq	= $this->faq_model->get_faq_count();
	$product = $product[0];
	$product_title = "FAQ";
	$save = array();
	$save['id'] = $tatalfaq+1;
	$save['id2'] = 1;
	$save['product_id'] = $product->id;
	$save['title'] = $product_title;
	$save['user1'] = $customer['id'];
	$save['user2'] = $product->user_id;
	$save['timestamp'] = time();
	$save['message'] = $message;
	$save['user1read'] = 'yes';
	$save['user2read'] = 'no';
	$faqrs = $this->faq_model->save_faq($save);
	if($faqrs!=false){
	echo "1";
	} else {
	echo 0;
	}
	}
	   /*
		@auther : Rameshwar Jaiswal
		@DAte : 4-april- 2014
		@desc : seller Inbox, out box , send rply
		@input : null
		
		*/
	public	function inbox(){
	$this->load->model(array('faq_model'));	
	$return = $this->faq_model->get_faq_list();
	$data['inbox'] = $return;
	$data["file"] = "inbox";
    $this->load->view('myaccount_template', $data);	
	}
	
	public function view_message($id){
		$this->customer = $this->go_cart->customer();
		$this->load->model(array('faq_model'));	
		$result = $this->faq_model->get_faq_by_id($id);
		if(count($result)<=0){
		// set error message to send return to inbox
		$this->common_model->setMessage(3, 'This discussion does not exists.');
		redirect('/myaccount/inbox');
		}
		//We check if the user have the right to read this discussion
		if($result->user1==$this->customer['id'] || $result->user2==$this->customer['id']){
		if($result->user1==$this->customer['id']){
			$save['user1read'] = 'yes';
			$user_partic = 2;
		} elseif($result->user2==$this->customer['id']){
			$save['user2read'] = 'yes';
			$user_partic = 1;
		}
		$condetion = array('id'=>$id, 'id2'=>1);
		$this->faq_model->save($save,$condetion);
		
		$req2 = $this->faq_model->get_message_by_id($id);
		// send message in rply 
		if(isset($_POST['message']) and $_POST['message']!='')
			{
	$message = $_POST['message'];
	//We remove slashes depending on the configuration
	if(get_magic_quotes_gpc())
	{
		$message = stripslashes($message);
	}
	//We protect the variables
	$message = mysql_real_escape_string(nl2br(htmlentities($message, ENT_QUOTES, 'UTF-8')));
	//We send the message and we change the status of the discussion to unread for the recipient
	$savedata = array();
	$savedata['id'] = $id;
	$savedata['id2'] = count($req2)+1;
	$savedata['product_id'] = $result->product_id;
	$savedata['title'] = '';
	$savedata['user1'] = $this->customer['id'];
	$savedata['user2'] = '';
	$savedata['message'] = $message;
	$savedata['timestamp'] = time();
	$savedata['user1read'] = '';
	$savedata['user2read'] = '';
	$this->faq_model->save($savedata);
	$save = array();
	$save['user'.$user_partic.'read']="yes";	
	$this->faq_model->save($save, $condetion);	
	 $this->common_model->setMessage(1, 'Your message sent.');
	redirect('/myaccount/view_message/'.$id);
	}elseif(isset($_POST['message']) and $_POST['message']==''){
	$this->common_model->setMessage(3, 'Please enter message.');
	redirect('/myaccount/view_message/'.$id);	
	}
		$data['convertion'] = $req2;
		$data['message'] = $result;
		}
		else {
				$this->common_model->setMessage(3, 'You dont have the rights to access this page.');
				redirect('/myaccount/inbox');
		}
		$data['id'] = $id;
		$data['file'] = 'view_message';
		$this->load->view('myaccount_template', $data);	
	}
	
	function save_feedback(){
	$haserror = false;
	$message = "";
	$this->load->model(array('faq_model'));
	$feedback_msg = $this->input->post('feedback_msg');
	$product_id = $this->input->post('product_id');
	$reting = $this->input->post('reting');
	$customer = $this->go_cart->customer();	
	$condetion = array('id'=>$product_id);	
	$product = $this->Product_model->get_similar_product($condetion,1);
	$product = $product[0];
	//print_r($product);
	$save['feedback'] = $feedback_msg;
	$save['product_id'] = $product_id;
	$save['rate'] = $reting;
	$save['feedback_by'] = $customer['id'];
	$save['feedback_to'] = $product->user_id;
	$save['type'] = "B2S";
	$save['feedback_on'] = time();
	if($customer['id']==$product->user_id){
	$message = "You are not allow to share feedback.";
	$haserror = true;
	}
	if($haserror==false){
	$feedback = $this->faq_model->save_feedback($save);
	} else $feedback = false;
	if($feedback!=false){
	$result = array('status'=>'1','message'=>'Thanks for sharing your feedback');	
	} else{
	if($message==''){
	$message = "Sorry ! Some error occured";
	} 
	$result = array('status'=>'0','message'=>$message);
	}
	echo json_encode($result);
	
	}
	
	public function open(){
	echo "hello";	
	}
}
