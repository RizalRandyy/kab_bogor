<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_manage extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_manage_model', 'manage_model');
	}


	public function index_get()
	{
		$this->data['title'] = 'List Users Manajemen';
		$this->data['version'] = $this->uri->segment(1);

		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css',
		];

		$this->data['js'] = array(
			'assets/js/app/user_manage.js?' . rand(),
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js'
		);

		$this->template->load($this->data, null, 'index');
	}

	public function getUsers_get()
	{
		$return = $this->manage_model->getUsers($this->get(NULL, TRUE),$this->data['users']);

		$header =  $this->manage_model->getheader();

		$return['header'] = $header;

		$this->response($return, $return['status']);
	}

	public function add_manage_get()
	{
		$this->data['formURL'] = site_url('user_manage/add_manage');
		$this->data['title']   = 'Form User Managements ~ HIGS';
		$this->data['version']   = 'user';
		$this->data['css'] = [
			'assets/plugins/animate/animate.min.css',
			'assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
			'assets/plugins/bootstrap-social/bootstrap-social.css',
			'assets/plugins/flag-icon-css/assets/docs.css',
			'assets/plugins/flag-icon-css/css/flag-icon.css',
			'assets/plugins/fancybox/dist/jquery.fancybox.min.css',
			'assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css'
		];
		$this->data['js'] = array(
			'assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
			'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js',
			'assets/plugins/fancybox/dist/jquery.fancybox.min.js',
			'assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js',
			'assets/js/app/form_user_manage.js?' . rand(),
			'assets/js/custom.js?' . rand()
		);

		$this->template->load($this->data, null, 'manage_form');
	}

	public function save_user_post()
	{	
		$data = $this->post();

		$id_user = !empty($this->post('id')) ? $this->post('id', TRUE) : false;

		$return = $this->manage_model->saveUser($data, $id_user);

        if($return['status'] && $return['email_kode']){
            $to = $data['email'];

            $url = base_url('api/users/aktivasi?id='.$return['email_kode']);
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
        }

        unset($return['email_kode']);
		$this->response($return);
	}

	public function delete_user_post()
	{
		$id_user = !empty($this->post('id')) ? $this->post('id', TRUE) : false;

		$return = $this->manage_model->deleteUser($id_user);
		$this->response($return);
	}

	public function saveChangeRole_post()
	{
		$id_users = $this->decryptAssets($this->post('id', TRUE));
		$params = $this->post(NULL, TRUE);

		unset($params['id']);

		$return = $this->manage_model->saveChangeRole($id_users, $params);
		$this->response($return, $return['status']);
	}

	public function getRoleUsers_get()
	{
		$return = $this->manage_model->getRoleUsers();
		$this->response($return, $return['status']);
	}

	public function update_password_post()
	{
		$id = $this->input->post('id', TRUE);
		$password = $this->input->post('password', TRUE);
		$confirm_pass = $this->input->post('confirm_pass', TRUE);

		if($password != $confirm_pass){
			$this->set_response(array(
				'status'    => false,
				'message'   => 'Password are not the same'
			));
		}else{
			$source = $this->manage_model->update_password($password, $id);
			if($source){
				$this->set_response(array(
					'status'    => true,
					'message'   => 'password changed successfully'
				));
			}else{
				$this->set_response(array(
					'status'    => false,
					'message'   => 'password failed to change'
				));
			}
		}
	}
}
