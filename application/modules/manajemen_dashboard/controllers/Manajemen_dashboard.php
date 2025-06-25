<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manajemen_dashboard extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Manajemen_dashboard_model');
	}

	public function getData_get()
	{
		$return = $this->Manajemen_dashboard_model->getReqById($this->get('id'),$this->data['users']);

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function index_get()
	{
		$this->data['title'] = 'Manajemen Dashboard';
		$this->data['page'] = 'tambah_manajemen_dashboard';
		$this->data['version'] = $this->uri->segment(2);
		$this->data['id'] = @$this->get('id')?:null;

		$this->data['js'] = array(
			'assets/js/app/tambah_manajemen_dashboard.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js',
			'assets/plugins/inputtags/js/bootstrap-tagsinput.js',
			'assets/plugins/select2/dist/js/select2.min.js'
		);

		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css',
			'assets/plugins/inputtags/css/bootstrap-tagsinput.css',
			'assets/plugins/select2/dist/css/select2.css'
		];

		$this->template->load($this->data, null, 'form');
	}

	public function kegiatan_get()
	{
		$return = $this->Manajemen_dashboard_model->getkegiatan();

		$this->response($return, 200);
	}

	public function saveData_post()
	{
		$params = $this->post(NULL, TRUE);

		$idItem = explode(",",$params['idItem']);

		$params['idItem'] = json_encode($idItem);

		$params['updated_by'] = decrypt_url($this->data['users']['id']);
		$params['updated_at'] = date('Y-m-d H:i:s');

		$return = $this->Manajemen_dashboard_model->saveData($params);

		$this->response($return, $return['status']);
	}

	public function getById_get()
	{
		$return = $this->Manajemen_dashboard_model->getReqById($this->get('id'),$this->data['users']);

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function deleteData_post()
	{
		$return = $this->Manajemen_dashboard_model->deleteReq($this->post('id', TRUE));
		$this->response($return, $return['status']);
	}
}
