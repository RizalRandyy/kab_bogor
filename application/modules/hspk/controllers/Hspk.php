<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hspk extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Hspk_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Harga Satuan Pekerjaan (HSP)';
		$this->data['page'] = 'hspk';
		$this->data['version'] = 'hspk';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css'
		];

		$this->data['js'] = array(
			'assets/js/app/hspk.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];
		$this->template->load($this->data, null, 'index', null, 'frontend');
	}

	public function getData_get()
	{
		$return = $this->Hspk_model->getData($this->get(NULL, TRUE),$this->data['users']);
		$return['header'] = $this->Hspk_model->getheader();

		$this->response($return, 200);
	}

	public function form_get()
	{
		$segment = $this->uri->segment(3);
		$this->data['title'] = ucfirst($segment).' Kegiatan ASB Detail';
		$this->data['page'] = 'tambah_kegiatan_asb_detail';
		$this->data['version'] = $this->uri->segment(2);
		$this->data['id'] = @$this->get('id')?$this->get('id', TRUE):null;

		$this->data['js'] = array(
			'assets/js/app/tambah_kegiatan_asb_detail.js?' . rand(),
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
		$return = $this->Hspk_model->getkegiatan();

		$this->response($return, 200);
	}

	public function getById_get()
	{
		$return = $this->Hspk_model->getReqById($this->get('id', TRUE));

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

}
