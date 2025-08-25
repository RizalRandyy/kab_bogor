<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usulan_kegiatan_asb extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Usulan_kegiatan_asb_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Usulan Kegiatan ASB';
		$this->data['page'] = 'usulan_kegiatan_asb';
		$this->data['version'] = 'usulan_kegiatan_asb';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css'
		];

		$this->data['js'] = array(
			'assets/js/app/usulan_kegiatan_asb.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'index');
	}

	public function getData_get()
	{
		$return = $this->Usulan_kegiatan_asb_model->getData($this->get(NULL, TRUE), $this->data['users']);
		$return['header'] = $this->Usulan_kegiatan_asb_model->getheader();

		$this->response($return, 200);
	}

	public function form_get()
	{
		$segment = $this->uri->segment(3);
		$this->data['title'] = ucfirst($segment) . ' Usulan Kegiatan ASB';
		$this->data['page'] = 'tambah_usulan_kegiatan_asb';
		$this->data['version'] = $this->uri->segment(2);
		$this->data['id'] = @$this->get('id') ? $this->get('id', TRUE) : null;

		$this->data['js'] = array(
			'assets/js/app/tambah_usulan_kegiatan_asb.js?' . rand(),
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
		$return = $this->Usulan_kegiatan_asb_model->getOpd();

		$this->response($return, 200);
	}

	public function opd_pengusul_get()
	{
		$return = $this->Usulan_kegiatan_asb_model->getOpdPengusul();

		$this->response($return, 200);
	}

	public function kel_spesifikasi_get()
	{
		$return = $this->Usulan_kegiatan_asb_model->getkel_spesifikasi();

		$this->response($return, 200);
	}

	public function setujuiUsulan_post()
	{
		$id = $this->input->post('id');
		$user = $this->data['users'];
		$user['id'] = decrypt_url($user['id']);

		$this->load->model('Usulan_kegiatan_asb_model');
		$result = $this->Usulan_kegiatan_asb_model->setujuiUsulan($id, $user);

		if ($result['status']) {
			$this->response(['message' => $result['message']], 200);
		} else {
			$this->response(['message' => $result['message']], 500);
		}
	}

	public function saveData_post()
	{
		$params = $this->post(NULL, TRUE);

		$params['idOpdPengusul'] = $this->data['users']['idOpd'];
		$params['updated_by'] = decrypt_url($this->data['users']['id']);
		$params['updated_at'] = date('Y-m-d H:i:s');

		$return = $this->Usulan_kegiatan_asb_model->saveData($params);

		$this->response($return, $return['status']);
	}

	public function getById_get()
	{
		$return = $this->Usulan_kegiatan_asb_model->getReqById($this->get('id', TRUE), $this->data['users']);

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function deleteData_post()
	{
		$return = $this->Usulan_kegiatan_asb_model->deleteReq($this->post('id', TRUE));
		$this->response($return, $return['status']);
	}
}
