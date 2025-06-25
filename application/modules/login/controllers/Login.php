<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Login extends My_Controller
{

	protected $CI;
	function __construct()
	{
		$this->CI = &get_instance();
		parent::__construct();
	}


	public function index_get()
	{
		$sess_login = $this->data['users'];

        if ($sess_login) {

        	redirect(base_url());
        }

		$this->data['title'] = 'Masuk';
		$this->data['page'] = 'login';

		$this->data['js'] = array(
		);

		$this->data['css'] = array(
			"assets/css/digit_box.css"
		);

		$this->template->load($this->data, "login", 'index');
	}

	public function masuk_post()
	{
		$this->load->model('UsersDB');

		$email = $this->input->post('email', TRUE);
		$password = $this->input->post('password', TRUE);
		$password_encrypt = setEncrypt($password);

		if ($email == null and $password == null) {
			$this->response(array('error' => true, 'message' => 'Please fill email and password!'));
		}else{
			$users = $this->UsersDB->getUser($email);

			if($users){
				if($users->status != '1'){
					$this->session->set_flashdata('error', "User tidak aktif. Silakan hubungi Administrator!");
					redirect(base_url("login"));
				}elseif($users->email_active != '1'){
					$this->session->set_flashdata('error', "Email belum terverifikasi. Silakan periksa email anda, atau hubungi Administrator!");
					redirect(base_url("login"));
				}
			
				$check = setDecrypt($users->password, $password);

				if ($check) {

					$data = array(
						'last_login' => date("Y-m-d H:i:s"),
						'email_kode' => null
					);

					$this->db->where('email', $email);
					$this->db->update('users', $data);

					$this->session->set_userdata([
						'kab_bogor' => [
							'id' => encrypt_url($users->id),
							'email' => $users->email,
							'full_name' => $users->full_name,
							'role_id' => $users->role_id,
							'role_name' => $users->role_name,
							'role_access'	=> json_decode($users->role_access, TRUE),
							'photo_profile' => $users->photo ? base_url($users->photo) : base_url('/resources/img/profile/photo_orang.JPG'),
						]
					]);

					$this->session->set_flashdata('success', "User valid, please wait!");

					$redirect = json_decode($users->role_access, TRUE);
					redirect(base_url());
				}else{
					$this->session->set_flashdata('error', "Password salah!");
					redirect(base_url("login"));
				}
			}else{
				$this->session->set_flashdata('error', "User tidak ditemukan!");
				redirect(base_url("login"));
			}
		}
	}

	public function update_password_post()
	{
		$this->load->model('UsersDB');

		$id = $this->input->post('id', TRUE);
		$password = $this->input->post('password', TRUE);
		$page = $this->input->post('page', TRUE);
		$source = $this->UsersDB->update_password($password, $id);
		if($source){
			if($page != 'user_manage'){
				$this->session->sess_destroy();
			}
			$this->set_response(array(
				'status'    => true,
				'message'   => 'password changed successfully'
			));
		}else{
			$this->set_response(array(
				'status'    => failed,
				'message'   => 'password failed to change'
			));
		}
	}

	public function logout_get()
	{
		$this->session->sess_destroy();
		redirect(base_url("login"));
	}
}
