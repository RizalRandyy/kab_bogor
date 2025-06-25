<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_role extends My_Controller
{


	function __construct()
	{
		parent::__construct();
		$this->load->model('user_role_model', 'role_model');
	}


	public function index_get()
	{
		$this->data['title'] = 'User Role';
		$this->data['version'] = $this->uri->segment(1);


		$this->data['js'] = array(
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js',
			'assets/js/app/user_role.js?' . rand()
		);

		$this->data['css'] = array(
		);

		$this->template->load($this->data, null, 'index');
	}

	public function get_role_get()
	{
		$formdata = $this->input->get(NULL, TRUE);
		$keyword = $formdata['keyword'];
		$column = !empty($formdata['column']) ? true : false;

		$pagination = [
			'page'  =>  !empty($formdata['page']) ? $formdata['page'] : null,
			'limit' =>  !empty($formdata['limit']) ? $formdata['limit'] : null
		];

		$data = $this->role_model->getAll($keyword, $pagination, $column, $this->data['users']);

		$this->set_response($data);
	}

	public function add_role_get()
	{
		$this->data['formURL'] = site_url('user_role/add_role');
		$this->data['title']   = 'Tambah Role';
		$this->data['version']   = 'user';
		$this->data['accessListNew'] = $this->get_all_modules();

		$this->template->load($this->data, null, 'role_form');
	}

	public function add_role_post()
	{
		$this->form_validation->set_rules('role_name', 'Role Name', 'required|min_length[3]');

		if ($this->form_validation->run() == FALSE) :
			$this->session->set_flashdata('alert-warning', validation_errors());
			redirect(base_url() . 'user_role/add_role', 'refresh');
		else :
			$data = array(
				"name"         => $this->input->post('role_name', TRUE),
				"role_access"       => json_encode($this->input->post('access', TRUE), JSON_NUMERIC_CHECK),
				"created_at"      => date('Y-m-d H:i:s'),
			);

			$Insert = $this->role_model->add($data);

			if ($Insert) {
				$this->session->set_flashdata('alert-success', "Success Add New User Role");
				redirect(base_url() . 'user_role', 'refresh');
			} else {
				$this->session->set_flashdata('alert-error', "Failed to add new user Role");
				redirect(base_url() . 'user_role', 'refresh');
			}
		endif;
	}

	public function edit_role_get($id)
	{
		$this->data['title']   = 'Edit Role';
		$this->data['formURL'] = site_url('user_role/edit_role/' . $id);
		$this->data['version'] = $this->uri->segment(1);

		$default_access = $this->get_all_modules();
		$this->data['accessListNew'] = $default_access;
		$roles = $this->db->select('*')->from('users_role')->where('id = ', $id)->get()->first_row();
		$this->data['roles']   = $roles;
		$this->data['access']  = !empty($this->data['roles']->role_access) ? json_decode($this->data['roles']->role_access) : array();

		$this->template->load($this->data, null, 'role_form', 'user_role');
	}

	public function edit_role_post($id)
	{
		$this->form_validation->set_rules('role_name', 'Role Name', 'required|min_length[3]');

		if ($this->form_validation->run() == FALSE) :
			$this->session->set_flashdata('alert-warning', validation_errors());
			redirect(base_url() . 'user_role/edit_role/' . $id, 'refresh');
		else :
			$data = array(
				"name"        => $this->input->post('role_name', TRUE),
				"role_access"      => json_encode($this->input->post('access', TRUE), JSON_NUMERIC_CHECK),
				"updated_at"     => date('Y-m-d H:i:s'),
			);

			$Insert = $this->db->where("id = ", $id)->update('users_role', $data);

			if ($Insert) {
				if ($id == $this->session->userdata('role_id')) :
					$this->session->set_userdata(array(
						'user_access' => json_decode($data['role_access'], TRUE)
					));
				endif;

				$this->session->set_flashdata('alert-success', "Success Edit User Role");
				redirect(base_url() . 'user_role', 'refresh');
			} else {
				$this->session->set_flashdata('alert-error', "Failed to Edit User Role");
				redirect(base_url() . 'user_role/edit_role/' . $id, 'refresh');
			}
		endif;
	}

	public function get_all_modules()
	{
		$controllers = array();
		$this->load->helper('directory');
		$map = directory_map(APPPATH . 'modules', 1);

		foreach ($map as $key => $file) {
			$file = substr($file, 0, -1);
			if ($file != 'login' && $file != 'backend' && $file != 'frontend' && $file != 'dashboard' && $file != 'hspk' && $file != 'ssh' && $file != 'asb' && $file != 'profile') {
				if($file == 'manage_dashboard' || $file == 'user_log'){
					$modules[$file] = [
						$file 					=> "off",
					];
				}else{
					$modules[$file] = [
						$file 					=> "off",
						"accessadd_" . $file 		=> "off",
						"accessedit_" . $file 	=> "off",
						"accessdelete_" . $file 	=> "off"
					];
				}
			}
		}
		return $modules;
	}

	public function delete_post()
	{
		$id = $this->post('id');
		$res = $this->db->where('id', $id)->delete('users_role');

		if ($res) {
			$response = array('status' => 'success');
		} else {
			$response = array('status' => 'error');
		}

		$this->response($response);
	}
}
