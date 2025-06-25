<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_log extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('User_log_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'User Log';
		$this->data['page'] = 'user_log';
		$this->data['version'] = 'user_log';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css'
		];

		$this->data['js'] = array(
			'assets/js/app/user_log.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'index');
	}

	public function getData_get()
	{
		$formdata = $this->input->get(NULL, TRUE);

		$keyresult = (array)json_decode($formdata['keyword']);
		$keyresult['date'] = @$formdata['date'] != null ? date('Y-m-d', strtotime($formdata['date'])) : date('Y-m-d');

		$formdata['keyword'] = json_encode($keyresult);
		
		$return = $this->User_log_model->getData($formdata,$this->data['users']);
		$return['header'] = $this->User_log_model->getheader();

		$this->response($return, 200);
	}
}
