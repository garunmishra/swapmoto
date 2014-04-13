<?php

class Secure extends Front_Controller {
	
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
	}
	
	function login($ajax = false)
	{
		//find out if they're already logged in, if they are redirect them to the my account page
		$redirect	= $this->Customer_model->is_logged_in(false, false);
		//if they are logged in, we send them back to the my_account by default, if they are not logging in
		
		if ($redirect)
		{
			redirect(base_url().'myaccount/');
		}
		
		$data['page_title']	= 'Login';
		$data['gift_cards_enabled'] = $this->gift_cards_enabled;
		
		$this->load->helper('form');
		$data['redirect']	= $this->session->flashdata('redirect');
		$submitted 		= $this->input->post('submitted');
		if ($submitted)
		{
			$email		= $this->input->post('email');
			$password	= $this->input->post('password');
			$remember   = $this->input->post('remember');
			$redirect	= $this->input->post('redirect');
			$login		= $this->Customer_model->login($email, $password, $remember);
			if ($login)
			{
				if ($redirect == '')
				{
					//if there is not a redirect link, send them to the my account page
					$redirect = 'myaccount/';
				}
				//to login via ajax
				if($ajax)
				{
					die(json_encode(array('result'=>true)));
				}
				else
				{
					redirect($redirect);
				}
				
			}
			else
			{
				//this adds the redirect back to flash data if they provide an incorrect credentials
				
				
				//to login via ajax
				if($ajax)
				{
					die(json_encode(array('result'=>false)));
				}
				else
				{
					$this->session->set_flashdata('redirect', $redirect);
					$this->session->set_flashdata('error', lang('login_failed'));
					$this->common_model->setMessage(3,lang('login_failed'));


					
					redirect('secure/login');
				}
			}
		}
		
		// load other page content 
		//$this->load->model('banner_model');
		$this->load->helper('directory');
	
		//if they want to limit to the top 5 banners and use the enable/disable on dates, add true to the get_banners function
		//$data['banners']	= $this->banner_model->get_banners();
		//$data['ads']		= $this->banner_model->get_banners(true);
		$data['categories']	= $this->Category_model->get_categories_tierd(0);
			
		$this->load->view('login', $data);
	}
	
	function logout()
	{
		$this->Customer_model->logout();
		redirect('secure/login');
	}
	
	function register()
	{
	
		$redirect	= $this->Customer_model->is_logged_in(false, false);
		//if they are logged in, we send them back to the my_account by default
		if ($redirect)
		{
			redirect('secure/my_account');
		}
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div>', '</div>');
		
		/*
		we're going to set this up early.
		we can set a redirect on this, if a customer is checking out, they need an account.
		this will allow them to register and then complete their checkout, or it will allow them
		to register at anytime and by default, redirect them to the homepage.
		*/
		$data['redirect']	= $this->session->flashdata('redirect');
		
		$data['page_title']	= lang('account_registration');
		$data['gift_cards_enabled'] = $this->gift_cards_enabled;
		
		//default values are empty if the customer is new

		$data['company']	= '';
		$data['firstname']	= '';
		$data['lastname']	= '';
		$data['email']		= '';
		$data['phone']		= '';
		$data['address1']	= '';
		$data['address2']	= '';
		$data['city']		= '';
		$data['state']		= '';
		$data['zip']		= '';

		$this->form_validation->set_rules('company', 'Company', 'trim|max_length[128]');
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]|callback_check_email');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|sha1');
		$this->form_validation->set_rules('confirm', 'Confirm Password', 'required|matches[password]');
		$this->form_validation->set_rules('email_subscribe', 'Subscribe', 'trim|numeric|max_length[1]');
		
		if ($this->form_validation->run() == FALSE)
		{
			//if they have submitted the form already and it has returned with errors, reset the redirect
			if ($this->input->post('submitted'))
			{
				$data['redirect']	= $this->input->post('redirect');				
			}
			// load other page content 
			//$this->load->model('banner_model');
			$this->load->helper('directory');		
			$data['categories']	= $this->Category_model->get_categories_tierd(0);			
			$data['error'] = validation_errors();		
			$this->common_model->setMessage(3,$data['error']);	
			$this->load->view('register', $data);
		}
		else
		{
			$save['id']		= false;
			$save['firstname']			= $this->input->post('firstname');
			$save['lastname']			= $this->input->post('lastname');
			$save['email']				= $this->input->post('email');
			$save['phone']				= $this->input->post('phone');
			$save['company']			= $this->input->post('company');
			$save['active']				= $this->config->item('new_customer_status');
			$save['email_subscribe']	= intval((bool)$this->input->post('email_subscribe'));			
			$save['password']			= $this->input->post('password');			
			$redirect					= $this->input->post('redirect');			
			//if we don't have a value for redirect
			if ($redirect == '')
			{
				$redirect = 'myaccount';
			}			
			// save the customer info and get their new id
			$id = $this->Customer_model->save($save);
			/* send an email */
			// get the email template
			$res = $this->db->where('id', '1')->get('canned_messages');
			$row = $res->row_array();			
			// set replacement values for subject & body			
			// {customer_name}
			$row['subject'] = str_replace('{customer_name}', $this->input->post('firstname').' '. $this->input->post('lastname'), $row['subject']);
			$row['content'] = str_replace('{customer_name}', $this->input->post('firstname').' '. $this->input->post('lastname'), $row['content']);			
			// {url}
			$row['subject'] = str_replace('{url}', $this->config->item('base_url'), $row['subject']);
			$row['content'] = str_replace('{url}', $this->config->item('base_url'), $row['content']);			
			// {site_name}
			$row['subject'] = str_replace('{site_name}', $this->config->item('company_name'), $row['subject']);
			$row['content'] = str_replace('{site_name}', $this->config->item('company_name'), $row['content']);			
			$this->load->library('email');			
			$config['mailtype'] = 'html';			
			$this->email->initialize($config);	
			$this->email->from($this->config->item('email'), $this->config->item('company_name'));
			$this->email->to($save['email']);
			$this->email->bcc($this->config->item('email'));
			$this->email->subject($row['subject']);
			$this->email->message(html_entity_decode($row['content']));			
			$this->email->send();			
			$this->session->set_flashdata('message', sprintf( lang('registration_thanks'), $this->input->post('firstname') ) );	
			$this->common_model->setMessage(1,"Thanks for registering ".$this->input->post('firstname'));		
			//lets automatically log them in
			$this->Customer_model->login($save['email'], $this->input->post('confirm'));			
			//we're just going to make this secure regardless, because we don't know if they are
			//wanting to redirect to an insecure location, if it needs to be secured then we can use the secure redirect in the controller
			//to redirect them, if there is no redirect, the it should redirect to the homepage.
			redirect($redirect);
		}
	}
	
	function check_email($str)
	{
		if(!empty($this->customer['id']))
		{
			$email = $this->Customer_model->check_email($str, $this->customer['id']);
		}
		else
		{
			$email = $this->Customer_model->check_email($str);
		}
		
        if ($email)
       	{
			$this->form_validation->set_message('check_email', lang('error_email'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function forgot_password()
	{
		$data['page_title']	= lang('forgot_password');
		$data['gift_cards_enabled'] = $this->gift_cards_enabled;
		$submitted = $this->input->post('submitted');
		if ($submitted)
		{
			$this->load->helper('string');
			$email = $this->input->post('email');
			
			$reset = $this->Customer_model->reset_password($email);
			
			if ($reset)
			{
                            $this->common_model->setMessage(1,'Your New Password has been send to registered email id');
			}
			else
			{
                            $this->common_model->setMessage(2,'You are not registered with us');
			}
			redirect('secure/forgot_password');
		}
		
		// load other page content 
		//$this->load->model('banner_model');
		$this->load->helper('directory');
	
		//if they want to limit to the top 5 banners and use the enable/disable on dates, add true to the get_banners function
		//$data['banners']	= $this->banner_model->get_banners();
		//$data['ads']		= $this->banner_model->get_banners(true);
		$data['categories']	= $this->Category_model->get_categories_tierd();
		
		
		$this->load->view('forgot_password', $data);
	}
	
	function my_downloads($code=false)
	{
		
		if($code!==false)
		{
			$data['downloads'] = $this->Digital_Product_model->get_downloads_by_code($code);
		} else {
			$this->Customer_model->is_logged_in();
			
			$customer = $this->go_cart->customer();
			
			$data['downloads'] = $this->Digital_Product_model->get_user_downloads($customer['id']);
		}
		
		$data['gift_cards_enabled']	= $this->gift_cards_enabled;
		
		$data['page_title'] = lang('my_downloads');
		
		$this->load->view('my_downloads', $data);
	}
	
	
	function download($link)
	{
		$filedata = $this->Digital_Product_model->get_file_info_by_link($link);
		
		// missing file (bad link)
		if(!$filedata)
		{
			show_404();
		}
		
		// validate download counter
		if(intval($filedata->downloads) >= intval($filedata->max_downloads))
		{
			show_404();
		}
		
		// increment downloads counter
		$this->Digital_Product_model->touch_download($link);
		
		// Deliver file
		$this->load->helper('download');
		force_download('uploads/digital_uploads/', $filedata->filename);
	}
	
	
	function set_default_address()
	{
		$id = $this->input->post('id');
		$type = $this->input->post('type');
	
		$customer = $this->go_cart->customer();
		$save['id'] = $customer['id'];
		
		if($type=='bill')
		{
			$save['default_billing_address'] = $id;

			$customer['bill_address'] = $this->Customer_model->get_address($id);
			$customer['default_billing_address'] = $id;
		} else {

			$save['default_shipping_address'] = $id;

			$customer['ship_address'] = $this->Customer_model->get_address($id);
			$customer['default_shipping_address'] = $id;
		} 
		
		//update customer db record
		$this->Customer_model->save($save);
		
		//update customer session info
		$this->go_cart->save_customer($customer);
		
		echo "1";
	}
	
	
	// this is a form partial for the checkout page
	function checkout_address_manager()
	{
		$customer = $this->go_cart->customer();
		
		$data['customer_addresses'] = $this->Customer_model->get_address_list($customer['id']);
	
		$this->load->view('address_manager', $data);
	}
	
	// this is a partial partial, to refresh the address manager
	function address_manager_list_contents()
	{
		$customer = $this->go_cart->customer();
		
		$data['customer_addresses'] = $this->Customer_model->get_address_list($customer['id']);
	
		$this->load->view('address_manager_list_content', $data);
	}
	
	
	
	
	
	// function add by Rameshwar 
	function add_item($id = false, $duplicate = false){
		$this->Customer_model->is_logged_in('secure/add_item/');
		$this->load->model('Product_model');
		$this->load->helper('form');
		$this->lang->load('product');
		
		
		$this->product_id	= $id;
		$this->load->library('form_validation');
		$this->load->model(array('Option_model', 'Category_model', 'Digital_Product_model'));
		//$this->lang->load('digital_product');
//		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$customer = $this->go_cart->customer();

		$data['page_title']		= lang('product_form');
		$data['file_list']		= $this->Digital_Product_model->get_list();
		//default values are empty if the product is new
		$data['id']					= '';
		$data['sku']				= '';
		$data['name']				= '';
		$data['slug']				= '';
		$data['description']		= '';
		$data['excerpt']			= '';
		$data['price']				= '';
		$data['saleprice']			= '';
		$data['weight']				= '';
		$data['track_stock'] 		= '';
		$data['seo_title']			= '';
		$data['meta']				= '';
		$data['shippable']			= '';
		$data['taxable']			= '';
		$data['fixed_quantity']		= '';
		$data['quantity']			= '';
		$data['enabled']			= '';
		$data['condition']			= '';
		$data['company_id'] 		='';
		$data['related_products']	= array();
		$data['product_categories']	= array();
		$data['images']				= array();
		$data['product_files']		= array();

		//create the photos array for later use
		$data['photos']		= array();
		
		///////////////////////////////////////////////////////////////////////////////////////
		$data['category_list'] = $this->Category_model->get_categories();
		$data['company_list'] = $this->Product_model->get_company_list();
	    if ($id)
		{	
			// get the existing file associations and create a format we can read from the form to set the checkboxes
			//$pr_files 		= $this->Digital_Product_model->get_associations_by_product($id);
			//foreach($pr_files as $f)
//			{
//				$data['product_files'][]  = $f->file_id;
//			}
			
			// get product & options data
			$data['product_options']	= $this->Option_model->get_product_options($id);
			$product					= $this->Product_model->get_product($id);
			
			//if the product does not exist, redirect them to the product list with an error
			if (!$product)
			{
				//$this->session->set_flashdata('error', lang('error_not_found'));
				$this->common_model->setMessage(3,lang('error_not_found'));
				redirect('/myaccount/list_item');
			}
			
			//helps us with the slug generation
			//$this->product_name	= $this->input->post('slug', $product->slug);
			
			//set values to db values
			$data['id']					= $id;
			$data['sku']				= $product->sku;
			$data['name']				= $product->name;
			$data['seo_title']			= $product->seo_title;
			$data['meta']				= $product->meta;
			$data['slug']				= $product->slug;
			$data['description']		= $product->description;
			$data['excerpt']			= $product->excerpt;
			$data['price']				= $product->price;
			$data['saleprice']			= $product->saleprice;
			$data['weight']				= $product->weight;
			$data['track_stock'] 		= $product->track_stock;
			$data['shippable']			= $product->shippable;
			$data['quantity']			= $product->quantity;
			$data['taxable']			= $product->taxable;
			$data['fixed_quantity']		= $product->fixed_quantity;
			$data['enabled']			= $product->enabled;
			$data['cat_id']                         = $product->cat_id;
			$data['sub_category_id']                = $product->sub_category_id;
			$data['company_id']                     = $product->company_id;
			$data['model_id']                     = $product->model_id;
			$data['condition'] = $product->condition;
                        
			$data['sub_category_data'] = $this->ajaxrequest($product->cat_id,'getsubcat',true,$product->sub_category_id);
            $data['model_data'] = $this->ajaxrequest($product->company_id,'getmodelcat',true,$product->model_id);
			//make sure we haven't submitted the form yet before we pull in the images/related products from the database
			if(!$this->input->post('submit'))
			{
				$data['product_categories']	= $product->categories;
				$data['related_products']	= $product->related_products;
				$data['images']				= (array)json_decode($product->images);
			}
		}
		
		//if $data['related_products'] is not an array, make it one.
		if(!is_array($data['related_products']))
		{
			$data['related_products']	= array();
		}
		if(!is_array($data['product_categories']))
		{
			$data['product_categories']	= array();
		}
		
		
		
		////////////////////////////////////////////////////////////////////////////////////////////////////

		//no error checking on these
		//$this->form_validation->set_rules('caption', 'Caption');
		//$this->form_validation->set_rules('primary_photo', 'Primary');

		//$this->form_validation->set_rules('sku', 'lang:sku', 'trim');
		//$this->form_validation->set_rules('seo_title', 'lang:seo_title', 'trim');
		//$this->form_validation->set_rules('meta', 'lang:meta_data', 'trim');
		$this->form_validation->set_rules('name', 'lang:name', 'trim|required|max_length[64]');
		//$this->form_validation->set_rules('slug', 'lang:slug', 'trim');
		$this->form_validation->set_rules('description', 'lang:description', 'trim');
		//$this->form_validation->set_rules('excerpt', 'lang:excerpt', 'trim');
		//$this->form_validation->set_rules('price', 'lang:price', 'trim|numeric|floatval');
		//$this->form_validation->set_rules('saleprice', 'lang:saleprice', 'trim|numeric|floatval');
		//$this->form_validation->set_rules('weight', 'lang:weight', 'trim|numeric|floatval');
		//$this->form_validation->set_rules('track_stock', 'lang:track_stock', 'trim|numeric');
		//$this->form_validation->set_rules('quantity', 'lang:quantity', 'trim|numeric');
		//$this->form_validation->set_rules('shippable', 'lang:shippable', 'trim|numeric');
		//$this->form_validation->set_rules('taxable', 'lang:taxable', 'trim|numeric');
		//$this->form_validation->set_rules('fixed_quantity', 'lang:fixed_quantity', 'trim|numeric');
		//$this->form_validation->set_rules('enabled', 'lang:enabled', 'trim|numeric');
		
		
		if($duplicate)
		{
			$data['id']	= false;
		}
		if($this->input->post('submit') == 'submit')
		{
			//reset the product options that were submitted in the post
			//$data['product_options']	= $this->input->post('option');
			//$data['related_products']	= $this->input->post('related_products');
			$data['product_categories']	= $this->input->post('categories');
			$data['images']				= $this->input->post('images');
			//$data['product_files']		= $this->input->post('downloads');
			
		}
		
		$data['customer'] = $customer;
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('add_item', $data);
		}
		else {
			
		$this->load->helper('text');
			
			//first check the slug field
			$slug = $this->input->post('slug');
			
			//if it's empty assign the name field
			if(empty($slug) || $slug=='')
			{
				$slug = $this->input->post('name');
			}
			
			$slug	= url_title(convert_accented_characters($slug), 'dash', TRUE);
			
			//validate the slug
			$this->load->model('Routes_model');

			if($id)
			{
				$slug		= $this->Routes_model->validate_slug($slug, $product->route_id);
				$route_id	= $product->route_id;
			}
			else
			{
				$slug	= $this->Routes_model->validate_slug($slug);
				
				$route['slug']	= $slug;	
				$route_id	= $this->Routes_model->save($route);
				$save['sku'] = $this->randomNumber(16); 
				$save['taxable']			= 0;
				$save['enabled']			= 1;
			
			}
			$save['user_id']			= $customer['id'];
			$save['id']					= $id;
			$save['name']				= $this->input->post('name');
			$save['seo_title']			= $this->input->post('seo_title');
			$save['meta']				= $this->input->post('meta');
			$save['description']		= $this->input->post('description');
			$save['excerpt']			= $this->input->post('excerpt');
			$save['price']				= $this->input->post('price');
			$save['saleprice']			= $this->input->post('saleprice');
			$save['weight']				= $this->input->post('weight');
			$save['track_stock']		= $this->input->post('track_stock');
			$save['fixed_quantity']		= $this->input->post('fixed_quantity');
			$save['quantity']			= $this->input->post('quantity');
			$save['shippable']			= $this->input->post('shippable');
			
			$save['condition'] 			= $this->input->post('condition');
			$post_images				= $this->input->post('images');
			
			
			$save['slug']				= $slug;
			//$save['route_id']			= $route_id;
			$save['cat_id']		= $this->input->post('category');
			$save['sub_category_id'] = $this->input->post('subcatlist');
			// check for exist other insert
			if($this->input->post('company_id')=='other'){
			$companyNmae =	$this->input->post('newcompany');
				if($companyNmae!=''){
				$save['company_id']=	$this->Product_model->get_compnyIdbyname($companyNmae);
				}
			} else  $save['company_id'] = $this->input->post('company_id');
			if($this->input->post('modellist')=='other' || $this->input->post('modellist')==''){
				 $modelNmae =	$this->input->post('newmodelname');	
				 $save['model_id']=	$this->Product_model->get_modelIdbyname($modelNmae,$save['company_id']);		
			} else $save['model_id'] = $this->input->post('modellist');
			
			if($save['model_id']==NULL){
			$save['model_id'] = 0;	
			}
			
			$save['images']				= json_encode($post_images);
			
			
			if($this->input->post('related_products'))
			{
				$save['related_products'] = json_encode($this->input->post('related_products'));
			}
			else
			{
				$save['related_products'] = '';
			}
			
			//save categories
			$categories			= $this->input->post('categories');
			if(!$categories)
			{
				$categories	= array();
			}
			
			// format options
			$options	= array();
			if($this->input->post('option'))
			{
				foreach ($this->input->post('option') as $option)
				{
					$options[]	= $option;
				}

			}	
			
			// save product 
			$product_id	= $this->Product_model->save($save, $options, $categories);
			
			// add file associations
			// clear existsing
			$this->Digital_Product_model->disassociate(false, $product_id);
			// save new
			//$downloads = $this->input->post('downloads');
//			if(is_array($downloads))
//			{
//				foreach($downloads as $d)
//				{
//					$this->Digital_Product_model->associate($d, $product_id);
//				}
//			}			

			////save the route
			$route['id']	= $route_id;
			$route['slug']	= $slug;
			$route['route']	= 'cart/product/'.$product_id;
			
			$this->Routes_model->save($route);
//			

// set message template message
			if($id){
			$res = $this->db->where('id', '12')->get('canned_messages');
			$row = $res->row_array();			
			// set replacement values for subject & body			
			// {customer_name}
			$row['subject'] = str_replace('{customer_name}', $customer['firstname'].' '. $customer['lastname'], $row['subject']);
			$row['content'] = str_replace('{customer_name}', $customer['firstname'].' '. $customer['lastname'], $row['content']);			
			// {url}
			$row['subject'] = str_replace('{url}', $this->config->item('base_url'), $row['subject']);
			$row['content'] = str_replace('{url}', $this->config->item('base_url'), $row['content']);			
			// {site_name}
			$row['subject'] = str_replace('{site_name}', $this->config->item('company_name'), $row['subject']);
			$row['content'] = str_replace('{site_name}', $this->config->item('company_name'), $row['content']);
			$row['content'] = str_replace('{product_name}', "<b>".$product->name."</b>", $row['content']);	
					
			$this->load->library('email');			
			$config['mailtype'] = 'html';			
			$this->email->initialize($config);	
			$this->email->from($this->config->item('email'), $this->config->item('company_name'));
			$this->email->to($customer['email']);
			$this->email->bcc($this->config->item('email'));
			$this->email->subject($row['subject']);
			$this->email->message(html_entity_decode($row['content']));			
			$this->email->send();	
			// send mail to admin for approval notification	
			
			$res = $this->db->where('id', '13')->get('canned_messages');
			$row = $res->row_array();			
			// set replacement values for subject & body			
			// {customer_name}
			$row['subject'] = str_replace('{customer_name}', $customer['firstname'].' '. $customer['lastname'], $row['subject']);
			$row['content'] = str_replace('{customer_name}', $customer['firstname'].' '. $customer['lastname'], $row['content']);			
			// {url}
			$row['subject'] = str_replace('{url}', $this->config->item('base_url'), $row['subject']);
			$row['content'] = str_replace('{url}', $this->config->item('base_url'), $row['content']);			
			// {site_name}
			$row['subject'] = str_replace('{site_name}', $this->config->item('company_name'), $row['subject']);
			$row['content'] = str_replace('{site_name}', $this->config->item('company_name'), $row['content']);
			$row['content'] = str_replace('{product_name}', "<b>".$product->name."</b>", $row['content']);	
					
			$this->load->library('email');			
			$config['mailtype'] = 'html';			
			$this->email->initialize($config);	
			$this->email->from($this->config->item('email'), $this->config->item('company_name'));
			$this->email->to($customer['email']);
			$this->email->bcc($this->config->item('email'));
			$this->email->subject($row['subject']);
			$this->email->message(html_entity_decode($row['content']));			
			$this->email->send();	
			
			}

// end template sending
			//$this->session->set_flashdata('message', lang('message_saved_product'));
			$this->common_model->setMessage(1,lang('message_saved_product'));
				//go back to the product list
			redirect('/myaccount/list_item');
		}	
		}
	
	public function ajaxrequest($id=false,$type=false, $returnType=false,$subcatid=false){
		$this->load->model(array('Option_model', 'Category_model','Product_model'));
                            $id = ($this->input->post('id')==''?$id:$this->input->post('id'));
                            $type = ($this->input->post('type')==''? $type:$this->input->post('type'));
                         
			if($type=='getsubcat'){
					$subcatlist =  $this->Category_model->get_categories($id);
					$str = null;
					if(count($subcatlist)>0){
						$str .= "<select name='subcatlist' id='subcatlist' class='text-box'>";						
						$str .= "<option value=''>Select Sub Category</option>";
					foreach($subcatlist as $subcatval):
					 $str.="<option value=".$subcatval->id."". (($returnType==true && $subcatval->id==$subcatid)?" selected":"").">".$subcatval->name."</option>";
					endforeach;
					$str .= "</select>";
					}
					if($str!=null){
					if($returnType==false){echo $str;} else {return $str;}
					} 
			}
			if($type=='getmodelcat'){
				$subcatlist =  $this->Product_model->get_model_list($id);
					$str = null;
					if(count($subcatlist)>0){
						$str = '<div class="styled-select"><select name="modellist" id="modelid" class="text-box" onChange="addNewModel(this.value)">';
					foreach($subcatlist as $subcatval):
					$str.="<option value=".$subcatval->model_id."". (($returnType==true && $subcatval->model_id==$subcatid)?" selected":"").">".$subcatval->model_name."</option>";
					endforeach;
					$str.= "<option value='other'>Other</option>";
					$str.= "</select></div>";
					}
					if($str!=null){
					if($returnType==false){echo $str;} else {return $str;}	;
					} else {
                                            	$str = '<input type="text" name="newmodelname" id="newmodelname" class="text-box" placeholder="Enter Model">';
					if($returnType==false){echo $str;} else {return $str;};
						}
				
			}
	}
	// end
	
	public function list_item($order_by="name", $sort_order="ASC", $code=0, $page=0, $rows=15){
		
		
		$this->load->model('Product_model');
		$this->load->helper('form');
		$this->lang->load('product');
		
		$data['page_title']	= lang('products');
		
		$data['code']		= $code;
		$term				= false;
		$category_id		= false;
		
		//get the category list for the drop menu
		$data['categories']	= $this->Category_model->get_categories_tierd();
		
		//$post				= $this->input->post(null, false);
		$customer = $this->go_cart->customer();
		$post  = array('user_id'=>$customer['id']); 
		$this->load->model('Search_model');
		if($post)
		{
			$term			= json_encode($post);
			$code			= $this->Search_model->record_term($term);
		}	
		//store the search term
		$data['term']		= $term;
		$data['order_by']	= $order_by;
		$data['sort_order']	= $sort_order;
		
		$data['products']	= $this->Product_model->get_my_products(array('term'=>$term, 'order_by'=>$order_by, 'sort_order'=>$sort_order, 'rows'=>$rows, 'page'=>$page));

		//total number of products
		$data['total']		= $this->Product_model->get_my_products(array('term'=>$term, 'order_by'=>$order_by, 'sort_order'=>$sort_order), true);

		
		$this->load->library('pagination');
		
		$config['base_url']			= site_url($this->config->item('admin_folder').'/products/index/'.$order_by.'/'.$sort_order.'/'.$code.'/');
		$config['total_rows']		= $data['total'];
		$config['per_page']			= $rows;
		$config['uri_segment']		= 7;
		$config['first_link']		= 'First';
		$config['first_tag_open']	= '<li>';
		$config['first_tag_close']	= '</li>';
		$config['last_link']		= 'Last';
		$config['last_tag_open']	= '<li>';
		$config['last_tag_close']	= '</li>';

		$config['full_tag_open']	= '<div class="pagination"><ul>';
		$config['full_tag_close']	= '</ul></div>';
		$config['cur_tag_open']		= '<li class="active"><a href="#">';
		$config['cur_tag_close']	= '</a></li>';
		
		$config['num_tag_open']		= '<li>';
		$config['num_tag_close']	= '</li>';
		
		$config['prev_link']		= '&laquo;';
		$config['prev_tag_open']	= '<li>';
		$config['prev_tag_close']	= '</li>';

		$config['next_link']		= '&raquo;';
		$config['next_tag_open']	= '<li>';
		$config['next_tag_close']	= '</li>';
		
		$this->pagination->initialize($config);
		
		$this->load->view('list_item', $data);
		
	}
	
	function thanks()
	{
		echo "Product Has been saved successfully";
		echo "<br>This page is under cunstruction";
		echo "<br><a href='".base_url()."secure/sale'>Go to Sale Page</a>";
	}
	
	public function remove_item($product_id = NULL){	
		if(empty($product_id)){
			$this->common_model->setMessage(3,'Item not exist');			
		} 
		$customer = $this->go_cart->customer();		
		$product	= $this->Product_model->get_user_product($product_id,$customer['id']);
		
		if($product==false){
			$this->common_model->setMessage(3,'Item not exist');
		} else{
				$this->Product_model->delete_product($product_id);
				$this->common_model->setMessage(1, 'Listed Item sucessfully deleted');			
		}
		
		redirect('/myaccount/list_item');
	}
	
	public  function randomNumber($length) {
    $result = '';
    for($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }

    return $result;
}

public function get_model_list(){
$id = ($this->input->post('id')==''?$id:$this->input->post('id'));
if($id!=''){
$subcatlist =  $this->Product_model->get_model_list($id);
					$str = null;
					if(count($subcatlist)>0){
						$str = '<select name="modelid" id="modelid"><option value="">Select model</option>';
					foreach($subcatlist as $subcatval):
					$str.="<option value=".$subcatval->model_id.">".$subcatval->model_name."</option>";
					endforeach;
					$str.= "</select>";
					echo $str;
					}
					}

}

public function open(){
echo "<b>Hello</b>";	
}
}