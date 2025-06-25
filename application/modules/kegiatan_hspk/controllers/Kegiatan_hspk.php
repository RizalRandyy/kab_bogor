<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kegiatan_hspk extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Kegiatan_hspk_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Data Kegiatan HSPK';
		$this->data['page'] = 'kegiatan_hspk';
		$this->data['version'] = 'kegiatan_hspk';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css'
		];

		$this->data['js'] = array(
			'assets/js/app/kegiatan_hspk.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'index');
	}

	public function getData_get()
	{
		$return = $this->Kegiatan_hspk_model->getData($this->get(NULL, TRUE),$this->data['users']);
		$return['header'] = $this->Kegiatan_hspk_model->getheader();

		$this->response($return, 200);
	}

	public function form_get()
	{
		$segment = $this->uri->segment(3);
		$this->data['title'] = ucfirst($segment).' Kegiatan HSPK';
		$this->data['page'] = 'tambah_kegiatan_hspk';
		$this->data['version'] = $this->uri->segment(2);
		$this->data['id'] = @$this->get('id')?$this->get('id', TRUE):null;

		$this->data['js'] = array(
			'assets/js/app/tambah_kegiatan_hspk.js?' . rand(),
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

	public function kel_spesifikasi_get()
	{
		$return = $this->Kegiatan_hspk_model->getkel_spesifikasi();

		$this->response($return, 200);
	}

	public function saveData_post()
	{
		$params = $this->post(NULL, TRUE);

		$params['updated_by'] = decrypt_url($this->data['users']['id']);
		$params['updated_at'] = date('Y-m-d H:i:s');

		$return = $this->Kegiatan_hspk_model->saveData($params);

		$this->response($return, $return['status']);
	}

	public function getById_get()
	{
		$return = $this->Kegiatan_hspk_model->getReqById($this->get('id', TRUE),$this->data['users']);

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function deleteData_post()
	{
		$return = $this->Kegiatan_hspk_model->deleteReq($this->post('id', TRUE));
		$this->response($return, $return['status']);
	}
}
