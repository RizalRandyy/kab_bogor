<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Dashboard extends My_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('dashboard_model', 'models');
	}

	public function index_get()
	{
	    // Initialize the array with a 'title' element for use for the <title> tag.
		$this->data['title'] = 'Dashboard';
		$this->data['version'] = $this->uri->segment(1);
		$this->data['page'] = 'dashboard';
		$this->data['js'] = array(
			'assets/js/app/dashboard.js?'.rand(),
			'assets/plugins/amcharts4/core.js',
			'assets/plugins/amcharts4/charts.js',
			'assets/plugins/amcharts4/themes/animated.js',
			'assets/plugins/sweetalert2/dist/sweetalert2.min.js',
		);	

		$this->data['css'] = array(
			'assets/plugins/sweetalert2/dist/sweetalert2.min.css',
		);

        $this->data['users'] = $this->data['users'];
		$this->template->load($this->data, null, 'index', null, 'frontend');
	}

	public function data_get()
	{
		$data = $this->models->getData();
		if (!$data) : 

			$this->set_response(
	        	array(
	        		
	        	  'status' => 500,
	        	  'icon' => 'failed',
	              'message'	=> 'Data tidak ada!',
	              'data' => []
	        	)
	        );	

		else :

			$this->set_response(
	        	array(

	        	  'status' => 200,
	        	  'icon' => 'success',
	              'message'	=> '',
	              'data' => $data
	        	)
	        );	

	    endif;
	}

	public function getDataLokasi_get()
	{
		$return = $this->models->getDataLokasi($this->get(NULL, TRUE),$this->data['users']);
		$return['header'] = $this->models->getheader();

		$this->response($return, 200);
	}

}
