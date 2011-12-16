<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gantibaju extends CI_Controller {

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
	
	function __construct(){
		parent::__construct();
		$this->api_key = 'c07c4f4b23b770c2d4e8d6d108b5c304472df238';
	}
	
	public function index($appkey = null,$trxid = null){
		$this->load->library('curl');
		
		$url = 'http://localhost/jayonadmin/api/v1/add/'.$appkey.'/'.$trxid;
		
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
	
	public function checkout(){
		$this->load->helper('string');

		$trx_id = random_string('numeric',16);
		
		$this->db->insert('transactions',array('trx_id'=>$trx_id));
		
		$data['checkoutlink'] = anchor_popup('http://localhost/jayonmember/buy/trx/'.$this->api_key.'/'.$trx_id,'COD by Jayon');

		$this->load->view('shoppingtest',$data);
		
	}
	
	public function jayon(){
		
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