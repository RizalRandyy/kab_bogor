<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kelompok_item extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Kelompok_item_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Data Kelompok Item';
		$this->data['page'] = 'kelompok_item';
		$this->data['version'] = 'kelompok_item';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css'
		];

		$this->data['js'] = array(
			'assets/js/app/kelompok_item.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'index');
	}

	public function getData_get()
	{
		$return = $this->Kelompok_item_model->getData($this->get(NULL, TRUE),$this->data['users']);
		$return['header'] = $this->Kelompok_item_model->getheader();

		$this->response($return, 200);
	}

	public function form_get()
	{
		$segment = $this->uri->segment(3);
		$this->data['title'] = ucfirst($segment).' Kelompok Item';
		$this->data['page'] = 'tambah_kelompok_item';
		$this->data['version'] = $this->uri->segment(2);
		$this->data['id'] = @$this->get('id')?$this->get('id', TRUE):null;

		$this->data['js'] = array(
			'assets/js/app/tambah_kelompok_item.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js',
			'assets/plugins/inputtags/js/bootstrap-tagsinput.js'
		);

		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css',
			'assets/plugins/inputtags/css/bootstrap-tagsinput.css'
		];

		$this->template->load($this->data, null, 'form');
	}

	public function saveData_post()
	{
		$params = $this->post(NULL, TRUE);

		$params['updated_by'] = decrypt_url($this->data['users']['id']);
		$params['updated_at'] = date('Y-m-d H:i:s');

		$return = $this->Kelompok_item_model->saveData($params);

		$this->response($return, $return['status']);
	}

	public function getById_get()
	{
		$return = $this->Kelompok_item_model->getReqById($this->get('id', TRUE),$this->data['users']);

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function deleteData_post()
	{
		$return = $this->Kelompok_item_model->deleteReq($this->post('id', TRUE));
		$this->response($return, $return['status']);
	}
}
