<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dokumen extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Dokumen_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Dokumen';
		$this->data['page'] = 'dokumen';
		$this->data['version'] = 'dokumen';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css'
		];

		$this->data['js'] = array(
			'assets/js/app/dokumen.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];
		$this->template->load($this->data, null, 'index', null, 'frontend');
	}

	public function getData_get()
	{
		$return = $this->Dokumen_model->getData($this->get(NULL, TRUE), $this->data['users']);
		$return['header'] = $this->Dokumen_model->getheader();

		$this->response($return, 200);
	}
	public function getJenisDokumen_get()
	{
		$data = $this->Dokumen_model->getJenisDokumen();
		echo json_encode([
			'status' => 200,
			'data' => $data
		]);
	}

	public function getById_get()
	{
		$return = $this->Dokumen_model->getReqById($this->get('id', TRUE));

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function show_get()
	{
		$id = $this->get('id', TRUE);
		$data = $this->Dokumen_model->getReqById($id, $this->data['users']);

		$this->data['title'] = 'Detail Dokumen';
		$this->data['page'] = 'dokumen_show';
		$this->data['data'] = $data['data'];
		$this->data['css'] = [];
		$this->data['js']  = [];

		$this->template->load($this->data, null, 'show', null, 'frontend');
	}
}
