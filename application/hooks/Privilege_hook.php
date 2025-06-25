<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Privilege_hook
{

	protected $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	public function Privilege_check()
	{
		$whiteList = array(
			'api',
			'login',
			'dashboard',
			'profile',
			'perkiraan_hps',
			'hspk',
			'ssh',
			'asb'
		);	

		// Check is this from whitelist or not ?
		if (!(str_replace($whiteList, '', $this->CI->router->uri->uri_string) != $this->CI->router->uri->uri_string) and !in_array($this->CI->router->fetch_module(), $whiteList) and !empty($this->CI->session->userdata('kab_bogor')['id'])) :
			// This method intended based on normal request
			$user_access = $this->CI->session->userdata('kab_bogor')['role_access'];
			// prepare var
			// we check current module & class is accessible for current user?
			if (isset($user_access[strtolower($this->CI->router->fetch_module())][strtolower($this->CI->router->fetch_class())]) && $user_access[strtolower($this->CI->router->fetch_module())][strtolower($this->CI->router->fetch_class())] == 'on') {
				return;
			};

			if (isset($user_access[strtolower($this->CI->router->fetch_module())][strtolower($this->CI->router->fetch_class())]) && $user_access[strtolower($this->CI->router->fetch_module())][strtolower($this->CI->router->fetch_class())] == 'off') {
				show_404();
			};

			if (!in_array($this->CI->router->fetch_module(), $whiteList)) {
				show_404();
			};

		else :
			// This method intended based on whitelist such as api request etc.
			// checker if user not in whitelist URL
			
			foreach ($whiteList as $list) :
				if($this->CI->router->fetch_module() == 'perkiraan_hps' &&  empty($this->CI->session->userdata('kab_bogor'))){
					redirect(base_url() . 'login');
				} else if($this->CI->router->fetch_module() == 'hspk' &&  empty($this->CI->session->userdata('kab_bogor'))){
					redirect(base_url() . 'login');
				} else if($this->CI->router->fetch_module() == 'ssh' &&  empty($this->CI->session->userdata('kab_bogor'))){
					redirect(base_url() . 'login');
				} else if($this->CI->router->fetch_module() == 'asb' &&  empty($this->CI->session->userdata('kab_bogor'))){
					redirect(base_url() . 'login');
				}

				if ($list == $this->CI->router->fetch_module() || $list == $this->CI->router->uri->uri_string || strpos($this->CI->router->uri->uri_string, $list) !== FALSE || $this->CI->router->uri->uri_string == '') {
					// print_r($this->CI->router->uri->uri_string); exit();
					return;
				}
			endforeach;
			
			// if user hasn't login
			if (empty($this->CI->session->userdata('kab_bogor')['id']) && !in_array($this->CI->router->fetch_module(), $whiteList)) {
				redirect(base_url(), 'refresh');
			};
		endif;

	}
}

/* End of file Privilege_hook.php */
/* Location: ./application/hooks/Privilege_hook.php */