<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {


	public $api_client_name = 'i4 Asia Incorporated';
	public $api_client_id = '7bc80b4445d841b681399941a007e491';
	public $api_client_secret = '55a77ad8cfd849fda4e5b030048364bc';

	public $sandbox_ninjavan_api_url = 'https://api-sandbox.ninjavan.co/sg/oauth/access_token?grant_type=client_credentials';
	public $live_ninjavan_api_url = 'https://api.ninjavan.co/sg/oauth/access_token?grant_type=client_credentials';

	public $access_token = '3VWwEl6gZ0ASZzrqW7obbd9bLmnSORbIGhaC9oRr';

	public $sandbox = true;
	public $order_api = 'https://api-sandbox.ninjavan.co/sg/3.0/orders';
	public $delete_order_api = 'https://api-sandbox.ninjavan.co/sg/2.0/orders/';
	public $track_order_api = 'https://api-sandbox.ninjavan.co/sg/2.0/track';
	public $get_waybill = 'https://api-sandbox.ninjavan.co/sg/2.0/reports/waybill';

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}


	public function get_waybill_print() {

		$headers = array();
	    $headers[0] = 'Authorization: Bearer 3VWwEl6gZ0ASZzrqW7obbd9bLmnSORbIGhaC9oRr';
	    $headers[1] = 'Content-type: application/json';


	    $tids  = 'NVSGI4ASI000BIGHIT';  

	    $waybill_data = array(
	    	'tids'			=> $tids, 
	    	'h' 			=> 0, 	
	    	's' 			=> 'A4',
		);

		$postData = '';
		foreach($waybill_data as $k => $v) {

	      $postData .= $k . '='.$v.'&'; 
	   }
	   $postData = rtrim($postData, '&');

	   	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->get_waybill.'?'.$postData);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Ninjavan cURL Request');

	    $body = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);

	    print_r($body);

		if($http_code == 200){
	        $body = json_decode($body);
	        print_r($body);
	    }

	}



	public function track_order(){

		$headers = array();
	    $headers[0] = 'Authorization: Bearer 3VWwEl6gZ0ASZzrqW7obbd9bLmnSORbIGhaC9oRr';
	    $headers[1] = 'Content-type: application/json';


		$order_data = array (
			'trackingIds' => array(
		    	 'NVSGI4ASI000000003', 
		    	 'NVSGI4ASI000BIGHIT', 
		    ),
	    );
	    
		$order_json = array();
		$order_json = $order_data;

	   	$ch = curl_init($this->track_order_api);
	    
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_json));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Codular Sample cURL Request');
	    
	    $body = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);

		## The Order was successfully received and is being processed. Order ID is returned.
	    
	    if($http_code == 200){
	        $body = json_decode($body);
	        print_r($body);
	    }

	}


	public function delete_order() {

	 	$headers = array();
	    $headers[0] = 'Authorization: Bearer 3VWwEl6gZ0ASZzrqW7obbd9bLmnSORbIGhaC9oRr';
	    $headers[1] = 'Content-type: application/json';

	    $order_id  = '7266de2a-5cbe-4b91-9a12-7b13ae0c5b78'; 

    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->delete_order_api.$order_id);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Codular Sample cURL Request');

		$body = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);

		if($http_code == 200){
	        $body = json_decode($body);
	        print_r($body);
	    }
	}


	public function get_order(){


	    $headers = array();
	    $headers[0] = 'Authorization: Bearer 3VWwEl6gZ0ASZzrqW7obbd9bLmnSORbIGhaC9oRr';
	    $headers[1] = 'Content-type: application/json';


	    $order_ref_no  			= null;
	    $requested_tracking_id  = null;
	    $tracking_id  			= 'NVSGI4ASI000BIGHIT';
	    $order_id  				= ''; //'6f27b5a5-e781-4931-997a-969c6247d180'; // 209a0ffc-7edd-4761-89e5-86b79b0e14f2 
	  

	    $order_data = array(
	    	'order_ref_no' 				=> $order_ref_no, 	
	    	'requested_tracking_id' 	=> $requested_tracking_id,	
	    	'tracking_id'				=> $tracking_id, 
	    	'order_id'					=> $order_id
		);

		$postData = '';
		foreach($order_data as $k => $v) {

	      $postData .= $k . '='.$v.'&'; 
	   }
	   $postData = rtrim($postData, '&');


	   	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->order_api.'?'.$postData);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Codular Sample cURL Request');

	    $body = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);

		if($http_code == 200){
	        $body = json_decode($body);
	        print_r($body);
	    }

	}


	public function post_order(){
		
		$headers = array();
	    $headers[0] = 'Authorization: Bearer 3VWwEl6gZ0ASZzrqW7obbd9bLmnSORbIGhaC9oRr';
	    $headers[1] = 'Content-type: application/json';

	    $from_postcode  = '652874';
	    $from_address1  = 'Strata 200';
	    $from_address2  = '';
	    $from_locality  = 'Metro Manila';
	    $from_city 		= 'Pasig City';
	    $from_state		= 'Metro Manila';
	    $from_country	= 'Philippines';
	    $from_email 	= 'phasia@ninjavan.com';
	    $from_name		= 'i4Asia';
	    $from_contact	= '0535515';

	    $to_postcode  	= '885415';
	    $to_address1  	= '35 Somewhere in Makati';
	    $to_address2  	= '';
	    $to_locality  	= 'Metro Manila';
	    $to_city 		= 'Makati City';
	    $to_state		= 'Metro Manila';
	    $to_country		= 'Philippines';
	    $to_email 		= 'somewhre@makati.com';
	    $to_name		= 'Jung Hoseok';
	    $to_contact		= '0966321578';
	    
	    $pickup_date 	= '2017-09-20';
	    $delivery_date  = '2017-09-23';
	    $pickup_weekend = true; 
	    $delivery_weekend = true;
	    $staging 		= false;
	    $pickup_timewindow_id = 1;
	    $delivery_timewindow_id = 2;
	    $max_delivery_days = 0;
	    $cod_goods = 0;
	    $insured_value = null;
	    $pickup_instruction = 'Pickup with care';
	    $delivery_instruction = 'Please deliver with care';
	    $requested_tracking_id = null;
	    $order_ref_no = null;
	    $type = 'NORMAL';
	    $parcel_size = 0;
	    $parcel_volume = 200;
	    $parcel_weight = '1.5';


	    $order_data = array(

	    	'from_postcode' 	=> $from_postcode, 	// @string @required - 6 digits
	    	'from_address1' 	=> $from_address1,	// @string @required - pick up address
	    	'from_address2'		=> $from_address2, 	// @string -optional - pick up address2
	    	'from_locality'		=> $from_locality, 	// @string -optional - district, community, province (Metro Manila)
	    	'from_city'			=> $from_city, 		// @string @required - city
	    	'from_state'		=> $from_state,		// @string -optional 
	    	'from_country'		=> $from_country,	// @string @required
			'from_email'		=> $from_email, 	
			'from_name'			=> $from_name,
			'from_contact'		=> $from_contact,

			'to_postcode' 		=> $to_postcode, 	// @string @required - 6 digits
	    	'to_address1' 		=> $to_address1,	// @string @required - pick up address
	    	'to_address2'		=> $to_address2, 	// @string -optional - pick up address2
	    	'to_locality'		=> $to_locality, 	// @string -optional - district, community, province (Metro Manila)
	    	'to_city'			=> $to_city, 		// @string @required - city
	    	'to_state'			=> $to_state,		// @string -optional 
	    	'to_country'		=> $to_country,		// @string @required
			'to_email'			=> $to_email, 	
			'to_name'			=> $to_name,
			'to_contact'		=> $to_contact,

			'delivery_date'				=> $delivery_date, 		// @string @required - YYYY-MM-DD (for same day set it = to pickup_date);
			'pickup_date'				=> $pickup_date,		// @string @required
			'pickup_weekend'			=> $pickup_weekend, 	// @bool -optional (order can be picked up on saturday)
			'delivery_weekend'			=> $delivery_weekend, 	// @bool -optional (order can be delivered up on saturday)
			'staging'					=> $staging,			// upon creation order will be 'staging' status pending 
			'pickup_timewindow_id'		=> $pickup_timewindow_id, // @int @required
			'delivery_timewindow_id'	=> $delivery_timewindow_id, // @int @required
			'max_delivery_days'			=> $max_delivery_days,	// @inteter @required
			'cod_goods'					=> $cod_goods,			// @decimal -optional
			'insured_value'				=> $insured_value,
			'pickup_instruction'		=> $pickup_instruction,
			'delivery_instruction'		=> $delivery_instruction,
			'requested_tracking_id'		=> $requested_tracking_id,
			'order_ref_no'				=> $order_ref_no,
			'type'						=> $type,
			'parcel_size'				=> $parcel_size,
			'parcel_volume'				=> $parcel_volume,
			'parcel_weight'				=> $parcel_weight,

	    );
	    

	    // $order_data['pickup_reach_by'] = "2017-09-22 15:00:00";
	    // $order_data['delivery_reach_by'] = "2017-09-23 17:00:00";
	   
	  
		$order_json = array();
		$order_json[] = $order_data;
   
	    $ch = curl_init($this->order_api);
	    
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_json));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	    
	    $body = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);

		## The Order was successfully received and is being processed. Order ID is returned.
	    
	    if($http_code == 200){
	        $body = json_decode($body);
	        print_r($body);
	 
			## The Order ID/ Order reference no along with the order creation status is returned. This can be stored in db if needed.
	        $parcel_id = $body[0]->id;
	        $parcel_creation_status = $body[0]->status;
	        $parcel_message = $body[0]->message;
	        $parcel_order_ref_no = $body[0]->order_ref_no;
	 
	        if($parcel_message == "ERROR"){
	            print_r($parcel_message);
	        } 
	    }

	}



	public function authenticate() {

		echo $response = $this->get_api_token();
	
	}


	public function get_api_token() {

		if($this->sandbox){
			$ninjavan_api_url = $this->sandbox_ninjavan_api_url;
		} else {
			$ninjavan_api_url = $this->live_ninjavan_api_url;
		}
		
	    $postData = array(
	        	'client_id' => $this->api_client_id,
				'client_secret' =>  $this->api_client_secret,
	        );

	    $content_type = array('Content-Type: application/x-www-form-urlencoded');

	    $handler = curl_init();

	    curl_setopt($handler, CURLOPT_HTTPHEADER, $content_type);

	    curl_setopt($handler, CURLOPT_URL, $ninjavan_api_url);
	    curl_setopt($handler, CURLOPT_POSTFIELDS, http_build_query($postData));
	    curl_setopt($handler, CURLOPT_POST, true);
	    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);

	    $token =  curl_exec($handler);
		curl_close($handler);

		$result = json_decode($token, true);

		if(isset ($result['code']) AND $result['code'] == 400){

			$base = base_url();
	    	redirect($base);
		}

		return $result['access_token'];	

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */