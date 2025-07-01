<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lokasi_toko extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Lokasi_toko_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Lokasi Survey';
		$this->data['page'] = 'lokasi_toko';
		$this->data['version'] = 'lokasi_toko';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css',
			'assets/plugins/leaflet/leaflet.css',
		];

		$this->data['js'] = array(
			'assets/js/app/lokasi_toko.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js',
			'assets/plugins/leaflet/leaflet.js',
		);

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'index');
	}

	public function getAll_get()
	{
		$result = $this->Lokasi_toko_model->getLocationAll();
		$this->response($result, 200);
	}


	public function getData_get()
	{
		$return = $this->Lokasi_toko_model->getData($this->get(NULL, TRUE), $this->data['users']);
		$return['header'] = $this->Lokasi_toko_model->getheader();

		$this->response($return, 200);
	}

	public function form_get()
	{
		$segment = $this->uri->segment(3);
		$this->data['title'] = ucfirst($segment) . ' Lokasi Survey';
		$this->data['page'] = 'tambah_lokasi_toko';
		$this->data['version'] = $this->uri->segment(2);
		$this->data['id'] = @$this->get('id') ? $this->get('id', TRUE) : null;

		$this->data['js'] = array(
			'assets/js/app/tambah_lokasi_toko.js?' . rand(),
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

		$return = $this->Lokasi_toko_model->saveData($params);

		$this->response($return, $return['status']);
	}

	public function getById_get()
	{
		$return = $this->Lokasi_toko_model->getReqById($this->get('id', TRUE), $this->data['users']);

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function deleteData_post()
	{
		$return = $this->Lokasi_toko_model->deleteReq($this->post('id', TRUE));
		$this->response($return, $return['status']);
	}
}
