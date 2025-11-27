<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usulan_spesifikasi_item extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Usulan_spesifikasi_item_model');
	}

	public function index_get()
	{
		$this->data['title'] = 'Usulan Data Spesifikasi Item';
		$this->data['page'] = 'usulan_spesifikasi_item';
		$this->data['version'] = 'usulan_spesifikasi_item';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css'
		];

		$this->data['js'] = array(
			'assets/js/app/usulan_spesifikasi_item.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->data['users'] = $this->data['users'];

		$this->template->load($this->data, null, 'index');
	}

	public function getData_get()
	{
		$return = $this->Usulan_spesifikasi_item_model->getData($this->get(NULL, TRUE), $this->data['users']);
		$return['header'] = $this->Usulan_spesifikasi_item_model->getheader();

		$this->response($return, 200);
	}

	public function form_get()
	{
		$segment = $this->uri->segment(3);
		$this->data['title'] = ucfirst($segment) . ' Usulan Spesifikasi Item';
		$this->data['page'] = 'tambah_usulan_spesifikasi_item';
		$this->data['version'] = $this->uri->segment(2);
		$this->data['id'] = @$this->get('id') ? $this->get('id', TRUE) : null;

		$this->data['js'] = array(
			'assets/js/app/tambah_usulan_spesifikasi_item.js?' . rand(),
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
		$return = $this->Usulan_spesifikasi_item_model->getOpd();

		$this->response($return, 200);
	}

	public function setujuiUsulan_post()
	{
		$id = $this->input->post('id');
		$user = $this->data['users'];
		$user['id'] = decrypt_url($user['id']);

		$this->load->model('Usulan_spesifikasi_item_model');
		$result = $this->Usulan_spesifikasi_item_model->setujuiByKode($id, $user);

		if ($result['status']) {
			$this->response(['message' => $result['message']], 200);
		} else {
			$this->response(['message' => $result['message']], 500);
		}
	}

	public function kel_item_get()
	{
		$return = $this->Usulan_spesifikasi_item_model->getkel_item();

		$this->response($return, 200);
	}

	public function saveData_post()
	{
		$params = $this->post(NULL, TRUE);

		$params['idOpd'] = $this->data['users']['idOpd'];
		$params['updated_by'] = decrypt_url($this->data['users']['id']);
		$params['updated_at'] = date('Y-m-d H:i:s');

		if (!empty($_FILES['dokumen']['name'])) {
			$config['upload_path']   = FCPATH . 'resources/uploads/dokumen_usulan_ssh/';
			$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
			$config['max_size']      = 1000000; // 1GB

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('dokumen')) {
				$uploadData = $this->upload->data();
				$params['dokumen'] = $uploadData['file_name'];

				// === HAPUS FILE LAMA JIKA ADA ===
				if (!empty($params['dokumen_lama'])) {
					$oldFile = FCPATH . 'resources/uploads/dokumen_usulan_ssh/' . $params['dokumen_lama'];
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

		$return = $this->Usulan_spesifikasi_item_model->saveData($params);

		$this->response($return, $return['status']);
	}

	public function getById_get()
	{
		$return = $this->Usulan_spesifikasi_item_model->getReqById($this->get('id', TRUE), $this->data['users']);

		$this->response($return, $return['status'] == 500 ? false : 200);
	}

	public function deleteData_post()
	{
		$return = $this->Usulan_spesifikasi_item_model->deleteReq($this->post('id', TRUE));
		$this->response($return, $return['status']);
	}

	public function download_get($filename)
	{
		$this->load->helper('download');

		$path = FCPATH . 'resources/uploads/dokumen_usulan_ssh/' . $filename;

		if (file_exists($path)) {
			force_download($path, NULL);
		} else {
			show_404();
		}
	}
}
