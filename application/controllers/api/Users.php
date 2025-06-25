<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Users extends My_Controller
{


	function __construct()
	{
		$this->load->helper();
		parent::__construct();
	}

    public function aktivasi_get()
    {
        // Initialize the array with a 'title' element for use for the <title> tag.
        $this->data['title'] = 'Aktivasi Email';
        $this->data['page'] = 'login';
        $this->data['id'] = $this->input->get('id', TRUE);

        $this->data['js'] = array(
        );

        $this->data['css'] = array(
            // 'assets/bootstrap/datepicker/css/bootstrap-datetimepicker.min.css'
            "assets/css/digit_box.css"
        );

        $this->load->model('UsersModel');
        $users = $this->UsersModel->getUser($this->input->get('id', TRUE));

        if(!$users){
            $this->session->set_flashdata('error', "Link tidak ditemukan!");
            redirect(base_url("login"));
        }

        $this->data['user'] = $users;
        $this->template->load($this->data, "login", 'register', "login");
    }

    public function aktivasi_post()
    {
        $this->load->model('UsersModel');

        $params = $this->input->post(NULL, TRUE);

        $params['email_active'] = 1;
        $params['email_kode'] = null;
        $kode = $params['id'];

        unset($params['id']);

        $source = $this->UsersModel->update_user($params, $kode);
        if($source){
            $this->session->set_flashdata('success', "Aktivasi berhasil, silahkan Login!");
            redirect(base_url("login"));
        }else{
            $this->session->set_flashdata('error', "Gagal melakukan Aktivasi, silahkan coba lagi atau hubungi Adminidtrator!");
            redirect(base_url("login"));

        }
    }

	public function register_get()
	{
		// Initialize the array with a 'title' element for use for the <title> tag.
		$this->data['title'] = 'Aktivasi Email';
		$this->data['page'] = 'login';
		$this->data['id'] = $this->input->get('id', TRUE);

		$this->data['js'] = array(
		);

		$this->data['css'] = array(
			// 'assets/bootstrap/datepicker/css/bootstrap-datetimepicker.min.css'
			"assets/css/digit_box.css"
		);

		$this->load->model('UsersModel');
		$users = $this->UsersModel->getUser($this->input->get('id', TRUE));

		if(!$users){
			$this->session->set_flashdata('error', "Link tidak ditemukan!");
			redirect(base_url("login"));
		}

		$this->data['user'] = $users;
		$this->template->load($this->data, "login", 'register', "login");
	}

	public function register_post()
	{
		$this->load->model('UsersModel');

		$params = $this->input->post(NULL, TRUE);

		if($params['password'] != $params['confirm-password']){
			$this->session->set_flashdata('error', "Password tidak sama!");
			redirect(base_url("login"));
		}else{
			$users_email = $this->UsersModel->getUser($params['email']);
			$users_phone = $this->UsersModel->getUser($params['phone']);
			if($users_email){
				$this->session->set_flashdata('error', "Email sudah terdaftar!");
				redirect(base_url("login"));
			}elseif($users_phone){
				$this->session->set_flashdata('error', "No Telepon sudah terdaftar!");
				redirect(base_url("login"));
			}
		}

		$params['password'] = setEncrypt($params['password']);
		$params['role_id'] = 3;
		$params['email_kode'] = $this->generate_string(4);
		$params['email_active'] = 0;
		$params['status'] = 1;
		$params['created_at'] = date("Y-m-d h:i:s");
		unset($params['confirm-password']);

		$source = $this->UsersModel->register($params);
		if($source){
			$to = $params['email'];

			$url = base_url('api/users/register?id='.$params['email_kode']);
			$subject = "Aktivasi User";
			$body = '<div style="text-align: center;">
						<p><h1><b>AKTIVASI</b></h1></p>
						<h2><button class="btn">
							<a href="'.$url.'">Klik Disini</a>
							</button>
						</h2><br>
						<span>untuk Aktivasi user Anda.</span>
					</div>';

			$header = file_get_contents(FCPATH.'email-header.html');
			$header .= '<div class="image-logo"><img src="'.base_url('assets/img/Kabupaten-bogor.png').'"></div>';
        	$footer = file_get_contents(FCPATH.'email-footer.html');

        	$body = $header.$body.$footer;

			$send_email = sendEmail($to, $subject, $body);

			$this->session->set_flashdata('success', "Pendaftaran selesai, periksa email untuk aktivasi!");
			redirect(base_url("login"));
		}else{
			$this->session->set_flashdata('error', "Gagal melakukan pendaftaran, silahkan coba lagi atau hubungi Adminidtrator!");
			redirect(base_url("login"));

		}
	}

	public function forgot_password_get()
	{
		// Initialize the array with a 'title' element for use for the <title> tag.
		$this->data['title'] = 'Ganti Password';
		$this->data['page'] = 'login';
		$this->data['id'] = $this->input->get('id', TRUE);

		$this->data['js'] = array(
		);

		$this->data['css'] = array(
			// 'assets/bootstrap/datepicker/css/bootstrap-datetimepicker.min.css'
			"assets/css/digit_box.css"
		);

		$this->load->model('UsersModel');
		$users = $this->UsersModel->getUser($this->input->get('id', TRUE));

		if(!$users){
			$this->session->set_flashdata('error', "Link tidak ditemukan!");
			redirect(base_url("login"));
		}

		$this->data['user'] = $users;
		$this->template->load($this->data, "login", 'forgot_password', "login");
	}

	public function forgot_password_post()
	{
		$this->load->model('UsersModel');

		$email = $this->input->post('email', TRUE);
		$params['email_kode'] = $this->generate_string(4);

		$source = $this->UsersModel->getUser($email);
		if($source){
			$update = $this->UsersModel->update_user($params,$email);

			if($update){
				$to = $email;

				$url = base_url('api/users/forgot_password?id='.$params['email_kode']);
				$subject = "Reset Password";
				$body = '<div style="text-align: center;">
							<p><h2><b>RESET PASSWORD</b></h2></p>
							<h2><button class="btn">
								<a href="'.$url.'">Klik Disini</a>
								</button>
							</h2><br>
							<span>untuk ganti password Anda.</span>
						</div>';

				$header = file_get_contents(FCPATH.'email-header.html');
				$header .= '<div class="image-logo"><img src="'.base_url('assets/img/Kabupaten-bogor.png').'"></div>';
	        	$footer = file_get_contents(FCPATH.'email-footer.html');

	        	$body = $header.$body.$footer;

				$send_email = sendEmail($to, $subject, $body);

				$this->session->set_flashdata('success', "Link ganti password telah dikirim ke Email: \\n".$to);
				redirect(base_url("login"));
			}else{
				$this->session->set_flashdata('error', "Error, Silahkan coba lagi atau hubungi Adminidtrator!");
			redirect(base_url("login"));
			}

			
		}else{
			$this->session->set_flashdata('error', "Email tidak ditemukan!");
			redirect(base_url("login"));

		}
	}

	public function u_password_post()
	{
		$this->load->model('UsersModel');

		$params = $this->input->post(NULL, TRUE);

		if($params['password'] != $params['confirm-password']){
			$this->session->set_flashdata('error', "Password tidak sama!");
		}

		$params['password'] = setEncrypt($params['password']);
		$params['email_kode'] = null;
		$kode = $params['id'];

		unset($params['id']);
		unset($params['confirm-password']);

		$source = $this->UsersModel->update_user($params, $kode);
		if($source){
			$this->session->set_flashdata('success', "Ganti Password berhasil!");
			redirect(base_url("login"));
		}else{
			$this->session->set_flashdata('error', "Gagal melakukan ganti password, silahkan coba lagi atau hubungi Adminidtrator!");
			redirect(base_url("login"));

		}
	}

	function generate_string($strength = 16)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length    = strlen($permitted_chars);
        $random_string   = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }
}
