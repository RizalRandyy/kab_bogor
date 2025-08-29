<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_dokumen extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Daftar_dokumen_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Daftar Dokumen';
		$this->data['page'] = 'daftar_dokumen';
		$this->data['version'] = 'daftar_dokumen';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css'
		];

		$this->data['js'] = array(
			'assets/js/app/daftar_dokumen.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'index');
	}

	public function getData_get()
	{
		$return = $this->Daftar_dokumen_model->getData($this->get(), $this->data['users']);
		$return['header'] = $this->Daftar_dokumen_model->getheader();

		$this->response($return, 200);
	}

	public function form_get()
	{
		$segment = $this->uri->segment(3);
		$this->data['title'] = ucfirst($segment) . ' Daftar Dokumen';
		$this->data['page'] = 'tambah_daftar_dokumen';
		$this->data['version'] = $this->uri->segment(2);
		$this->data['id'] = @$this->get('id') ?: null;

		$this->data['js'] = array(
			'assets/js/app/tambah_daftar_dokumen.js?' . rand(),
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

	public function saveData_post()
	{
		$params = $this->post();

		$params['updated_by'] = decrypt_url($this->data['users']['id']);
		$params['updated_at'] = date('Y-m-d H:i:s');

		// === HANDLE FILE UPLOAD ===
		if (!empty($_FILES['dokumen']['name'])) {
			$config['upload_path']   = FCPATH . 'resources/uploads/dokumen/';
			$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
			$config['max_size']      = 1000000; // 1GB

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('dokumen')) {
				$uploadData = $this->upload->data();
				$params['dokumen'] = $uploadData['file_name']; // simpan nama file baru

				// === HAPUS FILE LAMA JIKA ADA ===
				if (!empty($params['dokumen_lama'])) {
					$oldFile = FCPATH . 'resources/uploads/dokumen/' . $params['dokumen_lama'];
					if (file_exists($oldFile)) {
						unlink($oldFile); // hapus file lama
					}
				}

				unset($params['dokumen_lama']);
			} else {
				$this->response([
					'status'  => 500,
					'message' => $this->upload->display_errors()
				], 500);
				return;
			}
		} else {
			if (!empty($params['dokumen_lama'])) {
				$params['dokumen'] = $params['dokumen_lama'];
			}
			unset($params['dokumen_lama']);
		}


		$return = $this->Daftar_dokumen_model->saveData($params);
		$this->response($return, $return['status']);
	}

	public function getById_get()
	{
		$return = $this->Daftar_dokumen_model->getReqById($this->get('id'), $this->data['users']);

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function deleteData_post()
	{
		$return = $this->Daftar_dokumen_model->deleteReq($this->post('id'));
		$this->response($return, $return['status']);
	}

	public function getJenisDokumen_get()
	{
		$data = $this->Daftar_dokumen_model->getJenisDokumen();
		echo json_encode([
			'status' => 200,
			'data' => $data
		]);
	}
}
