<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {


	public $api_client_name = 'i4 Asia Incorporated';
	public $api_client_id = '7bc80b4445d841b681399941a007e491';
	public $api_client_secret = '55a77ad8cfd849fda4e5b030048364bc';

	public $sandbox_ninjavan_api_url = 'https://api-sandbox.ninjavan.co/sg/oauth/access_token?grant_type=client_credentials';
	public $live_ninjavan_api_url = 'https://api.ninjavan.co/sg/oauth/access_token?grant_type=client_credentials';

	public $access_token = '3VWwEl6gZ0ASZzrqW7obbd9bLmnSORbIGhaC9oRr';

	public $sandbox = true;

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

	public function order(){

		
	   // "https://api-sandbox.ninjavan.co/sg/3.0/orders";

	   $headers = array();
    $headers[0] = 'Authorization: Bearer 3VWwEl6gZ0ASZzrqW7obbd9bLmnSORbIGhaC9oRr';
    $headers[1] = 'Content-type: application/json';
    
    $order_data = array();
    $order_data['from_postcode'] = "159363";
    $order_data['from_address1'] = "Kim Namjoon House";
    $order_data['from_address2'] = "House of ARMY";
    $order_data['from_city'] = "KR";
    $order_data['from_country'] = "KR";
    $order_data['from_email'] = "bts@ibighit.com";
    $order_data['from_name'] = "Wooji Jung";
    $order_data['from_contact'] = "99001122";
    $order_data['to_postcode'] = "318993";
    $order_data['to_address1'] = "BTS PH ARMYs";
    $order_data['to_address2'] = "BTR KR ARMYs";
    $order_data['to_locality'] = "PH";
    $order_data['to_city'] = "KR";
    $order_data['to_country'] = "SG";
    $order_data['to_email'] = "egrava@i4asiacorp.com";
    $order_data['to_name'] = "PARK JIMIN";
    $order_data['to_contact'] = "99110022";
    $order_data['delivery_date'] = "2017-09-22";
    $order_data['pickup_date'] = "2017-09-22";
    $order_data['pickup_weekend'] = true;
    $order_data['delivery_weekend'] = false;
    $order_data['staging'] = false;
    $order_data['pickup_timewindow_id'] = 1;
    $order_data['delivery_timewindow_id'] = 2;
    $order_data['max_delivery_days'] = 1;
    $order_data['cod_goods'] = 35.50;
    $order_data['pickup_instuction'] = "PH ARMYs cant come to the phone right now.";
    $order_data['delivery_instuction'] = "Beacause everybody is dead waiting for comeback.";
    $order_data['requested_tracking_id'] = "98715";
    $order_data['order_ref_no'] = "68458";
    $order_data['type'] = "NORMAL";
    $order_data['parcel_size'] = 1;
    $order_data['parcel_volume'] = 4000;
    $order_data['parcel_weight'] = 1200;
 
	$order_json = array();
	$order_json[] = $order_data;
    
## Note that this is the production API endpoint
    $ch = curl_init("https://api-sandbox.ninjavan.co/sg/3.0/orders");
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
        } else{
## Get Order by ID API 
            $header = array();
            $header[0] = 'Authorization: Bearer 3VWwEl6gZ0ASZzrqW7obbd9bLmnSORbIGhaC9oRr';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api-sandbox.ninjavan.co/sg/2.0/orders/".$parcel_id);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, '3');
            $body = curl_exec($ch);
     
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            print_r($body);
     
## Order was created successfully and the Order object is returned, hence $body will not be null
            if($http_code == 200 && $body != ""){
        
## The created Order's tracking ID and other details can be stored in db if needed
                $order_tracking_id = json_decode($body)->tracking_id;
                $order_shipper_ref_no = json_decode($body)->shipper_ref_no;
                $order_customer_ref_no = json_decode($body)->customer_ref_no;
                return array('httpCode' => $http_code, 'Created Tracking ID: ' => json_decode($body)->tracking_id);
            
## Order was not created successfully. Check the Order JSON that you sent for order creation.
            } else if($http_code == 200 && $body == ""){
                return array('httpCode' => $http_code, 'body' => "Order not created successfully!");
            }
        }
    } else{
        return array('httpCode' => $http_code, 'body' => "Order not created successfully!");
    }


    // Array ( [0] => stdClass Object ( [id] => f7949e78-72ea-4c8c-a85c-bc8402866c76 [status] => SUCCESS [message] => Order Creation Successful. [order_ref_no] => 9012 [tracking_id] => NVSGI4ASI00090843A ) )

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