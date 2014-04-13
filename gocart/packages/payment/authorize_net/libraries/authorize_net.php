<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authorize_net
{
	var $CI;
	
	//this can be used in several places
	var	$method_name	= 'Charge by Credit Card';
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper("credit_card");
		$this->CI->load->library('authorize_net_lib');
		$this->CI->lang->load('authorize_net');
		
	}
	
	/*
	checkout_form()
	this function returns an array, the first part being the name of the payment type
	that will show up beside the radio button the next value will be the actual form if there is no form, then it should equal false
	there is also the posibility that this payment method is not approved for this purchase. in that case, it should return a blank array 
	*/
	
	//these are the front end form and check functions
	function checkout_form($post = false)
	{
		$settings	= $this->CI->Settings_model->get_settings('Authorize_net');
		$enabled	= $settings['enabled'];
		
		$form			= array();
		$form['name']	= $this->method_name;
		
		// Retrieve any previously stored cc data to redisplay in case of errors
		$cc_data = $this->CI->session->userdata('cc_data');
		
		if($enabled)
		{
			
			$form['form'] = $this->CI->load->view('customer_card', array('cc_data'=>$cc_data), true);
			
			return $form;
			
		} else return array();
		
	}
	
	function checkout_check()
	{
		
		$error_msg = lang('please_fix_errors').'<BR><UL>';
		$error_list = "";
		
		//Verify name field
		if( empty($_POST["x_first_name"]) || empty($_POST["x_last_name"])) 
			$error_list .= '<LI>'.lang('enter_card_name').'</LI>';
		
		//Verify date
		if( !card_expiry_valid($_POST["x_exp_date_mm"], $_POST["x_exp_date_yy"]) )
			$error_list .= '<LI>'.lang('invalid_card_exp').'</LI>';
			
		//Verify card number
		if( empty($_POST["x_card_num"]) || !card_number_valid($_POST["x_card_num"]) )
			$error_list .= '<LI>'.lang('invalid_card_num').'</LI>';
		
		//Verify security code
		if( empty($_POST["x_card_code"])) 
			$error_list .= '<LI>'.lang('enter_card_code').'</LI>';
		
		
		// We need to store the credit card information temporarily
		$cc_tmp_data["cc_data"] = $_POST;
		$this->CI->session->set_userdata($cc_tmp_data);
		
		if( $error_list ) 
			return $error_msg . $error_list . "</UL>";
		else 
		{
			return false;
		}
	}
	
	function description()
	{
		//create a description from the session which we can store in the database
		//this will be added to the database upon order confirmation
		
		/*
		access the payment information with the  $_POST variable since this is called
		from the same place as the checkout_check above.
		*/
		
	//	return 'Authorize.net Credit Card Instant Processing';
		return 'Credit Card';
		/*
		for a credit card, this may look something like
		
		$payment['description']	= 'Card Type: Visa
		Name on Card: John Doe<br/>
		Card Number: XXXX-XXXX-XXXX-9976<br/>
		Expires: 10/12<br/>';
		*/	
	}
	
	//back end installation functions
	function install()
	{
		//set default settings
		// -These will be user-editable
		$config['authorize_net_test_mode'] = 'TRUE'; // Set this to FALSE for live processing

		$config['authorize_net_live_x_login'] = '';
		$config['authorize_net_live_x_tran_key'] = '';		
		
		$config['authorize_net_test_x_login'] = '';
		$config['authorize_net_test_x_tran_key'] = '';
		
		
		// Lets setup some other values so we dont have to do it everytime we process a transaction
		//  - These are not user editable
		$config['authorize_net_test_api_host'] = 'https://test.authorize.net/gateway/transact.dll';
		$config['authorize_net_live_api_host'] = 'https://secure.authorize.net/gateway/transact.dll';
		$config['authorize_net_x_version'] = '3.1';
		$config['authorize_net_x_type'] = 'AUTH_CAPTURE';
		$config['authorize_net_x_relay_response'] = 'FALSE';
		$config['authorize_net_x_delim_data'] = 'TRUE';
		$config['authorize_net_x_delim_char'] = '|';
		$config['authorize_net_x_encap_char'] = '';
		$config['authorize_net_x_url'] = 'FALSE';
		
		$config['authorize_net_x_method'] = 'CC';
		
		$config['enabled'] = '0';
		
		$this->CI->Settings_model->save_settings('Authorize_net', $config);
	}
	
	function uninstall()
	{
		$this->CI->Settings_model->delete_settings('Authorize_net');
	}
	
	//payment processor
	function process_payment()
	{
		
		// Get previously entered customer info
		$cc_data = $this->CI->session->userdata('cc_data');
		$customer = $this->CI->go_cart->customer();
		
		
		// Set our authnet fields
        $this->CI->authorize_net_lib->add_x_field('x_first_name', $cc_data["x_first_name"]);
        $this->CI->authorize_net_lib->add_x_field('x_last_name', $cc_data["x_last_name"]);
        $this->CI->authorize_net_lib->add_x_field('x_address', $customer['bill_address']["address1"]. $customer['bill_address']["address2"]);
        $this->CI->authorize_net_lib->add_x_field('x_city', $customer['bill_address']["city"]);
        $this->CI->authorize_net_lib->add_x_field('x_state', $customer['bill_address']["zone"]);
        $this->CI->authorize_net_lib->add_x_field('x_zip', $customer['bill_address']["zip"]);
        $this->CI->authorize_net_lib->add_x_field('x_country', $customer['bill_address']["city"]);
        $this->CI->authorize_net_lib->add_x_field('x_email', $customer['bill_address']["email"]);
        $this->CI->authorize_net_lib->add_x_field('x_phone', $customer['bill_address']["phone"]);
        
        /**
		 * To test: 
         * Use credit card number 4111111111111111 for a good transaction
         * Use credit card number 4111111111111122 for a bad card
         */
        $this->CI->authorize_net_lib->add_x_field('x_card_num', $cc_data["x_card_num"]);
        
        $this->CI->authorize_net_lib->add_x_field('x_amount', $this->CI->go_cart->total()); 
        $this->CI->authorize_net_lib->add_x_field('x_exp_date', $cc_data["x_exp_date_mm"] . $cc_data["x_exp_date_yy"]);    // MM.YY
        $this->CI->authorize_net_lib->add_x_field('x_card_code', $cc_data["x_card_code"]);
        
   		// Send info to authorize.net and receive a response
		$this->CI->authorize_net_lib->process_payment();
        $authnet_response = $this->CI->authorize_net_lib->get_all_response_codes();
		
		// Forward results
        if($authnet_response['Response_Code'] == '1') 
		{    
            // payment success, we can destroy our tmp card data
			$this->CI->session->unset_userdata('cc_data');
			return $authnet_response;   // false == no error
        }
		else 
		{
            // payment declined, return our user to the form with an error.
			//return lang('transaction_declined');
			
			switch($authnet_response['Card_Code_CVV_Response Code'])
			{
			case 'N' :
			$ccresponse= 'The card code verification (CCV) Not Matched';
			break;
			case 'P' :
			$ccresponse= 'The card code verification (CCV) Not Processed';
			break;
			case 'S' :
			$ccresponse= 'The card code verification (CCV) Not Matched';
			break;
			case 'U' :
			$ccresponse= 'The card code verification (CCV) Issuer unable to process request';
			break;
			case '' :
			$ccresponse= 'The card code verification (CCV) Not Processed';
			break;
			}
			
			
			switch($authnet_response['AVS_Result_Code'])
			{
			case 'A' :
			$avsresponse= 'The Address Verification Service (AVS) Address (Street) matches, ZIP does not';
			break;
			case 'B' :
			$avsresponse= 'Address information not provided for AVS check';
			break;
			case 'E' :
			$avsresponse= 'AVS error';
			break;
			case 'G' :
			$avsresponse= 'Non-U.S. Card Issuing Bank';
			break;
			case 'N' :
			$avsresponse= 'No Match on Address (Street) or ZIP';
			break;
			case 'P' :
			$avsresponse= 'AVS not applicable for this transaction';
			break;
			case 'R' :
			$avsresponse= 'Retry—System unavailable or timed out';
			break;
			case 'S' :
			$avsresponse= 'Service not supported by issuer';
			break;
			case 'U' :
			$avsresponse= 'Address information is unavailable';
			break;
			case 'W' :
			$avsresponse= 'Nine digit ZIP matches, Address (Street) does not';
			break;
			case 'X' :
			$avsresponse= 'Address (Street) and nine digit ZIP match';
			break;
			case 'Y' :
			$avsresponse= 'Address (Street) and five digit ZIP match';
			break;
			case 'Z' :
			$avsresponse= 'Five digit ZIP matches, Address (Street) does not';
			break;
			case '' :
			$avsresponse= 'AVS error';
			break;
			}
			
			
			switch($authnet_response['Response_Code'])
			{
			case '3' :
			$responseCode= 'Error in Transaction, Some Thing Went Wrong';
			break;
			case '4' :
			$responseCode= 'Transaction is Held for Review';
			break;
			}
			
			switch($authnet_response['Cardholder_Authentication_Verification_Value_CAVV_Response_Code'])
			{
			case '0' :
			$cavvResponse= 'CAVV not validated because erroneous data was submitted';
			break;
			case '1' :
			$cavvResponse= 'CAVV failed validation';
			break;
			case '3' :
			$cavvResponse= 'CAVV validation could not be performed; issuer attempt incomplete';
			break;
			case '4' :
			$cavvResponse= 'CAVV validation could not be performed; issuer system error';
			break;
			case '5' :
			$cavvResponse= 'Reserved for future use';
			break;
			case '6' :
			$cavvResponse= 'Reserved for future use';
			break;
			case '7' :
			$cavvResponse= 'CAVV attempt – failed validation – issuer available (U.S.- issued card/non-U.S acquirer)';
			break;
			case '8' :
			$cavvResponse= 'CAVV attempt – passed validation – issuer available (U.S.- issued card/non-U.S. acquirer)';
			break;
			case '9' :
			$cavvResponse= 'CAVV attempt – failed validation – issuer unavailable (U.S.- issued card/non-U.S. acquirer)';
			break;
			case 'A' :
			$cavvResponse= 'CAVV attempt – passed validation – issuer unavailable (U.S.- issued card/non-U.S. acquirer)';
			break;
			case 'B' :
			$cavvResponse= 'CAVV passed validation, information only, no liability shift';
			break;
			case '' :
			$cavvResponse= 'Cardholder Authentication Verification Value(CAVV) not validated';
			break;
			}
			$response=array('done'=>'0',
			'error'=>$responseCode,
			'cavvResponse'=>$cavvResponse,
			'avsResponse'=>$avsresponse,
			'ccResponse'=>$ccresponse,
			'whole_responce'=>$authnet_response);
			
			return $response;
			
			
			
			
			
			
			                        
        }
   
	}
	
	//admin end form and check functions
	function form($post	= false)
	{
		//this same function processes the form
		    // what about check() ?? - GDA
		if(!$post)
		{
			$settings	= $this->CI->Settings_model->get_settings('Authorize_net');
		}
		else
		{
			$settings = $post;
		}
		
		return $this->CI->load->view('auth_net_form', array('settings'=>$settings), true);
	}
	
	
	function check()
	{	
		$error	= false;
		
		if ( $_POST["authorize_net_test_mode"]=="TRUE" )
		{
			if(empty($_POST["authorize_net_test_x_login"]) || empty($_POST["authorize_net_test_x_tran_key"]) ) 
			{
				$error = lang('enter_test_mode_credentials');
			}
		} 
		else 
		{
			if(empty($_POST["authorize_net_live_x_login"]) || empty($_POST["authorize_net_live_x_tran_key"]) ) 
			{
				$error = lang('enter_live_mode_credentials');
			}
		}
		
		//forward the error
		if($error)
		{
			return $error;
		}
		else
		{				
			//Save
			$this->CI->Settings_model->save_settings('Authorize_net', $_POST);
			return false;
		}
	}
	
}