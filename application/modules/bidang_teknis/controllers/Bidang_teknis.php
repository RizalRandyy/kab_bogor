<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bidang_teknis extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Bidang_teknis_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Bidang Teknis';
		$this->data['page'] = 'bidang_teknis';
		$this->data['version'] = 'bidang_teknis';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css'
		];

		$this->data['js'] = array(
			'assets/js/app/bidang_teknis.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'index');
	}

	public function getData_get()
	{
		$return = $this->Bidang_teknis_model->getData($this->get(),$this->data['users']);
		$return['header'] = $this->Bidang_teknis_model->getheader();

		$this->response($return, 200);
	}

	public function form_get()
	{
		$segment = $this->uri->segment(3);
		$this->data['title'] = ucfirst($segment).' Bidang Teknis';
		$this->data['page'] = 'tambah_bidang_teknis';
		$this->data['version'] = $this->uri->segment(2);
		$this->data['id'] = @$this->get('id')?:null;

		$this->data['js'] = array(
			'assets/js/app/tambah_bidang_teknis.js?' . rand(),
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

	public function opd_get()
	{
		$return = $this->Bidang_teknis_model->getOpd();

		$this->response($return, 200);
	}

	public function saveData_post()
	{
		$params = $this->post();

		$params['updated_by'] = decrypt_url($this->data['users']['id']);
		$params['updated_at'] = date('Y-m-d H:i:s');

		$return = $this->Bidang_teknis_model->saveData($params);

		$this->response($return, $return['status']);
	}

	public function getById_get()
	{
		$return = $this->Bidang_teknis_model->getReqById($this->get('id'),$this->data['users']);

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function deleteData_post()
	{
		$return = $this->Bidang_teknis_model->deleteReq($this->post('id'));
		$this->response($return, $return['status']);
	}
}
