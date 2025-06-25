<?php
defined('BASEPATH') or exit('No direct script access allowed');

class My_Controller extends REST_Controller
{

	protected $data;

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		
		$this->data['users'] = $this->session->userdata('kab_bogor');

		if(empty($this->data['users'])){
			// print_r("test"); exit();
			// redirect(base_url('login'));
		}

	}
}