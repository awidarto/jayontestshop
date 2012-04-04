<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gantibaju extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->api_key = 'd85cbf10a6e42a457b475cdea9ae58ab195cfe4f';
	}
	
	public function index($appkey = null,$trxid = null){
		$this->load->library('curl');
		
		$url = $this->config->item('api_url').'add/'.$appkey.'/'.$trxid;

		$trx = array(
			'shipping_address'=>'Kompleks DKI D3 Joglo',
			'phone' => '02112345678',
			'cod_cost' => '0',
			'trx_detail'=>array(
				array(
					'unit_description'=>'kaos oblong swan',
					'unit_price'=>3000,
					'unit_quantity'=>100,
					'unit_total'=>280000,
					'unit_discount'=>20000
				),
				array(
					'unit_description'=>'kaos turtle neck',
					'unit_price'=>35000,
					'unit_quantity'=>2,
					'unit_total'=>70000,
					'unit_discount'=>0,
				),
				array(
					'unit_description'=>'kaos kutung',
					'unit_price'=>15000,
					'unit_quantity'=>10,
					'unit_total'=>150000,
					'unit_discount'=>0
				)
			)
		);
		
		$result = $this->curl->simple_post($url,array('transaction_detail'=>json_encode($trx)));
		
		print $result;
	}
	
	public function ajaxpost($api_key){
		$this->load->helper('string');
		$this->load->library('curl');
		
		/*
			for this example only :
			trx_id is a string of 16 digit randomly generated numbers
			transactions table is a table containing generated trx_id only, transaction detail is fixed and hardcoded below
			$trx_id = random_string('numeric',16);
		*/
		
		$trx_id = $this->input->post('trx_id');
		$buyer_name = $this->input->post('buyer_name');
		$recipient_name = $this->input->post('recipient_name');
		$shipping_address = $this->input->post('shipping_address');
		$email = $this->input->post('email');
		$zip = $this->input->post('zip');

		$zone = $this->input->post('buyerdeliveryzone');
		$zone = explode(',', $zone);

		$buyerdeliveryzone = $zone[0];
		$buyerdeliverycity = $zone[1];
		$directions = $this->input->post('directions');
		$buyerdeliverytime = $this->input->post('buyerdeliverytime');
		$phone = $this->input->post('phone');
		$status = $this->input->post('status');

		//$this->db->insert('transactions',array('trx_id'=>$trx_id));

		$url = $this->config->item('api_url').'post/'.$api_key.'/'.$trx_id;
		
		$trx = array(
			'api_key'=>$api_key,
			'transaction_id'=>$trx_id,
			'buyer_name'=>$buyer_name,
			'recipient_name'=>$recipient_name,
			'shipping_address'=>$shipping_address,
			'buyerdeliveryzone'=>$buyerdeliveryzone,
			'buyerdeliverycity'=>$buyerdeliverycity,
			'buyerdeliverytime'=>$buyerdeliverytime,
			'directions'=>$directions,
			'auto_confirm'=>false,
			'email'=>$email,
			'zip' => $zip,
			'phone' => $phone,
			'total_price'=>500000,
			'total_discount'=>20000,
			'total_tax'=>'117.500',
			'chargeable_amount'=>500000,
			'cod_cost' => '0', 		/* cod_cost 0 if absorbed in price of goods sold, otherwise specify the amount here*/
			'currency' => 'IDR', 	/* currency in 3 digit codes*/
			'status'=>$status, 	/* status can be : pending or confirm, depending on merchant's workflow */

			/*
				trx_detail should contain merchants transaction details for perticular session, below are just example
			*/

			'trx_detail'=>array( // 
				array(
					'unit_description'=>'kaos oblong swan',
					'unit_price'=>3000,
					'unit_quantity'=>100,
					'unit_total'=>280000,
					'unit_discount'=>20000
				),
				array(
					'unit_description'=>'kaos turtle neck',
					'unit_price'=>35000,
					'unit_quantity'=>2,
					'unit_total'=>70000,
					'unit_discount'=>0,
				),
				array(
					'unit_description'=>'kaos polo biru',
					'unit_price'=>135000,
					'unit_quantity'=>5,
					'unit_total'=>675000,
					'unit_discount'=>0,
				),
				array(
					'unit_description'=>'kaos kutung',
					'unit_price'=>15000,
					'unit_quantity'=>10,
					'unit_total'=>150000,
					'unit_discount'=>0
				)
			)
		);
		
		$result = $this->curl->simple_post($url,array('transaction_detail'=>json_encode($trx)));

		
		print $result;

		//file_put_contents('post_result.txt', $result);
		//file_put_contents('sample.json', json_encode($trx));
	}	
	
	public function plaincheckout(){
		$this->load->helper('string');

		$trx_id = random_string('numeric',16);
		
		$this->db->insert('transactions',array('trx_id'=>$trx_id));
		
		$data['checkoutlink'] = anchor_popup($this->config->item('member_url').'buy/trx/'.$this->api_key.'/'.$trx_id,'COD by Jayon');

		$this->load->view('shoppingtest',$data);
		
	}

	public function checkout(){
		$this->load->helper('string');
		
		$this->load->config('zones');
		$this->load->helper('form');
		$this->load->library('curl');
		

		$trx_id = random_string('numeric',16);
		
		$this->db->insert('transactions',array('trx_id'=>$trx_id));
		
		$data['trx_id'] = $trx_id;
		
		$data['api_key'] = $this->api_key;

		$url = $this->config->item('api_url').'tsget/'.$this->api_key;

		$dateblock = $this->curl->simple_post($url);

		$dateblock = json_decode($dateblock);

		$dateblock = $dateblock->timeslot;

		$dateblock = json_encode($dateblock);

		//$dateblock = str_replace("\\", "",$dateblock);

		$data['dateblock'] = $dateblock;

		$this->load->view('process',$data);
	}
	
	public function jayonfetch($api_key,$trx_id){
		$trx = array(
			'api_key'=>$api_key,
			'transaction_id'=>$trx_id,
			'shipping_address'=>'Kompleks DKI D3 Joglo',
			'phone' => '02112345678',
			'cod_charges' => '0',
			'trx_detail'=>array(
				array(
					'unit_description'=>'kaos oblong swan',
					'unit_price'=>3000,
					'unit_quantity'=>100,
					'unit_total'=>280000,
					'unit_discount'=>20000
				),
				array(
					'unit_description'=>'kaos turtle neck',
					'unit_price'=>35000,
					'unit_quantity'=>2,
					'unit_total'=>70000,
					'unit_discount'=>0,
				),
				array(
					'unit_description'=>'kaos kutung',
					'unit_price'=>15000,
					'unit_quantity'=>10,
					'unit_total'=>150000,
					'unit_discount'=>0
				)
			)
		);
		
		print json_encode($trx);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */